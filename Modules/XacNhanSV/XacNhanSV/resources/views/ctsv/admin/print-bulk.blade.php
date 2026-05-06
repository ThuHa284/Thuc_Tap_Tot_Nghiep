<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>In đơn hàng loạt</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 14px; background: #f0f0f0; }
        .page { width: 19cm; margin: 0 auto 30px; padding: 1.5cm; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.15); }
        .page-break { page-break-after: always; }
        .no-print { background: #343a40; color: #fff; padding: 14px 24px; text-align: center; position: sticky; top: 0; z-index: 999; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .page { box-shadow: none; margin: 0; width: 100%; padding: 1cm; }
        }
    </style>
</head>
<body>

{{-- Toolbar --}}
<div class="no-print d-flex align-items-center justify-content-center gap-3">
    <span>📋 Tổng: <strong>{{ $submissions->count() }}</strong> đơn</span>
    <button onclick="window.print()"
            style="padding:8px 28px;font-size:15px;cursor:pointer;background:#0d6efd;color:white;border:none;border-radius:6px">
        🖨️ In tất cả
    </button>
    <button onclick="window.close()"
            style="padding:8px 20px;cursor:pointer;background:#6c757d;color:white;border:none;border-radius:6px">
        ✖ Đóng
    </button>
</div>

@foreach($submissions as $s)
@php
    $d      = $s->data ?? [];
    $formId = $s->form->formid ?? 0;
@endphp

