@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-primary">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">ĐANG ĐIỂM DANH: <span class="text-warning">{{ $eventName }}</span></h5>
        </div>
        <div class="card-body text-center">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div id="reader" class="border rounded shadow-sm" style="width: 100%;"></div>
                </div>
            </div>

            <div class="mt-4">
                <h5 id="scan-result" class="text-success"></h5>
            </div>

            <div class="mt-3">
                <a href="{{ route('diemdanh.create_event') }}" class="btn btn-outline-danger btn-sm">Kết thúc điểm danh</a>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    // Biến này để chống quét lặp 1 mã liên tục trong 3 giây
    let lastScannedCode = "";

    function onScanSuccess(decodedText, decodedResult) {
    if (decodedText === lastScannedCode) return;
    lastScannedCode = decodedText;

    // Hiển thị trạng thái đang xử lý
    let resultDiv = document.getElementById('scan-result');
    resultDiv.innerHTML = `<span class="spinner-border spinner-border-sm text-primary"></span> Đang lưu dữ liệu...`;
    resultDiv.className = "text-primary mt-3";

    // Gửi dữ liệu về Server bằng Fetch API (AJAX)
    fetch("{{ route('diemdanh.api.save') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ qr_code: decodedText })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // Phát tiếng bíp thành công
            new Audio('https://www.soundjay.com/buttons/beep-07.wav').play();
            
            resultDiv.innerHTML = `✅ ${data.message}`;
            resultDiv.className = "text-success fw-bold mt-3 animate__animated animate__bounceIn";
        }
    })
    .catch(error => {
        console.error("Lỗi:", error);
        resultDiv.innerHTML = "❌ Lỗi kết nối máy chủ!";
        resultDiv.className = "text-danger mt-3";
    });

    // Cho phép quét mã tiếp theo sau 2 giây
    setTimeout(() => { lastScannedCode = ""; }, 2000);
}

    function onScanFailure(error) {
        // Bỏ qua lỗi không tìm thấy QR trong khung hình
    }

    // Khởi tạo máy quét
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { fps: 10, qrbox: {width: 250, height: 250} }, 
        /* verbose= */ false
    );
    
    // Yêu cầu trình duyệt bật Camera
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endsection