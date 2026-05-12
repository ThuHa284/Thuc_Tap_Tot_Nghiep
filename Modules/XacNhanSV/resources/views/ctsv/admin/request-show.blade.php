@extends('layouts.master')
@section('title', 'Chi tiết đơn #' . $submission->id)

@section('content')
@php
    $d  = $submission->data ?? [];
    $st = (int) $submission->status;
    $alertType   = match($st){
        0 => 'warning', 1 => 'success', 2 => 'danger', 3 => 'info', default => 'secondary'
    };
    $statusLabel = match($st){
        0 => '⏳ Chờ duyệt', 1 => '✅ Đã duyệt', 2 => '❌ Từ chối', 3 => '🖨️ Đã in', default => '?'
    };
    $badgeClass = match($st){
        0 => 'warning text-dark', 1 => 'success', 2 => 'danger', 3 => 'info', default => 'secondary'
    };
    $formId = $submission->form->formid ?? 0;

    // ✅ Map khoa
    $khoaMap = [
        'cntt' => 'Công nghệ Thông tin', 'ckhi' => 'Cơ khí',
        'cntp' => 'Công nghệ Thực phẩm', 'ddtu' => 'Điện - Điện tử',
        'dsgn' => 'Thiết kế', 'kd' => 'Kinh doanh',
        'ktct' => 'Kế toán - Kiểm toán', 'qtkd' => 'Quản trị Kinh doanh',
    ];
    $rawKhoa     = $d['khoa'] ?? $submission->user->facultyid ?? '';
    $khoaTen     = $khoaMap[strtolower($rawKhoa)] ?? $rawKhoa;
    $khoaTenUser = $khoaMap[strtolower($submission->user->facultyid ?? '')] ?? ($submission->user->facultyid ?? '');
    $tenSV       = ($submission->user->last_name ?? '') . ' ' . ($submission->user->first_name ?? '');

    // ✅ Fallback ngày sinh từ DB
    $dobNgay1  = $d['ngay_sinh'] ?? '';
    $dobThang1 = $d['thang_sinh'] ?? '';
    $dobNam1   = $d['nam_sinh'] ?? '';
    $dobNgay2  = $d['ngay'] ?? '';
    $dobThang2 = $d['thang'] ?? '';
    $dobNam2   = $d['nam'] ?? '';
    if ($submission->user->birthdate ?? null) {
        $dob = \Carbon\Carbon::parse($submission->user->birthdate);
        $dobNgay1  = $dobNgay1  ?: $dob->format('d');
        $dobThang1 = $dobThang1 ?: $dob->format('m');
        $dobNam1   = $dobNam1   ?: $dob->format('Y');
        $dobNgay2  = $dobNgay2  ?: $dob->format('d');
        $dobThang2 = $dobThang2 ?: $dob->format('m');
        $dobNam2   = $dobNam2   ?: $dob->format('Y');
    }
@endphp

