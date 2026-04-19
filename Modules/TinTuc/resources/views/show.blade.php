@extends('layouts.master')

@section('content')
<div class="container mt-4">
    {{-- Nút quay lại --}}
    <div class="mb-3">
        <a href="{{ route('tintuc.index') }}" class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" style="font-size: 0.8rem;">
            ← Quay lại danh sách
        </a>
    </div>

    <div class="card rounded-0 border-dark mb-5">
        <div class="card-body p-4 p-md-5">
            {{-- Tiêu đề tin --}}
            <h2 class="fw-bold text-uppercase mb-3 border-start border-4 border-dark ps-3">
                {{ $tinTuc->title }}
            </h2>

            {{-- Thông tin phụ (Meta data) --}}
            <div class="d-flex flex-wrap text-muted mb-4 pb-3 border-bottom border-dark border-opacity-10" style="font-size: 0.9rem;">
                <div class="me-4">
                    <span class="fw-bold text-dark">Danh mục:</span> {{ $tinTuc->loaitin->name ?? 'Chưa phân loại' }}
                </div>
                <div class="me-4">
                    <span class="fw-bold text-dark">Ngày đăng:</span> 
                    {{ $tinTuc->date1 ? \Carbon\Carbon::parse($tinTuc->date1)->format('d/m/Y') : 'N/A' }}
                </div>
                <div>
                    <span class="fw-bold text-dark">Trạng thái:</span> 
                    {!! $tinTuc->status == 1 
                        ? '<span class="text-success">Công khai</span>' 
                        : '<span class="text-secondary">Bản nháp</span>' !!}
                </div>
            </div>

            {{-- Ảnh đại diện lớn --}}
            @if($tinTuc->img)
                <div class="mb-4 border border-dark p-1 shadow-sm mx-auto" style="max-width: 800px;">
                    <img src="{{ asset($tinTuc->img) }}" class="w-100 h-auto rounded-0" alt="{{ $tinTuc->title }}">
                </div>
            @endif

            {{-- Nội dung bài viết --}}
            <div class="content-area mt-4 lh-lg" style="text-align: justify; font-size: 1.1rem;">
                {!! $tinTuc->content !!}
            </div>
        </div>

        {{-- Footer của thẻ tin (Tùy chọn) --}}
        <div class="card-footer bg-white border-top border-dark rounded-0 py-3">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('tintuc.edit', $tinTuc->id) }}" class="btn btn-dark rounded-0 px-4">Sửa bài viết</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style để các thẻ HTML trong nội dung (như bảng, ảnh) không bị tràn khung */
    .content-area img {
        max-width: 100%;
        height: auto;
        border: 1px solid #000;
        padding: 5px;
        margin: 15px 0;
    }
    .content-area p {
        margin-bottom: 1.5rem;
    }
</style>
@endsection