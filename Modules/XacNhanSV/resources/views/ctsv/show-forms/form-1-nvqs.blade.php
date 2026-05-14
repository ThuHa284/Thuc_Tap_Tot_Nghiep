@extends('layouts.master')
@section('title', 'Chi tiết đơn #' . $submission->id)

@section('content')
@php    
    $d = $submission->data ?? [];
    $st = (int) $submission->status;
    $alertType = match($st) { 0=>'warning', 1=>'success', 2=>'danger', default=>'secondary' };
    $statusLabel = match($st) { 0=>'⏳ Chờ duyệt', 1=>'✅ Đã duyệt', 2=>'❌ Từ chối', default=>'?' };
    $khoaMap = [
        'cntt' => 'Công nghệ Thông tin',
        'ckhi' => 'Cơ khí',
        'cntp' => 'Công nghệ Thực phẩm',
        'ddtu' => 'Điện - Điện tử',
        'dsgn' => 'Thiết kế',
        'kd'   => 'Kinh doanh',
        'ktct' => 'Kế toán - Kiểm toán',
        'qtkd' => 'Quản trị Kinh doanh',
    ];
    $rawKhoa = $d['khoa'] ?? $submission->user->facultyid ?? '';
    $khoaTen = $khoaMap[strtolower($rawKhoa)] ?? $rawKhoa;
    $rawKhoaUser = $submission->user->facultyid ?? '';
    $khoaTenUser = $khoaMap[strtolower($rawKhoaUser)] ?? $rawKhoaUser;
@endphp

