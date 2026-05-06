@extends('layouts.master')

@section('title', 'Quản lý khai báo nội trú')

@section('content')
{{-- Menu ngang --}}
<div class="row">
    <div class="col-12">
        @include('tintuc::components.tintuc-menu')
    </div>
</div>

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Khai báo nội trú</li>
    </ol>
</nav>

{{-- Header --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-1 fw-bold">
            <i class="fas fa-building me-2"></i>Quản lý Khai báo Nội trú
        </h2>
        <p class="mb-0 opacity-75 small">Danh sách sinh viên đã khai báo nơi ở</p>
    </div>
    <a href="{{ route('tintuc.create') }}?type=khai_bao" class="btn btn-light rounded-pill px-4 fw-medium">
        <i class="fas fa-plus me-1"></i> Thêm khai báo nội trú
    </a>
</div>

@php
    $activeDeclaration = \Modules\TinTuc\Models\TinTuc::currentKhaiBaoNoiTru();
@endphp

@if($activeDeclaration)
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="text-muted small mb-1">Kỳ khai báo đang mở</div>
            <div class="fw-bold text-dark mb-1">{{ $activeDeclaration->title }}</div>
            <div class="text-secondary small">
                Từ {{ \Carbon\Carbon::parse($activeDeclaration->khai_bao_start_at)->format('d/m/Y H:i') }}
                đến {{ \Carbon\Carbon::parse($activeDeclaration->khai_bao_end_at)->format('d/m/Y H:i') }}
            </div>
        </div>
        <a href="{{ route('tintuc.show', $activeDeclaration->id) }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fas fa-eye me-1"></i> Xem bài viết
        </a>
    </div>
</div>
@endif

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Search --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('khai_bao_noi_tru.index') }}" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 rounded-end-pill" 
                           placeholder="Tìm kiếm họ tên, MSSV..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill">
                    <i class="fas fa-search me-1"></i> Tìm kiếm
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-md-start justify-content-between gap-2">
                <span class="badge bg-primary rounded-pill px-3 py-2 w-100 text-center">
                    <i class="fas fa-users me-1"></i>{{ $danhSach->count() }} sinh viên
                </span>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('khai_bao_noi_tru.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Xóa lọc</a>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-lg rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-gradient text-white">
                <tr>
                    <th class="rounded-start-pill ps-4"><i class="fas fa-hashtag me-1"></i>STT</th>
                    <th><i class="fas fa-user me-1"></i>Họ Tên</th>
                    <th><i class="fas fa-id-badge me-1"></i>MSSV</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Địa chỉ</th>
                    <th><i class="fas fa-user-tie me-1"></i>Chủ trọ</th>
                    <th><i class="fas fa-calendar me-1"></i>Ngày vào</th>
                    <th class="text-center rounded-end-pill pe-4"><i class="fas fa-cogs me-1"></i>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danhSach as $key => $item)
                <tr class="student-row">
                    <td class="ps-4 fw-bold text-muted">{{ str_pad($key + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="fw-semibold">{{ $item->ho_ten }}</td>
                    <td><span class="badge bg-secondary rounded-pill">{{ $item->mssv }}</span></td>
                    <td>
                        <small class="text-muted">{{ Str::limit($item->dia_chi_hien_tai, 30) }}</small>
                    </td>
                    <td>
                        <div>
                            <small class="fw-medium">{{ Str::limit($item->ten_chu_tro, 15) }}</small>
                            <br><small class="text-muted"><i class="fas fa-phone fa-xs me-1"></i>{{ $item->so_dien_thoai_chu_tro }}</small>
                        </div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->ngay_vao_tro)->format('d/m/Y') }}</td>
                    <td class="text-center pe-4">
                        <div class="btn-group">
                            <a href="{{ route('khai_bao_noi_tru.show', $item->id) }}" 
                               class="btn btn-sm btn-outline-primary rounded-start-pill px-3" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('khai_bao_noi_tru.edit', $item->id) }}" 
                               class="btn btn-sm btn-outline-warning rounded-0" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('khai_bao_noi_tru.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-end-pill" title="Xóa"
                                        onclick="return confirm('Xóa khai báo của: {{ $item->ho_ten }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted opacity-25 mb-3"></i>
                        <h6 class="text-muted">Chưa có khai báo nội trú nào</h6>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
        color: white;
    }
    .student-row {
        transition: all 0.3s ease;
    }
    .student-row:hover {
        background-color: #f8f9fa;
    }
    .table th {
        border: none;
        font-weight: 600;
    }
</style>
@endsection
