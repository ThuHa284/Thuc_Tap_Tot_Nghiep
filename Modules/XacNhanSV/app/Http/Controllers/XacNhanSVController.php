<?php

namespace Modules\XacNhanSV\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\XacNhanSV\Models\EtpForm;
use Modules\XacNhanSV\Models\EtpFormStudent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class XacNhanSVController extends Controller
{
    // ✅ Helper kiểm tra quyền Admin
    private function isAdmin(): bool
    {
        return auth()->user()->su == 1;
    }

    // ✅ Helper kiểm tra quyền Sinh viên hoặc Hỗ trợ
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
        // ❌ Admin không nộp đơn
        if ($this->isAdmin()) {
            return redirect()->route('xacnhansv.ctsv.index')
                ->with('error', 'Admin không thể nộp đơn.');
        }

        $form = EtpForm::with('details')->findOrFail($formid);
        $user = auth()->user();

        $templateMap = [
            1 => 'xacnhansv::ctsv.forms.form-1-nvqs',
            2 => 'xacnhansv::ctsv.forms.form-2-xac-nhan-khac',
            3 => 'xacnhansv::ctsv.forms.form-3-vay-von',
            4 => 'xacnhansv::ctsv.forms.form-4-hanh-chinh',
            5 => 'xacnhansv::ctsv.forms.form-5-ld-tbxh',
        ];

        $template = $templateMap[$formid] ?? 'xacnhansv::ctsv.create';
        return view($template, compact('form', 'user'));
    }

    public function store(Request $request, int $formid)
    {
        // ❌ Admin không nộp đơn
        if ($this->isAdmin()) {
            return redirect()->route('xacnhansv.ctsv.index')
                ->with('error', 'Admin không thể nộp đơn.');
        }

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

        $user = auth()->user();

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
            'ReceivingAddress' => $request->ReceivingAddress ?? 'Phòng CTSV',
        ]);

        return redirect()
            ->route('xacnhansv.ctsv.my-requests')
            ->with('success', 'Đã nộp đơn thành công! Vui lòng chờ admin duyệt.');
    }

    public function myRequests()
    {
        $user = auth()->user();

        if ($this->isAdmin()) {
            // Admin xem TẤT CẢ đơn của mọi sinh viên
            $submissions = EtpFormStudent::with('form')
                ->latest()
                ->paginate(10);
            return view('xacnhansv::admin.all-requests', compact('submissions'));
        }

        // Sinh viên / Hỗ trợ chỉ xem đơn của chính mình
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
            // Admin xem được đơn của bất kỳ sinh viên nào
            $submission = EtpFormStudent::with(['form.details', 'fileDetails'])
                ->findOrFail($id);
        } else {
            // Sinh viên / Hỗ trợ chỉ xem đơn của chính mình
            $submission = EtpFormStudent::with(['form.details', 'fileDetails'])
                ->where('uid', $user->uid)
                ->findOrFail($id);
        }

        $ngayLayGiay = null;
        $ngayHetHan  = null;
        if ((int)$submission->status === EtpFormStudent::STATUS_APPROVED && $submission->updated_at) {
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

        $template = $templateMap[$submission->formid] ?? 'xacnhansv::ctsv.show';
        return view($template, compact('submission', 'ngayLayGiay', 'ngayHetHan'));
    }

    // ✅ Hàm duyệt đơn - CHỈ Admin mới dùng được
    public function approve(int $id)
    {
        if (!$this->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $submission = EtpFormStudent::findOrFail($id);
        $submission->update(['status' => EtpFormStudent::STATUS_APPROVED]);

        return redirect()->back()->with('success', 'Đã duyệt đơn thành công.');
    }

    // ✅ Hàm từ chối đơn - CHỈ Admin mới dùng được
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