<div class="container py-4" style="max-width: 860px">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại lịch sử
        </a>
        <span class="badge bg-{{ $alertType }} fs-6 px-3 py-2">{{ $statusLabel }}</span>
    </div>

    {{-- Tờ giấy thật --}}
    <div class="card shadow" style="font-family:'Times New Roman',serif; font-size:14px; padding:40px 50px; background:#fff; border:1px solid #ccc">

        <div class="text-center mb-3">
            <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
            <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
            <div>———————————</div>
            <div class="fw-bold mt-2" style="font-size:16px">ĐƠN XIN XÁC NHẬN NGHĨA VỤ QUÂN SỰ</div>
        </div>

        <p class="mb-2">Kính gửi: Ban Giám Hiệu Trường Đại học Công nghệ Sài Gòn</p>

        <p class="mb-1">
            Tôi tên: <span class="border-bottom px-1" style="min-width:200px;display:inline-block">
                {{ $d['ho_ten'] ?? ($submission->user->last_name ?? '') . ' ' . ($submission->user->first_name ?? '') }}
            </span>
            &nbsp;&nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong>
        </p>

        <p class="mb-1">
            Sinh ngày:
            <span class="border-bottom px-1">{{ $d['ngay_sinh'] ?? '___' }}</span>
            tháng
            <span class="border-bottom px-1">{{ $d['thang_sinh'] ?? '___' }}</span>
            năm
            <span class="border-bottom px-1">{{ $d['nam_sinh'] ?? '___' }}</span>
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
            Bậc đào tạo: <span class="border-bottom px-1">{{ $d['bac_dao_tao'] ?? 'Đại học' }}</span>
            &nbsp; Hệ đào tạo: <span class="border-bottom px-1">{{ $d['he_dao_tao'] ?? 'Chính quy' }}</span>
            &nbsp; của Trường Đại học Công nghệ Sài Gòn.
        </p>

        <p class="mb-1">
            Số điện thoại liên lạc:
            <span class="border-bottom px-1">{{ $d['sdt'] ?? '___' }}</span>
        </p>

        <p class="mb-2">
            Nay tôi làm đơn này xin nhà trường cấp giấy chứng nhận tôi là Sinh viên đang theo học
            tại trường để bổ túc hồ sơ xin:
        </p>

        <p class="mb-1">
            @if(!empty($d['xin_hoan_nvqs']))
                <strong>☑</strong> Xin hoãn nghĩa vụ quân sự
            @else
                ☐ Xin hoãn nghĩa vụ quân sự
            @endif
        </p>

        <p class="mb-3">
            Lý do khác:
            <span class="border-bottom px-1" style="min-width:300px;display:inline-block">{{ $d['ly_do_khac'] ?? '___' }}</span>
        </p>

        <p class="mb-4">Trân trọng kính chào.</p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>
                    Tp.Hồ Chí Minh, ngày
                    <span class="border-bottom px-1">{{ $d['ngay_ky'] ?? '___' }}</span>
                    tháng
                    <span class="border-bottom px-1">{{ $d['thang_ky'] ?? '___' }}</span>
                    năm
                    <span class="border-bottom px-1">{{ $d['nam_ky'] ?? date('Y') }}</span>
                </p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">Người làm đơn</p>
                <br><br><br>
                <p>{{ $submission->user->last_name ?? '' }} {{ $submission->user->first_name ?? '' }}</p>
            </div>
        </div>

        <hr class="my-4">

        <div class="text-center fw-bold mb-3">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>

        <p>Xác nhận sinh viên: {{ $submission->user->last_name ?? '' }} {{ $submission->user->first_name ?? '' }}</p>
        <p class="mb-1">
            Hiện là sinh viên năm thứ
            <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
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
                <p class="fw-bold mb-0">HIỆU TRƯỞNG</p>
                <br><br><br>
                <p>PGS. TS. Cao Hào Thi</p>
            </div>
        </div>

    </div>

    {{-- Phần phụ: hình thức nhận + ngày lấy --}}
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
                        @switch($submission->get_at)
                            @case('truc_tiep') 🏢 Nhận trực tiếp tại phòng CTSV @break
                            @case('buu_dien')  📮 Nhận qua Bưu điện @break
                        @endswitch
                    </div>
                </div>
            </div>
        @elseif($st === 2)
            <div class="alert alert-danger mb-3">
                <i class="bi bi-x-circle-fill me-2"></i>
                <strong>Đơn bị từ chối.</strong>
                @if($submission->note)
                    <br><small><strong>Lý do:</strong> {{ $submission->note }}</small>
                @endif
            </div>
        @elseif($st === 0)
            <div class="alert alert-warning mb-3">
                <i class="bi bi-hourglass-split me-2"></i>
                Đơn đang chờ admin xét duyệt. Vui lòng chờ thông báo.
            </div>
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="text-muted small d-block">Phương thức nhận hồ sơ</label>
                <span class="fw-semibold">
                    @switch($submission->get_at)
                        @case('truc_tiep') 🏢 Nhận trực tiếp tại phòng CTSV @break
                        @case('buu_dien')  📮 Nhận qua Bưu điện
                            @if($submission->ReceivingAddress)
                                <br><small class="text-muted">{{ $submission->ReceivingAddress }}</small>
                            @endif
                            @break
                        @default —
                    @endswitch
                </span>
            </div>
            <div class="col-md-6">
                <label class="text-muted small d-block">Ngày nộp</label>
                <span>
                    {{ $submission->created_at
                        ? $submission->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i — d/m/Y')
                        : '—' }}
                </span>
            </div>
            @if($submission->note && $st !== 2)
            <div class="col-12">
                <label class="text-muted small d-block">Ghi chú</label>
                <span>{{ $submission->note }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Nút hành động --}}
    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại lịch sử
        </a>
        @if($st === 0)
            <span class="btn btn-warning disabled">
                <i class="bi bi-hourglass-split"></i> Đang chờ duyệt
            </span>
        @elseif($st === 2 && $submission->note)
            <div class="alert alert-danger mb-0 py-2 px-3">
                <strong>Lý do từ chối:</strong> {{ $submission->note }}
            </div>
        @endif
    </div>

</div>
@endsection