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
    $rawKhoa = $d['khoa_sv'] ?? $submission->user->facultyid ?? '';
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

        <div class="text-center mb-3">
            <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
            <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
            <div>———————————</div>
            <div class="fw-bold mt-2" style="font-size:16px">GIẤY XÁC NHẬN</div>
        </div>

        <p class="mb-1">Họ và tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">
           {{ $d['ho_ten'] ?? ($submission->user->last_name.' '.$submission->user->first_name) }}
        </span></p>

        <p class="mb-1">
            Sinh ngày:
            <span class="border-bottom px-1">{{ $d['ngay'] ?? '___' }}</span>
            tháng <span class="border-bottom px-1">{{ $d['thang'] ?? '___' }}</span>
            năm <span class="border-bottom px-1">{{ $d['nam'] ?? '___' }}</span>
            &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong>
        </p>

        <p class="mb-1">
            CMND/CCCD số: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span>
            &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ ($d['ngay_cap_cmnd_d']??'__').'/'.($d['ngay_cap_cmnd_m']??'__').'/'.($d['ngay_cap_cmnd_y']??'____') }}</span>
            &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span>
        </p>

        <p class="mb-1">Mã trường: DSG &nbsp;&nbsp; Tên trường: Trường Đại học Công nghệ Sài Gòn</p>

        <p class="mb-1">Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span>
            &nbsp; Hệ: Đại học &nbsp; Chính quy</p>

        <p class="mb-1">
            Khóa: <span class="border-bottom px-1">{{ $d['khoa'] ?? '___' }}</span>
            &nbsp; Lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
            &nbsp; Mã SV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span>
        </p>

        <p class="mb-1">Khoa: <span class="border-bottom px-1">{{ $khoaTen ?: '___' }}</span></p>

        <p class="mb-1">
            Ngày nhập học: <span class="border-bottom px-1">{{ ($d['ngay_nhap_hoc_d']??'__').'/'.($d['ngay_nhap_hoc_m']??'__').'/'.($d['ngay_nhap_hoc_y']??'____') }}</span>
            &nbsp; Năm ra trường dự kiến: <span class="border-bottom px-1">{{ $d['nam_ra_truong'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            Học phí hàng tháng: <span class="border-bottom px-1">{{ number_format($d['hoc_phi'] ?? 0) }}</span> đồng.
        </p>

        <p class="mb-1">Thuộc diện:
            @php $dien = $d['thuoc_dien'] ?? ''; @endphp
            <span>{{ $dien=='khong_mien_giam'?'☑':'☐' }}</span> Không miễn giảm &nbsp;
            <span>{{ $dien=='giam_hoc_phi'?'☑':'☐' }}</span> Giảm học phí &nbsp;
            <span>{{ $dien=='mien_hoc_phi'?'☑':'☐' }}</span> Miễn học phí
        </p>

        <p class="mb-3">Thuộc đối tượng:
            @php $dt = $d['doi_tuong'] ?? 'khong_mo_coi'; @endphp
            <span>{{ $dt=='mo_coi'?'☑':'☐' }}</span> Mồ côi &nbsp;
            <span>{{ $dt=='khong_mo_coi'?'☑':'☐' }}</span> Không mồ côi
        </p>

        <p class="mb-3">- Số tài khoản của nhà trường: 8770199, tại ngân hàng Á Châu (ACB).</p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>Tp.Hồ Chí Minh, ngày
                    <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span>
                    tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span>
                    năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span>
                </p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">Hiệu trưởng</p><br><br><br>
                <p>PGS. TS. Cao Hào Thi</p>
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