<?php

namespace Modules\TinTuc\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TinTuc\Models\LoaiTin as ModelsLoaiTin;

class LoaiTinController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAdmin();
        $loaiTins = ModelsLoaiTin::withCount('tintucs')->orderBy('id', 'desc')->get();
        return view('tintuc::loaitin.index', compact('loaiTins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdmin();
        return view('tintuc::loaitin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required|max:255|unique:loaitin,name'
        ], [
            'name.required' => 'Vui lòng nhập tên loại tin',
            'name.unique' => 'Loại tin này đã tồn tại'
        ]);

        ModelsLoaiTin::create(['name' => $request->name]);

        return redirect()->route('loaitin.index')->with('success', 'Đã thêm Loại Tin mới!');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('loaitin.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->checkAdmin();
        $loaiTin = ModelsLoaiTin::findOrFail($id);
        return view('tintuc::loaitin.edit', compact('loaiTin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required|max:255|unique:loaitin,name,' . $id
        ], [
            'name.required' => 'Vui lòng nhập tên loại tin',
            'name.unique' => 'Loại tin này đã tồn tại'
        ]);

        $loaiTin = ModelsLoaiTin::findOrFail($id);
        $loaiTin->update(['name' => $request->name]);

        return redirect()->route('loaitin.index')->with('success', 'Cập nhật Loại Tin thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->checkAdmin();
        $loaiTin = ModelsLoaiTin::findOrFail($id);

        if ($loaiTin->tintucs()->count() > 0) {
            return redirect()->route('loaitin.index')->with('error', 'Không thể xóa! Loại tin đang có tin tức.');
        }

        $loaiTin->delete();
        return redirect()->route('loaitin.index')->with('success', 'Xóa Loại Tin thành công!');
    }
}
