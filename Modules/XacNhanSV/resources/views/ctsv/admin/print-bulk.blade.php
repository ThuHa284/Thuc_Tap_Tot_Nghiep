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

    // ✅ Chữ ký động
    $signTitle = strtoupper($s->form->signtitle ?? 'HIỆU TRƯỞNG');
    $signName  = $s->form->signname ?? 'PGS. TS. Cao Hào Thi';

    // ✅ Fallback ngày sinh từ DB
    $khoaMap = [
        'cntt'=>'Công nghệ Thông tin','ckhi'=>'Cơ khí','cntp'=>'Công nghệ Thực phẩm',
        'ddtu'=>'Điện - Điện tử','dsgn'=>'Thiết kế','kd'=>'Kinh doanh',
        'ktct'=>'Kế toán - Kiểm toán','qtkd'=>'Quản trị Kinh doanh',
    ];
    $khoaTen = $khoaMap[strtolower($s->user->facultyid ?? '')] ?? ($s->user->facultyid ?? '___');

    // Ngày sinh key ngay_sinh/thang_sinh/nam_sinh (form 1,4,5)
    $dobNgay1  = $d['ngay_sinh'] ?? '';
    $dobThang1 = $d['thang_sinh'] ?? '';
    $dobNam1   = $d['nam_sinh'] ?? '';
    // Ngày sinh key ngay/thang/nam (form 2,3)
    $dobNgay2  = $d['ngay'] ?? '';
    $dobThang2 = $d['thang'] ?? '';
    $dobNam2   = $d['nam'] ?? '';

    if ($s->user->birthdate ?? null) {
        $dob = \Carbon\Carbon::parse($s->user->birthdate);
        $dobNgay1  = $dobNgay1  ?: $dob->format('d');
        $dobThang1 = $dobThang1 ?: $dob->format('m');
        $dobNam1   = $dobNam1   ?: $dob->format('Y');
        $dobNgay2  = $dobNgay2  ?: $dob->format('d');
        $dobThang2 = $dobThang2 ?: $dob->format('m');
        $dobNam2   = $dobNam2   ?: $dob->format('Y');
    }

    $tenSV = ($s->user->last_name ?? '') . ' ' . ($s->user->first_name ?? '');
@endphp

