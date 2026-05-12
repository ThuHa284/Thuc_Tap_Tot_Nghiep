<?php

namespace Modules\DiemDanh\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
            abort(403, 'Bạn không có quyền truy cập khu vực này.');
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
        $canUseQrTools = in_array($role, [self::ROLE_ADMIN, self::ROLE_SUPPORT], true);

        $recentEvents = Category::orderBy('cid', 'desc')->take(10)->get();

        return view('diemdanh::create_event', compact('recentEvents', 'canCreateEvent', 'canUseQrTools'));
    }

    public function selectEvent(Category $category)
    {
        $this->ensureStaff();

        session([
            'diemdanh_event_id' => $category->cid,
            'diemdanh_event_name' => $category->category_name,
            'diemdanh_ctxh_days' => $category->ctxh_days,
            'diemdanh_training_points' => $category->training_points,
            'diemdanh_attendance_start_at' => optional($category->attendance_start_at)->format('Y-m-d\TH:i'),
            'diemdanh_attendance_end_at' => optional($category->attendance_end_at)->format('Y-m-d\TH:i'),
        ]);

        return redirect()->route('diemdanh.create_event')->with('success', 'Đã chọn sự kiện: ' . $category->category_name);
    }

    public function storeEvent(Request $request)
    {
        $this->ensureStaff(adminOnly: true);

        $request->validate([
            'event_name' => 'required|string|max:255',
            'ctxh_days' => 'nullable|numeric|min:0|max:5',
            'training_points' => 'nullable|integer|min:0|max:3',
            'attendance_start_at' => 'required|date',
            'attendance_end_at' => 'required|date|after:attendance_start_at',
        ]);

        $eventName = (string) $request->input('event_name');

        $ctxhDaysRaw = (float) $request->input('ctxh_days', 0);
        $ctxhDays = round($ctxhDaysRaw, 1);

        $trainingPoints = (int) $request->input('training_points', 0);

        if ($ctxhDays > 0 && $trainingPoints > 0) {
            return redirect()->back()->withInput()->withErrors([
                'training_points' => 'Sự kiện chỉ được có 1 trong 2: hoặc ngày CTXH, hoặc điểm rèn luyện.',
                'ctxh_days' => 'Sự kiện chỉ được có 1 trong 2: hoặc ngày CTXH, hoặc điểm rèn luyện.',
            ]);
        }

        if ($ctxhDays > 0 && $trainingPoints !== 0) {
            return redirect()->back()->withInput()->withErrors([
                'training_points' => 'Nếu nhập ctxh_days > 0 thì training_points phải bằng 0.',
            ]);
        }

        if ($trainingPoints > 0 && $ctxhDays != 0.0) {
            return redirect()->back()->withInput()->withErrors([
                'ctxh_days' => 'Nếu nhập training_points > 0 thì ctxh_days phải bằng 0.',
            ]);
        }

        if ($ctxhDays > 0) {
            $steps = $ctxhDays / 0.5;
            if (abs($steps - round($steps)) > 1e-6) {
                return redirect()->back()->withInput()->withErrors([
                    'ctxh_days' => 'CTXH phải có bước nhảy 0.5 (ví dụ: 0.5, 1.0, 1.5...).',
                ]);
            }
        }

        if ($trainingPoints > 0 && ($trainingPoints < 1 || $trainingPoints > 3)) {
            return redirect()->back()->withInput()->withErrors([
                'training_points' => 'Điểm rèn luyện chỉ được trong khoảng 1 đến 3.',
            ]);
        }

        $startAt = Carbon::parse((string) $request->input('attendance_start_at'));
        $endAt = Carbon::parse((string) $request->input('attendance_end_at'));

        $category = Category::updateOrCreate(
            ['category_name' => $eventName],
            [
                'ctxh_days' => $ctxhDays,
                'training_points' => $trainingPoints,
                'attendance_start_at' => $startAt,
                'attendance_end_at' => $endAt,
            ]
        );

        session([
            'diemdanh_event_id' => $category->cid,
            'diemdanh_event_name' => $category->category_name,
            'diemdanh_ctxh_days' => $category->ctxh_days,
            'diemdanh_training_points' => $category->training_points,
            'diemdanh_attendance_start_at' => optional($category->attendance_start_at)->format('Y-m-d\TH:i'),
            'diemdanh_attendance_end_at' => optional($category->attendance_end_at)->format('Y-m-d\TH:i'),
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
            return redirect()->route('diemdanh.create_event')->with('error', 'Vui lòng chọn một sự kiện trước khi xem QR.');
        }

        $eventName = $request->session()->get('diemdanh_event_name');
        $eventId = (int) $request->session()->get('diemdanh_event_id');

        $category = Category::query()->find($eventId);
        if (!$category) {
            return redirect()->route('diemdanh.create_event')->with('error', 'Không tìm thấy sự kiện đang chọn.');
        }

        $expiresAt = now()->addHours(2);
        if ($category->attendance_end_at && $category->attendance_end_at->lt($expiresAt)) {
            $expiresAt = $category->attendance_end_at->copy();
        }

        $signedPath = URL::temporarySignedRoute(
            'diemdanh.student_checkin',
            $expiresAt,
            ['event' => $eventId],
            false
        );

        $baseUrl = rtrim((string) config('app.url'), '/');
        $qrData = $baseUrl . $signedPath;

        return view('diemdanh::show_qr', compact('eventName', 'qrData'));
    }

    public function studentCheckin(Request $request, Category $event)
    {
        $this->ensureStudent();

        if (!$request->hasValidSignature(false)) {
            return redirect()->route('diemdanh.history')
                ->with('error', 'Mã QR không hợp lệ hoặc đã hết hạn. Vui lòng quét lại mã mới.');
        }

        if (!$this->isEventAttendanceOpen($event)) {
            return redirect()->route('diemdanh.history')
                ->with('error', $this->buildEventClosedMessage($event));
        }

        $studentId = $this->resolveStudentId();
        if ($studentId === '') {
            return redirect()->route('diemdanh.history')
                ->with('error', 'Không tìm thấy MSSV trong tài khoản. Vui lòng liên hệ quản trị.');
        }

        $user = auth()->user();
        $studentName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        if ($studentName === '') {
            $studentName = $user->name ?? ($user->email ?? $studentId);
        }

        $existingAttendance = Attendance::where('cid', $event->cid)
            ->where('studentid', $studentId)
            ->first();

        if ($existingAttendance) {
            if ((string) $existingAttendance->student_name !== $studentName) {
                $existingAttendance->student_name = $studentName;
            }

            $point = ($event->training_points ?? 0) > 0 ? (int) $event->training_points : 0;
            if ((int) ($existingAttendance->point ?? 0) !== $point) {
                $existingAttendance->point = $point;
            }

            $existingAttendance->save();

            return redirect()->route('diemdanh.history')
                ->with('info', "Bạn đã điểm danh sự kiện {$event->category_name} rồi.");
        }

        $point = ($event->training_points ?? 0) > 0 ? (int) $event->training_points : 0;

        Attendance::create([
            'studentid' => $studentId,
            'student_name' => $studentName,
            'course_name' => $event->category_name,
            'cid' => $event->cid,
            'time1' => Carbon::now(),
            'info_staff' => 'Sinh viên tự quét QR',
            'date_class' => Carbon::now()->format('d-m-Y'),
            'subject' => 'Điểm danh sự kiện',
            'class_id' => 'EVENT_CTSV',
            'faculty_id' => 'CTSV',
            'point' => $point,
        ]);

        return redirect()->route('diemdanh.history')
            ->with('success', "Điểm danh thành công sự kiện {$event->category_name}.");
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

            $category = Category::query()->where('cid', $categoryID)->first();
            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sự kiện. Vui lòng chọn lại sự kiện.',
                ], 404);
            }

            if (!$this->isEventAttendanceOpen($category)) {
                return response()->json([
                    'status' => 'error',
                    'message' => $this->buildEventClosedMessage($category),
                ], 422);
            }

            $parsedStudent = $this->parseQrStudent($qrData);
            if (!$parsedStudent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mã QR không hợp lệ. Hỗ trợ: JSON, MSSV-HoTen, MSSV_HOTEN_NGAYSINH hoặc chỉ MSSV.',
                ], 400);
            }

            $studentId = $parsedStudent['student_id'];
            $studentName = trim((string) ($parsedStudent['student_name'] ?? ''));
            if ($studentName === '' || strcasecmp($studentName, 'Chua co ten') === 0) {
                $resolvedName = $this->resolveStudentNameById($studentId);
                if ($resolvedName !== '') {
                    $studentName = $resolvedName;
                }
            }

            if ($studentName === '') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không lấy được tên sinh viên từ mã QR. Vui lòng kiểm tra định dạng mã.',
                ], 422);
            }

            $existingAttendance = Attendance::where('cid', $categoryID)
                ->where('studentid', $studentId)
                ->first();

            if ($existingAttendance) {
                if ($studentName !== '' && (string) $existingAttendance->student_name !== $studentName) {
                    $existingAttendance->student_name = $studentName;
                }

                $point = ($category->training_points ?? 0) > 0 ? (int) $category->training_points : 0;
                if ((int) ($existingAttendance->point ?? 0) !== $point) {
                    $existingAttendance->point = $point;
                }

                $existingAttendance->save();

                return response()->json([
                    'status' => 'info',
                    'message' => "Sinh viên {$studentName} đã điểm danh sự kiện này rồi.",
                    'student_name' => $studentName,
                ]);
            }

            $user = auth()->user();
            $staffName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            if ($staffName === '') {
                $staffName = $user->name ?? ($user->email ?? 'Cán bộ quản lý');
            }

            $point = ($category->training_points ?? 0) > 0 ? (int) $category->training_points : 0;

            Attendance::create([
                'studentid' => $studentId,
                'student_name' => $studentName,
                'course_name' => $eventName,
                'cid' => $categoryID,
                'time1' => Carbon::now(),
                'info_staff' => $staffName,
                'date_class' => Carbon::now()->format('d-m-Y'),
                'subject' => 'Điểm danh sự kiện',
                'class_id' => 'EVENT_CTSV',
                'faculty_id' => 'CTSV',
                'point' => $point,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "Đã lưu: {$studentName} vào sự kiện {$eventName}",
                'student_name' => $studentName,
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
                'c.training_points',
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
        $missingStudentIds = $attendances
            ->filter(fn ($attendance) => trim((string) $attendance->student_name) === '')
            ->pluck('studentid')
            ->filter()
            ->unique()
            ->values();

        if ($missingStudentIds->isNotEmpty()) {
            $userMap = User::query()
                ->whereIn('studentid', $missingStudentIds->all())
                ->get()
                ->mapWithKeys(function ($user) {
                    $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                    $name = $fullName !== '' ? $fullName : trim((string) ($user->username ?? ''));
                    return [strtoupper((string) $user->studentid) => $name];
                });

            foreach ($attendances as $attendance) {
                if (trim((string) $attendance->student_name) !== '') {
                    continue;
                }

                $lookupId = strtoupper((string) $attendance->studentid);
                $resolvedName = trim((string) ($userMap[$lookupId] ?? ''));
                if ($resolvedName === '') {
                    continue;
                }

                $attendance->student_name = $resolvedName;
                $attendance->save();
            }
        }

        foreach ($attendances as $attendance) {
            $name = trim((string) $attendance->student_name);
            if ($name === '') {
                $name = $this->resolveStudentNameById((string) $attendance->studentid);
            }
            $attendance->display_name = $name;
        }

        session([
            'diemdanh_event_id' => $category->cid,
            'diemdanh_event_name' => $category->category_name,
            'diemdanh_ctxh_days' => $category->ctxh_days,
            'diemdanh_training_points' => $category->training_points,
            'diemdanh_attendance_start_at' => optional($category->attendance_start_at)->format('Y-m-d\TH:i'),
            'diemdanh_attendance_end_at' => optional($category->attendance_end_at)->format('Y-m-d\TH:i'),
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

        $json = json_decode($qrData, true);
        if (is_array($json)) {
            $studentId = strtoupper(trim((string) ($json['studentid'] ?? $json['mssv'] ?? $json['student_id'] ?? '')));
            $studentName = trim((string) ($json['name'] ?? $json['student_name'] ?? $json['full_name'] ?? ''));
            if ($studentId !== '') {
                return [
                    'student_id' => $studentId,
                    'student_name' => $this->normalizeStudentName($studentName),
                ];
            }
        }

        if (preg_match('/^([A-Za-z0-9]+)[_-](.+)$/u', $qrData, $matches)) {
            $studentId = strtoupper(trim((string) $matches[1]));
            $studentNameRaw = trim((string) $matches[2]);
            $studentNameRaw = preg_replace('/([_ -]?\d{2}[.\\/-]\d{2}[.\\/-]\d{4})$/u', '', $studentNameRaw) ?? $studentNameRaw;
            $studentName = $this->normalizeStudentName($studentNameRaw);
            $studentNameFromQr = $this->normalizeStudentName((string) $matches[2]);

            if ($studentId !== '') {
                if ($studentName === '' && $studentNameFromQr !== '') {
                    $studentName = $studentNameFromQr;
                }
                if ($studentName === '') {
                    $studentName = $this->resolveStudentNameById($studentId);
                }
                return [
                    'student_id' => $studentId,
                    'student_name' => $studentName,
                ];
            }
        }

        if (preg_match('/^[A-Za-z0-9]+$/', $qrData)) {
            $studentId = strtoupper(trim($qrData));
            return [
                'student_id' => $studentId,
                'student_name' => $this->resolveStudentNameById($studentId),
            ];
        }

        return null;
    }

    private function resolveStudentNameById(string $studentId): string
    {
        $user = User::query()
            ->where('studentid', $studentId)
            ->orWhere('email', 'like', $studentId . '@%')
            ->first();

        if (!$user) {
            return '';
        }

        $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
        if ($fullName !== '') {
            return $fullName;
        }

        return trim((string) ($user->username ?? ''));
    }

    private function normalizeStudentName(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return '';
        }

        $name = preg_replace('/[_-]+/u', ' ', $name) ?? $name;
        $name = preg_replace('/\s+/u', ' ', $name) ?? $name;

        return trim($name);
    }

    private function isEventAttendanceOpen(Category $category): bool
    {
        $now = now();

        if ($category->attendance_start_at && $now->lt($category->attendance_start_at)) {
            return false;
        }

        if ($category->attendance_end_at && $now->gt($category->attendance_end_at)) {
            return false;
        }

        return true;
    }

    private function buildEventClosedMessage(Category $category): string
    {
        $start = $category->attendance_start_at?->format('H:i d/m/Y');
        $end = $category->attendance_end_at?->format('H:i d/m/Y');

        if ($start && $end) {
            return "Sự kiện chỉ điểm danh trong khung {$start} - {$end}.";
        }

        return 'Sự kiện hiện không mở điểm danh.';
    }
}
