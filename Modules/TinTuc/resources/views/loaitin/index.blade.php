@extends('layouts.master')

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
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Loại Tin</li>
    </ol>
</nav>

<div class="card border-0 shadow-lg rounded-3">
    <div class="card-header bg-gradient text-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold"><i class="fas fa-folder-open me-2"></i>Quản lý Loại Tin</h5>
                <small>Quản lý danh mục tin tức</small>
            </div>
            <a href="{{ route('loaitin.create') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-medium">
                <i class="fas fa-plus me-1"></i> Thêm Loại Tin
            </a>
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

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Bảng danh sách --}}
        @if($loaiTins->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center rounded-start-pill" style="width: 80px;">
                            <i class="fas fa-hashtag me-1"></i>STT
                        </th>
                        <th><i class="fas fa-folder me-1 text-warning"></i>Tên Loại Tin</th>
                        <th class="text-center" style="width: 120px;">
                            <i class="fas fa-newspaper me-1 text-info"></i>Số Tin
                        </th>
                        <th class="text-center rounded-end-pill" style="width: 180px;">
                            <i class="fas fa-cogs me-1"></i>Hành động
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loaiTins as $index => $loaiTin)
                    <tr class="category-row">
                        <td class="text-center fw-bold text-muted">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <span class="fw-semibold">{{ $loaiTin->name }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info rounded-pill px-3">{{ $loaiTin->tintucs_count ?? 0 }} tin</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('loaitin.edit', $loaiTin->id) }}" class="btn btn-sm btn-outline-primary rounded-pill me-1">
                                <i class="fas fa-edit me-1"></i>Sửa
                            </a>
                            <form action="{{ route('loaitin.destroy', $loaiTin->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" 
                                        onclick="return confirm('Xóa loại tin: {{ $loaiTin->name }}?')">
                                    <i class="fas fa-trash me-1"></i>Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-5x text-muted opacity-25 mb-3"></i>
            <h5 class="text-muted">Chưa có loại tin nào</h5>
            <a href="{{ route('loaitin.create') }}" class="btn btn-primary rounded-pill mt-2">
                <i class="fas fa-plus me-1"></i>Thêm Loại Tin Đầu Tiên
            </a>
        </div>
        @endif
    </div>
</div>

<style>
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
    .category-row {
        transition: all 0.3s ease;
    }
    .category-row:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    .table th {
        border: none;
        font-weight: 600;
    }
</style>
@endsection