<div class="container py-4" style="max-width:1000px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">📄 Chi tiết đơn #{{ $submission->id }}</h4>
            <p class="text-muted mb-0 small">{{ $submission->form->name ?? '—' }}</p>
        </div>
        <a href="{{ route('xacnhansv.ctsv.admin.requests') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-{{ $alertType }} d-flex align-items-center gap-2 mb-4">
        <span class="fw-bold fs-6">{{ $statusLabel }}</span>
        @if($st === 0) <span class="small">— Đơn đang chờ xét duyệt</span>
        @elseif($st === 1) <span class="small">— Đơn đã được duyệt, chưa in</span>
        @elseif($st === 2) <span class="small">— Đơn đã bị từ chối</span>
        @elseif($st === 3) <span class="small">— Đơn đã được in và hoàn tất</span>
        @endif
    </div>

    <div class="row g-4">

        {{-- Cột trái --}}
        <div class="col-md-8">

            {{-- Thông tin sinh viên --}}
            <div class="card shadow-sm mb-3 no-print">
                <div class="card-header bg-white fw-bold">👤 Thông tin sinh viên</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Họ tên</label>
                            <span class="fw-semibold">{{ $tenSV }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">MSSV</label>
                            <span>{{ $submission->studentid }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Lớp</label>
                            <span>{{ $submission->user->classid ?? '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Khoa</label>
                            <span>{{ $khoaTenUser ?: '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Email</label>
                            <span>{{ $submission->user->email ?? '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Hình thức nhận</label>
                            <span>
                                @switch($submission->get_at)
                                    @case('truc_tiep') 🏢 Nhận trực tiếp tại phòng CTSV @break
                                    @case('email')     📧 Nhận qua Email @break
                                   @case('buu_dien')
    📮 Nhận qua Bưu điện
    @if($submission->ReceivingAddress)
        <div class="text-muted small mt-1">📍 {{ $submission->ReceivingAddress }}</div>
    @endif
    @break
                                    @default —
                                @endswitch
                            </span>
                        </div>
                        @if($submission->note)
                        <div class="col-12">
                            <label class="text-muted small d-block">Ghi chú</label>
                            <span>{{ $submission->note }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- MẪU GIẤY TỜ --}}
            <div id="print-area">

            @if($formId == 1)
            {{-- Form 1: Hoãn NVQS --}}
            <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">
                <div class="text-center mb-3">
                    <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
                    <div>———————————</div>
                    <div class="fw-bold mt-2" style="font-size:16px">ĐƠN XIN XÁC NHẬN HOÃN NGHĨA VỤ QUÂN SỰ</div>
                </div>
                <p>Kính gửi: Ban Giám Hiệu Trường Đại học Công nghệ Sài Gòn</p>
                <p>Tôi tên: <span class="border-bottom px-1">{{ $d['ho_ten'] ?? $tenSV }}</span>
                   &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $dobNgay1 ?: '___' }}</span>
                   tháng <span class="border-bottom px-1">{{ $dobThang1 ?: '___' }}</span>
                   năm <span class="border-bottom px-1">{{ $dobNam1 ?: '___' }}</span></p>
                <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
                   &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen ?: '___' }}</span>
                   &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Hộ khẩu thường trú: <span class="border-bottom px-1" style="min-width:300px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span></p>
                <p>Bậc đào tạo: <span class="border-bottom px-1">Đại học</span> &nbsp; Hệ đào tạo: <span class="border-bottom px-1">Chính quy</span> của Trường Đại học Công nghệ Sài Gòn.</p>
                <p>Số điện thoại: <span class="border-bottom px-1">{{ $d['sdt'] ?? '___' }}</span></p>
                <p>Nay tôi làm đơn xin nhà trường cấp giấy chứng nhận để bổ túc hồ sơ xin:</p>
                <p><span>{{ !empty($d['xin_hoan_nvqs']) ? '☑' : '☐' }}</span> Xin hoãn nghĩa vụ quân sự</p>
                <p>Lý do khác: <span class="border-bottom px-1" style="min-width:300px;display:inline-block">{{ $d['ly_do_khac'] ?? '___' }}</span></p>
                <p class="mb-4">Trân trọng kính chào.</p>
                <div class="d-flex justify-content-between">
                    <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
                    <div class="text-center"><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ $tenSV }}</p></div>
                </div>
                <hr>
                <div class="text-center fw-bold mb-2">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>
                <p>Xác nhận sinh viên: {{ $tenSV }}</p>
                <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
                   &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
                   &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span>
                   &nbsp; Khóa học: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? $submission->user->academic_year ?? '___' }}</span></p>
                <p>MSSV: {{ $submission->studentid }} &nbsp;&nbsp; Khoa: {{ $khoaTenUser ?: '___' }}</p>
                <p>Hệ đào tạo: chính quy của Trường Đại học Công nghệ Sài Gòn.</p>
                <div class="d-flex justify-content-between mt-3">
                    <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
                    <div class="text-center">
                        <p class="fw-bold mb-0">{{ strtoupper($submission->form->signtitle ?? 'HIỆU TRƯỞNG') }}</p>
                        <br><br><br>
                        <p>{{ $submission->form->signname ?? 'PGS. TS. Cao Hào Thi' }}</p>
                    </div>
                </div>
            </div>

            @elseif($formId == 2)
            {{-- Form 2: Xác nhận khác --}}
            <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">
                <div class="text-center mb-3">
                    <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
                    <div>———————————</div>
                    <div class="fw-bold mt-2" style="font-size:16px">ĐƠN XIN XÁC NHẬN 2</div>
                    <div>Kính gửi: Phòng Công tác Sinh viên</div>
                </div>
                <p>Tôi tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $d['ho_ten'] ?? $tenSV }}</span></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $dobNgay2 ?: '___' }}</span>
                   tháng <span class="border-bottom px-1">{{ $dobThang2 ?: '___' }}</span>
                   năm <span class="border-bottom px-1">{{ $dobNam2 ?: '___' }}</span>
                   &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
                   &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen ?: '___' }}</span>
                   &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Hộ khẩu thường trú: <span class="border-bottom px-1" style="min-width:300px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span></p>
                <p>Số điện thoại: <span class="border-bottom px-1">{{ $d['sdt'] ?? '___' }}</span></p>
                <p><span>{{ !empty($d['xin_giam_tru']) ? '☑' : '☐' }}</span> Xác nhận giảm trừ gia cảnh</p>
                <p>Xác nhận khác: <span class="border-bottom px-1" style="min-width:280px;display:inline-block">{{ $d['xac_nhan_khac'] ?? '___' }}</span></p>
                <p class="mb-4">Trân trọng kính chào.</p>
                <div class="d-flex justify-content-between">
                    <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
                    <div class="text-center"><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ $tenSV }}</p></div>
                </div>
                <hr>
                <div class="text-center fw-bold mb-2">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>
                <p>Xác nhận sinh viên: {{ $tenSV }}</p>
                <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
                   &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
                   &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span>
                   &nbsp; Khóa học: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? $submission->user->academic_year ?? '___' }}</span></p>
                <p>MSSV: {{ $submission->studentid }} &nbsp;&nbsp; Khoa: {{ $khoaTenUser ?: '___' }}</p>
                <div class="d-flex justify-content-between mt-3">
                    <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
                    <div class="text-center">
                        <p class="fw-bold mb-0">{{ strtoupper($submission->form->signtitle ?? 'HIỆU TRƯỞNG') }}</p>
                        <br><br><br>
                        <p>{{ $submission->form->signname ?? 'PGS. TS. Cao Hào Thi' }}</p>
                    </div>
                </div>
            </div>

            @elseif($formId == 3)
            {{-- Form 3: Vay vốn --}}
            <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">
                <div class="text-center mb-3">
                    <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
                    <div>———————————</div>
                    <div class="fw-bold mt-2" style="font-size:16px">GIẤY XÁC NHẬN</div>
                </div>
                <p>Họ và tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $d['ho_ten'] ?? $tenSV }}</span></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $dobNgay2 ?: '___' }}</span>
                   tháng <span class="border-bottom px-1">{{ $dobThang2 ?: '___' }}</span>
                   năm <span class="border-bottom px-1">{{ $dobNam2 ?: '___' }}</span>
                   &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>CMND/CCCD số: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span>
                   &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ ($d['ngay_cap_cmnd_d']??'__').'/'.($d['ngay_cap_cmnd_m']??'__').'/'.($d['ngay_cap_cmnd_y']??'____') }}</span>
                   &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
                <p>Mã trường: DSG &nbsp;&nbsp; Tên trường: Trường Đại học Công nghệ Sài Gòn</p>
                <p>Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span> &nbsp; Hệ: Đại học &nbsp; Chính quy</p>
                <p>Khóa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $submission->user->academic_year ?? '___' }}</span>
                   &nbsp; Lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
                   &nbsp; Mã SV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Khoa: <span class="border-bottom px-1">{{ $khoaTenUser ?: '___' }}</span></p>
                <p>Ngày nhập học: <span class="border-bottom px-1">{{ ($d['ngay_nhap_hoc_d']??'__').'/'.($d['ngay_nhap_hoc_m']??'__').'/'.($d['ngay_nhap_hoc_y']??'____') }}</span>
                   &nbsp; Năm ra trường dự kiến: <span class="border-bottom px-1">{{ $d['nam_ra_truong'] ?? '___' }}</span></p>
                <p>Học phí hàng tháng: <span class="border-bottom px-1">{{ number_format($d['hoc_phi'] ?? 0) }}</span> đồng.</p>
                @php $dien = $d['thuoc_dien'] ?? ''; @endphp
                <p>Thuộc diện: <span>{{ $dien=='khong_mien_giam'?'☑':'☐' }}</span> Không miễn giảm &nbsp; <span>{{ $dien=='giam_hoc_phi'?'☑':'☐' }}</span> Giảm học phí &nbsp; <span>{{ $dien=='mien_hoc_phi'?'☑':'☐' }}</span> Miễn học phí</p>
                @php $dt = $d['doi_tuong'] ?? 'khong_mo_coi'; @endphp
                <p>Thuộc đối tượng: <span>{{ $dt=='mo_coi'?'☑':'☐' }}</span> Mồ côi &nbsp; <span>{{ $dt=='khong_mo_coi'?'☑':'☐' }}</span> Không mồ côi</p>
                <p>- Số tài khoản nhà trường: 8770199, tại ngân hàng Á Châu (ACB).</p>
                <div class="d-flex justify-content-between mt-3">
                    <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
                    <div class="text-center">
                        <p class="fw-bold mb-0">{{ strtoupper($submission->form->signtitle ?? 'HIỆU TRƯỞNG') }}</p>
                        <br><br><br>
                        <p>{{ $submission->form->signname ?? 'PGS. TS. Cao Hào Thi' }}</p>
                    </div>
                </div>
            </div>

            @elseif($formId == 4)
            {{-- Form 4: Không bị xử phạt hành chính --}}
            <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">
                <div class="row mb-2">
                    <div class="col-6 text-center"><div>TRƯỜNG ĐH CÔNG NGHỆ SÀI GÒN</div><div class="fw-bold">PHÒNG CÔNG TÁC SINH VIÊN</div><div>———————————</div></div>
                    <div class="col-6 text-center"><div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div><div class="fw-bold"><em>Độc lập – Tự do – Hạnh phúc</em></div><div>———————————</div></div>
                </div>
                <div class="text-center my-3">
                    <div class="fw-bold" style="font-size:16px">GIẤY XÁC NHẬN</div>
                    <div><em>Không bị xử phạt hành chính</em></div>
                </div>
                <p>Sinh viên: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['ho_ten'] ?? $tenSV }}</span>
                   &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $dobNgay1 ?: '___' }}</span>
                   tháng <span class="border-bottom px-1">{{ $dobThang1 ?: '___' }}</span>
                   năm <span class="border-bottom px-1">{{ $dobNam1 ?: '___' }}</span></p>
                <p>CMND/CCCD: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span>
                   &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ ($d['ngay_cap_d']??'__').'/'.($d['ngay_cap_m']??'__').'/'.($d['ngay_cap_y']??'____') }}</span>
                   &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
                <p>Hộ khẩu thường trú: <span class="border-bottom px-1" style="min-width:350px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span></p>
                <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
                   &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen ?: '___' }}</span>
                   &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
                   &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
                   &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span></p>
                <p class="mt-3">Từ ngày
                   <span class="border-bottom px-1">{{ ($d['tu_ngay_d']??'__').'/'.($d['tu_ngay_m']??'__').'/'.($d['tu_ngay_y']??'____') }}</span>
                   đến ngày
                   <span class="border-bottom px-1">{{ ($d['den_ngay_d']??'__').'/'.($d['den_ngay_m']??'__').'/'.($d['den_ngay_y']??'____') }}</span>,
                   sinh viên <strong>{{ $tenSV }}</strong>
                   <span class="fw-bold">KHÔNG bị xử phạt hành chính</span>
                   về các hành vi: cờ bạc, nghiện hút, trộm cắp, buôn lậu và các hành vi vi phạm pháp luật khác.</p>
                <p>Mục đích:</p>
                @php $mucDich = $d['muc_dich'] ?? []; @endphp
                <div class="ms-3">
                    <p class="mb-1"><span>{{ in_array('xin_viec',(array)$mucDich)?'☑':'☐' }}</span> Xin việc làm</p>
                    <p class="mb-1"><span>{{ in_array('hoc_bong',(array)$mucDich)?'☑':'☐' }}</span> Xét học bổng</p>
                    <p class="mb-1"><span>{{ in_array('du_hoc',(array)$mucDich)?'☑':'☐' }}</span> Du học / Visa</p>
                    <p class="mb-1"><span>{{ in_array('ho_so_khac',(array)$mucDich)?'☑':'☐' }}</span> Bổ túc hồ sơ khác</p>
                    @if(!empty($d['muc_dich_khac']))<p>Khác: <span class="border-bottom px-1">{{ $d['muc_dich_khac'] }}</span></p>@endif
                </div>
                <p class="fst-italic text-muted mt-3" style="font-size:13px">* Giấy xác nhận có giá trị 30 ngày kể từ ngày cấp.</p>
                <div class="d-flex justify-content-between mt-3">
                    <div><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ $tenSV }}</p></div>
                    <div class="text-center" style="width:40%">
                        <p class="fw-bold mb-0">{{ strtoupper($submission->form->signtitle ?? 'TRƯỞNG PHÒNG CTSV') }}</p>
                        <br><br><br>
                        <p>(Ký, ghi rõ họ tên, đóng dấu)</p>
                        @if($submission->form->signname ?? null)<p>{{ $submission->form->signname }}</p>@endif
                    </div>
                </div>
            </div>

            @elseif($formId == 5)
            {{-- Form 5: Ưu đãi LĐ-TBXH --}}
            <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">
                <div class="row mb-2">
                    <div class="col-6 text-center"><div>TRƯỜNG ĐH CÔNG NGHỆ SÀI GÒN</div><div class="fw-bold">PHÒNG CÔNG TÁC SINH VIÊN</div><div>———————————</div></div>
                    <div class="col-6 text-center"><div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div><div class="fw-bold"><em>Độc lập – Tự do – Hạnh phúc</em></div><div>———————————</div></div>
                </div>
                <div class="text-center my-3">
                    <div class="fw-bold" style="font-size:16px">GIẤY XÁC NHẬN</div>
                    <div><em>Ưu đãi trong giáo dục và đào tạo</em></div>
                    <div>(Dùng cho Phòng Lao động – Thương binh và Xã hội)</div>
                </div>
                <p>Sinh viên: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['ho_ten'] ?? $tenSV }}</span>
                   &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $dobNgay1 ?: '___' }}</span>
                   tháng <span class="border-bottom px-1">{{ $dobThang1 ?: '___' }}</span>
                   năm <span class="border-bottom px-1">{{ $dobNam1 ?: '___' }}</span></p>
                <p>CMND/CCCD: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span>
                   &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ ($d['ngay_cap_d']??'__').'/'.($d['ngay_cap_m']??'__').'/'.($d['ngay_cap_y']??'____') }}</span>
                   &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
                <p>Hộ khẩu thường trú: <span class="border-bottom px-1" style="min-width:350px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span></p>
                <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
                   &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen ?: '___' }}</span>
                   &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
                   &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
                   &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span>
                   &nbsp; Khóa: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? $submission->user->academic_year ?? '___' }}</span></p>
                <p>Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span> &nbsp; Hệ đào tạo: Chính quy</p>
                <p>Thời gian đào tạo: từ tháng <span class="border-bottom px-1">{{ $d['thang_bat_dau'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_bat_dau'] ?? '___' }}</span> đến tháng <span class="border-bottom px-1">{{ $d['thang_ket_thuc'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ket_thuc'] ?? '___' }}</span></p>
                <hr>
                <p class="fw-bold">Đối tượng ưu đãi:</p>
                @php $doiTuong = $d['doi_tuong'] ?? []; @endphp
                <div class="ms-3">
                    <p class="mb-1"><span>{{ in_array('con_liet_si',(array)$doiTuong)?'☑':'☐' }}</span> Con liệt sĩ</p>
                    <p class="mb-1"><span>{{ in_array('con_thuong_binh',(array)$doiTuong)?'☑':'☐' }}</span> Con thương binh / bệnh binh (≥ 61%)</p>
                    <p class="mb-1"><span>{{ in_array('con_anh_hung',(array)$doiTuong)?'☑':'☐' }}</span> Con Anh hùng lực lượng vũ trang / Anh hùng lao động</p>
                    <p class="mb-1"><span>{{ in_array('nguoi_co_cong',(array)$doiTuong)?'☑':'☐' }}</span> Người có công với cách mạng được hưởng trợ cấp hàng tháng</p>
                    <p class="mb-1"><span>{{ in_array('chat_doc',(array)$doiTuong)?'☑':'☐' }}</span> Con của người bị nhiễm chất độc hoá học</p>
                    @if(!empty($d['doi_tuong_khac']))<p>Đối tượng khác: <span class="border-bottom px-1">{{ $d['doi_tuong_khac'] }}</span></p>@endif
                </div>
                <p class="mt-2">Giấy chứng nhận số: <span class="border-bottom px-1">{{ $d['so_gcn'] ?? '___' }}</span>
                   &nbsp; Cấp ngày: <span class="border-bottom px-1">{{ ($d['gcn_ngay']??'__').'/'.($d['gcn_thang']??'__').'/'.($d['gcn_nam']??'____') }}</span></p>
                <p>Do cơ quan: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['co_quan_cap'] ?? '___' }}</span> cấp.</p>
                <p class="fst-italic text-muted" style="font-size:13px">* Giấy xác nhận có giá trị 03 tháng kể từ ngày cấp.</p>
                <div class="d-flex justify-content-between mt-3">
                    <div><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ $tenSV }}</p></div>
                    <div class="text-center" style="width:40%">
                        <p class="fw-bold mb-0">{{ strtoupper($submission->form->signtitle ?? 'TRƯỞNG PHÒNG CTSV') }}</p>
                        <br><br><br>
                        <p>(Ký, ghi rõ họ tên, đóng dấu)</p>
                        @if($submission->form->signname ?? null)<p>{{ $submission->form->signname }}</p>@endif
                    </div>
                </div>
            </div>

            @else
            {{-- Fallback: form động --}}
            <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">
                <div class="row mb-2 text-center">
                    <div class="col-6"><div>TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div><div class="fw-bold">PHÒNG CÔNG TÁC SINH VIÊN</div><div>———————————</div></div>
                    <div class="col-6"><div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div><div><em>Độc lập – Tự do – Hạnh phúc</em></div><div>———————————</div></div>
                </div>
                <div class="text-center my-3">
                    <div class="fw-bold" style="font-size:16px">{{ strtoupper($submission->form->name ?? 'GIẤY XÁC NHẬN') }}</div>
                </div>
                <p>Kính gửi: Ban Giám Hiệu Trường Đại học Công nghệ Sài Gòn</p>
                <p>Tôi tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $tenSV }}</span>
                   &nbsp; MSSV: <span class="border-bottom px-1">{{ $submission->studentid }}</span></p>
                <p>Lớp: <span class="border-bottom px-1">{{ $submission->user->classid ?? '___' }}</span>
                   &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTenUser ?: '___' }}</span></p>
                @if($submission->form && $submission->form->details->isNotEmpty())
                    @foreach($submission->form->details->sortBy('fdetail_order') as $detail)
                        @php $val = $d['field_'.$detail->fdetailid] ?? '___'; @endphp
                        <p>{{ $detail->label }}: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ is_array($val) ? implode(', ', $val) : $val }}</span></p>
                    @endforeach
                @endif
                <p class="mb-4 mt-3">Trân trọng kính chào.</p>
                <div class="d-flex justify-content-between mt-4">
                    <div class="text-center">
                        <p class="fw-bold mb-0">Người làm đơn</p><br><br><br>
                        <p>{{ $tenSV }}</p>
                    </div>
                    <div class="text-center">
                        <p class="mb-0">Tp.HCM, ngày &nbsp;&nbsp;&nbsp; tháng &nbsp;&nbsp;&nbsp; năm {{ date('Y') }}</p>
                        <p class="fw-bold mb-0 mt-2">{{ strtoupper($submission->form->signtitle ?? 'HIỆU TRƯỞNG') }}</p>
                        <br><br><br>
                        <p>{{ $submission->form->signname ?? 'PGS. TS. Cao Hào Thi' }}</p>
                    </div>
                </div>
            </div>
            @endif
            </div>
            {{-- END print-area --}}

            {{-- File đính kèm --}}
            @if($submission->fileDetails->isNotEmpty())
            <div class="card shadow-sm mb-3 mt-3">
                <div class="card-header bg-white fw-bold">📎 File minh chứng</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($submission->fileDetails as $file)
                            @php
                                $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                $url = asset('storage/'.$file->path);
                            @endphp
                            @if(in_array($ext, ['jpg','jpeg','png']))
                                <a href="{{ $url }}" download="{{ $file->original_name }}" title="Tải về">
                                    <img src="{{ $url }}" class="img-thumbnail shadow-sm" style="max-width:150px;max-height:150px;object-fit:cover;cursor:pointer">
                                    <div class="text-center small mt-1 text-muted"><i class="bi bi-download"></i> Tải về</div>
                                </a>
                            @elseif($ext=='pdf')
                                <a href="{{ $url }}" download="{{ $file->original_name }}" class="btn btn-danger btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> {{ $file->original_name }} <i class="bi bi-download ms-1"></i>
                                </a>
                            @else
                                <a href="{{ $url }}" download="{{ $file->original_name }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-file-earmark"></i> {{ $file->original_name }} <i class="bi bi-download ms-1"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
        {{-- END cột trái --}}

        {{-- Cột phải: hành động --}}
        <div class="col-md-4 no-print">
            <div class="card shadow-sm" style="position:sticky;top:20px">
                <div class="card-header bg-white fw-bold">⚡ Hành động</div>
                <div class="card-body">

                    @if($st === 0)
                        <form action="{{ route('xacnhansv.ctsv.admin.requests.approve', $submission->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2"
                                onclick="return confirm('Xác nhận DUYỆT đơn #{{ $submission->id }}?')">
                                <i class="bi bi-check-circle-fill me-1"></i> Duyệt đơn
                            </button>
                        </form>
                        <div class="border rounded p-3" style="background:#fff5f5">
                            <label class="fw-semibold small mb-2 d-block text-danger">❌ Từ chối đơn</label>
                            <form action="{{ route('xacnhansv.ctsv.admin.requests.reject', $submission->id) }}" method="POST">
                                @csrf
                                <textarea name="note" class="form-control form-control-sm mb-2" rows="3" placeholder="Lý do từ chối..."></textarea>
                                <button type="submit" class="btn btn-danger btn-sm w-100"
                                    onclick="return confirm('Xác nhận TỪ CHỐI đơn #{{ $submission->id }}?')">
                                    <i class="bi bi-x-circle-fill me-1"></i> Từ chối
                                </button>
                            </form>
                        </div>

                    @elseif($st === 1)
                        <div class="alert alert-success text-center mb-3">
                            <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                            <strong>Đã duyệt</strong>
                            <div class="small mt-1">Chưa in</div>
                        </div>
                        <form action="{{ route('xacnhansv.ctsv.admin.requests.printed', $submission->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100 text-white"
                                onclick="return confirm('Xác nhận đã in đơn #{{ $submission->id }}?')">
                                <i class="bi bi-printer-fill me-1"></i> Đánh dấu đã in
                            </button>
                        </form>

                    @elseif($st === 2)
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-x-circle-fill"></i> <strong>Đã từ chối</strong>
                            @if($submission->note)<hr class="my-2"><small><strong>Lý do:</strong> {{ $submission->note }}</small>@endif
                        </div>

                    @elseif($st === 3)
                        <div class="alert alert-info text-center mb-0">
                            <i class="bi bi-printer-fill fs-3 d-block mb-2"></i>
                            <strong>🖨️ Đã in</strong>
                            <div class="small mt-1 text-muted">Đơn đã được in và hoàn tất</div>
                        </div>
                    @endif

                    <hr>
                    <div class="text-muted small">
                        <div><strong>ID đơn:</strong> #{{ $submission->id }}</div>
                        <div class="mt-1"><strong>Ngày nộp:</strong><br>
                            {{ $submission->created_at ? $submission->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i — d/m/Y') : '—' }}
                        </div>
                        <div class="mt-1"><strong>Trạng thái:</strong>
                            <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    #print-area { box-shadow: none !important; }
    body { background: white !important; }
    .container { max-width: 100% !important; }
    .col-md-8 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
}
</style>
@endsection