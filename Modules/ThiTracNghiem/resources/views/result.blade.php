@extends('layouts.master')

@section('title', 'Kết quả bài thi')

@section('content')

@php
$colorHex = '#10b981'; // Green for Success
$bgSoft = '#ecfdf5';

if ($finalScore >= 8) {
$color = 'success';
$colorHex = '#10b981';
$bgSoft = '#ecfdf5';
$message = 'XUẤT SẮC!';
$subMessage = 'Chúc mừng bạn đã hoàn thành bài thi với kết quả tuyệt vời.';
$icon = 'bi-trophy-fill';
} elseif ($finalScore >= 5) {
$color = 'warning';
$colorHex = '#f59e0b';
$bgSoft = '#fff7ed';
$message = 'ĐẠT YÊU CẦU';
$subMessage = 'Bạn đã vượt qua bài thi. Hãy tiếp tục cố gắng ở các bài sau nhé.';
$icon = 'bi-check-circle-fill';
} else {
$color = 'danger';
$colorHex = '#ef4444';
$bgSoft = '#fef2f2';
$message = 'CHƯA ĐẠT';
$subMessage = 'Rất tiếc, kết quả này chưa đủ để vượt qua. Hãy ôn tập thêm và thử lại.';
$icon = 'bi-exclamation-octagon-fill';
}
@endphp

<style>
    .main {
        padding-top: 20px !important;
    }

    .result-card {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        max-width: 700px;
        width: 100%;
        margin: auto;
    }

    .result-header {
        background-color: {
                {
                $colorHex
            }
        }

        ;
        padding: 50px 20px;
        text-align: center;
        color: white;
    }

    .result-body {
        padding: 40px;
        background-color: white;
    }

    .score-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;

        border: 8px solid {
                {
                $bgSoft
            }
        }

        ;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: -75px auto 20px;
        background-color: white;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 10;
    }

    .score-value {
        font-size: 3.5rem;
        font-weight: 800;

        color: {
                {
                $colorHex
            }
        }

        ;
        line-height: 1;
    }

    .score-label {
        font-size: 0.8rem;
        text-uppercase: uppercase;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 1px;
    }

    .stat-item {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }

    .btn-action {
        border-radius: 12px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .confetti-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<div class="container py-4">
    <div class="result-card shadow-lg">
        <div class="result-body text-center">
            <div class="row g-3 mb-5 mt-2">
                <div class="col-12">
                    <div class="stat-item">
                        <div class="small fw-semibold text-muted mb-1">Đã hoàn thành</div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-3 d-sm-flex justify-content-center">
                <a href="{{ route('thitracnghiem.quiz.start', ['quid' => $quid]) }}" class="btn btn-primary btn-action text-white">
                    <i class="bi bi-arrow-repeat me-2"></i> Làm lại đề này
                </a>
                <a href="{{ route('thitracnghiem.quiz.list') }}" class="btn btn-outline-primary btn-action">
                    <i class="bi bi-list-ul me-2"></i> Danh sách đề khác
                </a>
                <a href="{{ route('thitracnghiem.index') }}" class="btn btn-outline-secondary btn-action">
                    <i class="bi bi-house me-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection