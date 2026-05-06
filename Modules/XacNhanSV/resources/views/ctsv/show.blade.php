@extends('layouts.master')
@section('title', 'Chi tiết đơn #' . $submission->id)

@section('content')
<div class="container py-4" style="max-width:760px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">📄 Chi tiết đơn #{{ $submission->id }}</h4>
            <p class="text-muted mb-0 small">{{ $submission->form->name ?? '—' }}</p>
        </div>
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @php
        $st = (int) $submission->status;
        $alertType = match($st) {
            0 => 'warning',
            1 => 'success',
            2 => 'danger',
            3 => 'info',      // ✅ Đã in
            default => 'secondary'
        };
        $statusLabel = match($st) {
            0 => '⏳ Chờ duyệt',
            1 => '✅ Đã duyệt',
            2 => '❌ Từ chối',
            3 => '🖨️ Đã in',  // ✅ Đã in
            default => '?'
        };
    @endphp

    <div class="alert alert-{{ $alertType }} d-flex align-items-center gap-2 mb-4">
        <span class="fw-bold">{{ $statusLabel }}</span>
        @if($st === 0)
            <span class="small">— Đơn đang chờ xét duyệt</span>
        @elseif($st === 1)
            <span class="small">— Đơn đã được duyệt, vui lòng đến nhận theo hình thức đã chọn</span>
        @elseif($st === 2)
            <span class="small">— Đơn đã bị từ chối</span>
        @elseif($st === 3)
            <span class="small">— Đơn đã được in, vui lòng đến nhận giấy tờ tại phòng CTSV</span>
        @endif
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white fw-bold">📋 Thông tin đơn</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small d-block">Loại giấy tờ</label>
                    <span class="fw-semibold">{{ $submission->form->name ?? '—' }}</span>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">Ngày nộp</label>
                    <span>{{ $submission->created_at ? $submission->created_at->format('H:i — d/m/Y') : '—' }}</span>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">Từ ngày</label>
                    <span>{{ $submission->date1 ? \Carbon\Carbon::parse($submission->date1)->format('d/m/Y') : '—' }}</span>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">Đến ngày</label>
                    <span>{{ $submission->date2 ? \Carbon\Carbon::parse($submission->date2)->format('d/m/Y') : '—' }}</span>
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
                <div class="col-md-6">
                    <label class="text-muted small d-block">Nơi nhận</label>
                    <span>{{ $submission->ReceivingAddress ?? '—' }}</span>
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

    @if(!empty($submission->data))
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white fw-bold">📝 Nội dung đơn</div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($submission->data as $label => $value)
                    @if($value)
                    <div class="col-md-6">
                        <label class="text-muted small d-block">{{ $label }}</label>
                        <span class="fw-semibold">{{ $value }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($submission->fileDetails->isNotEmpty())
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white fw-bold">📎 File minh chứng</div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-3">
                @foreach($submission->fileDetails as $file)
                    @php
                        $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                        $url = asset('storage/'.$file->path);
                    @endphp
                    @if(in_array($ext, ['jpg','jpeg','png']))
                        <a href="{{ $url }}" download="{{ $file->original_name }}" title="Bấm để tải về">
                            <img src="{{ $url }}" class="img-thumbnail shadow-sm"
                                 style="max-width:150px;max-height:150px;object-fit:cover;cursor:pointer">
                            <div class="text-center small mt-1 text-muted">
                                <i class="bi bi-download"></i> Tải về
                            </div>
                        </a>
                    @elseif($ext == 'pdf')
                        <a href="{{ $url }}" download="{{ $file->original_name }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> {{ $file->original_name }}
                            <i class="bi bi-download ms-1"></i>
                        </a>
                    @else
                        <a href="{{ $url }}" download="{{ $file->original_name }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-file-earmark"></i> {{ $file->original_name }}
                            <i class="bi bi-download ms-1"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại lịch sử
        </a>
        @if($st === 0)
            <span class="btn btn-warning disabled">
                <i class="bi bi-hourglass-split"></i> Đang chờ duyệt
            </span>
        @elseif($st === 3)
            <span class="btn btn-info disabled text-white">
                <i class="bi bi-printer-fill"></i> Đã in — Vui lòng đến nhận giấy
            </span>
        @endif
    </div>
</div>
@endsection