@extends('layouts.master')
@section('title', 'Chi tiết đơn #' . $submission->id)

@section('content')
@php
    $d = $submission->data ?? [];
    $st = (int) $submission->status;
    $alertType = match($st) { 0=>'warning', 1=>'success', 2=>'danger', default=>'secondary' };
    $statusLabel = match($st) { 0=>'⏳ Chờ duyệt', 1=>'✅ Đã duyệt', 2=>'❌ Từ chối', default=>'?' };
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
            {{ $d['ho_ten'] ?? ($submission->user->first_name ?? '') . ' ' . ($submission->user->last_name ?? '') }}
        </span></p>

        <p class="mb-1">
            Sinh ngày:
            <span class="border-bottom px-1">{{ $d['ngay'] ?? '___' }}</span>
            tháng <span class="border-bottom px-1">{{ $d['thang'] ?? '___' }}</span>
            năm <span class="border-bottom px-1">{{ $d['nam'] ?? '___' }}</span>
            &nbsp; Giới tính: <strong>{{ $d['gioi_tinh'] ?? 'Nam' }}</strong>
        </p>

        <p class="mb-1">
            CMND số: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span>
            ngày cấp: <span class="border-bottom px-1">{{ $d['ngay_cap_cmnd'] ?? '___' }}</span>
            Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span>
        </p>

        <p class="mb-1">Mã trường theo học (mã quy ước trong tuyển sinh ĐH, CĐ, TCCN): DSG</p>
        <p class="mb-1">Tên trường: Trường Đại học Công nghệ Sài Gòn</p>

        <p class="mb-1">Ngành học: <span class="border-bottom px-1">{{ $d['nganh_hoc'] ?? '___' }}</span></p>

        <p class="mb-1">
            Hệ đào tạo: <span class="border-bottom px-1">{{ $d['he_dao_tao'] ?? 'Đại học' }}</span>
            &nbsp; Khóa: <span class="border-bottom px-1">{{ $d['khoa'] ?? '___' }}</span>
            &nbsp; Loại hình đào tạo: Chính quy
        </p>

        <p class="mb-1">
            Lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
            &nbsp; Mã SV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span>
        </p>

        <p class="mb-1">Khoa: <span class="border-bottom px-1">{{ $d['khoa_sv'] ?? $submission->user->facultyid ?? '___' }}</span></p>

        <p class="mb-1">
            Ngày nhập học: <span class="border-bottom px-1">{{ $d['ngay_nhap_hoc'] ?? '___' }}</span>
            &nbsp; Thời gian ra trường (dự kiến 25 tháng 08 năm:
            <span class="border-bottom px-1">{{ $d['nam_ra_truong'] ?? '___' }}</span>)
        </p>

        <p class="mb-1">
            - Số tiền học phí hàng tháng:
            <span class="border-bottom px-1">{{ $d['hoc_phi'] ?? '___' }}</span> đồng.
        </p>

        <p class="mb-1">Thuộc diện:
            @php $dien = $d['thuoc_dien'] ?? ''; @endphp
            <span>{{ $dien == 'khong_mien_giam' ? '☑' : '☐' }}</span> Không miễn giảm &nbsp;
            <span>{{ $dien == 'giam_hoc_phi' ? '☑' : '☐' }}</span> Giảm học phí &nbsp;
            <span>{{ $dien == 'mien_hoc_phi' ? '☑' : '☐' }}</span> Miễn học phí
        </p>

        <p class="mb-1">Thuộc đối tượng:
            @php $dt = $d['doi_tuong'] ?? 'khong_mo_coi'; @endphp
            <span>{{ $dt == 'mo_coi' ? '☑' : '☐' }}</span> Mồ côi &nbsp;
            <span>{{ $dt == 'khong_mo_coi' ? '☑' : '☐' }}</span> Không mồ côi
        </p>

        <p class="mb-2">
            - Trong thời gian theo học tại trường, anh (chị)
            {{ $submission->user->first_name ?? '' }} {{ $submission->user->last_name ?? '' }}
            không bị xử phạt hành chính trở lên về các hành vi: cờ bạc, nghiện hút, trộm cắp, buôn lậu.
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
        <div class="row g-3">
            <div class="col-md-6">
                <label class="text-muted small d-block">Phương thức nhận hồ sơ</label>
                <span class="fw-semibold">
                    @switch($submission->get_at)
                        @case('truc_tiep') 🏢 Nhận trực tiếp tại phòng CTSV @break
                        @case('email')     📧 Nhận qua Email @break
                        @case('buu_dien')  📮 Nhận qua Bưu điện @break
                        @default —
                    @endswitch
                </span>
            </div>
            <div class="col-md-6">
                <label class="text-muted small d-block">Ngày nộp</label>
                <span>{{ $submission->created_at ? $submission->created_at->format('H:i — d/m/Y') : '—' }}</span>
            </div>
        </div>

        @if($submission->fileDetails->isNotEmpty())
        <hr>
        <div class="fw-semibold mb-2">📎 File đính kèm</div>
        <div class="d-flex flex-wrap gap-3">
            @foreach($submission->fileDetails as $file)
                @php $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION)); $url = asset('storage/'.$file->path); @endphp
                @if(in_array($ext,['jpg','jpeg','png']))
                    <a href="{{ $url }}" target="_blank"><img src="{{ $url }}" class="img-thumbnail" style="max-width:150px;max-height:150px;object-fit:cover"></a>
                @elseif($ext=='pdf')
                    <a href="{{ $url }}" target="_blank" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-earmark-pdf"></i> {{ $file->original_name }}</a>
                @else
                    <a href="{{ $url }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-file-earmark"></i> {{ $file->original_name }}</a>
                @endif
            @endforeach
        </div>
        @endif
    </div>

    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại lịch sử
        </a>
        @if($st === 0)
            <span class="btn btn-warning disabled"><i class="bi bi-hourglass-split"></i> Đang chờ duyệt</span>
        @elseif($st === 2 && $submission->note)
            <div class="alert alert-danger mb-0 py-2 px-3"><strong>Lý do từ chối:</strong> {{ $submission->note }}</div>
        @endif
    </div>
</div>
@endsection