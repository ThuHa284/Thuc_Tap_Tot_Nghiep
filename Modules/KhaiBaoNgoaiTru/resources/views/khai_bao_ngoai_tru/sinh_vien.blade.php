@extends('layouts.master')

@section('title', 'Khai báo ngoại trú')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Khai báo ngoại trú</li>
    </ol>
</nav>

{{-- Header --}}
<div class="text-center mb-4">
    <span class="badge bg-primary rounded-pill px-4 py-2 mb-3">
        <i class="fas fa-home me-1"></i>Khai báo ngoại trú
    </span>
    <h3 class="fw-bold text-dark mb-2">Thông tin nơi ở hiện tại</h3>
    <p class="text-muted">Vui lòng cung cấp thông tin chính xác để phục vụ công tác quản lý sinh viên</p>
</div>

{{-- Thông báo khi chưa có kỳ khai báo --}}
@if(!$activeDeclaration)
<div class="card border-0 shadow-lg rounded-3">
    <div class="card-body text-center p-5">
        <div class="mb-4">
            <i class="fas fa-clock fa-4x text-muted"></i>
        </div>
        <h4 class="fw-bold text-dark mb-3">Chưa có kỳ khai báo ngoại trú</h4>
        <p class="text-muted mb-4">Hiện tại chưa có kỳ khai báo nào được mở. Vui lòng quay lại sau!</p>
        <a href="/" class="btn btn-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Quay về trang chủ
        </a>
    </div>
</div>
@else

{{-- Kỳ khai báo hiện tại --}}
<div class="alert alert-info border-0 rounded-3 mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-calendar-check fa-2x me-3"></i>
        <div>
            <strong>Kỳ khai báo: {{ $activeDeclaration->title }}</strong>
            <div class="small text-muted">
                Thời gian: {{ \Carbon\Carbon::parse($activeDeclaration->khai_bao_start_at)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($activeDeclaration->khai_bao_end_at)->format('d/m/Y') }}
            </div>
        </div>
    </div>
</div>

{{-- Trạng thái khai báo - collapsible --}}
@if($khaiBao)
<div class="card border-0 shadow-lg rounded-3 mb-4 cursor-pointer" id="card-thong-bao">
    <div class="card-body text-center p-4">
        <div class="d-flex align-items-center justify-content-center">
            <i class="fas fa-check-circle fa-2x text-success me-3"></i>
            <div>
                <strong class="text-success">Bạn đã khai báo ngoại trú</strong>
                <div class="small text-muted">Nhấn để xem chi tiết thông tin đã khai báo</div>
            </div>
        </div>
    </div>
</div>

