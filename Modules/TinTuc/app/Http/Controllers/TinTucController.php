<?php

namespace Modules\TinTuc\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
            $search = $request->search;

            // Chuẩn hóa Unicode về NFC
            $search = $this->normalizeUnicode($search);
            $searchNormalized = $this->normalizeSearch($search);

            // Kiểm tra xem search có dấu tiếng Việt không
            $hasDiacritics = $this->hasDiacritics($search);

            // Tìm kiếm: khớp từ bắt đầu
            $allTin = $query->get();
            $filtered = $allTin->filter(function($item) use ($search, $searchNormalized, $hasDiacritics) {
                $title = $this->normalizeUnicode($item->title ?? '');
                $titleNormalized = $this->normalizeSearch($title);

                if ($hasDiacritics) {
                    // Có dấu → chỉ tìm theo có dấu
                    return $this->startsWith($title, $search);
                } else {
                    // Không dấu → tìm theo không dấu
                    return $this->startsWith($titleNormalized, $searchNormalized);
                }
            });

            // Tạo paginator thủ công
            $perPage = 6;
            $currentPage = $request->get('page', 1);
            $pagedData = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $danhSachTin = new \Illuminate\Pagination\LengthAwarePaginator(
                $pagedData,
                $filtered->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => $request->query()]
            );

            return view('tintuc::index', compact('danhSachTin'));
        }

        $danhSachTin = $query->paginate(6)->withQueryString();
        return view('tintuc::index', compact('danhSachTin'));
    }

    /**
     * Kiểm tra xem chuỗi có chứa dấu tiếng Việt không
     */
    private function hasDiacritics($str) {
        $diacritics = 'àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ';
        $str = mb_strtolower($str, 'UTF-8');

        for ($i = 0; $i < mb_strlen($str, 'UTF-8'); $i++) {
            $char = mb_substr($str, $i, 1, 'UTF-8');
            if (strpos($diacritics, $char) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Kiểm tra xem chuỗi có chứa từ bắt đầu bằng $prefix không
     */
    private function startsWith($str, $prefix) {
        $str = mb_strtolower($str, 'UTF-8');
        $prefix = mb_strtolower($prefix, 'UTF-8');

        // Tách chuỗi thành các từ (theo khoảng trắng)
        $words = preg_split('/\s+/u', $str, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($words as $word) {
            if (strpos($word, $prefix) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Chuẩn hóa Unicode về dạng NFC (Normalization Form Composed)
     */
    private function normalizeUnicode($str) {
        if (function_exists('normalizer_normalize')) {
            return normalizer_normalize($str, \Normalizer::FORM_C) ?: $str;
        }
        return $str;
    }

    /**
     * Bỏ dấu tiếng Việt
     */
    private function normalizeSearch($str) {
        $str = mb_strtolower($str, 'UTF-8');

        $map = [
            'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
            'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
            'ì','í','ị','ỉ','ĩ',
            'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
            'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
            'ỳ','ý','ỵ','ỷ','ỹ',
            'đ'
        ];

        $replace = array_merge(
            array_fill(0, 17, 'a'),
            array_fill(0, 11, 'e'),
            array_fill(0, 5, 'i'),
            array_fill(0, 17, 'o'),
            array_fill(0, 11, 'u'),
            array_fill(0, 5, 'y'),
            ['d']
        );

        $str = str_replace($map, $replace, $str);

        return $str;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdmin();
        $loaiTins = ModelsLoaiTin::all();
        return view('tintuc::create', compact('loaiTins'));
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
            'extra_attachments.*.file' => 'nullable|file|mimes:pdf,xls,xlsx,csv,doc,docx|max:10240'
        ]);
        $data = $request->all();
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

        // Thêm thời gian tạo thực
        $data['created_at'] = now();
        $data['updated_at'] = now();

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

        if ($tinTuc->is_khai_bao_noi_tru) {
            return redirect()->route('khai_bao_ngoai_tru.ky_edit', $id);
        }

        $loaiTins = ModelsLoaiTin::all();
        return view('tintuc::edit', compact('tinTuc', 'loaiTins'));
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
            'extra_attachments.*.file' => 'nullable|file|mimes:pdf,xls,xlsx,csv,doc,docx|max:10240'
        ]);

        $tinTuc = ModelsTinTuc::findOrFail($id);
        $data = $request->all();
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

        // Cập nhật thời gian
        $data['updated_at'] = now();

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
}