<div class="page {{ !$loop->last ? 'page-break' : '' }}">

    @if($formId == 1)
    {{-- Form 1: Hoãn NVQS --}}
    <div class="text-center mb-3">
        <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
        <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
        <div>———————————</div>
        <div class="fw-bold mt-2" style="font-size:16px">ĐƠN XIN XÁC NHẬN 1</div>
    </div>
    <p>Kính gửi: Ban Giám Hiệu Trường Đại học Công nghệ Sài Gòn</p>
    <p>Tôi tên: <span class="border-bottom px-1">{{ $d['ho_ten'] ?? ($s->user->first_name??'').' '.($s->user->last_name??'') }}</span>
       &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
    <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay_sinh'] ?? '___' }}</span>
       tháng <span class="border-bottom px-1">{{ $d['thang_sinh'] ?? '___' }}</span>
       năm <span class="border-bottom px-1">{{ $d['nam_sinh'] ?? '___' }}</span></p>
    <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span>
       &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $s->user->facultyid ?? '___' }}</span>
       &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
    <p>Hộ khẩu thường trú: <span class="border-bottom px-1" style="min-width:300px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span></p>
    <p>Bậc đào tạo: <span class="border-bottom px-1">Đại học</span> &nbsp; Hệ đào tạo: <span class="border-bottom px-1">Chính quy</span></p>
    <p>Số điện thoại: <span class="border-bottom px-1">{{ $d['sdt'] ?? '___' }}</span></p>
    <p>Nay tôi làm đơn xin nhà trường cấp giấy chứng nhận để bổ túc hồ sơ xin:</p>
    <p><span>{{ !empty($d['xin_hoan_nvqs']) ? '☑' : '☐' }}</span> Xin hoãn nghĩa vụ quân sự</p>
    <p>Lý do khác: <span class="border-bottom px-1" style="min-width:300px;display:inline-block">{{ $d['ly_do_khac'] ?? '___' }}</span></p>
    <p class="mb-4">Trân trọng kính chào.</p>
    <div class="d-flex justify-content-between">
        <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
        <div class="text-center"><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ ($s->user->first_name??'').' '.($s->user->last_name??'') }}</p></div>
    </div>
    <hr>
    <div class="text-center fw-bold mb-2">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>
    <p>Xác nhận sinh viên: {{ ($s->user->first_name??'').' '.($s->user->last_name??'') }}</p>
    <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span> &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span> &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span></p>
    <p>MSSV: {{ $s->studentid }} &nbsp;&nbsp; Khoa: {{ $s->user->facultyid ?? '___' }}</p>
    <div class="d-flex justify-content-between mt-3">
        <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
        <div class="text-center"><p class="fw-bold mb-0">HIỆU TRƯỞNG</p><br><br><br><p>PGS. TS. Cao Hào Thi</p></div>
    </div>

    @elseif($formId == 2)
    {{-- Form 2: Xác nhận khác --}}
    <div class="text-center mb-3">
        <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
        <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
        <div>———————————</div>
        <div class="fw-bold mt-2" style="font-size:16px">ĐƠN XIN XÁC NHẬN 2</div>
        <div>Kính gửi: Phòng Công tác Sinh viên</div>
    </div>
    <p>Tôi tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $d['ho_ten'] ?? ($s->user->first_name??'').' '.($s->user->last_name??'') }}</span></p>
    <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam'] ?? '___' }}</span> &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
    <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span> &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $s->user->facultyid ?? '___' }}</span> &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
    <p>Hộ khẩu thường trú: <span class="border-bottom px-1" style="min-width:300px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span></p>
    <p>Số điện thoại: <span class="border-bottom px-1">{{ $d['sdt'] ?? '___' }}</span></p>
    <p><span>{{ !empty($d['xin_giam_tru']) ? '☑' : '☐' }}</span> Xác nhận giảm trừ gia cảnh</p>
    <p>Xác nhận khác: <span class="border-bottom px-1" style="min-width:280px;display:inline-block">{{ $d['xac_nhan_khac'] ?? '___' }}</span></p>
    <p class="mb-4">Trân trọng kính chào.</p>
    <div class="d-flex justify-content-between">
        <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
        <div class="text-center"><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ ($s->user->first_name??'').' '.($s->user->last_name??'') }}</p></div>
    </div>
    <hr>
    <div class="text-center fw-bold mb-2">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>
    <p>Xác nhận sinh viên: {{ ($s->user->first_name??'').' '.($s->user->last_name??'') }}</p>
    <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span> &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span> &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span></p>
    <p>MSSV: {{ $s->studentid }} &nbsp;&nbsp; Khoa: {{ $s->user->facultyid ?? '___' }}</p>
    <div class="d-flex justify-content-between mt-3">
        <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
        <div class="text-center"><p class="fw-bold mb-0">HIỆU TRƯỞNG</p><br><br><br><p>PGS. TS. Cao Hào Thi</p></div>
    </div>

    @elseif($formId == 3)
    {{-- Form 3: Vay vốn --}}
    <div class="text-center mb-3">
        <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
        <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
        <div>———————————</div>
        <div class="fw-bold mt-2" style="font-size:16px">GIẤY XÁC NHẬN</div>
    </div>
    <p>Họ và tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $d['ho_ten'] ?? ($s->user->first_name??'').' '.($s->user->last_name??'') }}</span></p>
    <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam'] ?? '___' }}</span> &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
    <p>CMND số: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span> ngày cấp: <span class="border-bottom px-1">{{ $d['ngay_cap_cmnd'] ?? '___' }}</span> Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
    <p>Tên trường: Trường Đại học Công nghệ Sài Gòn</p>
    <p>Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span></p>
    <p>Hệ đào tạo: <span class="border-bottom px-1">{{ $d['he_dao_tao'] ?? 'Đại học' }}</span> &nbsp; Khóa: <span class="border-bottom px-1">{{ $d['khoa'] ?? '___' }}</span> &nbsp; Loại hình: Chính quy</p>
    <p>Lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span> &nbsp; Mã SV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
    <p>Khoa: <span class="border-bottom px-1">{{ $d['khoa_sv'] ?? $s->user->facultyid ?? '___' }}</span></p>
    <p>Ngày nhập học: <span class="border-bottom px-1">{{ $d['ngay_nhap_hoc'] ?? '___' }}</span> &nbsp; Dự kiến ra trường năm: <span class="border-bottom px-1">{{ $d['nam_ra_truong'] ?? '___' }}</span></p>
    <p>Học phí hàng tháng: <span class="border-bottom px-1">{{ $d['hoc_phi'] ?? '___' }}</span> đồng.</p>
    @php $dien = $d['thuoc_dien'] ?? ''; @endphp
    <p>Thuộc diện: <span>{{ $dien=='khong_mien_giam'?'☑':'☐' }}</span> Không miễn giảm &nbsp; <span>{{ $dien=='giam_hoc_phi'?'☑':'☐' }}</span> Giảm học phí &nbsp; <span>{{ $dien=='mien_hoc_phi'?'☑':'☐' }}</span> Miễn học phí</p>
    @php $dt = $d['doi_tuong'] ?? 'khong_mo_coi'; @endphp
    <p>Đối tượng: <span>{{ $dt=='mo_coi'?'☑':'☐' }}</span> Mồ côi &nbsp; <span>{{ $dt=='khong_mo_coi'?'☑':'☐' }}</span> Không mồ côi</p>
    <p>- Trong thời gian học tại trường, {{ ($s->user->first_name??'').' '.($s->user->last_name??'') }} không bị xử phạt hành chính về các hành vi: cờ bạc, nghiện hút, trộm cắp, buôn lậu.</p>
    <p>- Số tài khoản nhà trường: 8770199, tại ngân hàng Á Châu (ACB).</p>
    <div class="d-flex justify-content-between mt-3">
        <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
        <div class="text-center"><p class="fw-bold mb-0">Hiệu trưởng</p><br><br><br><p>PGS. TS. Cao Hào Thi</p></div>
    </div>

    @elseif($formId == 4)
    {{-- Form 4: Không bị xử phạt hành chính --}}
    <div class="row mb-2">
        <div class="col-6 text-center"><div>TRƯỜNG ĐH CÔNG NGHỆ SÀI GÒN</div><div class="fw-bold">PHÒNG CÔNG TÁC SINH VIÊN</div><div>———————————</div></div>
        <div class="col-6 text-center"><div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div><div class="fw-bold"><em>Độc lập – Tự do – Hạnh phúc</em></div><div>———————————</div></div>
    </div>
    <div class="text-center my-3">
        <div class="fw-bold" style="font-size:16px">GIẤY XÁC NHẬN</div>
        <div><em>Không bị xử phạt hành chính</em></div>
    </div>
    <p class="fw-bold">PHÒNG CTSV TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN XÁC NHẬN:</p>
    <p>Sinh viên: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['ho_ten'] ?? ($s->user->first_name??'').' '.($s->user->last_name??'') }}</span> &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
    <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay_sinh'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_sinh'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_sinh'] ?? '___' }}</span></p>
    <p>CMND/CCCD: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span> &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ $d['ngay_cap_cmnd'] ?? '___' }}</span> &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
    <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span> &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $s->user->facultyid ?? '___' }}</span> &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
    <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span> &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span> &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span></p>
    <p class="mt-3">Trong thời gian theo học từ ngày <span class="border-bottom px-1">{{ $d['tu_ngay'] ?? '___' }}</span> đến ngày <span class="border-bottom px-1">{{ $d['den_ngay'] ?? '___' }}</span>, sinh viên <strong>{{ ($s->user->first_name??'').' '.($s->user->last_name??'') }}</strong> <span class="fw-bold">KHÔNG bị xử phạt hành chính</span> về các hành vi: cờ bạc, nghiện hút, trộm cắp, buôn lậu và các vi phạm pháp luật khác.</p>
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
        <div><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ ($s->user->first_name??'').' '.($s->user->last_name??'') }}</p></div>
        <div class="text-center"><p class="fw-bold mb-0">TRƯỞNG PHÒNG CTSV</p><br><br><br><p>(Ký, ghi rõ họ tên, đóng dấu)</p></div>
    </div>

    @elseif($formId == 5)
    {{-- Form 5: LĐ-TBXH --}}
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
    <p>Sinh viên: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['ho_ten'] ?? ($s->user->first_name??'').' '.($s->user->last_name??'') }}</span> &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
    <p>Sinh ngày: <span class="border-bottom px-1">{{ $d['ngay_sinh'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_sinh'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_sinh'] ?? '___' }}</span></p>
    <p>CMND/CCCD: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span> &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ $d['ngay_cap_cmnd'] ?? '___' }}</span> &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
    <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span> &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $s->user->facultyid ?? '___' }}</span> &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
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
        <div><p class="fw-bold mb-0">Người làm đơn</p><br><br><br><p>{{ ($s->user->first_name??'').' '.($s->user->last_name??'') }}</p></div>
        <div class="text-center"><p class="fw-bold mb-0">TRƯỞNG PHÒNG CTSV</p><br><br><br><p>(Ký, ghi rõ họ tên, đóng dấu)</p></div>
    </div>

    @else
    {{-- Fallback --}}
    <div class="text-center mb-3 fw-bold" style="font-size:16px">{{ $s->form->name ?? 'ĐƠN XIN GIẤY TỜ' }}</div>
    <p>Sinh viên: <strong>{{ ($s->user->first_name??'').' '.($s->user->last_name??'') }}</strong> &nbsp; MSSV: {{ $s->studentid }}</p>
    <p>Lớp: {{ $s->user->classid ?? '—' }} &nbsp; Khoa: {{ $s->user->facultyid ?? '—' }}</p>
    @if(!empty($s->data))
        @foreach($s->data as $label => $value)
            @if($value)<p><strong>{{ $label }}:</strong> {{ is_array($value) ? implode(', ', $value) : $value }}</p>@endif
        @endforeach
    @endif
    <div class="d-flex justify-content-between mt-5">
        <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
        <div class="text-center"><p class="fw-bold mb-0">TRƯỞNG PHÒNG CTSV</p><br><br><br><p>(Ký, ghi rõ họ tên, đóng dấu)</p></div>
    </div>
    @endif

</div>
@endforeach

</body>
</html>