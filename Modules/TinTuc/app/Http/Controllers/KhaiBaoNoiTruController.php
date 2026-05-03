<?php

namespace Modules\TinTuc\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TinTuc\Models\KhaiBaoNoiTru;

class KhaiBaoNoiTruController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }
    }

    /**
     * Trang khai báo nội trú cho sinh viên
     */
    public function khaiBaoSinhVien()
    {
        $user = auth()->user();
        // Lấy khai báo của sinh viên hiện tại (nếu có)
        $khaiBao = KhaiBaoNoiTru::where('mssv', $user->studentid)->first();
        return view('tintuc::khai_bao_noi_tru.sinh_vien', compact('khaiBao'));
    }

    /**
     * Lưu khai báo nội trú của sinh viên
     */
    public function luuKhaiBao(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'dia_chi_hien_tai' => 'required|string',
            'ten_chu_tro' => 'required|string|max:255',
            'so_dien_thoai_chu_tro' => 'required|string|max:20',
            'ngay_vao_tro' => 'required|date',
            'so_dien_thoai_sv' => 'required|string|max:20',
        ]);

        $data = [
            'ho_ten' => $user->full_name,
            'mssv' => $user->studentid,
            'so_dien_thoai_sv' => $request->so_dien_thoai_sv,
            'dia_chi_hien_tai' => $request->dia_chi_hien_tai,
            'ten_chu_tro' => $request->ten_chu_tro,
            'so_dien_thoai_chu_tro' => $request->so_dien_thoai_chu_tro,
            'ngay_vao_tro' => $request->ngay_vao_tro,
            'ghi_chu' => $request->ghi_chu,
            'trang_thai' => 2, // Tự động duyệt
        ];

        // Kiểm tra đã khai báo chưa
        $existing = KhaiBaoNoiTru::where('mssv', $user->studentid)->first();
        if ($existing) {
            $existing->update($data);
            return redirect()->back()->with('success', 'Cập nhật khai báo nội trú thành công!');
        }

        KhaiBaoNoiTru::create($data);
        return redirect()->back()->with('success', 'Khai báo nội trú thành công! Vui lòng chờ quản trị viên duyệt.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        return view('tintuc::khai_bao_noi_tru.index', compact('danhSach'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdmin();
        return view('tintuc::khai_bao_noi_tru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'mssv' => 'required|string|max:10|unique:khai_bao_noi_tru,mssv',
            'so_dien_thoai_sv' => 'required|string|max:20',
            'dia_chi_hien_tai' => 'required|string',
            'ten_chu_tro' => 'required|string|max:255',
            'so_dien_thoai_chu_tro' => 'required|string|max:20',
            'ngay_vao_tro' => 'required|date',
        ], [
            'mssv.unique' => 'MSSV này đã được khai báo trước đó.',
        ]);

        KhaiBaoNoiTru::create($request->all());
        return redirect()->route('khai_bao_noi_tru.index')->with('success', 'Thêm khai báo nội trú thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        return view('tintuc::khai_bao_noi_tru.show', compact('khaiBao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        return view('tintuc::khai_bao_noi_tru.edit', compact('khaiBao'));
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
            'ten_chu_tro' => 'required|string|max:255',
            'so_dien_thoai_chu_tro' => 'required|string|max:20',
            'ngay_vao_tro' => 'required|date',
        ], [
            'mssv.unique' => 'MSSV này đã được khai báo trước đó.',
        ]);

        $khaiBao->update($request->all());
        return redirect()->route('khai_bao_noi_tru.index')->with('success', 'Cập nhật khai báo nội trú thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        $khaiBao->delete();
        return redirect()->route('khai_bao_noi_tru.index')->with('success', 'Xóa khai báo nội trú thành công');
    }

    /**
     * Duyệt khai báo nội trú
     */
    public function duyet($id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        $khaiBao->update(['trang_thai' => 2]);
        return redirect()->route('khai_bao_noi_tru.index')->with('success', 'Đã duyệt khai báo nội trú');
    }

    /**
     * Từ chối khai báo nội trú
     */
    public function tuChoi($id)
    {
        $this->checkAdmin();
        $khaiBao = KhaiBaoNoiTru::findOrFail($id);
        $khaiBao->update(['trang_thai' => 0]);
        return redirect()->route('khai_bao_noi_tru.index')->with('success', 'Đã từ chối khai báo nội trú');
    }
}
