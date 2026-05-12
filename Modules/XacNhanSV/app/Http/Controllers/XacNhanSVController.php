<?php

namespace Modules\XacNhanSV\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\XacNhanSV\Models\EtpForm;
use Modules\XacNhanSV\Models\EtpFormStudent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class XacNhanSVController extends Controller
{
    private function isAdmin(): bool
    {
        return auth()->user()->su == 1;
    }

    private function isStudentOrSupport(): bool
    {
        return in_array(auth()->user()->su, [0, -1]);
    }

    // ================================================================
    // Trả về ngày bắt đầu và kết thúc của học kỳ hiện tại:
    //   Kỳ 1 : tháng 8  – 12
    //   Kỳ 2 : tháng 1  – 5
    //   Kỳ hè: tháng 6  – 7
    // ================================================================
    private function getSemesterDateRange(): array
    {
        $month = now()->month;
        $year  = now()->year;

        if ($month >= 8 && $month <= 12) {
            return [
                Carbon::create($year, 8, 1)->startOfDay(),
                Carbon::create($year, 12, 31)->endOfDay(),
            ];
        } elseif ($month >= 1 && $month <= 5) {
            return [
                Carbon::create($year, 1, 1)->startOfDay(),
                Carbon::create($year, 5, 31)->endOfDay(),
            ];
        } else {
            return [
                Carbon::create($year, 6, 1)->startOfDay(),
                Carbon::create($year, 7, 31)->endOfDay(),
            ];
        }
    }

    // ================================================================
    public function index()
    {
        $user = auth()->user();

        if ($this->isAdmin()) {
            $stats = [
                'pending'  => EtpFormStudent::where('status', EtpFormStudent::STATUS_PENDING)->count(),
                'approved' => EtpFormStudent::where('status', EtpFormStudent::STATUS_APPROVED)->count(),
                'rejected' => EtpFormStudent::where('status', EtpFormStudent::STATUS_REJECTED)->count(),
                'printed'  => EtpFormStudent::where('status', EtpFormStudent::STATUS_PRINTED)->count(),
                'total'    => EtpFormStudent::count(),
            ];
            $recent = EtpFormStudent::with(['form', 'user'])->latest()->limit(10)->get();
            return view('xacnhansv::ctsv.admin.dashboard', compact('stats', 'recent'));
        }

        $forms = EtpForm::withCount('submissions')->get();
        return view('xacnhansv::ctsv.index', compact('forms'));
    }

    // ================================================================
    public function showForm(int $formid)
    {
        if ($this->isAdmin()) {
            return redirect()->route('xacnhansv.index')
                ->with('error', 'Admin không thể nộp đơn.');
        }

        $form = EtpForm::with('details')->findOrFail($formid);
        $user = auth()->user();

        // ✅ YÊU CẦU 3: Lấy ngày sinh từ DB, không để SV nhập tay
        $dob = !empty($user->birthdate)
            ? Carbon::parse($user->birthdate)->format('d/m/Y')
            : null;

        // ✅ YÊU CẦU 1: Kiểm tra đơn trong kỳ học hiện tại
        [$semStart, $semEnd] = $this->getSemesterDateRange();

        $existingWarning = null;
        $cannotSubmit    = false;

        $existing = EtpFormStudent::where('uid', $user->uid)
            ->where('formid', $formid)
            ->whereBetween('created_at', [$semStart, $semEnd])
            ->whereIn('status', [
                EtpFormStudent::STATUS_PENDING,
                EtpFormStudent::STATUS_APPROVED,
                EtpFormStudent::STATUS_PRINTED,
            ])
            ->first();

        if ($existing) {
            $cannotSubmit    = true;
            $statusLabel     = $this->statusLabel((int) $existing->status);
            $existingWarning = 'Học kỳ này bạn đã nộp đơn vào ngày '
                . $existing->created_at->format('d/m/Y')
                . ' — Trạng thái: ' . $statusLabel . '.'
                . ' Mỗi kỳ chỉ được nộp 1 đơn.'
                . ' <a href="' . route('xacnhansv.ctsv.my-requests.show', $existing->id) . '">Xem đơn #' . $existing->id . '</a>.';
        }

        $templateMap = [
            1 => 'xacnhansv::ctsv.forms.form-1-nvqs',
            2 => 'xacnhansv::ctsv.forms.form-2-xac-nhan-khac',
            3 => 'xacnhansv::ctsv.forms.form-3-vay-von',
            4 => 'xacnhansv::ctsv.forms.form-4-hanh-chinh',
            5 => 'xacnhansv::ctsv.forms.form-5-ld-tbxh',
        ];

        $template = $templateMap[$formid] ?? 'xacnhansv::ctsv.forms.form-dynamic';
        return view($template, compact('form', 'user', 'existingWarning', 'cannotSubmit', 'dob'));
    }

    // ================================================================
    public function store(Request $request, int $formid)
    {
        if ($this->isAdmin()) {
            return redirect()->route('xacnhansv.index')
                ->with('error', 'Admin không thể nộp đơn.');
        }

        $user = auth()->user();

        // ✅ YÊU CẦU 1: Chặn nộp nếu đã có đơn trong kỳ học hiện tại
        [$semStart, $semEnd] = $this->getSemesterDateRange();

        $existing = EtpFormStudent::where('uid', $user->uid)
            ->where('formid', $formid)
            ->whereBetween('created_at', [$semStart, $semEnd])
            ->whereIn('status', [
                EtpFormStudent::STATUS_PENDING,
                EtpFormStudent::STATUS_APPROVED,
                EtpFormStudent::STATUS_PRINTED,
            ])
            ->first();

        if ($existing) {
            $statusLabel = $this->statusLabel((int) $existing->status);
            return redirect()->back()->with('error',
                'Bạn đã nộp đơn này trong học kỳ hiện tại vào ngày '
                . $existing->created_at->format('d/m/Y')
                . ' — Trạng thái: ' . $statusLabel
                . '. Mỗi kỳ học chỉ được nộp 1 đơn!'
            );
        }

        // ✅ VALIDATE & LƯU ĐƠN
        $form = EtpForm::with('details')->findOrFail($formid);

        $request->validate([
            'date1'            => 'nullable|date',
            'date2'            => 'nullable|date|after_or_equal:date1',
            'note'             => 'nullable|string|max:1000',
            'get_at'           => 'nullable|string|max:10',
            'ReceivingAddress' => 'nullable|string|max:300',
        ]);

        $excludeKeys = ['_token', 'date1', 'date2', 'note', 'get_at', 'ReceivingAddress'];
        $formData    = [];
        foreach ($request->except($excludeKeys) as $key => $value) {
            $formData[$key] = $value;
        }

        // ✅ YÊU CẦU 3: Lưu ngày sinh từ DB vào data (không lấy từ request)
        if (!empty($user->birthdate)) {
            $formData['_dob'] = Carbon::parse($user->birthdate)->format('d/m/Y');
        }

        EtpFormStudent::create([
            'uid'              => $user->uid,
            'studentid'        => $user->studentid,
            'formid'           => $formid,
            'date1'            => $request->date1,
            'date2'            => $request->date2,
            'note'             => $request->note,
            'data'             => $formData,
            'status'           => EtpFormStudent::STATUS_PENDING,
            'get_at'           => $request->get_at ?? 'truc_tiep',
            'ReceivingAddress' => $request->get_at === 'buu_dien' ? $request->ReceivingAddress : 'Phòng CTSV',
        ]);

        return redirect()
            ->route('xacnhansv.ctsv.my-requests')
            ->with('success', 'Đã nộp đơn thành công! Vui lòng chờ admin duyệt.');
    }

    // ================================================================
    // ✅ YÊU CẦU 2: Sinh viên xóa đơn — chỉ khi đơn PENDING (chưa duyệt)
    // ================================================================
    public function destroy(int $id)
    {
        if ($this->isAdmin()) {
            abort(403, 'Admin không dùng chức năng này.');
        }

        $user       = auth()->user();
        $submission = EtpFormStudent::where('uid', $user->uid)->findOrFail($id);

        if ((int) $submission->status !== EtpFormStudent::STATUS_PENDING) {
            return redirect()->back()->with('error',
                'Không thể xóa đơn #' . $id
                . ' vì đơn đã được xử lý (trạng thái: '
                . $this->statusLabel((int) $submission->status) . ').'
            );
        }

        $submission->delete();

        return redirect()
            ->route('xacnhansv.ctsv.my-requests')
            ->with('success', 'Đã xóa đơn #' . $id . ' thành công.');
    }

    // ================================================================
    public function myRequests()
    {
        $user = auth()->user();

        if ($this->isAdmin()) {
            $submissions = EtpFormStudent::with('form')->latest()->paginate(10);
            return view('xacnhansv::admin.all-requests', compact('submissions'));
        }

        $submissions = EtpFormStudent::with('form')
            ->where('uid', $user->uid)
            ->latest()
            ->paginate(10);
        return view('xacnhansv::ctsv.my-requests', compact('submissions'));
    }

    // ================================================================
    public function show(int $id)
    {
        $user = auth()->user();

        if ($this->isAdmin()) {
            $submission = EtpFormStudent::with(['form.details', 'fileDetails'])->findOrFail($id);
        } else {
            $submission = EtpFormStudent::with(['form.details', 'fileDetails'])
                ->where('uid', $user->uid)
                ->findOrFail($id);
        }

        $ngayLayGiay = null;
        $ngayHetHan  = null;
        if (in_array((int) $submission->status, [EtpFormStudent::STATUS_APPROVED, EtpFormStudent::STATUS_PRINTED])
            && $submission->updated_at) {
            $ngayLayGiay = Carbon::parse($submission->updated_at);
            $ngayHetHan  = Carbon::parse($submission->updated_at)->addDays(3);
        }

        // ✅ YÊU CẦU 2: Cho view biết SV có thể xóa đơn không
        $canDelete = !$this->isAdmin()
            && (int) $submission->status === EtpFormStudent::STATUS_PENDING;

        $templateMap = [
            1 => 'xacnhansv::ctsv.show-forms.form-1-nvqs',
            2 => 'xacnhansv::ctsv.show-forms.form-2-xac-nhan-khac',
            3 => 'xacnhansv::ctsv.show-forms.form-3-vay-von',
            4 => 'xacnhansv::ctsv.show-forms.form-4-hanh-chinh',
            5 => 'xacnhansv::ctsv.show-forms.form-5-ld-tbxh',
        ];

        $template = $templateMap[$submission->formid] ?? 'xacnhansv::ctsv.show-forms.form-dynamic';
        return view($template, compact('submission', 'ngayLayGiay', 'ngayHetHan', 'canDelete'));
    }

    // ================================================================
    public function approve(int $id)
    {
        if (!$this->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        EtpFormStudent::findOrFail($id)->update(['status' => EtpFormStudent::STATUS_APPROVED]);
        return redirect()->back()->with('success', 'Đã duyệt đơn thành công.');
    }

    public function reject(int $id)
    {
        if (!$this->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        EtpFormStudent::findOrFail($id)->update(['status' => EtpFormStudent::STATUS_REJECTED]);
        return redirect()->back()->with('success', 'Đã từ chối đơn.');
    }

    // ================================================================
    // HELPER
    // ================================================================
    private function statusLabel(int $status): string
    {
        return match($status) {
            EtpFormStudent::STATUS_PENDING  => 'Đang chờ duyệt',
            EtpFormStudent::STATUS_APPROVED => 'Đã được duyệt',
            EtpFormStudent::STATUS_PRINTED  => 'Đã in',
            EtpFormStudent::STATUS_REJECTED => 'Đã từ chối',
            default                         => 'Không rõ',
        };
    }
}