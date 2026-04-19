@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-info" style="max-width: 600px; margin: auto;">
      
        <div class="card-body text-center">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <p class="mb-2">Sự kiện: <strong class="text-dark">{{ $eventName }}</strong></p>    
            
            {{-- Nơi để hiển thị mã QR --}}
            <div id="qrcode" class="d-flex justify-content-center p-3"></div>

            <hr>
            <a href="{{ route('diemdanh.create_event') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

{{-- Thư viện để tạo mã QR từ CDN --}}
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

<script type="text/javascript">
    // Tạo mã QR từ dữ liệu được controller truyền sang ($qrData)
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $qrData }}",
        width: 256,
        height: 256,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
</script>
@endsection