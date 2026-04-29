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
            <div><em>Không bị xử phạt hành chính</em></div>
        </div>

        <p class="mb-2 fw-bold">PHÒNG CÔNG TÁC SINH VIÊN TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN XÁC NHẬN:</p>

        <p class="mb-1">
            Sinh viên: <span class="border-bottom px-1" style="min-width:220px;display:inline-block">
                {{ $d['ho_ten'] ?? ($submission->user->first_name ?? '') . ' ' . ($submission->user->last_name ?? '') }}
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
            CMND/CCCD số: <span class="border-bottom px-1">{{ $d['cmnd'] ?? '___' }}</span>
            &nbsp; Ngày cấp: <span class="border-bottom px-1">{{ $d['ngay_cap_cmnd'] ?? '___' }}</span>
            &nbsp; Nơi cấp: <span class="border-bottom px-1">{{ $d['noi_cap_cmnd'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            Hộ khẩu thường trú:
            <span class="border-bottom px-1" style="min-width:350px;display:inline-block">{{ $d['ho_khau'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            Học lớp: <span class="border-bottom px-1">{{ $d['lop'] ?? $submission->user->classid ?? '___' }}</span>
            &nbsp; Khoa: <span class="border-bottom px-1">{{ $d['khoa'] ?? $submission->user->facultyid ?? '___' }}</span>
            &nbsp; MSSV: <span class="border-bottom px-1">{{ $d['mssv'] ?? $submission->studentid }}</span>
        </p>

        <p class="mb-1">
            Hiện là sinh viên năm thứ
            <span class="border-bottom px-1">{{ $d['nam_thu'] ?? '___' }}</span>
            &nbsp; Học kỳ: <span class="border-bottom px-1">{{ $d['hoc_ky'] ?? '___' }}</span>
            &nbsp; Năm học: <span class="border-bottom px-1">{{ $d['nam_hoc'] ?? '___' }}</span>
        </p>

        <p class="mb-1">
            Hệ đào tạo:
            <span class="border-bottom px-1">Đại học</span>
            &nbsp; Hệ: Chính quy của Trường Đại học Công nghệ Sài Gòn.
        </p>

        <p class="mb-3 mt-3">
            Trong thời gian theo học tại trường từ ngày
            <span class="border-bottom px-1">{{ $d['tu_ngay'] ?? '___' }}</span>
            đến ngày
            <span class="border-bottom px-1">{{ $d['den_ngay'] ?? '___' }}</span>,
            sinh viên <strong>{{ ($submission->user->first_name ?? '') . ' ' . ($submission->user->last_name ?? '') }}</strong>
            <span class="fw-bold">KHÔNG bị xử phạt hành chính</span>
            về các hành vi vi phạm pháp luật như: cờ bạc, nghiện hút, trộm cắp, buôn lậu và các hành vi vi phạm pháp luật khác.
        </p>

        <p class="mb-2">Giấy xác nhận này được cấp để:</p>
        <div class="ms-3 mb-3">
            @php $mucDich = $d['muc_dich'] ?? []; @endphp
            <p class="mb-1"><span>{{ in_array('xin_viec', (array)$mucDich) ? '☑' : '☐' }}</span> &nbsp; Xin việc làm</p>
            <p class="mb-1"><span>{{ in_array('hoc_bong', (array)$mucDich) ? '☑' : '☐' }}</span> &nbsp; Xét học bổng</p>
            <p class="mb-1"><span>{{ in_array('du_hoc', (array)$mucDich) ? '☑' : '☐' }}</span> &nbsp; Du học / Visa</p>
            <p class="mb-1"><span>{{ in_array('ho_so_khac', (array)$mucDich) ? '☑' : '☐' }}</span> &nbsp; Bổ túc hồ sơ khác</p>
            @if(!empty($d['muc_dich_khac']))
            <p class="mb-1">Mục đích khác: <span class="border-bottom px-1">{{ $d['muc_dich_khac'] }}</span></p>
            @endif
        </div>

        <p class="mb-4 fst-italic text-muted" style="font-size:13px">
            * Giấy xác nhận này có giá trị trong vòng 30 ngày kể từ ngày cấp.
        </p>

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
                <p class="fw-bold mb-0">Người làm đơn</p>
                <br><br><br>
                <p>{{ $submission->user->first_name ?? '' }} {{ $submission->user->last_name ?? '' }}</p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">TRƯỞNG PHÒNG CTSV</p>
                <br><br><br>
                <p>(Ký, ghi rõ họ tên, đóng dấu)</p>
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
            @if($submission->note)
            <div class="col-12">
                <label class="text-muted small d-block">Ghi chú</label>
                <span>{{ $submission->note }}</span>
            </div>
            @endif
        </div>

        @if($submission->fileDetails->isNotEmpty())
        <hr>
        <div class="fw-semibold mb-2">📎 File đính kèm</div>
        <div class="d-flex flex-wrap gap-3">
            @foreach($submission->fileDetails as $file)
                @php $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION)); $url = asset('storage/'.$file->path); @endphp
                @if(in_array($ext,['jpg','jpeg','png']))
                    <a href="{{ $url }}" download="{{ $file->original_name }}">
                        <img src="{{ $url }}" class="img-thumbnail" style="max-width:150px;max-height:150px;object-fit:cover;cursor:pointer">
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