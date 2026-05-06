<?php

namespace Modules\DiemDanh\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\DiemDanh\Models\Attendance;
use Modules\DiemDanh\Models\Category;

class DiemDanhController extends Controller
{
    private const ROLE_ADMIN = 1;
    private const ROLE_SUPPORT = -1;
    private const ROLE_STUDENT = 0;

    public function index()
    {
        $role = $this->resolveRole();

        if (in_array($role, [self::ROLE_ADMIN, self::ROLE_SUPPORT], true)) {
            return redirect()->route('diemdanh.create_event');
        }

        return redirect()->route('diemdanh.history');
    }

    private function resolveRole(): int
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if (isset($user->su) && $user->su !== null) {
            return (int) $user->su;
        }

        // Fallback dữ liệu cũ: gid=0 là admin.
        return ((int) ($user->gid ?? 999) === 0)
            ? self::ROLE_ADMIN
            : self::ROLE_STUDENT;
    }

    private function ensureStaff(bool $adminOnly = false): int
    {
        $role = $this->resolveRole();

        if ($adminOnly && $role !== self::ROLE_ADMIN) {
            abort(403, 'Bạn không có quyền tạo sự kiện.');
        }

        if (!$adminOnly && !in_array($role, [self::ROLE_ADMIN, self::ROLE_SUPPORT], true)) {
            abort(403, 'Bạn không có quyền truy cập vào khu vực này.');
        }

        return $role;
    }

    private function ensureStudent(): void
    {
        if ($this->resolveRole() !== self::ROLE_STUDENT) {
            abort(403, 'Chỉ sinh viên mới truy cập được khu vực này.');
        }
    }

    public function createEvent()
    {
        $role = $this->ensureStaff();
        $canCreateEvent = ($role === self::ROLE_ADMIN);
        $recentEvents = Category::orderBy('cid', 'desc')->take(10)->get();
        $canUseQrTools = in_array($role, [self::ROLE_ADMIN, self::ROLE_SUPPORT], true);

        return view('diemdanh::create_event', compact('recentEvents', 'canCreateEvent', 'canUseQrTools'));
    }

    public function selectEvent(Category $category)
    {
        $this->ensureStaff();

        session([
            'diemdanh_event_id' => $category->cid,
            'diemdanh_event_name' => $category->category_name,
            'diemdanh_ctxh_days' => $category->ctxh_days,
        ]);

        return redirect()->route('diemdanh.create_event')->with('success', 'Đã chọn sự kiện: ' . $category->category_name);
    }

    public function storeEvent(Request $request)
    {
        $this->ensureStaff(adminOnly: true);

        $request->validate([
            'event_name' => 'required|string|max:255',
            'ctxh_days' => 'required|numeric|min:0|max:999.9',
        ]);

        $eventName = $request->input('event_name');
        $ctxhDays = round((float) $request->input('ctxh_days'), 1);

        $category = Category::updateOrCreate(
            ['category_name' => $eventName],
            ['ctxh_days' => $ctxhDays]
        );

        session([
            'diemdanh_event_id' => $category->cid,
            'diemdanh_event_name' => $category->category_name,
            'diemdanh_ctxh_days' => $category->ctxh_days,
        ]);

        return redirect()->route('diemdanh.create_event')->with('success', 'Đã tạo/cập nhật sự kiện thành công.');
    }

    public function scanCamera(Request $request)
    {
        $this->ensureStaff();

        if (!$request->session()->has('diemdanh_event_name')) {
            return redirect()->route('diemdanh.create_event')->with('error', 'Vui lòng chọn một sự kiện trước khi quét mã.');
        }

        $eventName = $request->session()->get('diemdanh_event_name');

        return view('diemdanh::scan', compact('eventName'));
    }

    public function showEventQr(Request $request)
    {
        $this->ensureStaff();

        if (!$request->session()->has('diemdanh_event_id')) {
            return redirect()->route('diemdanh.create_event')->with('error', 'Vui lòng chọn một sự kiện trước khi tạo mã QR.');
        }

        $eventName = $request->session()->get('diemdanh_event_name');
        $qrData = $request->session()->get('diemdanh_event_id');

        return view('diemdanh::show_qr', compact('eventName', '$qrData'));
    }

    public function saveAttendance(Request $request)
    {
        $this->ensureStaff();

        try {
            $qrData = (string) $request->input('qr_code', '');

            $eventName = session('diemdanh_event_name', 'Sự kiện không xác định');
            $categoryID = session('diemdanh_event_id');

            if (!$categoryID) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Phiên làm việc đã hết hạn. Vui lòng tạo lại sự kiện.',
                ], 419);
            }

            // 1. Giải mã QR để lấy đúng thông tin sinh viên
            $parsedStudent = $this->parseQrStudent($qrData);
            if (!$parsedStudent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mã QR không hợp lệ. Định dạng yêu cầu: MSSV_HOTEN_NGAYSINH',
                ], 400);
            }

            $studentId = $parsedStudent['student_id'];
            $studentName = $parsedStudent['student_name']; // Tên lấy trực tiếp từ QR

            // 2. Kiểm tra xem sinh viên này đã điểm danh sự kiện này chưa
            $existingAttendance = Attendance::where('cid', $categoryID)
                ->where('studentid', $studentId)
                ->first();

            if ($existingAttendance) {
                // Nếu đã tồn tại, cập nhật lại tên mới nhất từ mã QR (nếu có thay đổi)
                if ((string) $existingAttendance->student_name !== $studentName) {
                    $existingAttendance->student_name = $studentName;
                    $existingAttendance->save();
                }

                return response()->json([
                    'status' => 'info',
                    'student_name' => $studentName, // GỬI VỀ ĐỂ HIỂN THỊ NGAY
                    'message' => "Sinh viên $studentName đã điểm danh sự kiện này rồi.",
                ]);
            }

            // 3. Lấy tên cán bộ thực hiện điểm danh (Admin/Hỗ trợ)
            $user = auth()->user();
            $staffName = trim(($user->last_name ?? '') . ' ' . ($user->first_name ?? ''));
            if ($staffName === '') {
                $staffName = $user->name ?? ($user->email ?? 'Cán bộ quản lý');
            }

            // 4. Lưu mới vào database
            Attendance::create([
                'studentid'    => $studentId,
                'student_name' => $studentName, // Lưu tên từ QR
                'course_name'  => $eventName,
                'cid'          => $categoryID,
                'time1'        => now(),
                'info_staff'   => $staffName,
                'date_class'   => date('d-m-Y'),
                'subject'      => 'Điểm danh sự kiện',
                'class_id'     => 'EVENT_CTSV',
                'faculty_id'   => 'CTSV',
                'point'        => 0,
            ]);

            return response()->json([
                'status'       => 'success',
                'student_name' => $studentName, // GỬI VỀ ĐỂ HIỂN THỊ NGAY
                'message'      => "Đã lưu: $studentName vào sự kiện $eventName",
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu QR không hợp lệ: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi lưu dữ liệu: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function studentHistory()
    {
        $this->ensureStudent();

        $studentId = $this->resolveStudentId();

        $events = Attendance::query()
            ->from('savsoft_attendance as a')
            ->leftJoin('savsoft_category as c', 'c.cid', '=', 'a.cid')
            ->where('a.studentid', $studentId)
            ->select(
                'c.category_name',
                'c.ctxh_days',
                'a.time1'
            )
            ->orderByDesc('a.time1')
            ->paginate(20);

        return view('diemdanh::student_history', compact('events'));
    }

    public function showEventDetails(Category $category)
    {
        $this->ensureStaff();

        $attendances = $category->attendances()->orderBy('time1', 'desc')->get();

        session([
            'diemdanh_event_id' => $category->cid,
            'diemdanh_event_name' => $category->category_name,
            'diemdanh_ctxh_days' => $category->ctxh_days,
        ]);

        return view('diemdanh::show_details', compact('category', 'attendances'));
    }

    private function resolveStudentId(): string
    {
        $user = auth()->user();
        $studentId = trim((string) ($user->studentid ?? ''));

        if ($studentId !== '') {
            return $studentId;
        }

        // Trường hợp tài khoản sinh viên dùng email: dh52200914@student.stu.edu.vn
        $email = (string) ($user->email ?? '');
        if (str_ends_with(strtolower($email), '@student.stu.edu.vn')) {
            return trim((string) strstr($email, '@', true));
        }

        return '';
    }

    private function parseQrStudent(string $qrData): ?array
    {
        $qrData = trim($qrData);
        if ($qrData === '') {
            return null;
        }

        // Định dạng mới: MSSV_HOTEN_DD.MM.YYYY (hoặc DD-MM-YYYY / DD/MM/YYYY)
        // Hỗ trợ Unicode đầy đủ để lấy tên tiếng Việt có dấu
        if (preg_match('/^([A-Za-z0-9]+)_([\p{L}\p{M}0-9_]+?)(?:_(\d{2}[.\\/-]\d{2}[.\\/-]\d{4}))?$/u', $qrData, $matches)) {
            $studentId = strtoupper(trim((string) $matches[1]));
            $studentName = trim(str_replace('_', ' ', (string) $matches[2]));

            if ($studentId !== '' && $studentName !== '') {
                return [
                    'student_id' => $studentId,
                    'student_name' => $studentName,
                ];
            }
        }

        // Định dạng cũ: MSSV-Ho_Ten
        if (preg_match('/^([A-Za-z0-9]+)-(.+)$/u', $qrData, $matches)) {
            $studentId = strtoupper(trim((string) $matches[1]));
            $studentName = trim(str_replace('_', ' ', (string) $matches[2]));

            if ($studentId !== '' && $studentName !== '') {
                return [
                    'student_id' => $studentId,
                    'student_name' => $studentName,
                ];
            }
        }

        return null;
    }
}
