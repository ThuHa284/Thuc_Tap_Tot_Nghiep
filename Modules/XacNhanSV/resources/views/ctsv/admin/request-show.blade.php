@extends('layouts.master')
@section('title', 'Chi tiết đơn #' . $submission->id)

@section('content')
@php
    $d  = $submission->data ?? [];
    $st = (int) $submission->status;
    $alertType   = match($st){ 0=>'warning', 1=>'success', 2=>'danger', default=>'secondary' };
    $statusLabel = match($st){ 0=>'⏳ Chờ duyệt', 1=>'✅ Đã duyệt', 2=>'❌ Từ chối', default=>'?' };
    $badgeClass  = match($st){ 0=>'warning text-dark', 1=>'success', 2=>'danger', default=>'secondary' };
    $formId      = $submission->form->formid ?? 0;
@endphp

<div class="container py-4" style="max-width:1000px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">📄 Chi tiết đơn #{{ $submission->id }}</h4>
            <p class="text-muted mb-0 small">{{ $submission->form->name ?? '—' }}</p>
        </div>
        <div class="d-flex gap-2">
            
            <a href="{{ route('xacnhansv.ctsv.admin.requests') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-{{ $alertType }} d-flex align-items-center gap-2 mb-4">
        <span class="fw-bold fs-6">{{ $statusLabel }}</span>
        @if($st===0) <span class="small">— Đơn đang chờ xét duyệt</span>
        @elseif($st===1) <span class="small">— Đơn đã được duyệt</span>
        @elseif($st===2) <span class="small">— Đơn đã bị từ chối</span>
        @endif
    </div>

    <div class="row g-4">

        {{-- Cột trái: nội dung đơn --}}
        <div class="col-md-8">

            {{-- Thông tin sinh viên --}}
            <div class="card shadow-sm mb-3 no-print">
                <div class="card-header bg-white fw-bold">👤 Thông tin sinh viên</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Họ tên</label>
                            <span class="fw-semibold">{{ $submission->user ? $submission->user->first_name.' '.$submission->user->last_name : '—' }}</span>
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
                            <span>{{ $submission->user->facultyid ?? '—' }}</span>
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
                                    @case('buu_dien')  📮 Nhận qua Bưu điện @break
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
                    <div class="fw-bold mt-2" style="font-size:16px">ĐƠN XIN XÁC NHẬN 1</div>
                </div>
                <p>Kính gửi: Ban Giám Hiệu Trường Đại học Công nghệ Sài Gòn</p>
                <p>Tôi tên: <span class="border-bottom px-1">{{ $d['ho_ten'] ?? ($submission->user->first_name??'').' '.($submission->user->last_name??'') }}</span>
                   &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay_sinh'] ?? '___' }}</span>
                   tháng <span class="border-bottom px-1">{{ $d['thang_sinh'] ?? '___' }}</span>
                   năm <span class="border-bottom px-1">{{ $d['nam_sinh'] ?? '___' }}</span></p>
                <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
                   &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $submission->user->facultyid ?? '___' }}</span>
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
                    <div class="text-center"><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ $submission->user->first_name??'' }} {{ $submission->user->last_name??'' }}</p></div>
                </div>
                <hr>
                <div class="text-center fw-bold mb-2">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>
                <p>Xác nhận sinh viên: {{ $submission->user->first_name??'' }} {{ $submission->user->last_name??'' }}</p>
                <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span> &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span> &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span></p>
                <p>MSSV: {{ $submission->studentid }} &nbsp;&nbsp; Khoa: {{ $submission->user->facultyid ?? '___' }}</p>
                <div class="d-flex justify-content-between mt-3">
                    <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
                    <div class="text-center"><p class="fw-bold mb-0">HIỆU TRƯỞNG</p><br><br><br><p>PGS. TS. Cao Hào Thi</p></div>
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
                <p>Tôi tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $d['ho_ten'] ?? ($submission->user->first_name??'').' '.($submission->user->last_name??'') }}</span></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam'] ?? '___' }}</span> &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span> &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $submission->user->facultyid ?? '___' }}</span> &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Hộ khẩu thường trú: <span class="border-bottom px-1" style="min-width:300px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span></p>
                <p>Số điện thoại: <span class="border-bottom px-1">{{ $d['sdt'] ?? '___' }}</span></p>
                <p><span>{{ !empty($d['xin_giam_tru']) ? '☑' : '☐' }}</span> Xác nhận giảm trừ gia cảnh</p>
                <p>Xác nhận khác: <span class="border-bottom px-1" style="min-width:280px;display:inline-block">{{ $d['xac_nhan_khac'] ?? '___' }}</span></p>
                <p class="mb-4">Trân trọng kính chào.</p>
                <div class="d-flex justify-content-between">
                    <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
                    <div class="text-center"><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ $submission->user->first_name??'' }} {{ $submission->user->last_name??'' }}</p></div>
                </div>
                <hr>
                <div class="text-center fw-bold mb-2">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>
                <p>Xác nhận sinh viên: {{ $submission->user->first_name??'' }} {{ $submission->user->last_name??'' }}</p>
                <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span> &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span> &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span></p>
                <p>MSSV: {{ $submission->studentid }} &nbsp;&nbsp; Khoa: {{ $submission->user->facultyid ?? '___' }}</p>
                <div class="d-flex justify-content-between mt-3">
                    <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
                    <div class="text-center"><p class="fw-bold mb-0">HIỆU TRƯỞNG</p><br><br><br><p>PGS. TS. Cao Hào Thi</p></div>
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
                <p>Họ và tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $d['ho_ten'] ?? ($submission->user->first_name??'').' '.($submission->user->last_name??'') }}</span></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam'] ?? '___' }}</span> &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>CMND số: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span> ngày cấp: <span class="border-bottom px-1">{{ $d['ngay_cap_cmnd'] ?? '___' }}</span> Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
                <p>Tên trường: Trường Đại học Công nghệ Sài Gòn</p>
                <p>Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span></p>
                <p>Hệ đào tạo: <span class="border-bottom px-1">{{ $d['he_dao_tao'] ?? 'Đại học' }}</span> &nbsp; Khóa: <span class="border-bottom px-1">{{ $d['khoa'] ?? '___' }}</span> &nbsp; Loại hình: Chính quy</p>
                <p>Lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span> &nbsp; Mã SV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Khoa: <span class="border-bottom px-1">{{ $d['khoa_sv'] ?? $submission->user->facultyid ?? '___' }}</span></p>
                <p>Ngày nhập học: <span class="border-bottom px-1">{{ $d['ngay_nhap_hoc'] ?? '___' }}</span> &nbsp; Dự kiến ra trường năm: <span class="border-bottom px-1">{{ $d['nam_ra_truong'] ?? '___' }}</span></p>
                <p>Học phí hàng tháng: <span class="border-bottom px-1">{{ $d['hoc_phi'] ?? '___' }}</span> đồng.</p>
                @php $dien = $d['thuoc_dien'] ?? ''; @endphp
                <p>Thuộc diện: <span>{{ $dien=='khong_mien_giam'?'☑':'☐' }}</span> Không miễn giảm &nbsp; <span>{{ $dien=='giam_hoc_phi'?'☑':'☐' }}</span> Giảm học phí &nbsp; <span>{{ $dien=='mien_hoc_phi'?'☑':'☐' }}</span> Miễn học phí</p>
                @php $dt = $d['doi_tuong'] ?? 'khong_mo_coi'; @endphp
                <p>Đối tượng: <span>{{ $dt=='mo_coi'?'☑':'☐' }}</span> Mồ côi &nbsp; <span>{{ $dt=='khong_mo_coi'?'☑':'☐' }}</span> Không mồ côi</p>
                <p>- Trong thời gian học tại trường, {{ $submission->user->first_name??'' }} {{ $submission->user->last_name??'' }} không bị xử phạt hành chính về các hành vi: cờ bạc, nghiện hút, trộm cắp, buôn lậu.</p>
                <p>- Số tài khoản nhà trường: 8770199, tại ngân hàng Á Châu (ACB).</p>
                <div class="d-flex justify-content-between mt-3">
                    <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
                    <div class="text-center"><p class="fw-bold mb-0">Hiệu trưởng</p><br><br><br><p>PGS. TS. Cao Hào Thi</p></div>
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
                <p class="fw-bold">PHÒNG CTSV TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN XÁC NHẬN:</p>
                <p>Sinh viên: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['ho_ten'] ?? ($submission->user->first_name??'').' '.($submission->user->last_name??'') }}</span> &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay_sinh'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_sinh'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_sinh'] ?? '___' }}</span></p>
                <p>CMND/CCCD: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span> &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ $d['ngay_cap_cmnd'] ?? '___' }}</span> &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
                <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span> &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $submission->user->facultyid ?? '___' }}</span> &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span> &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span> &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span></p>
                <p class="mt-3">Trong thời gian theo học từ ngày <span class="border-bottom px-1">{{ $d['tu_ngay'] ?? '___' }}</span> đến ngày <span class="border-bottom px-1">{{ $d['den_ngay'] ?? '___' }}</span>, sinh viên <strong>{{ ($submission->user->first_name??'').' '.($submission->user->last_name??'') }}</strong> <span class="fw-bold">KHÔNG bị xử phạt hành chính</span> về các hành vi: cờ bạc, nghiện hút, trộm cắp, buôn lậu và các vi phạm pháp luật khác.</p>
                <p>Giấy xác nhận này được cấp để:</p>
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
                    <div><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ $submission->user->first_name??'' }} {{ $submission->user->last_name??'' }}</p></div>
                    <div class="text-center"><p class="fw-bold mb-0">TRƯỞNG PHÒNG CTSV</p><br><br><br><p>(Ký, ghi rõ họ tên, đóng dấu)</p></div>
                </div>
            </div>

            @elseif($formId == 5)
            {{-- Form 5: LĐ-TBXH --}}
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
                <p class="fw-bold">TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN XÁC NHẬN:</p>
                <p>Sinh viên: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['ho_ten'] ?? ($submission->user->first_name??'').' '.($submission->user->last_name??'') }}</span> &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
                <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay_sinh'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_sinh'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_sinh'] ?? '___' }}</span></p>
                <p>CMND/CCCD: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span> &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ $d['ngay_cap_cmnd'] ?? '___' }}</span> &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
                <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span> &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $submission->user->facultyid ?? '___' }}</span> &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span></p>
                <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span> &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span> &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span> &nbsp; Khóa: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? '___' }}</span></p>
                <p>Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span> &nbsp; Hệ đào tạo: Chính quy</p>
                <p>Thời gian đào tạo: từ tháng <span class="border-bottom px-1">{{ $d['thang_bat_dau'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_bat_dau'] ?? '___' }}</span> đến tháng <span class="border-bottom px-1">{{ $d['thang_ket_thuc'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ket_thuc'] ?? '___' }}</span></p>
                <hr>
                <p class="fw-bold">Đối tượng ưu đãi:</p>
                @php $doiTuong = $d['doi_tuong'] ?? []; @endphp
                <div class="ms-3">
                    <p class="mb-1"><span>{{ in_array('con_liet_si',(array)$doiTuong)?'☑':'☐' }}</span> Con liệt sĩ</p>
                    <p class="mb-1"><span>{{ in_array('con_thuong_binh',(array)$doiTuong)?'☑':'☐' }}</span> Con thương binh / bệnh binh (từ 61% trở lên)</p>
                    <p class="mb-1"><span>{{ in_array('con_anh_hung',(array)$doiTuong)?'☑':'☐' }}</span> Con Anh hùng lực lượng vũ trang / Anh hùng lao động</p>
                    <p class="mb-1"><span>{{ in_array('nguoi_co_cong',(array)$doiTuong)?'☑':'☐' }}</span> Người có công với cách mạng</p>
                    <p class="mb-1"><span>{{ in_array('chat_doc',(array)$doiTuong)?'☑':'☐' }}</span> Con người bị nhiễm chất độc hoá học</p>
                    @if(!empty($d['doi_tuong_khac']))<p>Khác: <span class="border-bottom px-1">{{ $d['doi_tuong_khac'] }}</span></p>@endif
                </div>
                <p class="mt-2">GCN người có công số: <span class="border-bottom px-1">{{ $d['so_gcn'] ?? '___' }}</span> &nbsp; Cấp ngày: <span class="border-bottom px-1">{{ $d['ngay_cap_gcn'] ?? '___' }}</span></p>
                <p>Do cơ quan: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['co_quan_cap'] ?? '___' }}</span> cấp.</p>
                <p class="mt-3">Giấy xác nhận này được cấp để sinh viên làm thủ tục hưởng chế độ <strong>ưu đãi trong giáo dục và đào tạo</strong> tại Phòng Lao động – Thương binh và Xã hội.</p>
                <p class="fst-italic text-muted" style="font-size:13px">* Giấy xác nhận có giá trị 03 tháng kể từ ngày cấp.</p>
                <div class="d-flex justify-content-between mt-3">
                    <div><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ $submission->user->first_name??'' }} {{ $submission->user->last_name??'' }}</p></div>
                    <div class="text-center"><p class="fw-bold mb-0">TRƯỞNG PHÒNG CTSV</p><br><br><br><p>(Ký, ghi rõ họ tên, đóng dấu)</p></div>
                </div>
            </div>

            @else
            {{-- Fallback: hiển thị dạng bảng nếu không có mẫu --}}
            @if(!empty($submission->data))
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-bold">📝 Nội dung đơn</div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($submission->data as $label => $value)
                            @if($value)
                            <div class="col-md-6">
                                <label class="text-muted small d-block">{{ $label }}</label>
                                <span class="fw-semibold">
                                    @if(is_array($value)) {{ implode(', ', $value) }}
                                    @else {{ $value }} @endif
                                </span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
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
                                    <img src="{{ $url }}" class="img-thumbnail shadow-sm"
                                         style="max-width:150px;max-height:150px;object-fit:cover;cursor:pointer">
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

        {{-- Cột phải: hành động --}}
        <div class="col-md-4 no-print">
            <div class="card shadow-sm" style="position:sticky;top:20px">
                <div class="card-header bg-white fw-bold">⚡ Hành động</div>
                <div class="card-body">
                    {{-- Nút in --}}
                    

                    @if($st === 0)
                        <form action="{{ route('xacnhansv.ctsv.admin.requests.approve', $submission->id) }}"
                              method="POST" class="mb-3">
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
                        <div class="alert alert-success mb-0 text-center">
                            <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                            <strong>Đã duyệt</strong>
                        </div>
                    @elseif($st === 2)
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-x-circle-fill"></i> <strong>Đã từ chối</strong>
                            @if($submission->note)
                                <hr class="my-2">
                                <small><strong>Lý do:</strong> {{ $submission->note }}</small>
                            @endif
                        </div>
                    @endif

                    <hr>
                    <div class="text-muted small">
                        <div><strong>ID đơn:</strong> #{{ $submission->id }}</div>
                        <div><strong>Ngày nộp:</strong><br>{{ $submission->created_at ? $submission->created_at->format('H:i — d/m/Y') : '—' }}</div>
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

<script>
function printForm() {
    window.print();
}
</script>

@endsection