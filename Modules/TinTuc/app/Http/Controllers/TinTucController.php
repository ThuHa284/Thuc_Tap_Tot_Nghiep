<?php

namespace Modules\TinTuc\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Modules\TinTuc\Models\TinTuc as ModelsTinTuc;
use Modules\TinTuc\Models\LoaiTin as ModelsLoaiTin;

class TinTucController extends Controller
{
    private function checkAdmin()
    {
        $user = Auth::user();

        if (!Auth::check() || !$user || !method_exists($user, 'isAdmin') || !$user->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ModelsTinTuc::with('loaitin')->orderBy('created_at', 'desc');

        if($request->has('search') && $request->search !=''){
            $query->where('title','like','%'.$request->search.'%');
        }
        $danhSachTin = $query->get();
        return view('tintuc::index', compact('danhSachTin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdmin();
        $loaiTins = ModelsLoaiTin::all();
        $usedDeclarationSemesters = $this->getUsedDeclarationSemesters();
        return view('tintuc::create', compact('loaiTins', 'usedDeclarationSemesters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $this->checkAdmin();
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'loaitin_id' => 'required|exists:loaitin,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'attachment' => 'nullable|file|mimes:pdf,xls,xlsx,csv,doc,docx|max:10240',
            'attachment_label' => 'nullable|string|max:255',
            'extra_attachments' => 'nullable|array',
            'extra_attachments.*.label' => 'nullable|string|max:255',
            'extra_attachments.*.existing_path' => 'nullable|string',
            'extra_attachments.*.existing_name' => 'nullable|string|max:255',
            'extra_attachments.*.file' => 'nullable|file|mimes:pdf,xls,xlsx,csv,doc,docx|max:10240',
            'is_khai_bao_noi_tru' => 'nullable|boolean',
            'khai_bao_ky' => 'required_if:is_khai_bao_noi_tru,1|nullable|in:1,2',
            'khai_bao_start_at' => 'required_if:is_khai_bao_noi_tru,1|nullable|date',
            'khai_bao_end_at' => 'required_if:is_khai_bao_noi_tru,1|nullable|date|after_or_equal:khai_bao_start_at'
        ]);
        $data = $request->all();
        $data['is_khai_bao_noi_tru'] = $request->boolean('is_khai_bao_noi_tru');
        $data['attachment_label'] = $request->input('attachment_label');
        $data['attachments'] = [];

        if($request->hasFile('img')){
            File::ensureDirectoryExists(public_path('uploads/tintuc'));
            $imageName = time().'.'.$request->img->extension();
            $request->img->move(public_path('uploads/tintuc'),$imageName);
            $data['img'] = 'uploads/tintuc/'.$imageName;
        }

        if ($request->hasFile('attachment')) {
            File::ensureDirectoryExists(public_path('uploads/tintuc/files'));
            $attachmentName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($request->attachment->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $request->attachment->extension();
            $request->attachment->move(public_path('uploads/tintuc/files'), $attachmentName);
            $data['attachment_path'] = 'uploads/tintuc/files/' . $attachmentName;
            $data['attachment_name'] = $request->attachment->getClientOriginalName();
            $data['attachment_label'] = $request->input('attachment_label');
        }

        $extraAttachments = [];
        foreach ($request->input('extra_attachments', []) as $index => $extraAttachment) {
            $uploadedFile = data_get($request->file('extra_attachments', []), $index . '.file');

            if ($uploadedFile) {
                File::ensureDirectoryExists(public_path('uploads/tintuc/files'));
                $attachmentName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $uploadedFile->extension();
                $uploadedFile->move(public_path('uploads/tintuc/files'), $attachmentName);

                $extraAttachments[] = [
                    'label' => trim((string) ($extraAttachment['label'] ?? '')),
                    'path' => 'uploads/tintuc/files/' . $attachmentName,
                    'name' => $uploadedFile->getClientOriginalName(),
                ];
                continue;
            }

            if (!empty($extraAttachment['existing_path'])) {
                $extraAttachments[] = [
                    'label' => trim((string) ($extraAttachment['label'] ?? '')),
                    'path' => $extraAttachment['existing_path'],
                    'name' => $extraAttachment['existing_name'] ?? basename($extraAttachment['existing_path']),
                ];
            }
        }

        $data['attachments'] = $extraAttachments;

        if ($data['is_khai_bao_noi_tru']) {
            $this->ensureDeclarationSemesterIsAvailable(
                $data['khai_bao_start_at'] ?? null,
                $data['khai_bao_ky'] ?? null
            );
        } else {
            $data['khai_bao_ky'] = null;
        }

        if (!$data['is_khai_bao_noi_tru']) {
            $data['khai_bao_start_at'] = null;
            $data['khai_bao_end_at'] = null;
        }

        ModelsTinTuc::create($data);
        return redirect()->route('tintuc.index')->with('success','Thêm tin tức thành công');


    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        // Tìm tin tức, nếu không thấy sẽ trả về trang 404
        $tinTuc = ModelsTinTuc::with('loaitin')->findOrFail($id);
        return view('tintuc::show', compact('tinTuc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->checkAdmin();
        $tinTuc = ModelsTinTuc::findOrFail($id);
        $loaiTins = ModelsLoaiTin::all();
        $usedDeclarationSemesters = $this->getUsedDeclarationSemesters($tinTuc->id);
        return view('tintuc::edit', compact('tinTuc', 'loaiTins', 'usedDeclarationSemesters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $this->checkAdmin();
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'loaitin_id' => 'required|exists:loaitin,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'attachment' => 'nullable|file|mimes:pdf,xls,xlsx,csv,doc,docx|max:10240',
            'attachment_label' => 'nullable|string|max:255',
            'extra_attachments' => 'nullable|array',
            'extra_attachments.*.label' => 'nullable|string|max:255',
            'extra_attachments.*.existing_path' => 'nullable|string',
            'extra_attachments.*.existing_name' => 'nullable|string|max:255',
            'extra_attachments.*.file' => 'nullable|file|mimes:pdf,xls,xlsx,csv,doc,docx|max:10240',
            'is_khai_bao_noi_tru' => 'nullable|boolean',
            'khai_bao_ky' => 'required_if:is_khai_bao_noi_tru,1|nullable|in:1,2',
            'khai_bao_start_at' => 'required_if:is_khai_bao_noi_tru,1|nullable|date',
            'khai_bao_end_at' => 'required_if:is_khai_bao_noi_tru,1|nullable|date|after_or_equal:khai_bao_start_at'
        ]);

        $tinTuc = ModelsTinTuc::findOrFail($id);
        $data = $request->all();
        $data['is_khai_bao_noi_tru'] = $request->boolean('is_khai_bao_noi_tru');
        $data['attachment_label'] = $request->input('attachment_label');
        $data['attachments'] = [];

        if ($request->hasFile('img')) {
            File::ensureDirectoryExists(public_path('uploads/tintuc'));
            $imageName = time() . '.' . $request->img->extension();
            $request->img->move(public_path('uploads/tintuc'), $imageName);

            if (!empty($tinTuc->img) && File::exists(public_path($tinTuc->img))) {
                File::delete(public_path($tinTuc->img));
            }

            $data['img'] = 'uploads/tintuc/' . $imageName;
        } else {
            unset($data['img']);
        }

        if ($request->hasFile('attachment')) {
            File::ensureDirectoryExists(public_path('uploads/tintuc/files'));
            $attachmentName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($request->attachment->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $request->attachment->extension();
            $request->attachment->move(public_path('uploads/tintuc/files'), $attachmentName);

            if (!empty($tinTuc->attachment_path) && File::exists(public_path($tinTuc->attachment_path))) {
                File::delete(public_path($tinTuc->attachment_path));
            }

            $data['attachment_path'] = 'uploads/tintuc/files/' . $attachmentName;
            $data['attachment_name'] = $request->attachment->getClientOriginalName();
            $data['attachment_label'] = $request->input('attachment_label');
        }

        $oldExtraAttachments = $tinTuc->attachments ?? [];
        $oldPaths = array_values(array_filter(array_map(fn ($item) => $item['path'] ?? null, $oldExtraAttachments)));

        $extraAttachments = [];
        foreach ($request->input('extra_attachments', []) as $index => $extraAttachment) {
            $uploadedFile = data_get($request->file('extra_attachments', []), $index . '.file');

            if ($uploadedFile) {
                File::ensureDirectoryExists(public_path('uploads/tintuc/files'));
                $attachmentName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $uploadedFile->extension();
                $uploadedFile->move(public_path('uploads/tintuc/files'), $attachmentName);

                $extraAttachments[] = [
                    'label' => trim((string) ($extraAttachment['label'] ?? '')),
                    'path' => 'uploads/tintuc/files/' . $attachmentName,
                    'name' => $uploadedFile->getClientOriginalName(),
                ];
                continue;
            }

            if (!empty($extraAttachment['existing_path'])) {
                $extraAttachments[] = [
                    'label' => trim((string) ($extraAttachment['label'] ?? '')),
                    'path' => $extraAttachment['existing_path'],
                    'name' => $extraAttachment['existing_name'] ?? basename($extraAttachment['existing_path']),
                ];
            }
        }

        $newPaths = array_values(array_filter(array_map(fn ($item) => $item['path'] ?? null, $extraAttachments)));
        foreach (array_diff($oldPaths, $newPaths) as $removedPath) {
            if (!empty($removedPath) && File::exists(public_path($removedPath))) {
                File::delete(public_path($removedPath));
            }
        }

        $data['attachments'] = $extraAttachments;

        if ($data['is_khai_bao_noi_tru']) {
            $this->ensureDeclarationSemesterIsAvailable(
                $data['khai_bao_start_at'] ?? null,
                $data['khai_bao_ky'] ?? null,
                $tinTuc->id
            );
        } else {
            $data['khai_bao_ky'] = null;
        }

        if (!$data['is_khai_bao_noi_tru']) {
            $data['khai_bao_start_at'] = null;
            $data['khai_bao_end_at'] = null;
        }

        $tinTuc->update($data);

        return redirect()->route('tintuc.index')->with('success','Cập nhật tin tức thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $this->checkAdmin();
        $tinTuc = ModelsTinTuc::findOrFail($id);

        if (!empty($tinTuc->img) && File::exists(public_path($tinTuc->img))) {
            File::delete(public_path($tinTuc->img));
        }

        if (!empty($tinTuc->attachment_path) && File::exists(public_path($tinTuc->attachment_path))) {
            File::delete(public_path($tinTuc->attachment_path));
        }

        foreach (($tinTuc->attachments ?? []) as $attachment) {
            $attachmentPath = $attachment['path'] ?? null;

            if (!empty($attachmentPath) && File::exists(public_path($attachmentPath))) {
                File::delete(public_path($attachmentPath));
            }
        }

        $tinTuc->delete();
        return redirect()->route('tintuc.index')->with('success','Xóa tin tức thành công');
    }

    public function download($id)
    {
        $tinTuc = ModelsTinTuc::findOrFail($id);

        if (empty($tinTuc->attachment_path) || !File::exists(public_path($tinTuc->attachment_path))) {
            abort(404, 'Không tìm thấy tệp đính kèm.');
        }

        $filePath = public_path($tinTuc->attachment_path);
        $fileName = $tinTuc->attachment_name ?: basename($tinTuc->attachment_path);

        // Encode filename for browsers (handle Vietnamese characters)
        $encodedFileName = rawurlencode($fileName);

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename*=UTF-8''{$encodedFileName}",
        ]);
    }

    public function downloadFile(Request $request)
    {
        $path = $request->input('path');

        if (empty($path)) {
            abort(404, 'Không tìm thấy tệp đính kèm.');
        }

        $filePath = public_path($path);

        if (!File::exists($filePath)) {
            abort(404, 'Tệp không tồn tại trên server.');
        }

        $fileName = basename($path);

        // Lay ten file goc tu database neu co
        $tinTucId = $request->input('tin_tuc_id');
        if ($tinTucId) {
            $tinTuc = ModelsTinTuc::find($tinTucId);
            if ($tinTuc) {
                // Kiem tra trong attachments array
                foreach (($tinTuc->attachments ?? []) as $attachment) {
                    if (($attachment['path'] ?? '') === $path && !empty($attachment['name'])) {
                        $fileName = $attachment['name'];
                        break;
                    }
                }
                // Neu la file chinh va co attachment_name
                if (($tinTuc->attachment_path ?? '') === $path && !empty($tinTuc->attachment_name)) {
                    $fileName = $tinTuc->attachment_name;
                }
            }
        }

        // Encode filename for browsers (handle Vietnamese characters)
        $encodedFileName = rawurlencode($fileName);

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename*=UTF-8''{$encodedFileName}",
        ]);
    }

    private function getUsedDeclarationSemesters(?int $ignoreId = null): array
    {
        $query = ModelsTinTuc::query()
            ->where('is_khai_bao_noi_tru', true)
            ->whereNotNull('khai_bao_start_at')
            ->whereNotNull('khai_bao_ky');

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $used = [];

        foreach ($query->get(['id', 'khai_bao_start_at', 'khai_bao_ky']) as $tinTuc) {
            $year = optional($tinTuc->khai_bao_start_at)->year;
            if (!$year) {
                continue;
            }

            $used[$year][] = (int) $tinTuc->khai_bao_ky;
        }

        return $used;
    }

    private function ensureDeclarationSemesterIsAvailable($startAt, $semester, ?int $ignoreId = null): void
    {
        if (empty($startAt) || empty($semester)) {
            return;
        }

        $year = \Carbon\Carbon::parse($startAt)->year;

        $query = ModelsTinTuc::query()
            ->where('is_khai_bao_noi_tru', true)
            ->whereYear('khai_bao_start_at', $year)
            ->where('khai_bao_ky', (int) $semester);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'khai_bao_ky' => 'Kỳ này trong năm đã được khai báo nội trú rồi.',
            ]);
        }
    }
}
