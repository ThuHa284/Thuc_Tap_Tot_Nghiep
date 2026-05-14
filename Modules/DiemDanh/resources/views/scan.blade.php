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
                <h5 id="scan-result" class="mt-3"></h5>
            </div>

            <div class="mt-3">
                <a href="{{ route('diemdanh.create_event') }}" class="btn btn-outline-danger btn-sm">Kết thúc điểm danh</a>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let lastSentCode = "";
    let isProcessing = false;

    function playSound(type) {
        const sounds = {
            success: 'https://www.soundjay.com/buttons/beep-07.wav',
            error: 'https://www.soundjay.com/buttons/beep-03.wav',
            info: 'https://www.soundjay.com/buttons/beep-09.wav'
        };
        new Audio(sounds[type]).play().catch(() => {});
    }

    function sendAttendance(code) {
        if (isProcessing) return;
        isProcessing = true;

        let resultDiv = document.getElementById('scan-result');
        resultDiv.innerHTML = `<span class="spinner-border spinner-border-sm text-primary"></span> Đang lưu dữ liệu...`;
        resultDiv.className = 'text-primary mt-3';

        fetch("{{ route('diemdanh.save') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                playSound('success');
                resultDiv.innerHTML = `Đã lưu: <strong>${data.student_name}</strong> thành công!`;
                resultDiv.className = 'text-success fw-bold mt-3';
            } else if (data.status === 'info') {
                playSound('info');
                resultDiv.innerHTML = `${data.message}`;
                resultDiv.className = 'text-info fw-bold mt-3';
            } else {
                playSound('error');
                resultDiv.innerHTML = `${data.message || 'Lỗi không xác định.'}`;
                resultDiv.className = 'text-danger mt-3';
            }
        })
        .catch(() => {
            playSound('error');
            resultDiv.innerHTML = 'Lỗi kết nối máy chủ!';
            resultDiv.className = 'text-danger mt-3';
        })
        .finally(() => {
            setTimeout(() => {
                isProcessing = false;
                lastSentCode = '';
            }, 3000);
        });
    }

    function onScanSuccess(decodedText) {
        const code = decodedText.trim();
        if (!code || isProcessing) return;
        if (code === lastSentCode) return;

        lastSentCode = code;
        sendAttendance(code);
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        'reader',
        {
            fps: 15,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        },
        false
    );

    html5QrcodeScanner.render(onScanSuccess, () => {});
</script>
@endsection
