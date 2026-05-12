@extends('layouts.master')

@section('content')
<style>
    .news-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
    }

    .news-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .news-card img {
        transition: transform 0.5s ease;
    }

    .news-card:hover img {
        transform: scale(1.1);
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .horizontal-menu {
        background: white;
        border-radius: 12px;
        padding: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .horizontal-menu a {
        padding: 10px 20px;
        border-radius: 8px;
        color: #555;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .horizontal-menu a:hover {
        background: #f0f4ff;
        color: #667eea;
    }

    .horizontal-menu a.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .horizontal-menu a i {
        font-size: 14px;
    }
</style>

<div class="row">
    {{-- Menu ngang --}}
    <div class="col-12">
        @include('tintuc::components.tintuc-menu')
    </div>
</div>

{{-- Page Header --}}
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1 fw-bold">
                <i class="fas fa-newspaper me-2"></i>Tin Tức & Sự Kiện
            </h2>
            <p class="mb-0 opacity-75 small">Cập nhật những tin tức mới nhất về hoạt động của trường</p>
        </div>
        @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('tintuc.create') }}" class="btn btn-light rounded-pill px-4 fw-medium">
            <i class="fas fa-plus me-1"></i> Thêm Tin Tức
        </a>
        @endif
    </div>
</div>

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Tin Tức</li>
    </ol>
</nav>

<div class="card border-0 shadow-lg rounded-3">
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-list me-2 text-primary"></i>Danh Sách Tin Tức
            </h5>
            <span class="badge bg-primary rounded-pill px-3">{{ $danhSachTin->count() }} tin</span>
        </div>
    </div>
    <div class="card-body p-4">

        {{-- Thông báo --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Tìm kiếm cho Admin --}}
        @if(auth()->check() && auth()->user()->isAdmin())
        <div class="mb-4">
            <form method="GET" action="{{ route('tintuc.index') }}" class="row g-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-pill"
                            placeholder="Tìm kiếm tiêu đề tin tức..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100 rounded-pill" type="submit">Tìm kiếm</button>
                </div>
                @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('tintuc.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Xóa lọc</a>
                </div>
                @endif
            </form>
        </div>
        @endif

        @if($danhSachTin->count() > 0)
        <div class="row g-3">
            @foreach($danhSachTin as $tin)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border-0 rounded-3 news-card overflow-hidden shadow-sm {{ $tin->status == 0 && auth()->check() && auth()->user()->isAdmin() ? 'bg-light' : '' }}">
                    @php
                        $declarationText = \Illuminate\Support\Str::lower($tin->title . ' ' . $tin->content);
                        $isDeclarationPost = (bool) $tin->is_khai_bao_noi_tru || \Illuminate\Support\Str::contains($declarationText, 'khai báo nội trú') || \Illuminate\Support\Str::contains($declarationText, 'khai bao noi tru');
                    @endphp
                    <div class="position-relative">
                        @if($tin->img)
                        <img src="{{ asset($tin->img) }}" class="card-img-top rounded-top-3"
                            style="height: 180px; object-fit: cover;" alt="{{ $tin->title }}">
                        @else
                        <div class="d-flex justify-content-center align-items-center rounded-top-3 text-white"
                            style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-image fa-4x opacity-50"></i>
                        </div>
                        @endif

                        <span class="badge bg-primary rounded-pill px-3 position-absolute bottom-0 start-0 m-3">
                            <i class="fas fa-tag me-1"></i>{{ $tin->loaitin->name ?? 'Tin tức' }}
                        </span>

                        @if(auth()->check() && auth()->user()->isAdmin() && $tin->status == 0)
                        <span class="badge bg-secondary rounded-pill px-3 position-absolute top-0 end-0 m-2">
                            <i class="fas fa-eye-slash me-1"></i>Ẩn
                        </span>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column p-3">
                        <h6 class="card-title fw-bold lh-sm mb-2">
                            <a href="{{ route('tintuc.show', $tin->id) }}" class="text-decoration-none text-dark stretched-link text-truncate d-block">
                                {{ $tin->title }}
                            </a>
                        </h6>

                        <p class="text-muted small mb-2">
                            <i class="far fa-calendar-alt me-1"></i>
                            {{ $tin->date1 ? \Carbon\Carbon::parse($tin->date1)->format('d/m/Y') : '' }}
                        </p>

                        @if(!empty($tin->attachment_path))
                        <p class="text-primary small mb-2">
                            <i class="fas fa-paperclip me-1"></i> Có {{ $tin->attachment_display_label ?? 'file' }} đính kèm
                        </p>
                        @endif

                        @if(!empty($tin->attachments))
                        <p class="text-primary small mb-2">
                            <i class="fas fa-layer-group me-1"></i> Có {{ count($tin->attachments) }} tệp bổ sung
                        </p>
                        @endif

                        @if($isDeclarationPost)
                        <p class="text-success small mb-2">
                            <i class="fas fa-home me-1"></i> Kỳ khai báo nội trú {{ $tin->khai_bao_ky ? '- Kỳ ' . $tin->khai_bao_ky : '' }}
                        </p>
                        @endif

                        <p class="text-secondary small grow">
                            {{ Str::limit(strip_tags($tin->content), 80) }}
                        </p>

                        @if(auth()->check() && auth()->user()->isAdmin())
                        <div class="mt-auto pt-3 border-top">
                            <div class="d-flex gap-2">
                                <a href="{{ route('tintuc.edit', $tin->id) }}" class="btn btn-sm btn-outline-primary rounded-pill flex-fill">
                                    <i class="fas fa-edit me-1"></i>Sửa
                                </a>
                                <form action="{{ route('tintuc.destroy', $tin->id) }}" method="POST" class="d-inline flex-fill"
                                    onsubmit="return confirm('Xóa tin: {{ Str::limit($tin->title, 30) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill w-100">
                                        <i class="fas fa-trash me-1"></i>Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('tintuc.show', $tin->id) }}" class="btn btn-sm btn-primary rounded-pill mt-auto">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-newspaper fa-6x text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted mb-3">Chưa có tin tức nào</h5>
            @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('tintuc.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus me-1"></i>Thêm Tin Tức Đầu Tiên
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection