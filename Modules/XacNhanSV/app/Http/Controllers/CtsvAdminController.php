<?php

namespace Modules\XacNhanSV\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\XacNhanSV\Models\EtpForm;
use Modules\XacNhanSV\Models\EtpFormDetail;
use Modules\XacNhanSV\Models\EtpFormStudent;
use Illuminate\Http\Request;

class CtsvAdminController extends Controller
{
    private function checkAdmin()
    {
        if ((int) auth()->user()->su !== 1) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();
        $stats = [
            'pending'  => EtpFormStudent::where('status', EtpFormStudent::STATUS_PENDING)->count(),
            'approved' => EtpFormStudent::where('status', EtpFormStudent::STATUS_APPROVED)->count(),
            'rejected' => EtpFormStudent::where('status', EtpFormStudent::STATUS_REJECTED)->count(),
            'total'    => EtpFormStudent::count(),
        ];
        $recent = EtpFormStudent::with(['form', 'user'])->latest()->limit(10)->get();
        return view('xacnhansv::ctsv.admin.dashboard', compact('stats', 'recent'));
    }

    public function forms()
    {
        $this->checkAdmin();
        $forms = EtpForm::withCount('submissions')->get();
        return view('xacnhansv::ctsv.admin.forms', compact('forms'));
    }

    public function createForm()
    {
        $this->checkAdmin();
        return view('xacnhansv::ctsv.admin.form-create');
    }

    public function storeForm(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields'      => 'nullable|array',
            'fields.*'    => 'nullable|string|max:255',
        ]);
        $form = EtpForm::create($request->only([
            'name', 'description', 'url', 'signtitle', 'signname', 'schoolid', 'schoolname'
        ]));
        if ($request->filled('fields')) {
            foreach (array_values(array_filter($request->fields)) as $order => $label) {
                EtpFormDetail::create([
                    'formid'        => $form->formid,
                    'label'         => $label,
                    'fdetail_order' => $order,
                ]);
            }
        }
        return redirect()->route('xacnhansv.ctsv.admin.forms')
            ->with('success', 'Đã tạo mẫu giấy tờ mới.');
    }

    public function editForm(int $formid)
    {
        $this->checkAdmin();
        $form = EtpForm::with('details')->findOrFail($formid);
        return view('xacnhansv::ctsv.admin.form-edit', compact('form'));
    }

    public function updateForm(Request $request, int $formid)
    {
        $this->checkAdmin();
        $form = EtpForm::findOrFail($formid);
        $request->validate([
            'name'     => 'required|string|max:255',
            'fields'   => 'nullable|array',
            'fields.*' => 'nullable|string|max:255',
        ]);
        $form->update($request->only([
            'name', 'description', 'url', 'signtitle', 'signname', 'schoolid', 'schoolname'
        ]));
        $form->details()->delete();
        if ($request->filled('fields')) {
            foreach (array_values(array_filter($request->fields)) as $order => $label) {
                EtpFormDetail::create([
                    'formid'        => $form->formid,
                    'label'         => $label,
                    'fdetail_order' => $order,
                ]);
            }
        }
        return redirect()->route('xacnhansv.ctsv.admin.forms')
            ->with('success', 'Đã cập nhật mẫu giấy tờ.');
    }

    public function destroyForm(int $formid)
    {
        $this->checkAdmin();
        EtpForm::findOrFail($formid)->delete();
        return back()->with('success', 'Đã xóa mẫu giấy tờ.');
    }

    public function requests(Request $request)
    {
        $this->checkAdmin();
        $query = EtpFormStudent::with(['form', 'user']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('formid')) {
            $query->where('formid', $request->formid);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('studentid', 'like', '%'.$request->search.'%');
            })->orWhereHas('user', function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%');
            });
        }
        $submissions = $query->latest()->paginate(20)->withQueryString();
        $forms       = EtpForm::all();
        return view('xacnhansv::ctsv.admin.requests', compact('submissions', 'forms'));
    }

    public function showRequest(int $id)
    {
        $this->checkAdmin();
        $submission = EtpFormStudent::with(['form.details', 'user', 'fileDetails'])->findOrFail($id);
        return view('xacnhansv::ctsv.admin.request-show', compact('submission'));
    }

    public function approveRequest(int $id)
    {
        $this->checkAdmin();
        EtpFormStudent::findOrFail($id)->update(['status' => EtpFormStudent::STATUS_APPROVED]);
        return back()->with('success', 'Đã duyệt đơn #'.$id.' thành công!');
    }

    public function rejectRequest(Request $request, int $id)
    {
        $this->checkAdmin();
        $request->validate(['note' => 'nullable|string|max:500']);
        EtpFormStudent::findOrFail($id)->update([
            'status' => EtpFormStudent::STATUS_REJECTED,
            'note'   => $request->note,
        ]);
        return back()->with('success', 'Đã từ chối đơn #'.$id.'.');
    }

    // ✅ In hàng loạt
    public function printBulk(Request $request)
    {
        $this->checkAdmin();
        $ids = array_filter(explode(',', $request->query('ids', '')));

        if (empty($ids)) {
            return redirect()->route('xacnhansv.ctsv.admin.requests')
                ->with('error', 'Chưa chọn đơn nào để in.');
        }

        $submissions = EtpFormStudent::with(['form.details', 'user'])
            ->whereIn('id', $ids)
            ->get();

        return view('xacnhansv::ctsv.admin.print-bulk', compact('submissions'));
    }
}