<?php

namespace Modules\TinTuc\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TinTuc\app\Models\TinTuc;
use Modules\TinTuc\Models\TinTuc as ModelsTinTuc;
use Modules\TinTuc\app\Models\LoaiTin;
use Modules\TinTuc\Models\LoaiTin as ModelsLoaiTin;
use PhpParser\Node\Expr\AssignOp\Mod;
use Illuminate\Support\Facades\File;

class TinTucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ModelsTinTuc::with('loaitin')->orderBy('id','desc');

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
        $loaiTins = ModelsLoaiTin::all();
        return view('tintuc::create', compact('loaiTins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'loaitin_id' => 'required|exists:loaitin,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);
        $data = $request->all();

        if($request->hasFile('img')){
            $imageName = time().'.'.$request->img->extension();
            $request->img->move(public_path('uploads/tintuc'),$imageName);
            $data['img'] = 'uploads/tintuc/'.$imageName;
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
        $tinTuc = ModelsTinTuc::findOrFail($id);
        $loaiTins = ModelsLoaiTin::all();
        return view('tintuc::edit', compact('tinTuc', 'loaiTins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
    // 1. Validate dữ liệu (Thêm dòng validate ảnh cho bảo mật)
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'loaitin_id' => 'required|exists:loaitin,id',
        'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Nên có để tránh ai đó up file .exe lên hack web
    ]);

    $tinTuc = ModelsTinTuc::findOrFail($id);
    $data = $request->all();

    if ($request->hasFile('img')) {
        $imageName = time() . '.' . $request->img->extension();
        
        // 2. FIX LỖI hàm move: Dùng dấu phẩy ngăn cách thư mục và tên file
        $request->img->move(public_path('uploads/tintuc'), $imageName);
        
        // 3. TỐI ƯU: Xóa ảnh cũ đi trước khi lưu đường dẫn ảnh mới (tránh rác server)
        // Yêu cầu: Khai báo use Illuminate\Support\Facades\File; ở trên cùng
        if (!empty($tinTuc->img) && File::exists(public_path($tinTuc->img))) {
            File::delete(public_path($tinTuc->img));
        }

        $data['img'] = 'uploads/tintuc/' . $imageName;
    } else {
        unset($data['img']);
    }

    $tinTuc->update($data);
    
    return redirect()->route('tintuc.index')->with('success','Cập nhật tin tức thành công');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $tinTuc = ModelsTinTuc::findOrFail($id);
        $tinTuc->delete();
        return redirect()->route('tintuc.index')->with('success','Xóa tin tức thành công');
    }
}
