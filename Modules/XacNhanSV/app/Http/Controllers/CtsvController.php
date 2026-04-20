<?php

namespace Modules\XacNhanSV\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\XacNhanSV\Models\EtpForm;
use Modules\XacNhanSV\Models\EtpFormStudent;
use Modules\XacNhanSV\Models\EtpFormStudentDetail;
use Illuminate\Http\Request;

class CtsvController extends Controller
{
    public function index()
    {
        $forms = EtpForm::withCount('submissions')->get();
        return view('xacnhansv::ctsv.index', compact('forms'));
    }

    public function showForm(int $formid)
    {
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
        $form = EtpForm::with('details')->findOrFail($formid);

        $rules = [
            'date1'            => 'nullable|date',
            'date2'            => 'nullable|date|after_or_equal:date1',
            'note'             => 'nullable|string|max:1000',
            'get_at'           => 'nullable|string|max:10',
            'ReceivingAddress' => 'nullable|string|max:300',
            'attachments.*'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        foreach ($form->details as $detail) {
            $rules['field_' . $detail->fdetailid] = 'nullable|string|max:500';
        }

        $request->validate($rules);

        $dynamicData = [];
        foreach ($form->details as $detail) {
            $key = 'field_' . $detail->fdetailid;
            $dynamicData[$detail->label] = $request->input($key, '');
        }

        $user = auth()->user();

        $submission = EtpFormStudent::create([
            'uid'              => $user->uid,
            'studentid'        => $user->studentid,
            'formid'           => $formid,
            'date1'            => $request->date1,
            'date2'            => $request->date2,
            'note'             => $request->note,
            'data'             => $dynamicData,
            'status'           => EtpFormStudent::STATUS_PENDING,
            'get_at'           => $request->get_at ?? 'truc_tiep',
            'ReceivingAddress' => $request->ReceivingAddress ?? 'Phòng CTSV',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("ctsv/{$submission->id}", 'public');
                $submission->fileDetails()->create([
                    'filename'      => $file->hashName(),
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('xacnhansv.ctsv.my-requests')
            ->with('success', 'Đã nộp đơn thành công! Vui lòng chờ admin duyệt.');
    }

    public function myRequests()
    {
        $user = auth()->user();
        $submissions = EtpFormStudent::with('form')
            ->where('uid', $user->uid)
            ->latest()
            ->paginate(10);
        return view('xacnhansv::ctsv.my-requests', compact('submissions'));
    }

    public function show(int $id)
    {
        $user = auth()->user();
        $submission = EtpFormStudent::with(['form.details', 'fileDetails'])
            ->where('uid', $user->uid)
            ->findOrFail($id);
        return view('xacnhansv::ctsv.show', compact('submission'));
    }
}