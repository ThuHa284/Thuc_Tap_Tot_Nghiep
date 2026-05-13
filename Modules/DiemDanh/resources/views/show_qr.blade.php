@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-info" style="max-width: 600px; margin: auto;">
        <div class="card-body text-center">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <p class="mb-2">Sự kiện: <strong class="text-dark">{{ $eventName }}</strong></p>
            <div id="qrcode" class="d-flex justify-content-center p-3"></div>

            <hr>
            <a href="{{ route('diemdanh.create_event') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script type="text/javascript">
    new QRCode(document.getElementById('qrcode'), {
        text: @json($qrData),
        width: 256,
        height: 256,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });
</script>
@endsection
