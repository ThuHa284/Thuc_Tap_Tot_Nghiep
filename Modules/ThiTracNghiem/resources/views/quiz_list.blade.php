@extends('layouts.master')

@section('title', 'Danh sách đề thi')

@section('content')
<style>
    .main {
        padding-top: 20px !important;
    }

    .quiz-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: white;
    }

    .quiz-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .quiz-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
    }

    .quiz-icon-box {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        color: #2563eb;
        font-size: 3rem;
    }

    .btn-view {
        border-radius: 10px;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.2s;
    }

    .btn-view:hover {
        background-color: #2563eb;
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .section-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 60px;
        height: 4px;
        background-color: #2563eb;
        border-radius: 2px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-card {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>

<div class="container py-4">
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        <strong>Lỗi:</strong>
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark section-title">Danh sách đề thi</h2>
            <!-- <p class="text-muted">Chọn một bài thi để xem thông tin và bắt đầu làm bài</p> -->
        </div>
        <a href="{{ route('thitracnghiem.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    @if(isset($quizzes) && count($quizzes))
    <div class="row g-4">
        @foreach($quizzes as $index => $quiz)
        @php
        $isExpired = $quiz->end_date && $quiz->end_date !== '0000-00-00 00:00:00' && now() > \Carbon\Carbon::parse($quiz->end_date);
        @endphp
        <div class="col-md-6 col-lg-4 animate-card" style="animation-delay: {{ $index * 0.1 }}s">
            <div class="card shadow-sm quiz-card h-100 position-relative {{ $isExpired ? 'opacity-75' : '' }}">
                @if($isExpired)
                <span class="badge bg-danger quiz-badge rounded-pill px-3 py-2 shadow-sm">
                    <i class="bi bi-calendar-x me-1"></i> Hết hạn
                </span>
                @else
                <span class="badge bg-primary quiz-badge rounded-pill px-3 py-2 shadow-sm">
                    <i class="bi bi-clock me-1"></i> {{ $quiz->duration ?? 0 }} Phút
                </span>
                @endif

                <div class="quiz-icon-box {{ $isExpired ? 'opacity-50' : '' }}">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3 line-clamp-2" style="min-height: 3rem;">
                        {{ $quiz->quiz_name ?? ('Đề #' . ($quiz->quid ?? '')) }}
                    </h5>

                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-calendar3 me-2"></i>
                            <span>Hết hạn: {{ !empty($quiz->end_date) && $quiz->end_date !== '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($quiz->end_date)->format('d/m/Y') : 'Không giới hạn' }}</span>
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-clipboard-pulse me-2"></i>
                            <span>Số lần thi tối đa: {{ $quiz->maximum_attempts ?? 1 }} lần</span>
                        </div>
                        @if($isExpired)
                        <div class="d-flex align-items-center text-danger small fw-bold">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <span>Bài thi đã hết hạn</span>
                        </div>
                        @endif
                    </div>

                    <div class="d-grid mt-auto">
                        @if($isExpired)
                        <button class="btn btn-outline-danger btn-view" disabled>
                            Hết hạn <i class="bi bi-lock ms-1"></i>
                        </button>
                        @else
                        <a href="{{ route('thitracnghiem.quiz.show', ['quid' => $quiz->quid]) }}" class="btn btn-outline-primary btn-view">
                            Chi tiết <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
        <i class="bi bi-clipboard-x display-1 text-muted opacity-25"></i>
        <h4 class="mt-3 fw-bold text-dark">Chưa có đề thi nào!</h4>
        <p class="text-muted">Hiện tại không có đề thi nào khả dụng cho bạn. Vui lòng quay lại sau.</p>
        <a href="{{ route('thitracnghiem.index') }}" class="btn btn-primary rounded-pill px-4 mt-3">Quay lại trang chủ</a>
    </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection