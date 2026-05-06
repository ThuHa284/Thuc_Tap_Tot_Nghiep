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
            abort(403, 'Ban khong co quyen tao su kien.');
        }

        if (!$adminOnly && !in_array($role, [self::ROLE_ADMIN, self::ROLE_SUPPORT], true)) {
            abort(403, 'Ban khong co quyen truy cap vao khu vuc nay.');
        }

        return $role;
    }

    private function ensureStudent(): void
    {
        if ($this->resolveRole() !== self::ROLE_STUDENT) {
            abort(403, 'Chi sinh vien moi truy cap duoc khu vuc nay.');
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
        ]);

        return redirect()->route('diemdanh.create_event')->with('success', 'Da chon su kien: ' . $category->category_name);
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

        return redirect()->route('diemdanh.create_event')->with('success', 'Da tao/cap nhat su kien thanh cong.');
    }

    public function scanCamera(Request $request)
    {
        $this->ensureStaff();

        if (!$request->session()->has('diemdanh_event_name')) {
            return redirect()->route('diemdanh.create_event')->with('error', 'Vui long chon mot su kien truoc khi quet ma.');
        }

        $eventName = $request->session()->get('diemdanh_event_name');

        return view('diemdanh::scan', compact('eventName'));
    }

    public function showEventQr(Request $request)
    {
        $this->ensureStaff();

        if (!$request->session()->has('diemdanh_event_id')) {
            return redirect()->route('diemdanh.create_event')->with('error', 'Vui long chon mot su kien truoc khi xem QR.');
        }

        $eventName = $request->session()->get('diemdanh_event_name');
        $eventId = (int) $request->session()->get('diemdanh_event_id');
        $signedPath = URL::temporarySignedRoute(
            'diemdanh.student_checkin',
            now()->addHours(2),
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
                ->with('error', 'Ma QR khong hop le hoac da het han. Vui long quet lai ma moi.');
        }

        $studentId = $this->resolveStudentId();
        if ($studentId === '') {
            return redirect()->route('diemdanh.history')
                ->with('error', 'Khong tim thay MSSV trong tai khoan. Vui long lien he quan tri.');
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
                $existingAttendance->save();
            }

            return redirect()->route('diemdanh.history')
                ->with('info', "Ban da diem danh su kien {$event->category_name} roi.");
        }

        Attendance::create([
            'studentid' => $studentId,
            'student_name' => $studentName,
            'course_name' => $event->category_name,
            'cid' => $event->cid,
            'time1' => Carbon::now(),
            'info_staff' => 'Sinh vien tu quet QR',
            'date_class' => Carbon::now()->format('d-m-Y'),
            'subject' => 'Diem danh su kien',
            'class_id' => 'EVENT_CTSV',
            'faculty_id' => 'CTSV',
            'point' => 0,
        ]);

        return redirect()->route('diemdanh.history')
            ->with('success', "Diem danh thanh cong su kien {$event->category_name}.");
    }

    public function saveAttendance(Request $request)
    {
        $this->ensureStaff();

        try {
            $qrData = (string) $request->input('qr_code', '');

            $eventName = session('diemdanh_event_name', 'Su kien khong xac dinh');
            $categoryID = session('diemdanh_event_id');

            if (!$categoryID) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Phien lam viec da het han. Vui long tao lai su kien.',
                ], 419);
            }

            $parsedStudent = $this->parseQrStudent($qrData);
            if (!$parsedStudent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ma QR khong hop le. Ho tro: MSSV-Ho_Ten hoac MSSV_HOTEN_NGAYSINH.',
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
                    'message' => 'Khong lay duoc ten sinh vien tu ma QR. Vui long kiem tra dinh dang ma.',
                ], 422);
            }

            $existingAttendance = Attendance::where('cid', $categoryID)
                ->where('studentid', $studentId)
                ->first();

            if ($existingAttendance) {
                if ($studentName !== '' && (string) $existingAttendance->student_name !== $studentName) {
                    $existingAttendance->student_name = $studentName;
                    $existingAttendance->save();
                }

                return response()->json([
                    'status' => 'info',
                    'message' => "Sinh vien {$studentName} da diem danh su kien nay roi.",
                    'student_name' => $studentName,
                ]);
            }

            $user = auth()->user();
            $staffName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            if ($staffName === '') {
                $staffName = $user->name ?? ($user->email ?? 'Can bo quan ly');
            }

            Attendance::create([
                'studentid' => $studentId,
                'student_name' => $studentName,
                'course_name' => $eventName,
                'cid' => $categoryID,
                'time1' => Carbon::now(),
                'info_staff' => $staffName,
                'date_class' => Carbon::now()->format('d-m-Y'),
                'subject' => 'Diem danh su kien',
                'class_id' => 'EVENT_CTSV',
                'faculty_id' => 'CTSV',
                'point' => 0,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "Da luu: {$studentName} vao su kien $eventName",
                'student_name' => $studentName,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Du lieu QR khong hop le: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Loi luu du lieu: ' . $e->getMessage(),
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
}


