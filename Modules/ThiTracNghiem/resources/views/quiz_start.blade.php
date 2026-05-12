@extends('layouts.master')

@section('title', 'Làm bài thi: ' . ($quiz->quiz_name ?? 'Đề thi'))

@section('content')
<style>
    /* 1. Ẩn các thành phần không cần thiết từ Layout Master */
    .main>.banner,
    .main>.card-box {
        display: none !important;
    }

    /* 2. Tối ưu không gian chung */
    .main {
        padding-top: 5px !important;
    }

    .bg-soft-primary {
        background-color: #eef2ff;
    }

    .text-primary {
        color: #2563eb !important;
    }

    .bg-primary {
        background-color: #2563eb !important;
    }

    /* 3. Thiết kế các nút bấm */
    .btn-primary {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }

    /* 4. Thiết kế các tùy chọn câu hỏi */
    .option-item input[type="radio"]:checked+label {
        background-color: #f0f7ff;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 1px #2563eb !important;
    }

    .option-item input[type="radio"]:checked+label .option-label {
        background-color: #2563eb !important;
        color: white !important;
    }

    .option-item label:hover {
        background-color: #f8fafc;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    /* 5. Thiết kế bản đồ câu hỏi */
    .question-map-item.active {
        background-color: #2563eb !important;
        color: white !important;
        border-color: #2563eb !important;
    }

    .question-map-item.answered {
        background-color: #dcfce7 !important;
        color: #166534 !important;
        border-color: #86efac !important;
    }

    /* 6. Hiệu ứng và định vị */
    .question-block {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .sticky-top-quiz {
        position: sticky;
        top: 10px;
        z-index: 100;
    }
</style>

<div class="container-fluid py-2"
    data-quiz-id="{{ $quiz->quid }}"
    data-quiz-duration="{{ $quizDuration ?? 40 }}"
    data-total-questions="{{ count($questions) }}">
    @if(isset($showWarning) && $showWarning)
    <div class="alert alert-{{ $remainingAttempts == 1 ? 'danger' : 'warning' }} alert-dismissible fade show m-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>{{ $remainingAttempts == 1 ? ' CẢNH BÁO:' : ' LƯU Ý:' }}</strong>
        {!! $warningMessage !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px; min-height: 550px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-journal-text me-2"></i>{{ $quiz->quiz_name ?? ('Đề #' . ($quiz->quid ?? '')) }}
                            </h5>
                            <small class="text-muted">Thí sinh: <strong>{{ $user['name'] }}</strong> ({{ $user['studentid'] }})</small>
                        </div>
                        <span class="badge bg-soft-primary text-primary px-3 py-2" style="background-color: #e0e7ff; font-size: 0.9rem;">
                            Câu hỏi <span id="current-question-num">1</span>/{{ count($questions) }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(isset($questions) && count($questions))
                    <form id="quiz-form" action="{{ route('thitracnghiem.quiz.submit', ['quid' => $quiz->quid]) }}" method="POST">
                        @csrf

                        <div id="questions-container">
                            @foreach($questions as $index => $question)
                            <div class="question-block" id="question-{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Câu {{ $index + 1 }}:</h6>
                                    <div class="question-content fs-5 text-dark">
                                        {!! html_entity_decode($question->question ?? ($question->description ?? '')) !!}
                                    </div>
                                </div>

                                <div class="options-list mt-4">
                                    @php
                                    $answers = $answersByQid[$question->qid] ?? collect();
                                    @endphp

                                    @if(count($answers))
                                    @foreach($answers as $ansIndex => $answer)
                                    <div class="option-item mb-3">
                                        <input type="radio" class="btn-check"
                                            name="question_{{ $question->qid }}"
                                            id="ans_{{ $answer->oid }}"
                                            value="{{ $answer->oid }}"
                                            data-index="{{ $index }}"
                                            required>
                                        <label class="btn btn-outline-light w-100 text-start p-3 shadow-sm border" for="ans_{{ $answer->oid }}">
                                            <div class="d-flex align-items-center">
                                                <span class="option-label me-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-dark fw-bold" style="width: 35px; height: 35px;">
                                                    {{ chr(65 + $ansIndex) }}
                                                </span>
                                                <div class="text-dark fs-6">
                                                    {!! html_entity_decode($answer->q_option ?? '') !!}
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="alert alert-light border">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Chưa có đáp án cho câu hỏi này.
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <button type="button" id="prev-btn" class="btn btn-outline-secondary px-4 py-2 rounded-pill" disabled>
                                <i class="bi bi-arrow-left me-2"></i>Câu trước
                            </button>
                            <button type="button" id="next-btn" class="btn btn-primary px-4 py-2 rounded-pill">
                                Câu tiếp <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-x display-1 text-muted"></i>
                        <p class="mt-3 fs-5">Không có câu hỏi cho đề thi này.</p>
                        <a href="{{ route('thitracnghiem.quiz.show', ['quid' => $quiz->quid]) }}" class="btn btn-primary mt-3">Quay lại</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="bg-primary text-white p-3 text-center">
                        <h6 class="text-uppercase opacity-75 mb-2" style="letter-spacing: 1px; font-size: 0.8rem;">Thời gian còn lại</h6>
                        <div id="timer-display" class="h2 fw-bold mb-0">--:--</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 sticky-top-quiz" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h6 class="mb-0 fw-bold">Danh sách câu hỏi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-center" id="question-map">
                        @foreach($questions as $index => $question)
                        <button type="button"
                            class="btn btn-outline-secondary question-map-item d-flex align-items-center justify-content-center shadow-sm"
                            data-index="{{ $index }}"
                            id="map-item-{{ $index }}"
                            style="width: 40px; height: 40px; border-radius: 8px; font-weight: 600; font-size: 0.85rem;">
                            {{ $index + 1 }}
                        </button>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex flex-column gap-2 small text-muted">
                            <div class="d-flex justify-content-between">
                                <span><span class="d-inline-block bg-primary rounded-circle me-1" style="width:10px; height:10px;"></span> Đang xem</span>
                                <span><span class="d-inline-block bg-success rounded-circle me-1" style="width:10px; height:10px;"></span> Đã làm</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><span class="d-inline-block bg-light border rounded-circle me-1" style="width:10px; height:10px;"></span> Chưa làm</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4 gap-2">
                        <button type="button" id="submit-btn-trigger" class="btn btn-success rounded-pill py-3 fw-bold shadow-sm border-0" style="font-size: 1.1rem; letter-spacing: 0.5px;">
                            <i class="bi bi-check-circle me-2"></i>Nộp bài thi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get data from data attributes - NO BLADE SYNTAX IN JAVASCRIPT
        const container = document.querySelector('[data-quiz-id]');
        const quizId = parseInt(container.dataset.quizId);
        const quizDuration = parseInt(container.dataset.quizDuration);
        const totalQuestions = parseInt(container.dataset.totalQuestions);

        let currentIdx = 0;

        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtnTrigger = document.getElementById('submit-btn-trigger');
        const currentNumSpan = document.getElementById('current-question-num');
        const mapItems = document.querySelectorAll('.question-map-item');
        const questionBlocks = document.querySelectorAll('.question-block');
        const quizForm = document.getElementById('quiz-form');

        // Validate required elements
        if (!prevBtn || !nextBtn || !submitBtnTrigger || !quizForm || questionBlocks.length === 0) {
            console.error('Missing required form elements. Quiz may not load correctly.');
            console.log('prevBtn:', !!prevBtn, 'nextBtn:', !!nextBtn, 'submitBtnTrigger:', !!submitBtnTrigger, 'quizForm:', !!quizForm, 'questionBlocks:', questionBlocks.length);
            return;
        }

        const storageKey = `quiz_answers_${quizId}`;
        const timerKey = `quiz_timer_end_${quizId}`;

        let isAllowingExit = false;

        // Debug warning
        console.log('Checking for swal_warning...');
        const sawlWarningData = @json(session('swal_warning'));
        console.log('swal_warning data:', sawlWarningData);

        @if(session('swal_warning'))
        console.log('Found swal_warning, showing alert');
        Swal.fire({
            icon: '{{ session("swal_warning.icon") }}',
            title: '{{ session("swal_warning.title") }}',
            text: '{{ session("swal_warning.text") }}',
            confirmButtonColor: '#d9534f',
            confirmButtonText: 'Quay lại',
            borderRadius: '15px',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            // Reload page để clear session
            window.location.href = '{{ route("thitracnghiem.quiz.list") }}';
        });
        @else
        console.log('No swal_warning found in session');
        @if(isset($showWarning) && $showWarning)
        Swal.fire({
            title: ' {{ $remainingAttempts == 1 ? "Cảnh báo" : "Lưu ý" }}',
            text: '{{ $warningMessage }}',
            icon: '{{ $remainingAttempts == 1 ? "warning" : "info" }}',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Hiểu rồi',
            borderRadius: '15px'
        });
        @endif
        @endif

        // --- LOGIC ĐIỀU HƯỚNG CÂU HỎI ---
        function updateUI() {
            questionBlocks.forEach((block, idx) => {
                block.style.display = (idx === currentIdx) ? 'block' : 'none';
            });

            prevBtn.disabled = (currentIdx === 0);
            nextBtn.disabled = (currentIdx === totalQuestions - 1);
            if (currentIdx === totalQuestions - 1) {
                nextBtn.classList.add('opacity-50');
            } else {
                nextBtn.classList.remove('opacity-50');
            }

            mapItems.forEach((item, idx) => {
                item.classList.toggle('active', idx === currentIdx);
            });

            currentNumSpan.innerText = currentIdx + 1;
        }

        prevBtn.addEventListener('click', () => {
            if (currentIdx > 0) {
                currentIdx--;
                updateUI();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentIdx < totalQuestions - 1) {
                currentIdx++;
                updateUI();
            }
        });

        mapItems.forEach(item => {
            item.addEventListener('click', () => {
                currentIdx = parseInt(item.getAttribute('data-index'));
                updateUI();
            });
        });

        // --- LOGIC LƯU ĐÁP ÁN (AUTO-SAVE) ---
        function saveAnswers() {
            const formData = new FormData(quizForm);
            const answers = {};
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('question_')) {
                    answers[key] = value;
                }
            }
            localStorage.setItem(storageKey, JSON.stringify(answers));
            updateMapStatus();
        }

        function loadAnswers() {
            const saved = localStorage.getItem(storageKey);
            if (saved) {
                const answers = JSON.parse(saved);
                for (let key in answers) {
                    const input = quizForm.querySelector(`input[name="${key}"][value="${answers[key]}"]`);
                    if (input) {
                        input.checked = true;
                    }
                }
            }
            updateMapStatus();
        }

        function updateMapStatus() {
            const checkedInputs = quizForm.querySelectorAll('input[type="radio"]:checked');
            const answeredIndices = new Set();
            checkedInputs.forEach(input => {
                const idx = input.getAttribute('data-index');
                if (idx !== null) answeredIndices.add(parseInt(idx));
            });

            mapItems.forEach((item, idx) => {
                if (answeredIndices.has(idx)) {
                    item.classList.add('answered');
                } else {
                    item.classList.remove('answered');
                }
            });
        }

        // --- LOGIC XỬ LÝ NỘP BÀI & HỦY BÀI (SWEETALERT2) ---
        function getUnansweredCount() {
            const checkedInputs = quizForm.querySelectorAll('input[type="radio"]:checked');
            return totalQuestions - checkedInputs.length;
        }

        function clearStorages() {
            localStorage.removeItem(storageKey);
            localStorage.removeItem(timerKey);
        }

        submitBtnTrigger.addEventListener('click', function() {
            const unanswered = getUnansweredCount();
            let title = 'Xác nhận nộp bài?';
            let text = 'Bạn có chắc chắn muốn kết thúc bài thi này?';
            let icon = 'question';

            if (unanswered > 0) {
                title = ' Bạn chưa hoàn thành bài thi!';
                text = `Bạn vẫn còn ${unanswered} câu chưa chọn đáp án. Bạn vẫn muốn nộp bài chứ?`;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6c757d',
                confirmButtonText: ' Đồng ý nộp bài',
                cancelButtonText: ' Kiểm tra lại',
                borderRadius: '15px',
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    isAllowingExit = true;
                    clearStorages();
                    quizForm.submit();
                }
            });
        });

        quizForm.addEventListener('change', saveAnswers);

        // --- LOGIC ĐỒNG HỒ ĐẾM NGƯỢC (TIMER) ---
        const quizDurationSeconds = quizDuration * 60;
        let endTime = null;

        try {
            endTime = localStorage.getItem(timerKey);
            if (!endTime) {
                endTime = Date.now() + (quizDurationSeconds * 1000);
                localStorage.setItem(timerKey, endTime);
            } else {
                endTime = parseInt(endTime);
            }
        } catch (e) {
            console.warn('localStorage not available, using in-memory timer:', e.message);
            endTime = Date.now() + (quizDurationSeconds * 1000);
        }

        function startTimer() {
            const timerDisplay = document.getElementById('timer-display');
            if (!timerDisplay) {
                console.error('Timer display element not found');
                return;
            }

            const interval = setInterval(() => {
                const now = Date.now();
                const distance = endTime - now;

                if (distance <= 0) {
                    clearInterval(interval);
                    timerDisplay.innerText = "00:00";

                    Swal.fire({
                        title: ' Hết giờ làm bài!',
                        text: 'Hệ thống đang tự động nộp bài của bạn.',
                        icon: 'info',
                        timer: 3000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        borderRadius: '15px',
                        didOpen: () => {
                            Swal.showLoading();
                            setTimeout(() => {
                                isAllowingExit = true;
                                clearStorages();
                                quizForm.submit();
                            }, 2000);
                        }
                    });
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerDisplay.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (distance < 60 * 1000) {
                    timerDisplay.classList.add('text-danger');
                    timerDisplay.style.animation = 'pulse 0.5s infinite';
                }
            }, 1000);
        }

        const style = document.createElement('style');
        style.textContent = `
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
    `;
        document.head.appendChild(style);

        quizForm.addEventListener('submit', function() {
            isAllowingExit = true;
            clearStorages();
        });

        window.addEventListener('beforeunload', function(e) {
            if (!isAllowingExit) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        if (performance.navigation.type === 1) {
            Swal.fire({
                title: ' Bạn đã rời khỏi bài thi!',
                text: 'Bài thi chưa được hoàn thành và sẽ không được ghi nhận kết quả.',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Đã hiểu',
                background: '#fff',
                borderRadius: '15px',
                backdrop: true
            });
        }

        loadAnswers();
        updateUI();
        startTimer();
    });
</script>
@endsection