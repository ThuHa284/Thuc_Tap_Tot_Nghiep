@extends('layouts.master')
@section('title', 'Chi tiết đơn #' . $submission->id)

@section('content')
<div class="container py-4" style="max-width:860px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">📄 Chi tiết đơn #{{ $submission->id }}</h4>
            <p class="text-muted mb-0 small">{{ $submission->form->name ?? '—' }}</p>
        </div>
        <a href="{{ route('xacnhansv.ctsv.admin.requests') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $st = (int) $submission->status;
        $alertType   = match($st){ 0=>'warning', 1=>'success', 2=>'danger', default=>'secondary' };
        $statusLabel = match($st){ 0=>'⏳ Chờ duyệt', 1=>'✅ Đã duyệt', 2=>'❌ Từ chối', default=>'?' };
        $badgeClass  = match($st){ 0=>'warning text-dark', 1=>'success', 2=>'danger', default=>'secondary' };
    @endphp

    <div class="alert alert-{{ $alertType }} d-flex align-items-center gap-2 mb-4">
        <span class="fw-bold fs-6">{{ $statusLabel }}</span>
        @if($st===0) <span class="small">— Đơn đang chờ xét duyệt</span>
        @elseif($st===1) <span class="small">— Đơn đã được duyệt</span>
        @elseif($st===2) <span class="small">— Đơn đã bị từ chối</span>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-md-8">

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-bold">👤 Thông tin sinh viên</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Họ tên</label>
                            <span class="fw-semibold">
                                {{ $submission->user ? $submission->user->first_name.' '.$submission->user->last_name : '—' }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">MSSV</label>
                            <span>{{ $submission->studentid }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Lớp</label>
                            <span>{{ $submission->user->classid ?? '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Khoa</label>
                            <span>{{ $submission->user->facultyid ?? '—' }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Email</label>
                            <span>{{ $submission->user->email ?? '—' }}</span>
                        </div>
                    </div>
                </div>
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
                <div class="card-header bg-white fw-bold">📎 File đính kèm</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($submission->fileDetails as $file)
                            @php
                                $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                $url = asset('storage/'.$file->path);
                            @endphp
                            @if(in_array($ext, ['jpg','jpeg','png']))
                                <a href="{{ $url }}" target="_blank">
                                    <img src="{{ $url }}" class="img-thumbnail shadow-sm"
                                         style="max-width:150px;max-height:150px;object-fit:cover">
                                </a>
                            @elseif($ext=='pdf')
                                <a href="{{ $url }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> {{ $file->original_name }}
                                </a>
                            @else
                                <a href="{{ $url }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-file-earmark"></i> {{ $file->original_name }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Cột phải: hành động --}}
        <div class="col-md-4">
            <div class="card shadow-sm" style="position:sticky;top:20px">
                <div class="card-header bg-white fw-bold">⚡ Hành động</div>
                <div class="card-body">
                    @if($st === 0)
                        <form action="{{ route('xacnhansv.ctsv.admin.requests.approve', $submission->id) }}"
                              method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2"
                                onclick="return confirm('Xác nhận DUYỆT đơn #{{ $submission->id }}?')">
                                <i class="bi bi-check-circle-fill me-1"></i> Duyệt đơn
                            </button>
                        </form>

                        <div class="border rounded p-3" style="background:#fff5f5">
                            <label class="fw-semibold small mb-2 d-block text-danger">❌ Từ chối đơn</label>
                            <form action="{{ route('xacnhansv.ctsv.admin.requests.reject', $submission->id) }}"
                                  method="POST">
                                @csrf
                                <textarea name="note" class="form-control form-control-sm mb-2"
                                          rows="3" placeholder="Lý do từ chối..."></textarea>
                                <button type="submit" class="btn btn-danger btn-sm w-100"
                                    onclick="return confirm('Xác nhận TỪ CHỐI đơn #{{ $submission->id }}?')">
                                    <i class="bi bi-x-circle-fill me-1"></i> Từ chối
                                </button>
                            </form>
                        </div>

                    @elseif($st === 1)
                        <div class="alert alert-success mb-0 text-center">
                            <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                            <strong>Đã duyệt</strong>
                        </div>

                    @elseif($st === 2)
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-x-circle-fill"></i> <strong>Đã từ chối</strong>
                            @if($submission->note)
                                <hr class="my-2">
                                <small><strong>Lý do:</strong> {{ $submission->note }}</small>
                            @endif
                        </div>
                    @endif

                    <hr>
                    <div class="text-muted small">
                        <div><strong>ID đơn:</strong> #{{ $submission->id }}</div>
                        <div><strong>Ngày nộp:</strong><br>
                            {{ $submission->created_at ? $submission->created_at->format('H:i — d/m/Y') : '—' }}
                        </div>
                        <div class="mt-1"><strong>Trạng thái:</strong>
                            <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection