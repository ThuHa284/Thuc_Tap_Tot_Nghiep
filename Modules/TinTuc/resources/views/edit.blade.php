@extends('layouts.master') 

@section('content')
<div class="card mb-4 rounded-0 border-dark">
    <div class="card-header bg-white border-bottom border-dark rounded-0">
        <h4 class="mb-0 text-uppercase fw-bold">Cập nhật Tin Tức</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('tintuc.update', $tinTuc->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            
            <div class="mb-3">
                <label class="fw-bold mb-1">Tiêu đề tin tức</label>
                <input type="text" 
                       class="form-control rounded-0 border-dark @error('title') is-invalid @enderror" 
                       name="title" 
                       value="{{ old('title', $tinTuc->title) }}" 
                       required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1">Loại tin</label>
                <select class="form-select rounded-0 border-dark @error('loaitin_id') is-invalid @enderror" name="loaitin_id" required>
                    <option value="">-- Chọn loại tin --</option>
                    @foreach($loaiTins as $loai)
                        <option value="{{ $loai->id }}" {{ (old('loaitin_id', $tinTuc->loaitin_id) == $loai->id) ? 'selected' : '' }}>
                            {{ $loai->name }} 
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1">Hình ảnh đại diện</label>
                @if(!empty($tinTuc->img))
                    <div class="mb-2 p-1 border border-dark" style="display: inline-block;">
                        <img src="{{ asset($tinTuc->img) }}" alt="Ảnh hiện tại" style="max-height: 120px; object-fit: cover;">
                    </div>
                @endif
                <input type="file" 
                       class="form-control rounded-0 border-dark" 
                       name="img" 
                       accept="image/*">
                <div class="form-text text-muted">Chỉ chọn file mới nếu bạn muốn thay đổi ảnh hiện tại.</div>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1">Ngày diễn ra (Date1)</label>
                <input type="date" 
                       class="form-control rounded-0 border-dark" 
                       name="date1" 
                       value="{{ old('date1', $tinTuc->date1 ? \Carbon\Carbon::parse($tinTuc->date1)->format('Y-m-d') : '') }}">
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1">Trạng thái</label>
                <select name="status" class="form-select rounded-0 border-dark">
                    <option value="1" {{ old('status', $tinTuc->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('status', $tinTuc->status) == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold mb-1">Nội dung chi tiết</label>
                <textarea class="form-control rounded-0 border-dark @error('content') is-invalid @enderror" 
                          name="content" 
                          rows="6" 
                          required>{{ old('content', $tinTuc->content) }}</textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('tintuc.index') }}" class="btn btn-outline-dark rounded-0 px-4">Quay lại danh sách</a>
                <button type="submit" class="btn btn-dark rounded-0 px-4">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection