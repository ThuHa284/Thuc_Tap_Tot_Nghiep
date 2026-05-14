@extends('layouts.master')
@section('title', 'Chi tiết đơn #' . $submission->id)

@section('content')
@php
    $d = $submission->data ?? [];
    $st = (int) $submission->status;
    $alertType   = match($st) { 0=>'warning', 1=>'success', 2=>'danger', default=>'secondary' };
    $statusLabel = match($st) { 0=>'⏳ Chờ duyệt', 1=>'✅ Đã duyệt', 2=>'❌ Từ chối', default=>'?' };
    $khoaMap = [
        'cntt'=>'Công nghệ Thông tin','ckhi'=>'Cơ khí','cntp'=>'Công nghệ Thực phẩm',
        'ddtu'=>'Điện - Điện tử','dsgn'=>'Thiết kế','kd'=>'Kinh doanh',
        'ktct'=>'Kế toán - Kiểm toán','qtkd'=>'Quản trị Kinh doanh',
    ];
    $rawKhoa = $d['khoa'] ?? $submission->user->facultyid ?? '';
    $khoaTen = $khoaMap[strtolower($rawKhoa)] ?? $rawKhoa;
@endphp

<div class="container py-4" style="max-width:860px">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại lịch sử
        </a>
        <span class="badge bg-{{ $alertType }} fs-6 px-3 py-2">{{ $statusLabel }}</span>
    </div>

    <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">

        <div class="row mb-2">
            <div class="col-6 text-center">
                <div>TRƯỜNG ĐH CÔNG NGHỆ SÀI GÒN</div>
                <div class="fw-bold">PHÒNG CÔNG TÁC SINH VIÊN</div>
                <div>———————————</div>
            </div>
            <div class="col-6 text-center">
                <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                <div class="fw-bold"><em>Độc lập – Tự do – Hạnh phúc</em></div>
                <div>———————————</div>
            </div>
        </div>

        <div class="text-center my-3">
            <div class="fw-bold" style="font-size:16px">GIẤY XÁC NHẬN</div>
            <div><em>Ưu đãi trong giáo dục và đào tạo</em></div>
            <div class="mt-1">(Dùng cho Phòng Lao động – Thương binh và Xã hội)</div>
        </div>

        <p class="mb-1">
            Sinh viên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">
                {{ $d['ho_ten'] ?? ($submission->user->last_name.' '.$submission->user->first_name) }}
            </span>
            &nbsp;&nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong>
        </p>

        <p class="mb-1">
            Sinh ngày:
            <span class="border-bottom px-1">{{ $d['ngay_sinh'] ?? '___' }}</span>
            tháng <span class="border-bottom px-1">{{ $d['thang_sinh'] ?? '___' }}</span>
            năm <span class="border-bottom px-1">{{ $d['nam_sinh'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            CMND/CCCD số: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span>
            &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ ($d['ngay_cap_d']??'__').'/'.($d['ngay_cap_m']??'__').'/'.($d['ngay_cap_y']??'____') }}</span>
            &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            Hộ khẩu thường trú:
            <span class="border-bottom px-1" style="min-width:350px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
            &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen ?: '___' }}</span>
            &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span>
        </p>

        <p class="mb-1">
            Năm thứ: <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
            &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
            &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span>
            &nbsp; Khóa: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span>
            &nbsp; Hệ đào tạo: Chính quy
        </p>

        <p class="mb-1">
            Thời gian đào tạo: từ tháng
            <span class="border-bottom px-1">{{ $d['thang_bat_dau'] ?? '___' }}</span>
            năm <span class="border-bottom px-1">{{ $d['nam_bat_dau'] ?? '___' }}</span>
            đến tháng <span class="border-bottom px-1">{{ $d['thang_ket_thuc'] ?? '___' }}</span>
            năm <span class="border-bottom px-1">{{ $d['nam_ket_thuc'] ?? '___' }}</span>
        </p>

        <hr class="my-3">
        <p class="mb-2 fw-bold">Đối tượng ưu đãi:</p>
        @php $doiTuong = $d['doi_tuong'] ?? []; @endphp
        <div class="ms-3 mb-3">
            <p class="mb-1"><span>{{ in_array('con_liet_si',(array)$doiTuong)?'☑':'☐' }}</span> &nbsp; Con liệt sĩ</p>
            <p class="mb-1"><span>{{ in_array('con_thuong_binh',(array)$doiTuong)?'☑':'☐' }}</span> &nbsp; Con thương binh / bệnh binh (≥ 61%)</p>
            <p class="mb-1"><span>{{ in_array('con_anh_hung',(array)$doiTuong)?'☑':'☐' }}</span> &nbsp; Con Anh hùng lực lượng vũ trang / Anh hùng lao động</p>
            <p class="mb-1"><span>{{ in_array('nguoi_co_cong',(array)$doiTuong)?'☑':'☐' }}</span> &nbsp; Người có công với cách mạng được hưởng trợ cấp hàng tháng</p>
            <p class="mb-1"><span>{{ in_array('chat_doc',(array)$doiTuong)?'☑':'☐' }}</span> &nbsp; Con của người bị nhiễm chất độc hoá học</p>
            @if(!empty($d['doi_tuong_khac']))
            <p class="mb-1">Đối tượng khác: <span class="border-bottom px-1">{{ $d['doi_tuong_khac'] }}</span></p>
            @endif
        </div>

        <p class="mb-1">
            Giấy chứng nhận số: <span class="border-bottom px-1">{{ $d['so_gcn'] ?? '___' }}</span>
            &nbsp; Cấp ngày: <span class="border-bottom px-1">{{ ($d['gcn_ngay']??'__').'/'.($d['gcn_thang']??'__').'/'.($d['gcn_nam']??'____') }}</span>
        </p>

        <p class="mb-3">
            Do cơ quan: <span class="border-bottom px-1" style="min-width:250px;display:inline-block">{{ $d['co_quan_cap'] ?? '___' }}</span> cấp.
        </p>

        <p class="mb-4 fst-italic text-muted" style="font-size:13px">* Giấy xác nhận này có giá trị trong vòng 03 tháng kể từ ngày cấp.</p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>Tp.Hồ Chí Minh, ngày
                    <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span>
                    tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span>
                    năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span>
                </p>
                <p class="fw-bold mb-0">Người làm đơn</p><br><br><br>
                <p>{{ $submission->user->last_name }} {{ $submission->user->first_name }}</p>
            </div>
            <div class="text-center" style="width:40%">
                {{-- ✅ Chữ ký động --}}
                <p class="fw-bold mb-0">{{ strtoupper($submission->form->signtitle ?? 'TRƯỞNG PHÒNG CTSV') }}</p>
                <br><br><br>
                <p>(Ký, ghi rõ họ tên, đóng dấu)</p>
                @if($submission->form->signname ?? null)
                    <p>{{ $submission->form->signname }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="card mt-3 p-4 shadow-sm">

        @if($st === 1 && isset($ngayLayGiay) && $ngayLayGiay)
            <div class="alert alert-success d-flex align-items-center gap-3 mb-3">
                <span style="font-size:32px">📅</span>
                <div>
                    <div class="fw-bold">Đơn đã được duyệt! Vui lòng đến lấy giấy tờ.</div>
                    <div class="small mt-1">
                        Từ ngày: <strong>{{ $ngayLayGiay->format('d/m/Y') }}</strong>
                        &nbsp;—&nbsp;
                        Hết hạn lấy: <strong class="text-danger">{{ $ngayHetHan->format('d/m/Y') }}</strong>
                        (trong vòng 3 ngày kể từ ngày duyệt)
                    </div>
                    <div class="small text-muted mt-1">
                        Hình thức nhận:
                        @if($submission->get_at === 'buu_dien') 📮 Bưu điện
                        @else 🏢 Nhận trực tiếp tại phòng CTSV @endif
                    </div>
                </div>
            </div>
        @elseif($st === 2)
            <div class="alert alert-danger mb-3">
                <i class="bi bi-x-circle-fill me-2"></i> <strong>Đơn bị từ chối.</strong>
                @if($submission->note)<br><small><strong>Lý do:</strong> {{ $submission->note }}</small>@endif
            </div>
        @elseif($st === 0)
            <div class="alert alert-warning mb-3">
                <i class="bi bi-hourglass-split me-2"></i> Đơn đang chờ admin xét duyệt. Vui lòng chờ thông báo.
            </div>
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="text-muted small d-block">Phương thức nhận hồ sơ</label>
                <span class="fw-semibold">
                    @if($submission->get_at === 'buu_dien')
                        📮 Nhận qua Bưu điện
                        @if($submission->ReceivingAddress)<br><small class="text-muted">{{ $submission->ReceivingAddress }}</small>@endif
                    @else 🏢 Nhận trực tiếp tại phòng CTSV @endif
                </span>
            </div>
            <div class="col-md-6">
                <label class="text-muted small d-block">Ngày nộp</label>
                <span>{{ $submission->created_at ? $submission->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i — d/m/Y') : '—' }}</span>
            </div>
            @if($submission->note && $st !== 2)
            <div class="col-12">
                <label class="text-muted small d-block">Ghi chú</label>
                <span>{{ $submission->note }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại lịch sử
        </a>
        @if($st === 0)
            <span class="btn btn-warning disabled"><i class="bi bi-hourglass-split"></i> Đang chờ duyệt</span>
        @endif
    </div>
</div>
@endsection