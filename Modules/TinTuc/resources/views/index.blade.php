@extends('layouts.master')

@section('content')
<div class="card mb-4 rounded-0 border-dark">
    <div class="card-header bg-white border-bottom border-dark rounded-0">
        <h4 class="mb-0 text-uppercase fw-bold">Quản lý Tin Tức</h4>
    </div>
    <div class="card-body">

        {{-- Thông báo thành công --}}
        @if(session('success'))
        <div class="alert alert-light border border-dark rounded-0 alert-dismissible fade show" role="alert">
            <strong>Thành công:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Cụm điều hướng: Tìm kiếm & Thêm mới --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('tintuc.create') }}" class="btn btn-dark rounded-0 me-2">Thêm Tin Mới</a>
                <a href="{{ route('loaitin.create') }}" class="btn btn-outline-dark rounded-0">Thêm Loại Tin</a>
            </div>

            <form action="{{ route('tintuc.index') }}" method="GET" class="m-0 w-50">
                <div class="input-group">
                    <input type="text" name="search" class="form-control rounded-0 border-dark" placeholder="Nhập tiêu đề tin..." value="{{ request('search') }}">
                    <button class="btn btn-dark rounded-0" type="submit">Tìm kiếm</button>
                </div>
            </form>
        </div>

        {{-- DANH SÁCH TIN TỨC DẠNG THẺ NGANG NHỎ GỌN (COMPACT LIST) --}}
        <div class="row">
            @forelse($danhSachTin as $tin)
            {{-- Chia 2 cột trên máy tính để danh sách không bị kéo dài thênh thang --}}
            <div class="col-12 col-xl-6 mb-3">
                {{-- Dùng flex-row để ép nó luôn nằm ngang kể cả trên điện thoại --}}
                {{-- Dùng flex-row để ép nó luôn nằm ngang --}}
                {{-- Thêm 'position-relative' để làm mỏ neo cho stretched-link, thêm class custom 'hover-card' --}}
                <div class="card rounded-0 border-dark flex-row h-100 position-relative hover-card">

                    {{-- 1. Khu vực ảnh --}}
                    <div class="border-end border-dark flex-shrink-0" style="width: 180px;">
                        @if($tin->img)
                        <img src="{{ asset($tin->img) }}" class="w-100 h-100 rounded-0" style="object-fit: cover; object-position: center;" alt="Tin tức">
                        @else
                        <div class="w-100 h-100 bg-light d-flex justify-content-center align-items-center">
                            <span class="text-muted small">Chưa có ảnh</span>
                        </div>
                        @endif
                    </div>

                    {{-- 2. Khu vực nội dung --}}
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            {{-- ĐÃ SỬA: Xóa đoạn style cắt chữ (-webkit-line-clamp) để hiện FULL tiêu đề --}}
                            <h6 class="card-title fw-bold mb-2 fs-6 lh-base">
                                {{-- ĐÃ SỬA: Thêm class 'stretched-link' để mở rộng vùng click ra toàn bộ thẻ card --}}
                                <a href="{{ route('tintuc.show', $tin->id) }}" class="text-dark text-decoration-none stretched-link">
                                    {{ $tin->title }}
                                </a>
                            </h6>

                            <div class="text-muted mb-1" style="font-size: 0.85rem;">
                                <span class="text-dark fw-bold">Danh mục:</span> {{ $tin->loaitin->name ?? 'Chưa có' }}
                                <span class="mx-1">|</span>
                                @if($tin->status == 1)
                                <span class="text-success fw-bold">Hiển thị</span>
                                @else
                                <span class="text-secondary">Đã ẩn</span>
                                @endif
                            </div>

                            <div class="text-muted" style="font-size: 0.85rem;">
                                <span class="text-dark fw-bold">Ngày đăng:</span>
                                {{ $tin->date1 ? \Carbon\Carbon::parse($tin->date1)->format('d/m/Y') : 'Trống' }}
                            </div>
                        </div>

                        {{-- 3. Nút hành động --}}
                        {{-- ĐÃ SỬA: Thêm 'position-relative' và 'z-index' để 2 nút này nổi lên trên, không bị stretched-link che mất --}}
                        <div class="d-flex justify-content-end gap-2 mt-3 position-relative" style="z-index: 2;">
                            <a href="{{ route('tintuc.edit', $tin->id) }}" class="btn btn-outline-dark rounded-0" style="padding: 0.2rem 0.75rem; font-size: 0.8rem;">Sửa</a>

                            <form action="{{ route('tintuc.destroy', $tin->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-dark rounded-0" style="padding: 0.2rem 0.75rem; font-size: 0.8rem;" onclick="return confirm('Bạn muốn xóa bài này?')">Xóa</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Thêm một chút CSS mỏng ở cuối file để tạo hiệu ứng hover cho xịn xò (nếu thích) --}}
                <style>
                    .hover-card {
                        transition: background-color 0.2s ease-in-out;
                    }

                    .hover-card:hover {
                        background-color: #f8f9fa;
                        /* Đổi màu nền xám nhạt khi lia chuột vào */
                    }
                </style>
            </div>
            @empty
            <div class="col-12 text-center py-4 text-muted border border-dark rounded-0">
                Chưa có tin tức nào.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection