@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-primary" style="max-width: 780px; margin: auto;">
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
                        <label for="event_name" class="form-label fw-bold">Tên sự kiện:</label>
                        <input
                            type="text"
                            name="event_name"
                            id="event_name"
                            class="form-control"
                            placeholder="Ví dụ: Tuần sinh hoạt công dân 2026"
                            value="{{ old('event_name', session('diemdanh_event_name')) }}"
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
                            step="0.5"
                            min="0"
                            max="5"
                            value="{{ old('ctxh_days', session('diemdanh_ctxh_days', 0.5)) }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="training_points" class="form-label fw-bold">Điểm rèn luyện:</label>
                        <input
                            type="number"
                            name="training_points"
                            id="training_points"
                            class="form-control"
                            step="1"
                            min="0"
                            max="3"
                            value="{{ old('training_points', session('diemdanh_training_points', 0)) }}"
                            required
                        >
                        <div class="form-text">Chỉ được chọn 1 trong 2: CTXH hoặc Điểm rèn luyện.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="attendance_start_at" class="form-label fw-bold">Bắt đầu điểm danh:</label>
                            <input
                                type="datetime-local"
                                name="attendance_start_at"
                                id="attendance_start_at"
                                class="form-control"
                                value="{{ old('attendance_start_at', session('diemdanh_attendance_start_at')) }}"
                                required
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="attendance_end_at" class="form-label fw-bold">Kết thúc điểm danh:</label>
                            <input
                                type="datetime-local"
                                name="attendance_end_at"
                                id="attendance_end_at"
                                class="form-control"
                                value="{{ old('attendance_end_at', session('diemdanh_attendance_end_at')) }}"
                                required
                            >
                        </div>
                    </div>

                    <script>
                        (function () {
                            const ctxh = document.getElementById('ctxh_days');
                            const points = document.getElementById('training_points');
                            const startAt = document.getElementById('attendance_start_at');
                            const endAt = document.getElementById('attendance_end_at');
                            if (!ctxh || !points || !startAt || !endAt) return;

                            function syncState() {
                                const ctxhVal = parseFloat(ctxh.value || '0');
                                const ptsVal = parseInt(points.value || '0', 10);

                                if (ctxhVal > 0) {
                                    points.disabled = true;
                                    if (ptsVal !== 0) points.value = 0;
                                } else if (ptsVal > 0) {
                                    ctxh.disabled = true;
                                    if (ctxh.value !== '0') ctxh.value = 0;
                                } else {
                                    points.disabled = false;
                                    ctxh.disabled = false;
                                }
                            }

                            function syncWindow() {
                                if (startAt.value) {
                                    endAt.min = startAt.value;
                                    if (endAt.value && endAt.value < startAt.value) {
                                        endAt.value = startAt.value;
                                    }
                                }
                            }

                            ctxh.addEventListener('input', syncState);
                            points.addEventListener('input', syncState);
                            startAt.addEventListener('change', syncWindow);
                            syncState();
                            syncWindow();
                        })();
                    </script>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Tạo / Cập nhật</button>
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
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div>{{ $event->category_name }}</div>
                                @if($event->attendance_start_at && $event->attendance_end_at)
                                    <small class="text-muted">{{ $event->attendance_start_at->format('H:i d/m/Y') }} - {{ $event->attendance_end_at->format('H:i d/m/Y') }}</small>
                                @endif
                            </div>
                            @if($canUseQrTools)
                                <div class="d-flex gap-2">
                                    <a href="{{ route('diemdanh.select_event', $event->cid) }}" class="btn btn-sm btn-success">Điểm danh</a>
                                    <a href="{{ route('diemdanh.show_details', $event->cid) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