<div class="page {{ !$loop->last ? 'page-break' : '' }}">

    @if($formId == 1)
    {{-- Form 1: Hoãn NVQS --}}
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
    <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span>
       &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen }}</span>
       &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
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
       &nbsp; Khóa học: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? $s->user->academic_year ?? '___' }}</span></p>
    <p>MSSV: {{ $s->studentid }} &nbsp;&nbsp; Khoa: {{ $khoaTen }}</p>
    <p>Hệ đào tạo: chính quy của Trường Đại học Công nghệ Sài Gòn.</p>
    <div class="d-flex justify-content-between mt-3">
        <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
        {{-- ✅ Chữ ký động --}}
        <div class="text-center"><p class="fw-bold mb-0">{{ $signTitle }}</p><br><br><br><p>{{ $signName }}</p></div>
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
    <p>Tôi tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $d['ho_ten'] ?? $tenSV }}</span></p>
    <p>Sinh ngày: <span class="border-bottom px-1">{{ $dobNgay2 ?: '___' }}</span>
       tháng <span class="border-bottom px-1">{{ $dobThang2 ?: '___' }}</span>
       năm <span class="border-bottom px-1">{{ $dobNam2 ?: '___' }}</span>
       &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
    <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span>
       &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen }}</span>
       &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
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
       &nbsp; Khóa học: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? $s->user->academic_year ?? '___' }}</span></p>
    <p>MSSV: {{ $s->studentid }} &nbsp;&nbsp; Khoa: {{ $khoaTen }}</p>
    <div class="d-flex justify-content-between mt-3">
        <div>Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</div>
        {{-- ✅ Chữ ký động --}}
        <div class="text-center"><p class="fw-bold mb-0">{{ $signTitle }}</p><br><br><br><p>{{ $signName }}</p></div>
    </div>

    @elseif($formId == 3)
    {{-- Form 3: Vay vốn --}}
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
    <p>Khóa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $s->user->academic_year ?? '___' }}</span>
       &nbsp; Lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span>
       &nbsp; Mã SV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
    <p>Khoa: <span class="border-bottom px-1">{{ $khoaTen }}</span></p>
    <p>Ngày nhập học: <span class="border-bottom px-1">{{ ($d['ngay_nhap_hoc_d']??'__').'/'.($d['ngay_nhap_hoc_m']??'__').'/'.($d['ngay_nhap_hoc_y']??'____') }}</span>
       &nbsp; Năm ra trường dự kiến: <span class="border-bottom px-1">{{ $d['nam_ra_truong'] ?? '___' }}</span></p>
    <p>Học phí hàng tháng: <span class="border-bottom px-1">{{ number_format($d['hoc_phi'] ?? 0) }}</span> đồng.</p>
    @php $dien = $d['thuoc_dien'] ?? ''; @endphp
    <p>Thuộc diện:
       <span>{{ $dien=='khong_mien_giam'?'☑':'☐' }}</span> Không miễn giảm &nbsp;
       <span>{{ $dien=='giam_hoc_phi'?'☑':'☐' }}</span> Giảm học phí &nbsp;
       <span>{{ $dien=='mien_hoc_phi'?'☑':'☐' }}</span> Miễn học phí</p>
    @php $dt = $d['doi_tuong'] ?? 'khong_mo_coi'; @endphp
    <p>Thuộc đối tượng:
       <span>{{ $dt=='mo_coi'?'☑':'☐' }}</span> Mồ côi &nbsp;
       <span>{{ $dt=='khong_mo_coi'?'☑':'☐' }}</span> Không mồ côi</p>
    <p>- Số tài khoản nhà trường: 8770199, tại ngân hàng Á Châu (ACB).</p>
    <div class="d-flex justify-content-between mt-3">
        <div>Tp.HCM, ngày <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span> tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span> năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span></div>
        {{-- ✅ Chữ ký động --}}
        <div class="text-center"><p class="fw-bold mb-0">{{ $signTitle }}</p><br><br><br><p>{{ $signName }}</p></div>
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
    <p>Sinh viên: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">{{ $d['ho_ten'] ?? $tenSV }}</span>
       &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong></p>
    <p>Sinh ngày: <span class="border-bottom px-1">{{ $dobNgay1 ?: '___' }}</span>
       tháng <span class="border-bottom px-1">{{ $dobThang1 ?: '___' }}</span>
       năm <span class="border-bottom px-1">{{ $dobNam1 ?: '___' }}</span></p>
    <p>CMND/CCCD: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span>
       &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ ($d['ngay_cap_d']??'__').'/'.($d['ngay_cap_m']??'__').'/'.($d['ngay_cap_y']??'____') }}</span>
       &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span></p>
    <p>Hộ khẩu thường trú: <span class="border-bottom px-1" style="min-width:350px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span></p>
    <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span>
       &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen }}</span>
       &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
    <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
       &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
       &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span></p>
    {{-- ✅ Sửa tu_ngay/den_ngay thành tu_ngay_d/m/y --}}
    <p class="mt-3">Từ ngày
       <span class="border-bottom px-1">{{ ($d['tu_ngay_d']??'__').'/'.($d['tu_ngay_m']??'__').'/'.($d['tu_ngay_y']??'____') }}</span>
       đến ngày
       <span class="border-bottom px-1">{{ ($d['den_ngay_d']??'__').'/'.($d['den_ngay_m']??'__').'/'.($d['den_ngay_y']??'____') }}</span>,
       sinh viên <strong>{{ $tenSV }}</strong> <span class="fw-bold">KHÔNG bị xử phạt hành chính</span>
       về các hành vi: cờ bạc, nghiện hút, trộm cắp, buôn lậu và các hành vi vi phạm pháp luật khác.</p>
    <p>Mục đích:</p>
    @php $mucDich = $d['muc_dich'] ?? []; @endphp
    <div class="ms-3">
        <p class="mb-1"><span>{{ in_array('xin_viec',(array)$mucDich)?'☑':'☐' }}</span> Xin việc làm</p>
        <p class="mb-1"><span>{{ in_array('hoc_bong',(array)$mucDich)?'☑':'☐' }}</span> Xét học bổng</p>
        <p class="mb-1"><span>{{ in_array('du_hoc',(array)$mucDich)?'☑':'☐' }}</span> Du học / Visa</p>
        <p class="mb-1"><span>{{ in_array('ho_so_khac',(array)$mucDich)?'☑':'☐' }}</span> Bổ túc hồ sơ khác</p>
        @if(!empty($d['muc_dich_khac']))<p>Mục đích khác: <span class="border-bottom px-1">{{ $d['muc_dich_khac'] }}</span></p>@endif
    </div>
    <p class="fst-italic text-muted mt-3" style="font-size:13px">* Giấy xác nhận có giá trị 30 ngày kể từ ngày cấp.</p>
    <div class="d-flex justify-content-between mt-3">
        <div>
            <p class="fw-bold mb-0">Người làm đơn</p><br><br><br>
            <p>{{ $tenSV }}</p>
        </div>
        {{-- ✅ Chữ ký động --}}
        <div class="text-center">
            <p class="fw-bold mb-0">{{ $signTitle }}</p><br><br><br>
            <p>(Ký, ghi rõ họ tên, đóng dấu)</p>
            @if($s->form->signname ?? null)<p>{{ $s->form->signname }}</p>@endif
        </div>
    </div>

    @elseif($formId == 5)
    {{-- Form 5: Ưu đãi LĐ-TBXH --}}
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
    <p>Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $s->user->classid ?? '___' }}</span>
       &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen }}</span>
       &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $s->studentid }}</span></p>
    <p>Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
       &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
       &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span>
       &nbsp; Khóa: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? $s->user->academic_year ?? '___' }}</span></p>
    <p>Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span> &nbsp; Hệ đào tạo: Chính quy</p>
    <p>Thời gian đào tạo: từ tháng
       <span class="border-bottom px-1">{{ $d['thang_bat_dau'] ?? '___' }}</span> năm
       <span class="border-bottom px-1">{{ $d['nam_bat_dau'] ?? '___' }}</span>
       đến tháng <span class="border-bottom px-1">{{ $d['thang_ket_thuc'] ?? '___' }}</span> năm
       <span class="border-bottom px-1">{{ $d['nam_ket_thuc'] ?? '___' }}</span></p>
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
        <div>
            <p class="fw-bold mb-0">Người làm đơn</p><br><br><br>
            <p>{{ $tenSV }}</p>
        </div>
        {{-- ✅ Chữ ký động --}}
        <div class="text-center">
            <p class="fw-bold mb-0">{{ $signTitle }}</p><br><br><br>
            <p>(Ký, ghi rõ họ tên, đóng dấu)</p>
            @if($s->form->signname ?? null)<p>{{ $s->form->signname }}</p>@endif
        </div>
    </div>

    @else
    {{-- Fallback: form động --}}
    <div class="row mb-2 text-center">
        <div class="col-6"><div>TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div><div class="fw-bold">PHÒNG CÔNG TÁC SINH VIÊN</div><div>———————————</div></div>
        <div class="col-6"><div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div><div><em>Độc lập – Tự do – Hạnh phúc</em></div><div>———————————</div></div>
    </div>
    <div class="text-center my-3">
        <div class="fw-bold" style="font-size:16px">{{ strtoupper($s->form->name ?? 'GIẤY XÁC NHẬN') }}</div>
    </div>
    <p>Kính gửi: Ban Giám Hiệu Trường Đại học Công nghệ Sài Gòn</p>
    <p>Tôi tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">{{ $tenSV }}</span>
       &nbsp; MSSV: <span class="border-bottom px-1">{{ $s->studentid }}</span></p>
    <p>Lớp: <span class="border-bottom px-1">{{ $s->user->classid ?? '___' }}</span>
       &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen }}</span></p>
    @if($s->form && $s->form->details->isNotEmpty())
        @foreach($s->form->details->sortBy('fdetail_order') as $detail)
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
            <p class="mb-0">Tp.HCM, ngày &nbsp;&nbsp; tháng &nbsp;&nbsp; năm {{ date('Y') }}</p>
            {{-- ✅ Chữ ký động --}}
            <p class="fw-bold mb-0 mt-2">{{ $signTitle }}</p><br><br><br>
            <p>{{ $signName }}</p>
        </div>
    </div>
    @endif

</div>
@endforeach

</body>
</html>