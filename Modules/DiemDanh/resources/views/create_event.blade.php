@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-primary" style="max-width: 700px; margin: auto;">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">TẠO MÃ ĐIỂM DANH</h5>
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            @if($canCreateEvent)
                <form action="{{ route('diemdanh.store_event') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="event_name" class="form-label fw-bold">Nhập tên sự kiện:</label>
                        <input
                            type="text"
                            name="event_name"
                            id="event_name"
                            class="form-control"
                            placeholder="Ví dụ: Tuần sinh hoạt công dân 2024"
                            value="{{ session('diemdanh_event_name') }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="ctxh_days" class="form-label fw-bold">Số ngày CTXH:</label>
                        <input
                            type="number"
                            name="ctxh_days"
                            id="ctxh_days"
                            class="form-control"
                            step="0.1"
                            min="0"
                            max="999.9"
                            value="{{ old('ctxh_days', session('diemdanh_ctxh_days', 0.5)) }}"
                            required
                        >
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Tạo</button>
                    </div>
                </form>
            @else
                <div class="alert alert-info mb-0 text-center">
                    Tài khoản hỗ trợ không được tạo sự kiện mới, nhưng có thể chọn sự kiện có sẵn để tạo mã và điểm danh.
                </div>
            @endif

            @if($canUseQrTools && session('diemdanh_event_id'))
                <hr>
                <div class="alert alert-secondary text-center mb-3">
                    Đang chọn sự kiện: <strong>{{ session('diemdanh_event_name') }}</strong>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 d-grid mb-2">
                        <a href="{{ route('diemdanh.scan') }}" class="btn btn-success btn-lg">1. Quét mã QR</a>
                    </div>
                    <div class="col-md-6 d-grid mb-2">
                        <a href="{{ route('diemdanh.show_qr') }}" class="btn btn-info btn-lg">2. Tạo mã QR</a>
                    </div>
                </div>
            @endif
        </div>

        @if(isset($recentEvents) && $recentEvents->count() > 0)
            <div class="card-footer bg-light">
                <h6 class="text-muted mb-2">Các sự kiện gần đây:</h6>
                <div class="list-group">
                    @foreach($recentEvents as $event)
                        @if($canUseQrTools)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $event->category_name }}</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('diemdanh.select_event', $event->cid) }}" class="btn btn-sm btn-success">
                                        Điểm danh
                                    </a>
                                    <a href="{{ route('diemdanh.show_details', $event->cid) }}" class="btn btn-sm btn-outline-primary">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $event->category_name }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection