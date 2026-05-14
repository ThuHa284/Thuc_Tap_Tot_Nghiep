@extends('layouts.master')

@section('title', 'Thêm khai báo ngoại trú')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('khai_bao_ngoai_tru.index') }}" class="text-decoration-none text-secondary">Khai báo ngoại trú</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Thêm mới</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-header bg-gradient text-white border-0 py-4">
                <h4 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Thêm Khai báo Ngoại trú</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('khai_bao_ngoai_tru.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="ho_ten" class="form-control rounded-3 border-0 shadow-sm @error('ho_ten') is-invalid @enderror" 
                                       id="ho_ten" placeholder="Họ tên" value="{{ old('ho_ten') }}" required>
                                <label for="ho_ten"><i class="fas fa-user me-2 text-muted"></i>Họ tên</label>
                                @error('ho_ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="mssv" class="form-control rounded-3 border-0 shadow-sm @error('mssv') is-invalid @enderror" 
                                       id="mssv" placeholder="MSSV" value="{{ old('mssv') }}" required maxlength="10">
                                <label for="mssv"><i class="fas fa-id-badge me-2 text-muted"></i>MSSV</label>
                                @error('mssv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="so_dien_thoai_sv" class="form-control rounded-3 border-0 shadow-sm @error('so_dien_thoai_sv') is-invalid @enderror" 
                                       id="sdt_sv" placeholder="ĐT SV" value="{{ old('so_dien_thoai_sv') }}" 
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
                                       id="ngay_vao" value="{{ old('ngay_vao_tro') }}" 
                                       max="{{ date('Y-m-d') }}" required>
                                <label for="ngay_vao"><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày vào trọ</label>
                                @error('ngay_vao_tro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="loai_dia_chi" class="form-select rounded-3 border-0 shadow-sm @error('loai_dia_chi') is-invalid @enderror" 
                                        id="loai_dia_chi" required>
                                    <option value="">-- Chọn loại địa chỉ --</option>
                                    <option value="khac" {{ old('loai_dia_chi') == 'khac' ? 'selected' : '' }}>Khác</option>
                                    <option value="ki_tuc_xa" {{ old('loai_dia_chi') == 'ki_tuc_xa' ? 'selected' : '' }}>Kí túc xá</option>
                                </select>
                                <label for="loai_dia_chi"><i class="fas fa-building me-2 text-muted"></i>Loại địa chỉ</label>
                                @error('loai_dia_chi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea name="dia_chi_hien_tai" class="form-control rounded-3 border-0 shadow-sm @error('dia_chi_hien_tai') is-invalid @enderror" 
                                          id="dia_chi" style="height: 80px" placeholder="Địa chỉ" required>{{ old('dia_chi_hien_tai') }}</textarea>
                                <label for="dia_chi"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Địa chỉ hiện tại</label>
                                @error('dia_chi_hien_tai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6" id="chu_tro_wrapper">
                            <div class="form-floating">
                                <input type="text" name="ten_chu_tro" class="form-control rounded-3 border-0 shadow-sm @error('ten_chu_tro') is-invalid @enderror" 
                                       id="chu_tro" placeholder="Chủ trọ" value="{{ old('ten_chu_tro') }}" 
                                       required pattern="^[a-zA-ZÀ-ỹ\s]+$"
                                       oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ỹ\s]/g, '')">
                                <label for="chu_tro"><i class="fas fa-user-tie me-2 text-muted"></i>Tên chủ trọ</label>
                                <small class="text-muted">Chỉ nhập chữ cái</small>
                                @error('ten_chu_tro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6" id="sdt_chu_tro_wrapper">
                            <div class="form-floating">
                                <input type="text" name="so_dien_thoai_chu_tro" class="form-control rounded-3 border-0 shadow-sm @error('so_dien_thoai_chu_tro') is-invalid @enderror" 
                                       id="sdt_chu_tro" placeholder="ĐT chủ trọ" value="{{ old('so_dien_thoai_chu_tro') }}" 
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
                                          id="ghi_chu" style="height: 80px" placeholder="Ghi chú">{{ old('ghi_chu') }}</textarea>
                                <label for="ghi_chu"><i class="fas fa-sticky-note me-2 text-muted"></i>Ghi chú (không bắt buộc)</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('khai_bao_ngoai_tru.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-1"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
    /* Disabled state styling */
    .form-disabled {
        background-color: #e9ecef !important;
        opacity: 0.7;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loaiDiaChi = document.getElementById('loai_dia_chi');
    const diaChiInput = document.getElementById('dia_chi');
    const chuTroInput = document.getElementById('chu_tro');
    const sdtChuTroInput = document.getElementById('sdt_chu_tro');

    function toggleFields() {
        const diaChiKtx = 'Kí túc xá (180 Cao Lỗ, Phường Chánh Hưng, Thành phố Hồ Chí Minh)';

        if (loaiDiaChi.value === 'ki_tuc_xa') {
            // Auto fill dia chi
            diaChiInput.value = diaChiKtx;
            diaChiInput.readOnly = true;

            // Xóa giá trị và disable
            chuTroInput.value = '';
            sdtChuTroInput.value = '';
            chuTroInput.disabled = true;
            sdtChuTroInput.disabled = true;
            chuTroInput.classList.add('form-disabled');
            sdtChuTroInput.classList.add('form-disabled');
            chuTroInput.removeAttribute('required');
            sdtChuTroInput.removeAttribute('required');
        } else {
            // Enable editing dia chi
            if (diaChiInput.value.startsWith('Kí túc xá')) {
                diaChiInput.value = '';
            }
            diaChiInput.readOnly = false;

            // Enable chu tro fields
            chuTroInput.disabled = false;
            sdtChuTroInput.disabled = false;
            chuTroInput.classList.remove('form-disabled');
            sdtChuTroInput.classList.remove('form-disabled');
            chuTroInput.setAttribute('required', true);
            sdtChuTroInput.setAttribute('required', true);
        }
    }

    loaiDiaChi.addEventListener('change', toggleFields);
    toggleFields(); // Run on page load
});
</script>
@endsection
