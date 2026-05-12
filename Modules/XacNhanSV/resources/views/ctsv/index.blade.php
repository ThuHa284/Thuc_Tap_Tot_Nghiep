@extends('layouts.master')
@section('title', 'Xin Giấy Tờ - Phòng CTSV')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">📄 Xin Giấy Tờ Online</h4>
            <p class="text-muted mb-0">Chọn loại giấy tờ bạn cần xin từ Phòng Công tác Sinh viên</p>
        </div>
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-primary rounded-pill">
            <i class="bi bi-clock-history me-1"></i> Lịch sử đơn của tôi
        </a>
    </div>

    @auth
    @php
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
        $khoaLabel = $khoaMap[strtolower(auth()->user()->facultyid ?? '')] ?? (auth()->user()->facultyid ?? '—');
    @endphp
    <div class="alert alert-light border mb-4 d-flex align-items-center gap-3">
        <div style="font-size:32px">👤</div>
        <div class="flex-grow-1">
            <div class="fw-semibold">{{ auth()->user()->last_name }} {{ auth()->user()->first_name }}</div>
            <div class="small text-muted">
                MSSV: <strong>{{ auth()->user()->studentid }}</strong>
                &nbsp;|&nbsp; Lớp: <strong>{{ auth()->user()->classid ?? '—' }}</strong>
                &nbsp;|&nbsp; Khoa: <strong>{{ $khoaLabel }}</strong>
            </div>
        </div>
        {{-- su = 1 là Admin theo tài liệu đặc tả --}}
        @if((int) auth()->user()->su === 1)
        <a href="{{ route('xacnhansv.ctsv.admin.dashboard') }}" class="btn btn-warning btn-sm rounded-pill">
            🔧 Admin CTSV
        </a>
        @endif
    </div>
    @endauth

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($forms as $form)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm"
                 style="transition:transform 0.2s"
                 onmouseover="this.style.transform='translateY(-4px)'"
                 onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center
                                    justify-content-center me-3 flex-shrink-0"
                             style="width:48px;height:48px">
                            <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $form->name }}</h6>
                            <small class="text-muted">{{ $form->submissions_count }} đơn đã nộp</small>
                        </div>
                    </div>

                    @if($form->description)
                        <p class="text-muted small mb-3 flex-grow-1">
                            {{ Str::limit($form->description, 120) }}
                        </p>
                    @else
                        <div class="flex-grow-1"></div>
                    @endif

                    @if($form->url)
                        <div class="mb-3">
                            <small class="text-muted fw-semibold d-block mb-1">
                                <i class="bi bi-paperclip"></i> Giấy tờ cần nộp kèm:
                            </small>
                            @foreach(explode(',', $form->url) as $item)
                                <span class="badge bg-light text-dark border me-1 mb-1">
                                    {{ trim($item) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('xacnhansv.ctsv.form.show', $form->formid) }}"
                       class="btn btn-primary w-100 rounded-pill mt-auto">
                        <i class="bi bi-pencil-square me-1"></i> Tạo đơn
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 text-muted">
                <i class="bi bi-folder-x fs-1 d-block mb-3"></i>
                <h5>Chưa có mẫu giấy tờ nào</h5>
                <p class="small">Vui lòng liên hệ phòng CTSV để được hỗ trợ</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($forms->isNotEmpty())
    <div class="text-center mt-5">
        <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-clock-history me-1"></i> Xem lịch sử tất cả đơn đã nộp
        </a>
    </div>
    @endif

</div>
@endsection