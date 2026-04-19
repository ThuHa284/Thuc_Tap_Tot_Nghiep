@extends('layouts.master')
@section('content')
<div class="container mt-4">
    <div class="card shadow border-primary" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-primary text-white text-center">
            <h5>TẠO ĐIỂM DANH</h5>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <form action="{{ route('diemdanh.store_event') }}" method="POST">
                @csrf
               <div class="mb-3">
                    <label for="event_name" class="form-label fw-bold">Nhập tên Sự kiện / Hoạt động:</label>
                    <input type="text" name="event_name" id="event_name" class="form-control"
                           placeholder="Ví dụ: Tuần sinh hoạt công dân 2024"
                           value="{{ session('diemdanh_event_name') }}" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Tạo sự kiện</button>
                </div>
            </form>

            @if(session('diemdanh_event_id'))
            <hr>
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
    </div>
</div>
@endsection