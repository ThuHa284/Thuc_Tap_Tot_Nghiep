@extends('layouts.master') 

@section('content')
<div class="card mb-4 rounded-0 border-dark">
    <div class="card-header bg-white border-bottom border-dark rounded-0">
        <h4 class="mb-0 text-uppercase fw-bold">Thêm Tin Tức Mới</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('tintuc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            
            <div class="mb-3">
                <label class="fw-bold mb-1">Tiêu đề</label>
                <input type="text" name="title" class="form-control rounded-0 border-dark" required>
            </div>
            
            <div class="mb-3">
                <label class="fw-bold mb-1">Loại Tin</label>
                <select name="loaitin_id" class="form-select rounded-0 border-dark" required>
                    <option value="">-- Chọn loại tin --</option>
                    @foreach($loaiTins as $loai)
                        <option value="{{ $loai->id }}">{{ $loai->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="fw-bold mb-1">Hình ảnh đại diện</label>
                <input type="file" name="img" class="form-control rounded-0 border-dark" accept="image/*">
            </div>
            
            <div class="mb-3">
                <label class="fw-bold mb-1">Ngày diễn ra (Date1)</label>
                <input type="date" name="date1" class="form-control rounded-0 border-dark">
            </div>
            
            <div class="mb-3">
                <label class="fw-bold mb-1">Trạng thái</label>
                <select name="status" class="form-select rounded-0 border-dark">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="fw-bold mb-1">Nội dung chi tiết</label>
                <textarea name="content" class="form-control rounded-0 border-dark" rows="6" required></textarea>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('tintuc.index') }}" class="btn btn-outline-dark rounded-0 px-4">Quay lại</a>
                <button type="submit" class="btn btn-dark rounded-0 px-4">Lưu Tin Tức</button>
            </div>
        </form>
    </div>
</div>
@endsection