{{-- Chi tiết khai báo đã lưu - collapsible --}}
<div class="card border-0 shadow-lg rounded-3 mb-4" id="chi-tiet-khai-bao" style="display: none;">
    <div class="card-header bg-light border-0 py-3">
        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-info-circle me-2"></i>Chi tiết khai báo</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">Họ và tên</label>
                    <div class="fw-medium">{{ $khaiBao->ho_ten }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">MSSV</label>
                    <div class="fw-medium">{{ $khaiBao->mssv }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">Điện thoại cá nhân</label>
                    <div class="fw-medium">{{ $khaiBao->so_dien_thoai_sv }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">Ngày vào trọ</label>
                    <div class="fw-medium">{{ $khaiBao->ngay_vao_tro->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">Loại địa chỉ</label>
                    <div class="fw-medium">{{ $khaiBao->loai_dia_chi === 'ki_tuc_xa' ? 'Kí túc xá' : 'Khác' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">Ngày khai báo</label>
                    <div class="fw-medium">{{ $khaiBao->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="col-12">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">Địa chỉ hiện tại</label>
                    <div class="fw-medium">{{ $khaiBao->dia_chi_hien_tai }}</div>
                </div>
            </div>
            @if($khaiBao->ten_chu_tro && $khaiBao->loai_dia_chi !== 'ki_tuc_xa')
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">Họ tên chủ trọ</label>
                    <div class="fw-medium">{{ $khaiBao->ten_chu_tro }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">ĐT chủ trọ</label>
                    <div class="fw-medium">{{ $khaiBao->so_dien_thoai_chu_tro }}</div>
                </div>
            </div>
            @endif
            @if($khaiBao->ghi_chu)
            <div class="col-12">
                <div class="p-3 bg-light rounded-3">
                    <label class="text-muted small mb-1">Ghi chú</label>
                    <div class="fw-medium">{{ $khaiBao->ghi_chu }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@else
<div class="alert alert-warning border-0 rounded-3 mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-circle fa-2x me-3"></i>
        <div>
            <strong>Bạn chưa khai báo ngoại trú</strong>
            <div class="small text-muted">
                Vui lòng điền thông tin bên dưới để hoàn tất khai báo.
            </div>
        </div>
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

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Form Card --}}
<div class="card border-0 shadow-lg rounded-3">
    <div class="card-header bg-stu-primary text-white border-0 py-4">
        <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Form khai báo</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('sinh_vien.luu') }}" method="POST">
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
                               id="ngay_vao" value="{{ old('ngay_vao_tro', $khaiBao && $khaiBao->ngay_vao_tro ? $khaiBao->ngay_vao_tro->format('Y-m-d') : '') }}"
                               max="{{ date('Y-m-d') }}" required>
                        <label for="ngay_vao"><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày vào trọ <span class="text-danger">*</span></label>
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
                            <option value="khac" {{ old('loai_dia_chi', $khaiBao->loai_dia_chi ?? '') == 'khac' ? 'selected' : '' }}>Khác</option>
                            <option value="ki_tuc_xa" {{ old('loai_dia_chi', $khaiBao->loai_dia_chi ?? '') == 'ki_tuc_xa' ? 'selected' : '' }}>Kí túc xá</option>
                        </select>
                        <label for="loai_dia_chi"><i class="fas fa-building me-2 text-muted"></i>Loại địa chỉ <span class="text-danger">*</span></label>
                        @error('loai_dia_chi')
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

                <div class="col-md-6" id="chu_tro_wrapper">
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

                <div class="col-md-6" id="sdt_chu_tro_wrapper">
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
@endif

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
    /* Disabled state styling */
    .form-disabled {
        background-color: #e9ecef !important;
        opacity: 0.7;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loaiDiaChi = document.getElementById('loai_dia_chi');
    const diaChiInput = document.getElementById('dia_chi');
    const chuTroInput = document.getElementById('chu_tro');
    const sdtChuTroInput = document.getElementById('sdt_chu_tro');
    const chiTietKhaiBao = document.getElementById('chi-tiet-khai-bao');

    // Toggle chi tiết khai báo
    const cardThongBao = document.querySelector('.card.cursor-pointer');
    if (cardThongBao && chiTietKhaiBao) {
        cardThongBao.addEventListener('click', function() {
            if (chiTietKhaiBao.style.display === 'none') {
                chiTietKhaiBao.style.display = 'block';
            } else {
                chiTietKhaiBao.style.display = 'none';
            }
        });
    }

    // Nếu có thông báo thành công, tự động hiện chi tiết
    @if(session('success'))
        if (chiTietKhaiBao) {
            chiTietKhaiBao.style.display = 'block';
        }
    @endif

    function toggleFields() {
        const diaChiKtx = 'Kí túc xá (180 Cao Lỗ, Phường Chánh Hưng, Thành phố Hồ Chí Minh)';

        if (loaiDiaChi.value === 'ki_tuc_xa') {
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
            if (diaChiInput.value.startsWith('Kí túc xá')) {
                diaChiInput.value = '';
            }
            diaChiInput.readOnly = false;

            chuTroInput.disabled = false;
            sdtChuTroInput.disabled = false;
            chuTroInput.classList.remove('form-disabled');
            sdtChuTroInput.classList.remove('form-disabled');
            chuTroInput.setAttribute('required', true);
            sdtChuTroInput.setAttribute('required', true);
        }
    }

    loaiDiaChi.addEventListener('change', toggleFields);
    toggleFields();
});
</script>
@endsection
