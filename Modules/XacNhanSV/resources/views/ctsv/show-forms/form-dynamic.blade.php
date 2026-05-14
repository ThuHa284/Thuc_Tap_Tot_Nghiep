@extends('layouts.master')
@section('title', 'Chi tiết đơn #' . $submission->id)

@section('content')
@php
    $d  = $submission->data ?? [];
    $st = (int) $submission->status;
    $alertType   = match($st) { 0=>'warning', 1=>'success', 2=>'danger', default=>'secondary' };
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
    $rawKhoa  = $submission->user->facultyid ?? '';
    $khoaTen  = $khoaMap[strtolower($rawKhoa)] ?? $rawKhoa;
@endphp

<div class="container py-4" style="max-width:860px">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại lịch sử
        </a>
        <span class="badge bg-{{ $alertType }} fs-6 px-3 py-2">{{ $statusLabel }}</span>
    </div>

    {{-- Tờ giấy thật --}}
    <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">

        {{-- Header giấy --}}
        <div class="row mb-3 text-center" style="font-size:13px">
            <div class="col-6 border-end">
                <div class="fw-bold">{{ strtoupper($submission->form->schoolname ?? 'TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN') }}</div>
                <div><em>Phòng Công tác Sinh viên</em></div>
                <div>———————————</div>
            </div>
            <div class="col-6">
                <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
                <div>———————————</div>
            </div>
        </div>

        <div class="text-center mb-4">
            <div class="fw-bold mt-2" style="font-size:16px">
                {{ strtoupper($submission->form->name) }}
            </div>
        </div>

        <p class="mb-2">
            Kính gửi: Ban Giám Hiệu {{ $submission->form->schoolname ?? 'Trường Đại học Công nghệ Sài Gòn' }}
        </p>

        {{-- Thông tin SV cố định --}}
        <p class="mb-1">
            Tôi tên:
            <span class="border-bottom px-1" style="min-width:200px;display:inline-block">
                {{ $d['ho_ten'] ?? ($submission->user->last_name.' '.$submission->user->first_name) }}
            </span>
            &nbsp;&nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? '—' }}</strong>
        </p>

        <p class="mb-1">
            Học lớp:
            <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
            &nbsp; Khoa:
            <span class="border-bottom px-1">{{ $khoaTen ?: '___' }}</span>
            &nbsp; MSSV:
            <span class="border-bottom px-1">{{ $submission->studentid }}</span>
        </p>

        {{-- Các trường động từ etp_form_detail --}}
        @foreach($submission->form->details as $detail)
        <p class="mb-1">
            {{ $detail->label }}:
            <span class="border-bottom px-1" style="min-width:300px;display:inline-block">
                {{ $d['field_'.$detail->fdetailid] ?? '___' }}
            </span>
        </p>
        @endforeach

        <p class="mt-3 mb-4">Trân trọng kính chào.</p>

        {{-- Ngày ký + Chữ ký --}}
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
                <p>{{ $submission->user->last_name }} {{ $submission->user->first_name }}</p>
            </div>
        </div>

        <hr class="my-4">

        {{-- Phần xác nhận của trường --}}
        <div class="text-center fw-bold mb-3">
            XÁC NHẬN CỦA {{ strtoupper($submission->form->schoolname ?? 'TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN') }}
        </div>

        <p>Xác nhận sinh viên: <strong>{{ $submission->user->last_name }} {{ $submission->user->first_name }}</strong></p>
        <p class="mb-1">
            Hiện là sinh viên năm thứ
            <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
            &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
            &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span>
            &nbsp; Khóa học: <span class="border-bottom px-1">{{ $d['khoa_hoc'] ?? '___' }}</span>
        </p>
        <p>MSSV: {{ $submission->studentid }} &nbsp;&nbsp; Khoa: {{ $khoaTen ?: '___' }}</p>
        <p>Hệ đào tạo: chính quy của {{ $submission->form->schoolname ?? 'Trường Đại học Công nghệ Sài Gòn' }}.</p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>Tp.Hồ Chí Minh, ngày &nbsp;&nbsp;&nbsp; tháng &nbsp;&nbsp;&nbsp; năm {{ date('Y') }}</p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">{{ strtoupper($submission->form->signtitle ?? 'HIỆU TRƯỞNG') }}</p>
                <br><br><br>
                <p>{{ $submission->form->signname ?? 'PGS. TS. Cao Hào Thi' }}</p>
            </div>
        </div>
    </div>

    {{-- Phần phụ: trạng thái + hình thức nhận --}}
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
                        @else 🏢 Nhận trực tiếp tại phòng CTSV
                        @endif
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
                    @if($submission->get_at === 'buu_dien')
                        📮 Nhận qua Bưu điện
                        @if($submission->ReceivingAddress)
                            <br><small class="text-muted">{{ $submission->ReceivingAddress }}</small>
                        @endif
                    @else
                        🏢 Nhận trực tiếp tại phòng CTSV
                    @endif
                </span>
            </div>
            <div class="col-md-6">
                <label class="text-muted small d-block">Ngày nộp</label>
                <span>
                    {{ $submission->submitted_at
                        ? \Carbon\Carbon::parse($submission->submitted_at)->format('H:i — d/m/Y')
                        : ($submission->created_at ? $submission->created_at->format('H:i — d/m/Y') : '—') }}
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
        @endif
    </div>

</div>
@endsection