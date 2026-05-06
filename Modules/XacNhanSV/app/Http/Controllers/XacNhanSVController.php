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

    public function showForm(int $formid)
    {
        if ($this->isAdmin()) {
            return redirect()->route('xacnhansv.ctsv.index')
                ->with('error', 'Admin không thể nộp đơn.');
        }

        $form = EtpForm::with('details')->findOrFail($formid);
        $user = auth()->user();

        // ============================================================
        // ✅ KIỂM TRA ĐỂ HIỆN CẢNH BÁO TRÊN FORM
        // ============================================================
        $existingWarning = null;

        // Form 1: Hoãn NVQS — 1 năm/lần
        if ($formid === 1) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 1)
                ->whereYear('created_at', now()->year)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                    EtpFormStudent::STATUS_PRINTED,
                ])
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt',
                    EtpFormStudent::STATUS_PRINTED  => 'đã được in',
                    default => 'đã tồn tại',
                };
                $existingWarning = 'Năm ' . now()->year . ' bạn đã nộp đơn hoãn NVQS vào ngày '
                    . $existing->created_at->format('d/m/Y')
                    . ' — Trạng thái: ' . $statusLabel . '.';
            }
        }

        // Form 2: Xác nhận khác — chặn khi còn đơn chưa xử lý xong
        if ($formid === 2) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 2)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                ])
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt, vui lòng đến nhận giấy',
                    default => 'đang xử lý',
                };
                $existingWarning = 'Bạn còn đơn xác nhận #' . $existing->id
                    . ' (' . $statusLabel . ')'
                    . '. Vui lòng hoàn tất đơn cũ trước khi nộp đơn mới.';
            }
        }

        // Form 3: Vay vốn — 1 năm/lần
        if ($formid === 3) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 3)
                ->whereYear('created_at', now()->year)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                    EtpFormStudent::STATUS_PRINTED,
                ])
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt',
                    EtpFormStudent::STATUS_PRINTED  => 'đã được in',
                    default => 'đã tồn tại',
                };
                $existingWarning = 'Năm ' . now()->year . ' bạn đã nộp đơn vay vốn vào ngày '
                    . $existing->created_at->format('d/m/Y')
                    . ' — Trạng thái: ' . $statusLabel . '.';
            }
        }

        // Form 4: Không bị xử phạt — 6 tháng/lần
        if ($formid === 4) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 4)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                    EtpFormStudent::STATUS_PRINTED,
                ])
                ->where('created_at', '>=', now()->subMonths(6))
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt',
                    EtpFormStudent::STATUS_PRINTED  => 'đã được in',
                    default => 'đã tồn tại',
                };
                $hetHan = $existing->created_at->addMonths(6)->format('d/m/Y');
                $existingWarning = 'Bạn đã nộp đơn xác nhận không bị xử phạt vào ngày '
                    . $existing->created_at->format('d/m/Y')
                    . ' — Trạng thái: ' . $statusLabel
                    . '. Có thể nộp lại sau ngày ' . $hetHan . '.';
            }
        }

        // Form 5: LĐ-TBXH — 1 năm/lần
        if ($formid === 5) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 5)
                ->whereYear('created_at', now()->year)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                    EtpFormStudent::STATUS_PRINTED,
                ])
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt',
                    EtpFormStudent::STATUS_PRINTED  => 'đã được in',
                    default => 'đã tồn tại',
                };
                $existingWarning = 'Năm ' . now()->year . ' bạn đã nộp đơn ưu đãi LĐ-TBXH vào ngày '
                    . $existing->created_at->format('d/m/Y')
                    . ' — Trạng thái: ' . $statusLabel . '.';
            }
        }

        $templateMap = [
            1 => 'xacnhansv::ctsv.forms.form-1-nvqs',
            2 => 'xacnhansv::ctsv.forms.form-2-xac-nhan-khac',
            3 => 'xacnhansv::ctsv.forms.form-3-vay-von',
            4 => 'xacnhansv::ctsv.forms.form-4-hanh-chinh',
            5 => 'xacnhansv::ctsv.forms.form-5-ld-tbxh',
        ];

        $template = $templateMap[$formid] ?? 'xacnhansv::ctsv.forms.form-dynamic';
        return view($template, compact('form', 'user', 'existingWarning'));
    }

    public function store(Request $request, int $formid)
    {
        if ($this->isAdmin()) {
            return redirect()->route('xacnhansv.ctsv.index')
                ->with('error', 'Admin không thể nộp đơn.');
        }

        $user = auth()->user();

        // ============================================================
        // ✅ RÀNG BUỘC THEO TỪNG FORM
        // ============================================================

        // Form 1: Hoãn NVQS — 1 năm/lần
        if ($formid === 1) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 1)
                ->whereYear('created_at', now()->year)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                    EtpFormStudent::STATUS_PRINTED,
                ])
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt',
                    EtpFormStudent::STATUS_PRINTED  => 'đã được in',
                    default => 'đã tồn tại',
                };
                return redirect()->back()->with('error',
                    'Bạn đã nộp đơn hoãn NVQS năm ' . now()->year
                    . ' vào ngày ' . $existing->created_at->format('d/m/Y')
                    . ' — Trạng thái: ' . $statusLabel
                    . '. Mỗi sinh viên chỉ được nộp 1 lần/năm!'
                );
            }
        }

        // Form 2: Xác nhận khác — chặn khi còn đơn chưa xử lý xong
        if ($formid === 2) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 2)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                ])
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt, vui lòng đến nhận giấy',
                    default => 'đang xử lý',
                };
                return redirect()->back()->with('error',
                    'Bạn còn đơn xác nhận #' . $existing->id
                    . ' (' . $statusLabel . ')'
                    . '. Vui lòng hoàn tất đơn cũ trước khi nộp đơn mới!'
                );
            }
        }

        // Form 3: Vay vốn — 1 năm/lần
        if ($formid === 3) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 3)
                ->whereYear('created_at', now()->year)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                    EtpFormStudent::STATUS_PRINTED,
                ])
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt',
                    EtpFormStudent::STATUS_PRINTED  => 'đã được in',
                    default => 'đã tồn tại',
                };
                return redirect()->back()->with('error',
                    'Bạn đã nộp đơn vay vốn năm ' . now()->year
                    . ' vào ngày ' . $existing->created_at->format('d/m/Y')
                    . ' — Trạng thái: ' . $statusLabel
                    . '. Mỗi sinh viên chỉ được nộp 1 lần/năm!'
                );
            }
        }

        // Form 4: Không bị xử phạt hành chính — 6 tháng/lần
        if ($formid === 4) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 4)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                    EtpFormStudent::STATUS_PRINTED,
                ])
                ->where('created_at', '>=', now()->subMonths(6))
                ->first();

            if ($existing) {
                $hetHanNopLai = $existing->created_at->addMonths(6)->format('d/m/Y');
                $statusLabel  = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt',
                    EtpFormStudent::STATUS_PRINTED  => 'đã được in',
                    default => 'đã tồn tại',
                };
                return redirect()->back()->with('error',
                    'Bạn đã nộp đơn xác nhận không bị xử phạt vào ngày '
                    . $existing->created_at->format('d/m/Y')
                    . ' — Trạng thái: ' . $statusLabel
                    . '. Bạn có thể nộp lại sau ngày ' . $hetHanNopLai . '!'
                );
            }
        }

        // Form 5: LĐ-TBXH — 1 năm/lần
        if ($formid === 5) {
            $existing = EtpFormStudent::where('uid', $user->uid)
                ->where('formid', 5)
                ->whereYear('created_at', now()->year)
                ->whereIn('status', [
                    EtpFormStudent::STATUS_PENDING,
                    EtpFormStudent::STATUS_APPROVED,
                    EtpFormStudent::STATUS_PRINTED,
                ])
                ->first();

            if ($existing) {
                $statusLabel = match((int)$existing->status) {
                    EtpFormStudent::STATUS_PENDING  => 'đang chờ duyệt',
                    EtpFormStudent::STATUS_APPROVED => 'đã được duyệt',
                    EtpFormStudent::STATUS_PRINTED  => 'đã được in',
                    default => 'đã tồn tại',
                };
                return redirect()->back()->with('error',
                    'Bạn đã nộp đơn ưu đãi LĐ-TBXH năm ' . now()->year
                    . ' vào ngày ' . $existing->created_at->format('d/m/Y')
                    . ' — Trạng thái: ' . $statusLabel
                    . '. Mỗi sinh viên chỉ được nộp 1 lần/năm!'
                );
            }
        }

        // ============================================================
        // ✅ VALIDATE & LƯU ĐƠN
        // ============================================================

        $form = EtpForm::with('details')->findOrFail($formid);

        $rules = [
            'date1'            => 'nullable|date',
            'date2'            => 'nullable|date|after_or_equal:date1',
            'note'             => 'nullable|string|max:1000',
            'get_at'           => 'nullable|string|max:10',
            'ReceivingAddress' => 'nullable|string|max:300',
        ];

        $request->validate($rules);

        $excludeKeys = ['_token', 'date1', 'date2', 'note', 'get_at', 'ReceivingAddress'];
        $formData = [];
        foreach ($request->except($excludeKeys) as $key => $value) {
            $formData[$key] = $value;
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

    public function myRequests()
    {
        $user = auth()->user();

        if ($this->isAdmin()) {
            $submissions = EtpFormStudent::with('form')
                ->latest()
                ->paginate(10);
            return view('xacnhansv::admin.all-requests', compact('submissions'));
        }

        $submissions = EtpFormStudent::with('form')
            ->where('uid', $user->uid)
            ->latest()
            ->paginate(10);
        return view('xacnhansv::ctsv.my-requests', compact('submissions'));
    }

    public function show(int $id)
    {
        $user = auth()->user();

        if ($this->isAdmin()) {
            $submission = EtpFormStudent::with(['form.details', 'fileDetails'])
                ->findOrFail($id);
        } else {
            $submission = EtpFormStudent::with(['form.details', 'fileDetails'])
                ->where('uid', $user->uid)
                ->findOrFail($id);
        }

        $ngayLayGiay = null;
        $ngayHetHan  = null;
        if (in_array((int)$submission->status, [EtpFormStudent::STATUS_APPROVED, EtpFormStudent::STATUS_PRINTED])
            && $submission->updated_at) {
            $ngayLayGiay = Carbon::parse($submission->updated_at);
            $ngayHetHan  = Carbon::parse($submission->updated_at)->addDays(3);
        }

        $templateMap = [
            1 => 'xacnhansv::ctsv.show-forms.form-1-nvqs',
            2 => 'xacnhansv::ctsv.show-forms.form-2-xac-nhan-khac',
            3 => 'xacnhansv::ctsv.show-forms.form-3-vay-von',
            4 => 'xacnhansv::ctsv.show-forms.form-4-hanh-chinh',
            5 => 'xacnhansv::ctsv.show-forms.form-5-ld-tbxh',
        ];

        $template = $templateMap[$submission->formid] ?? 'xacnhansv::ctsv.show-forms.form-dynamic';
        return view($template, compact('submission', 'ngayLayGiay', 'ngayHetHan'));
    }

    public function approve(int $id)
    {
        if (!$this->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $submission = EtpFormStudent::findOrFail($id);
        $submission->update(['status' => EtpFormStudent::STATUS_APPROVED]);

        return redirect()->back()->with('success', 'Đã duyệt đơn thành công.');
    }

    public function reject(int $id)
    {
        if (!$this->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $submission = EtpFormStudent::findOrFail($id);
        $submission->update(['status' => EtpFormStudent::STATUS_REJECTED]);

        return redirect()->back()->with('success', 'Đã từ chối đơn.');
    }
}