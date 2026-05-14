<?php

namespace Modules\KhaiBaoNgoaiTru\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\TinTuc\Models\TinTuc;
use Modules\TinTuc\Models\KhaiBaoNoiTru;

class KhaiBaoNgoaiTruController extends Controller
{
    private function checkAdmin()
    {
        $user = Auth::user();

        if (!Auth::check() || !$user || !method_exists($user, 'isAdmin') || !$user->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }
    }

    /**
     * Trang chính - Tự động phân chia theo role
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('khai_bao_ngoai_tru.index');
        } else {
            return redirect()->route('sinh_vien.index');
        }
    }

    /**
     * Trang tạo kỳ khai báo ngoại trú (admin)
     */
    public function taoKyKhaiBao()
    {
        $this->checkAdmin();
        return view('khaibaongoaitru::khai_bao_ngoai_tru.create_ky');
    }

    /**
     * Lưu kỳ khai báo ngoại trú (admin)
     */
    public function luuKyKhaiBao(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'khai_bao_start_at' => 'required|date',
            'khai_bao_end_at' => 'required|date|after:khai_bao_start_at',
        ]);

        TinTuc::create([
            'title' => $request->title,
            'content' => $request->content,
            'loaitin_id' => 1,
            'is_khai_bao_noi_tru' => 1,
            'khai_bao_start_at' => $request->khai_bao_start_at,
            'khai_bao_end_at' => $request->khai_bao_end_at,
        ]);

        return redirect()->route('khai_bao_ngoai_tru.index')->with('success', 'Tạo kỳ khai báo ngoại trú thành công');
    }

    /**
     * Sửa kỳ khai báo ngoại trú (admin)
     */
    public function suaKyKhaiBao($id)
    {
        $this->checkAdmin();
        $tinTuc = TinTuc::findOrFail($id);

        if (!$tinTuc->is_khai_bao_noi_tru) {
            abort(404, 'Không phải kỳ khai báo ngoại trú.');
        }

        return view('khaibaongoaitru::khai_bao_ngoai_tru.edit_ky', compact('tinTuc'));
    }

    /**
     * Cập nhật kỳ khai báo ngoại trú (admin)
     */
    public function capNhatKyKhaiBao(Request $request, $id)
    {
        $this->checkAdmin();

        $tinTuc = TinTuc::findOrFail($id);

        if (!$tinTuc->is_khai_bao_noi_tru) {
            abort(404, 'Không phải kỳ khai báo ngoại trú.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'khai_bao_start_at' => 'required|date',
            'khai_bao_end_at' => 'required|date|after:khai_bao_start_at',
        ]);

        $tinTuc->update([
            'title' => $request->title,
            'content' => $request->content,
            'khai_bao_start_at' => $request->khai_bao_start_at,
            'khai_bao_end_at' => $request->khai_bao_end_at,
        ]);

        return redirect()->route('khai_bao_ngoai_tru.index')->with('success', 'Cập nhật kỳ khai báo ngoại trú thành công');
    }

    /**
     * Kích hoạt khai báo từ trang tin tức
     */
    public function kichHoatTuTin(TinTuc $tinTuc)
    {
        if (
            !$tinTuc->is_khai_bao_noi_tru ||
            !$tinTuc->khai_bao_start_at ||
            !$tinTuc->khai_bao_end_at ||
            now()->isBefore($tinTuc->khai_bao_start_at) ||
            now()->isAfter($tinTuc->khai_bao_end_at)
        ) {
            return redirect()->route('tintuc.show', $tinTuc->id)->with('error', 'Kỳ khai báo này chưa mở hoặc đã hết hạn.');
        }

        return redirect()->route('sinh_vien_khai_bao');
    }

    /**
     * Display a listing of the resource (admin).
     */
    public function danhSach(Request $request)
    {
        $query = KhaiBaoNoiTru::orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('ho_ten', 'like', '%' . $request->search . '%')
                  ->orWhere('mssv', 'like', '%' . $request->search . '%');
        }

        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('trang_thai', $request->trang_thai);
        }

        $danhSach = $query->get();
        return view('khaibaongoaitru::khai_bao_ngoai_tru.index', compact('danhSach'));
    }

    /**
     * Show the form for creating a new resource (admin).
     */
    public function create()
    {
        $this->checkAdmin();
        return view('khaibaongoaitru::khai_bao_ngoai_tru.create');
    }

    /**
     * Store a newly created resource in storage (admin).
     */
    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'mssv' => 'required|string|max:10|unique:khai_bao_noi_tru,mssv',
            'so_dien_thoai_sv' => 'required|string|max:20',
            'dia_chi_hien_tai' => 'required|string',
            'loai_dia_chi' => 'required|string|in:khac,ki_tuc_xa',
            'ten_chu_tro' => 'required_unless:loai_dia_chi,ki_tuc_xa|string|max:255',
            'so_dien_thoai_chu_tro' => 'required_unless:loai_dia_chi,ki_tuc_xa|string|max:20',
            'ngay_vao_tro' => 'required|date',
        ], [
            'mssv.unique' => 'MSSV này đã được khai báo trước đó.',
            'ten_chu_tro.required_unless' => 'Tên chủ trọ là bắt buộc khi không phải kí túc xá.',
            'so_dien_thoai_chu_tro.required_unless' => 'Số điện thoại chủ trọ là bắt buộc khi không phải kí túc xá.',
        ]);

        KhaiBaoNoiTru::create($request->all());
        return redirect()->route('khai_bao_ngoai_tru.index')->with('success', 'Thêm khai báo ngoại trú thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        return view('khaibaongoaitru::khai_bao_ngoai_tru.show', compact('khaiBao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        return view('khaibaongoaitru::khai_bao_ngoai_tru.edit', compact('khaiBao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'mssv' => 'required|string|max:10|unique:khai_bao_noi_tru,mssv,' . $id,
            'so_dien_thoai_sv' => 'required|string|max:20',
            'dia_chi_hien_tai' => 'required|string',
            'loai_dia_chi' => 'required|string|in:khac,ki_tuc_xa',
            'ten_chu_tro' => 'required_unless:loai_dia_chi,ki_tuc_xa|string|max:255',
            'so_dien_thoai_chu_tro' => 'required_unless:loai_dia_chi,ki_tuc_xa|string|max:20',
            'ngay_vao_tro' => 'required|date',
        ], [
            'mssv.unique' => 'MSSV này đã được khai báo trước đó.',
            'ten_chu_tro.required_unless' => 'Tên chủ trọ là bắt buộc khi không phải kí túc xá.',
            'so_dien_thoai_chu_tro.required_unless' => 'Số điện thoại chủ trọ là bắt buộc khi không phải kí túc xá.',
        ]);

        $khaiBao->update($request->all());
        return redirect()->route('khai_bao_ngoai_tru.index')->with('success', 'Cập nhật khai báo ngoại trú thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        $khaiBao->delete();
        return redirect()->route('khai_bao_ngoai_tru.index')->with('success', 'Xóa khai báo ngoại trú thành công');
    }

    /**
     * Duyệt khai báo ngoại trú
     */
    public function duyet($id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        $khaiBao->update(['trang_thai' => 2]);
        return redirect()->route('khai_bao_ngoai_tru.index')->with('success', 'Đã duyệt khai báo ngoại trú');
    }

    /**
     * Từ chối khai báo ngoại trú
     */
    public function tuChoi($id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        $khaiBao->update(['trang_thai' => 0]);
        return redirect()->route('khai_bao_ngoai_tru.index')->with('success', 'Đã từ chối khai báo ngoại trú');
    }

    // =============================================
    // ROUTE CHO SINH VIÊN
    // =============================================

    /**
     * Trang khai báo ngoại trú cho sinh viên
     */
    public function sinhVienIndex()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $activeDeclaration = TinTuc::currentKhaiBaoNoiTru();
        $khaiBao = null;

        if ($activeDeclaration) {
            $khaiBao = KhaiBaoNoiTru::where('mssv', $user->studentid)->first();
        }

        return view('khaibaongoaitru::khai_bao_ngoai_tru.sinh_vien', compact('khaiBao', 'activeDeclaration'));
    }

    /**
     * Lưu khai báo ngoại trú của sinh viên
     */
    public function luuKhaiBao(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $request->validate([
            'dia_chi_hien_tai' => 'required|string',
            'loai_dia_chi' => 'required|string|in:khac,ki_tuc_xa',
            'ten_chu_tro' => ['required_unless:loai_dia_chi,ki_tuc_xa', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ỹ\s]+$/'],
            'so_dien_thoai_chu_tro' => ['required_unless:loai_dia_chi,ki_tuc_xa', 'string', 'max:20', 'regex:/^[0-9]{10,11}$/'],
            'ngay_vao_tro' => 'required|date|before_or_equal:today',
            'so_dien_thoai_sv' => ['required', 'string', 'max:20', 'regex:/^[0-9]{10,11}$/'],
        ], [
            'so_dien_thoai_sv.regex' => 'Số điện thoại sinh viên phải là chữ số, 10-11 số.',
            'so_dien_thoai_chu_tro.regex' => 'Số điện thoại chủ trọ phải là chữ số, 10-11 số.',
            'ten_chu_tro.regex' => 'Họ tên chủ trọ chỉ được nhập chữ cái.',
            'ten_chu_tro.required_unless' => 'Họ tên chủ trọ là bắt buộc khi không phải kí túc xá.',
            'so_dien_thoai_chu_tro.required_unless' => 'Số điện thoại chủ trọ là bắt buộc khi không phải kí túc xá.',
            'ngay_vao_tro.before_or_equal' => 'Ngày vào trọ không được chọn ngày trong tương lai.',
        ]);

        $data = [
            'ho_ten' => $user->full_name,
            'mssv' => $user->studentid,
            'so_dien_thoai_sv' => $request->so_dien_thoai_sv,
            'dia_chi_hien_tai' => $request->dia_chi_hien_tai,
            'loai_dia_chi' => $request->loai_dia_chi,
            'ten_chu_tro' => $request->loai_dia_chi === 'ki_tuc_xa' ? null : $request->ten_chu_tro,
            'so_dien_thoai_chu_tro' => $request->loai_dia_chi === 'ki_tuc_xa' ? null : $request->so_dien_thoai_chu_tro,
            'ngay_vao_tro' => \Carbon\Carbon::parse($request->ngay_vao_tro)->format('Y-m-d'),
            'ghi_chu' => $request->ghi_chu,
            'trang_thai' => 2,
        ];

        $existing = KhaiBaoNoiTru::where('mssv', $user->studentid)->first();
        if ($existing) {
            $existing->update($data);
            return redirect()->route('sinh_vien.index')->with('success', 'Cập nhật khai báo ngoại trú thành công!');
        }

        KhaiBaoNoiTru::create($data);
        return redirect()->route('sinh_vien.index')->with('success', 'Khai báo ngoại trú thành công!');
    }

    /**
     * Form khai báo từ trang tin tức
     */
    public function formKhaiBao($tinTucId)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $activeDeclaration = TinTuc::findOrFail($tinTucId);

        if (!$activeDeclaration->is_khai_bao_noi_tru) {
            abort(404, 'Không tìm thấy kỳ khai báo.');
        }

        $khaiBao = KhaiBaoNoiTru::where('mssv', $user->studentid)->first();

        return view('khaibaongoaitru::khai_bao_ngoai_tru.sinh_vien', compact('khaiBao', 'activeDeclaration'));
    }
}
