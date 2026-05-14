@extends('layouts.master')

@section('content')
<style>
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
    }

    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin-bottom: 20px;
    }

    .breadcrumb-custom .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .breadcrumb-custom .breadcrumb-item a:hover {
        color: #667eea;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: #2c3e50;
        font-weight: 500;
    }

    .news-section {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .news-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .news-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .news-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .news-item {
        padding: 22px 25px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: flex-start;
        gap: 20px;
        transition: background 0.2s;
    }

    .news-item:last-child {
        border-bottom: none;
    }

    .news-item:hover {
        background: #f8f9fa;
    }

    .news-icon {
        width: 90px;
        height: 90px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .news-icon.khai-bao {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .news-content {
        flex: 1;
        min-width: 0;
    }

    .news-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .news-title a {
        color: #2c3e50;
        text-decoration: none;
        transition: color 0.2s;
    }

    .news-title a:hover {
        color: #667eea;
    }

    .news-meta {
        font-size: 0.9rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .news-meta i {
        margin-right: 4px;
    }

    .news-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .news-badge.khai-bao {
        background: #e8f5f3;
        color: #11998e;
    }

    .news-badge.thong-bao {
        background: #fff3e0;
        color: #e65100;
    }

    .news-badge.tin-tuc {
        background: #e3f2fd;
        color: #1976d2;
    }

    .news-date-badge {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 6px;
        text-align: center;
        min-width: 60px;
        flex-shrink: 0;
    }

    .news-date-badge .day {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
    }

    .news-date-badge .month {
        font-size: 0.7rem;
        color: #6c757d;
        text-transform: uppercase;
    }

    .pagination-box {
        padding: 15px 25px;
        background: #f8f9fa;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: center;
    }

    .pagination-box .pagination {
        margin: 0;
    }

    .pagination-box .page-link {
        color: #667eea;
        border: 1px solid #e0e0e0;
        padding: 8px 14px;
        font-size: 0.9rem;
    }

    .pagination-box .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
    }

    .pagination-box .page-link:hover {
        background: #f0f4ff;
        color: #667eea;
    }

    .search-box {
        padding: 20px 25px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }

    .search-form {
        display: flex;
        gap: 10px;
    }

    .search-form input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 15px;
        font-size: 0.9rem;
    }

    .search-form input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-form button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
    }

    .empty-state {
        padding: 60px 25px;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .btn-add-news {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-add-news:hover {
        opacity: 0.9;
        color: white;
    }

    /* Banner khai báo */
    .declaration-banner {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .declaration-banner h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .declaration-banner p {
        margin: 0;
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .declaration-banner .btn {
        background: white;
        color: #11998e;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 6px;
    }

    .declaration-banner .btn:hover {
        background: #f0f9f8;
        color: #0d7a70;
    }

    /* Admin actions */
    .admin-actions {
        display: flex;
        gap: 8px;
    }

    .admin-actions .btn {
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 4px;
    }
</style>

<div class="container-fluid px-4">
    {{-- Tiêu đề trang --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="page-title">
                <i class="fas fa-newspaper me-2 text-primary"></i>Tin Tức
            </h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Cập nhật thông tin hoạt động của trường</p>
        </div>
        @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('tintuc.create') }}" class="btn-add-news">
            <i class="fas fa-plus"></i> Thêm Tin Tức
        </a>
        @endif
    </div>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="breadcrumb-custom">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i> Trang chủ</a></li>
            <li class="breadcrumb-item active">Tin Tức</li>
        </ol>
    </nav>

    {{-- Banner khai báo ngoại trú đang mở --}}
    @php
        $activeDeclaration = \Modules\TinTuc\Models\TinTuc::currentKhaiBaoNoiTru();
    @endphp

    @if($activeDeclaration)
    <div class="declaration-banner">
        <div>
            <h5><i class="fas fa-bullhorn me-2"></i>Kỳ khai báo ngoại trú đang mở</h5>
            <p>{{ $activeDeclaration->title }}</p>
        </div>
        <a href="{{ route('tintuc.show', $activeDeclaration->id) }}" class="btn">
            <i class="fas fa-edit me-1"></i> Khai báo ngay
        </a>
    </div>
    @endif

    {{-- Thông báo --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert" style="border: none;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Danh sách tin tức --}}
    <div class="news-section">
        <div class="news-header">
            <h4><i class="fas fa-list me-2"></i>Danh Sách Tin Tức</h4>
            <span class="badge bg-light text-dark">{{ $danhSachTin->total() }} tin</span>
        </div>

        {{-- Tìm kiếm cho Admin --}}
        @if(auth()->check() && auth()->user()->isAdmin())
        <div class="search-box">
            <form method="GET" action="{{ route('tintuc.index') }}" class="search-form">
                <input type="text" name="search" placeholder="Tìm kiếm tiêu đề tin tức..." value="{{ request('search') }}">
                <button type="submit"><i class="fas fa-search"></i> Tìm</button>
                @if(request('search'))
                <a href="{{ route('tintuc.index') }}" class="btn btn-outline-secondary">Xóa</a>
                @endif
            </form>
        </div>
        @endif

        {{-- Danh sách tin --}}
        @if($danhSachTin->count() > 0)
        <ul class="news-list">
            @foreach($danhSachTin as $tin)
            <li class="news-item">
                {{-- Ngày --}}
                <div class="news-date-badge">
                    <div class="day">{{ $tin->created_at ? \Carbon\Carbon::parse($tin->created_at)->format('d') : '' }}</div>
                    <div class="month">{{ $tin->created_at ? \Carbon\Carbon::parse($tin->created_at)->format('m/Y') : '' }}</div>
                </div>

                {{-- Hình ảnh --}}
                <div class="news-icon {{ $tin->is_khai_bao_noi_tru ? 'khai-bao' : '' }}">
                    @if($tin->img && (str_starts_with($tin->img, 'http') || file_exists(public_path($tin->img))))
                    <img src="{{ str_starts_with($tin->img, 'http') ? $tin->img : asset($tin->img) }}" alt="{{ $tin->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    @endif
                    <i class="fas {{ $tin->is_khai_bao_noi_tru ? 'fa-home' : (($tin->loaitin && str_contains(strtolower($tin->loaitin->name ?? ''), 'thông báo')) ? 'fa-bullhorn' : 'fa-newspaper') }}" style="{{ $tin->img && (str_starts_with($tin->img, 'http') || file_exists(public_path($tin->img))) ? 'display:none' : '' }}"></i>
                </div>

                {{-- Nội dung --}}
                <div class="news-content">
                    <h5 class="news-title">
                        <a href="{{ route('tintuc.show', $tin->id) }}">{{ $tin->title }}</a>
                    </h5>
                    <div class="news-meta">
                        <span>
                            <i class="far fa-calendar-alt"></i>
                            {{ $tin->created_at ? \Carbon\Carbon::parse($tin->created_at)->format('H:i d/m/Y') : '' }}
                        </span>
                        <span class="news-badge {{ $tin->is_khai_bao_noi_tru ? 'khai-bao' : (($tin->loaitin && Str::contains(Str::lower($tin->loaitin->name), 'thông báo')) ? 'thong-bao' : 'tin-tuc') }}">
                            @if($tin->is_khai_bao_noi_tru)
                            <i class="fas fa-home"></i> Khai báo ngoại trú
                            @else
                            <i class="fas fa-tag"></i> {{ $tin->loaitin->name ?? 'Tin tức' }}
                            @endif
                        </span>
                        @if(!empty($tin->attachment_path))
                        <span><i class="fas fa-paperclip"></i> Có file đính kèm</span>
                        @endif
                        @if(auth()->check() && auth()->user()->isAdmin() && $tin->status == 0)
                        <span class="badge bg-secondary"><i class="fas fa-eye-slash"></i> Ẩn</span>
                        @endif
                    </div>

                    {{-- Actions cho Admin --}}
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <div class="admin-actions mt-2">
                        <a href="{{ route('tintuc.edit', $tin->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('tintuc.destroy', $tin->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Xóa tin: {{ Str::limit($tin->title, 30) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>

        {{-- Phân trang --}}
        @if($danhSachTin->hasPages())
        <div class="pagination-box">
            {{ $danhSachTin->links('pagination::bootstrap-4') }}
        </div>
        @endif
        @else
        <div class="empty-state">
            <i class="fas fa-newspaper"></i>
            <h5>Chưa có tin tức nào</h5>
            <p>Hiện tại chưa có tin tức nào được đăng tải.</p>
        </div>
        @endif
    </div>
</div>
@endsection
