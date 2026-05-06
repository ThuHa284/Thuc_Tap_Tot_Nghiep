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
        <li class="breadcrumb-item"><a href="{{ route('khai_bao_noi_tru.index') }}" class="text-decoration-none text-secondary">Khai báo nội trú</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Chỉnh sửa</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-header bg-warning text-dark border-0 py-4">
                <h4 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Chỉnh sửa Khai báo Nội trú</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('khai_bao_noi_tru.update', $khaiBao->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="ho_ten" class="form-control rounded-3 border-0 shadow-sm @error('ho_ten') is-invalid @enderror" 
                                       id="ho_ten" placeholder="Họ tên" value="{{ old('ho_ten', $khaiBao->ho_ten) }}" required>
                                <label for="ho_ten"><i class="fas fa-user me-2 text-muted"></i>Họ tên</label>
                                @error('ho_ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="mssv" class="form-control rounded-3 border-0 shadow-sm @error('mssv') is-invalid @enderror" 
                                       id="mssv" placeholder="MSSV" value="{{ old('mssv', $khaiBao->mssv) }}" required maxlength="10">
                                <label for="mssv"><i class="fas fa-id-badge me-2 text-muted"></i>MSSV</label>
                                @error('mssv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="so_dien_thoai_sv" class="form-control rounded-3 border-0 shadow-sm @error('so_dien_thoai_sv') is-invalid @enderror" 
                                       id="sdt_sv" placeholder="ĐT SV" value="{{ old('so_dien_thoai_sv', $khaiBao->so_dien_thoai_sv) }}" 
                                       required maxlength="11" inputmode="numeric"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <label for="sdt_sv"><i class="fas fa-phone me-2 text-muted"></i>ĐT Sinh viên</label>
                                <small class="text-muted">Chỉ nhập số (10-11 chữ số)</small>
                                @error('so_dien_thoai_sv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" name="ngay_vao_tro" class="form-control rounded-3 border-0 shadow-sm @error('ngay_vao_tro') is-invalid @enderror" 
                                       id="ngay_vao" value="{{ old('ngay_vao_tro', $khaiBao->ngay_vao_tro) }}" 
                                       max="{{ date('Y-m-d') }}" required>
                                <label for="ngay_vao"><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày vào trọ</label>
                                @error('ngay_vao_tro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="dia_chi_hien_tai" class="form-control rounded-3 border-0 shadow-sm @error('dia_chi_hien_tai') is-invalid @enderror" 
                                          id="dia_chi" style="height: 80px" placeholder="Địa chỉ" required>{{ old('dia_chi_hien_tai', $khaiBao->dia_chi_hien_tai) }}</textarea>
                                <label for="dia_chi"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Địa chỉ hiện tại</label>
                                @error('dia_chi_hien_tai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="ten_chu_tro" class="form-control rounded-3 border-0 shadow-sm @error('ten_chu_tro') is-invalid @enderror" 
                                       id="chu_tro" placeholder="Chủ trọ" value="{{ old('ten_chu_tro', $khaiBao->ten_chu_tro) }}" 
                                       required pattern="^[a-zA-ZÀ-ỹ\s]+$"
                                       oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ỹ\s]/g, '')">
                                <label for="chu_tro"><i class="fas fa-user-tie me-2 text-muted"></i>Tên chủ trọ</label>
                                <small class="text-muted">Chỉ nhập chữ cái</small>
                                @error('ten_chu_tro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="so_dien_thoai_chu_tro" class="form-control rounded-3 border-0 shadow-sm @error('so_dien_thoai_chu_tro') is-invalid @enderror" 
                                       id="sdt_chu_tro" placeholder="ĐT chủ trọ" value="{{ old('so_dien_thoai_chu_tro', $khaiBao->so_dien_thoai_chu_tro) }}" 
                                       required maxlength="11" inputmode="numeric"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <label for="sdt_chu_tro"><i class="fas fa-phone-square me-2 text-muted"></i>ĐT Chủ trọ</label>
                                <small class="text-muted">Chỉ nhập số (10-11 chữ số)</small>
                                @error('so_dien_thoai_chu_tro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="ghi_chu" class="form-control rounded-3 border-0 shadow-sm" 
                                          id="ghi_chu" style="height: 80px" placeholder="Ghi chú">{{ old('ghi_chu', $khaiBao->ghi_chu) }}</textarea>
                                <label for="ghi_chu"><i class="fas fa-sticky-note me-2 text-muted"></i>Ghi chú (không bắt buộc)</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('khai_bao_noi_tru.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-1"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-warning text-dark rounded-pill px-5">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-warning {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        border: none;
    }
    .btn-warning:hover {
        background: linear-gradient(135deg, #f5c55a 0%, #fc9358 100%);
        color: #333;
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
