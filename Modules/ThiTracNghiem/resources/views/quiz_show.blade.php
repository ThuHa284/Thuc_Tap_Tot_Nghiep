@extends('layouts.master')

@section('title', 'Chi tiết đề thi: ' . ($quiz->quiz_name ?? 'Đề thi'))

@section('content')
<style>
    .main {
        padding-top: 20px !important;
    }

    .detail-card {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        animation: slideUp 0.5s ease-out;
    }

    .detail-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        padding: 40px;
        color: white;
    }

    .detail-body {
        padding: 40px;
        background-color: white;
    }

    .info-box {
        background-color: #f8fafc;
        border-radius: 15px;
        padding: 20px;
        height: 100%;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .info-box:hover {
        background-color: #ffffff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border-color: #2563eb;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 15px;
        background-color: #eef2ff;
        color: #2563eb;
    }

    .btn-start {
        background-color: #10b981;
        border: none;
        border-radius: 12px;
        padding: 15px 30px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-start:hover {
        background-color: #059669;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }

    .description-box {
        border-left: 4px solid #2563eb;
        background-color: #f0f7ff;
        padding: 20px;
        border-radius: 0 12px 12px 0;
        font-style: italic;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('thitracnghiem.quiz.list') }}" class="btn btn-link text-muted text-decoration-none p-0">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách đề thi
        </a>
    </div>

    <div class="card shadow-lg detail-card">
        <div class="detail-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 fw-bold">THÔNG TIN CHI TIẾT</span>
                    <h1 class="fw-bold mb-0 display-6">{{ $quiz->quiz_name ?? ('Đề #' . ($quiz->quid ?? '')) }}</h1>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block text-center bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur">
                        <div class="h2 fw-bold mb-0">{{ $quiz->duration ?? 0 }}</div>
                        <div class="small opacity-75">PHÚT LÀM BÀI</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-body">
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h6 class="fw-bold text-muted small text-uppercase">Thời gian bắt đầu</h6>
                        <p class="mb-0 fw-semibold text-dark">
                            {{ !empty($quiz->start_date) && $quiz->start_date !== '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($quiz->start_date)->format('H:i - d/m/Y') : 'Không giới hạn' }}
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <h6 class="fw-bold text-muted small text-uppercase">Thời gian kết thúc</h6>
                        <p class="mb-0 fw-semibold text-dark">
                            {{ !empty($quiz->end_date) && $quiz->end_date !== '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($quiz->end_date)->format('H:i - d/m/Y') : 'Không giới hạn' }}
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h6 class="fw-bold text-muted small text-uppercase">Số lần làm bài tối đa</h6>
                        <p class="mb-0 fw-semibold text-dark">
                            {{ $quiz->maximum_attempts ?? 1 }} lần nộp bài
                        </p>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Mô tả bài thi</h5>
            <div class="description-box mb-5 text-muted">
                {!! html_entity_decode($quiz->description ?? 'Không có mô tả chi tiết cho đề thi này.') !!}
            </div>

            <div class="alert alert-warning border-0 rounded-4 d-flex align-items-center p-4 mb-5">
                <i class="bi bi-exclamation-triangle-fill fs-3 me-4"></i>
                <div>
                    <h6 class="fw-bold mb-1">Lưu ý quan trọng:</h6>
                    <p class="mb-0 small">Sau khi nhấn nút "Bắt đầu làm bài", thời gian sẽ được tính ngay lập tức. Hãy đảm bảo kết nối mạng ổn định và bạn có đủ thời gian để hoàn thành bài thi.</p>
                </div>
            </div>

            <div class="d-grid gap-3 d-md-flex justify-content-center pt-2">
                @php
                $isExpired = $quiz->end_date && $quiz->end_date !== '0000-00-00 00:00:00' && now() > \Carbon\Carbon::parse($quiz->end_date);
                @endphp

                @if($isExpired)
                <button class="btn btn-danger btn-start px-5 shadow-sm border-0 text-white" disabled>
                    <i class="bi bi-calendar-x me-2"></i> Bài thi đã hết hạn
                </button>
                @else
                <a href="{{ route('thitracnghiem.quiz.start', ['quid' => $quiz->quid]) }}" class="btn btn-success btn-start px-5 shadow-sm border-0 text-white">
                    <i class="bi bi-play-circle me-2"></i> Bắt đầu làm bài ngay
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection