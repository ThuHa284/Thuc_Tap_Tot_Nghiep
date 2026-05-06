@extends('layouts.master')

@section('title', 'Khai báo nội trú')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Khai báo nội trú</li>
    </ol>
</nav>

{{-- Header --}}
<div class="text-center mb-4">
    <span class="badge bg-primary rounded-pill px-4 py-2 mb-3">
        <i class="fas fa-home me-1"></i>Khai báo nội trú
    </span>
    <h3 class="fw-bold text-dark mb-2">Thông tin nơi ở hiện tại</h3>
    <p class="text-muted">Vui lòng cung cấp thông tin chính xác để phục vụ công tác quản lý sinh viên</p>
</div>

{{-- Alerts --}}
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

{{-- Status card if exists --}}
@if($khaiBao)
<div class="alert alert-info rounded-3 border-0 shadow-sm mb-4">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <i class="fas fa-info-circle fa-2x"></i>
        </div>
        <div>
            <strong>Trạng thái khai báo:</strong> {!! $khaiBao->trang_thai_text !!}
            @if($khaiBao->ghi_chu)
                <br><small class="text-muted"><strong>Ghi chú:</strong> {{ $khaiBao->ghi_chu }}</small>
            @endif
        </div>
    </div>
</div>
@endif

{{-- Form Card --}}
<div class="card border-0 shadow-lg rounded-3">
    <div class="card-header bg-stu-primary text-white border-0 py-4">
        <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Form khai báo</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('khai_bao_noi_tru.luu') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control rounded-3 border-0 shadow-sm bg-light" 
                               value="{{ auth()->user()->full_name }}" readonly>
                        <label><i class="fas fa-user me-2 text-muted"></i>Họ và tên</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control rounded-3 border-0 shadow-sm bg-light" 
                               value="{{ auth()->user()->studentid }}" readonly>
                        <label><i class="fas fa-id-badge me-2 text-muted"></i>Mã số sinh viên (MSSV)</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="so_dien_thoai_sv" class="form-control rounded-3 border-0 shadow-sm @error('so_dien_thoai_sv') is-invalid @enderror" 
                               id="sdt_sv" placeholder="ĐT" value="{{ old('so_dien_thoai_sv', $khaiBao->so_dien_thoai_sv ?? '') }}" 
                               required pattern="[0-9]{10,11}" maxlength="11" inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <label for="sdt_sv"><i class="fas fa-phone me-2 text-muted"></i>ĐT cá nhân <span class="text-danger">*</span></label>
                        <small class="text-muted">Chỉ nhập số (10-11 chữ số)</small>
                        @error('so_dien_thoai_sv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="date" name="ngay_vao_tro" class="form-control rounded-3 border-0 shadow-sm @error('ngay_vao_tro') is-invalid @enderror" 
                               id="ngay_vao" value="{{ old('ngay_vao_tro', $khaiBao->ngay_vao_tro ?? '') }}" 
                               max="{{ date('Y-m-d') }}" required>
                        <label for="ngay_vao"><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày vào trọ <span class="text-danger">*</span></label>
                        @error('ngay_vao_tro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating">
                        <textarea name="dia_chi_hien_tai" class="form-control rounded-3 border-0 shadow-sm @error('dia_chi_hien_tai') is-invalid @enderror" 
                                  id="dia_chi" style="height: 80px" placeholder="Địa chỉ" required>{{ old('dia_chi_hien_tai', $khaiBao->dia_chi_hien_tai ?? '') }}</textarea>
                        <label for="dia_chi"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Địa chỉ hiện tại <span class="text-danger">*</span></label>
                        <small class="text-muted">Ví dụ: 123 Nguyễn Trãi, P.5, Q.5, TP.HCM</small>
                        @error('dia_chi_hien_tai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="ten_chu_tro" class="form-control rounded-3 border-0 shadow-sm @error('ten_chu_tro') is-invalid @enderror" 
                               id="chu_tro" placeholder="Chủ trọ" value="{{ old('ten_chu_tro', $khaiBao->ten_chu_tro ?? '') }}" 
                               required pattern="^[a-zA-ZÀ-ỹ\s]+$"
                               oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ỹ\s]/g, '')">
                        <label for="chu_tro"><i class="fas fa-user-tie me-2 text-muted"></i>Họ tên chủ trọ <span class="text-danger">*</span></label>
                        <small class="text-muted">Chỉ nhập chữ cái</small>
                        @error('ten_chu_tro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="so_dien_thoai_chu_tro" class="form-control rounded-3 border-0 shadow-sm @error('so_dien_thoai_chu_tro') is-invalid @enderror" 
                               id="sdt_chu_tro" placeholder="ĐT chủ trọ" value="{{ old('so_dien_thoai_chu_tro', $khaiBao->so_dien_thoai_chu_tro ?? '') }}" 
                               required pattern="[0-9]{10,11}" maxlength="11" inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <label for="sdt_chu_tro"><i class="fas fa-phone-square me-2 text-muted"></i>ĐT chủ trọ <span class="text-danger">*</span></label>
                        <small class="text-muted">Chỉ nhập số (10-11 chữ số)</small>
                        @error('so_dien_thoai_chu_tro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating">
                        <textarea name="ghi_chu" class="form-control rounded-3 border-0 shadow-sm" 
                                  id="ghi_chu" style="height: 80px" placeholder="Ghi chú">{{ old('ghi_chu', $khaiBao->ghi_chu ?? '') }}</textarea>
                        <label for="ghi_chu"><i class="fas fa-sticky-note me-2 text-muted"></i>Ghi chú (không bắt buộc)</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-stu-primary rounded-pill px-5 py-2">
                    <i class="fas fa-save me-2"></i> {{ $khaiBao ? 'Cập nhật khai báo' : 'Gửi khai báo' }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-stu-primary {
        background: #004a99 !important;
    }
    .btn-stu-primary {
        background: #004a99;
        border: none;
        color: #fff;
    }
    .btn-stu-primary:hover {
        background: #003a7a;
        color: white;
    }
    .form-floating > .form-control:focus,
    .form-floating > .form-control:not(:placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    .form-floating > label {
        padding: 1rem 1rem;
    }
</style>
@endsection
