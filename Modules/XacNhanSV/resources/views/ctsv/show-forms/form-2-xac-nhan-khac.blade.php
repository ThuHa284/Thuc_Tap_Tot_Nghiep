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
    $rawKhoa  = $d['khoa'] ?? $submission->user->facultyid ?? '';
    $khoaTen  = $khoaMap[strtolower($rawKhoa)] ?? $rawKhoa;
    $khoaTenUser = $khoaMap[strtolower($submission->user->facultyid ?? '')] ?? ($submission->user->facultyid ?? '');
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
            <div class="fw-bold mt-2" style="font-size:16px">ĐƠN XIN XÁC NHẬN 2</div>
            <div>Kính gửi: Phòng Công tác Sinh viên</div>
        </div>

        <p class="mb-1">
            Tôi tên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">
                {{ $d['ho_ten'] ?? ($submission->user->last_name.' '.$submission->user->first_name) }}
            </span>
        </p>

        <p class="mb-1">
            Sinh ngày:
            <span class="border-bottom px-1">{{ $d['ngay'] ?? '___' }}</span>
            tháng <span class="border-bottom px-1">{{ $d['thang'] ?? '___' }}</span>
            năm <span class="border-bottom px-1">{{ $d['nam'] ?? '___' }}</span>
            &nbsp;&nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong>
        </p>

        <p class="mb-1">
            Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
            &nbsp; Khoa: <span class="border-bottom px-1">{{ $khoaTen ?: '___' }}</span>
            &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span>
        </p>

        <p class="mb-1">
            Hộ khẩu thường trú:
            <span class="border-bottom px-1" style="min-width:350px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            Bậc đào tạo: <span class="border-bottom px-1">Đại học</span>
            &nbsp; Hệ đào tạo: chính quy của Trường Đại học Công nghệ Sài Gòn.
        </p>

        <p class="mb-1">
            Số điện thoại liên lạc:
            <span class="border-bottom px-1">{{ $d['sdt'] ?? '___' }}</span>
        </p>

        <p class="mb-2">Nay tôi làm đơn này xin nhà trường cấp giấy chứng nhận tôi là Sinh viên đang theo học tại trường để bổ túc hồ sơ xin:</p>

        <p class="mb-1">
            @if(!empty($d['xin_giam_tru']))
                <strong>☑</strong> Xác nhận giảm trừ gia cảnh
            @else
                ☐ Xác nhận giảm trừ gia cảnh
            @endif
        </p>

        <p class="mb-3">
            Xác nhận khác:
            <span class="border-bottom px-1" style="min-width:280px;display:inline-block">{{ $d['xac_nhan_khac'] ?? '___' }}</span>
        </p>

        <p class="mb-4">Trân trọng kính chào.</p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>Tp.Hồ Chí Minh, ngày
                    <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span>
                    tháng <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span>
                    năm <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span>
                </p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">Người làm đơn</p><br><br><br>
                <p>{{ $submission->user->last_name }} {{ $submission->user->first_name }}</p>
            </div>
        </div>

        <hr class="my-3">
        <div class="text-center fw-bold mb-2">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>
        <p>Xác nhận sinh viên: {{ $submission->user->last_name }} {{ $submission->user->first_name }}</p>
        <p class="mb-1">
            Hiện là sinh viên năm thứ <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
            &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
            &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span>
            &nbsp; Khóa học: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? '___' }}</span>
        </p>
        <p>MSSV: {{ $submission->studentid }} &nbsp;&nbsp; Khoa: {{ $khoaTenUser ?: '___' }}</p>
        <p>Hệ đào tạo: chính quy của Trường Đại học Công nghệ Sài Gòn.</p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>Tp.Hồ Chí Minh, ngày &nbsp;&nbsp;&nbsp; tháng &nbsp;&nbsp;&nbsp; năm {{ date('Y') }}</p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">HIỆU TRƯỞNG</p><br><br><br>
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