@extends('layouts.master')

@section('title', 'Chi tiết khai báo ngoại trú')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('khai_bao_ngoai_tru.index') }}" class="text-decoration-none text-secondary">Khai báo ngoại trú</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Chi tiết</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        {{-- Header --}}
        <div class="text-center mb-4">
            <span class="badge bg-primary rounded-pill px-4 py-2 mb-3">
                <i class="fas fa-building me-1"></i>Thông tin khai báo ngoại trú
            </span>
            <h3 class="fw-bold text-dark">{{ $khaiBao->ho_ten }}</h3>
            <span class="badge bg-secondary rounded-pill">{{ $khaiBao->mssv }}</span>
        </div>

        {{-- Card --}}
        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="card-header bg-gradient text-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Chi tiết khai báo</h5>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted fw-medium ps-0" style="width: 180px;">
                            <i class="fas fa-user me-2 text-primary"></i>Họ tên:
                        </td>
                        <td class="fw-semibold">{{ $khaiBao->ho_ten }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">
                            <i class="fas fa-id-badge me-2 text-primary"></i>MSSV:
                        </td>
                        <td><span class="badge bg-secondary rounded-pill">{{ $khaiBao->mssv }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">
                            <i class="fas fa-phone me-2 text-primary"></i>ĐT Sinh viên:
                        </td>
                        <td>{{ $khaiBao->so_dien_thoai_sv }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>Địa chỉ:
                        </td>
                        <td>{{ $khaiBao->dia_chi_hien_tai }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">
                            <i class="fas fa-user-tie me-2 text-success"></i>Chủ trọ:
                        </td>
                        <td>{{ $khaiBao->ten_chu_tro }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">
                            <i class="fas fa-phone-square me-2 text-success"></i>ĐT Chủ trọ:
                        </td>
                        <td>{{ $khaiBao->so_dien_thoai_chu_tro }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">
                            <i class="fas fa-calendar-alt me-2 text-warning"></i>Ngày vào trọ:
                        </td>
                        <td>{{ \Carbon\Carbon::parse($khaiBao->ngay_vao_tro)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium ps-0">
                            <i class="fas fa-toggle-on me-2 text-info"></i>Trạng thái:
                        </td>
                        <td>{!! $khaiBao->trangThaiText !!}</td>
                    </tr>
                    @if($khaiBao->ghi_chu)
                    <tr>
                        <td class="text-muted fw-medium ps-0">
                            <i class="fas fa-sticky-note me-2 text-secondary"></i>Ghi chú:
                        </td>
                        <td class="fst-italic">{{ $khaiBao->ghi_chu }}</td>
                    </tr>
                    @endif
                </table>

                <hr class="my-4">

                {{-- Actions --}}
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('khai_bao_ngoai_tru.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <div class="d-flex gap-2">
                        @if($khaiBao->trang_thai == 1)
                            <a href="{{ route('khai_bao_ngoai_tru.duyet', $khaiBao->id) }}" class="btn btn-success rounded-pill px-4">
                                <i class="fas fa-check me-1"></i> Duyệt
                            </a>
                            <a href="{{ route('khai_bao_ngoai_tru.tuChoi', $khaiBao->id) }}" class="btn btn-danger rounded-pill px-4">
                                <i class="fas fa-times me-1"></i> Từ chối
                            </a>
                        @endif
                        <a href="{{ route('khai_bao_ngoai_tru.edit', $khaiBao->id) }}" class="btn btn-warning text-dark rounded-pill px-4">
                            <i class="fas fa-edit me-1"></i> Sửa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .btn-warning {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        border: none;
    }
</style>
@endsection
