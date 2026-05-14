@extends('layouts.master')

@section('title', 'Thi Trắc Nghiệm - Dashboard')

@section('content')
<style>
    .main {
        padding-top: 20px !important;
    }

    .dashboard-card {
        border-radius: 15px;
        border: none;
        transition: all 0.3s ease;
    }

    .profile-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        overflow: hidden;
    }

    .profile-header {
        background-color: #2563eb;
        height: 80px;
        margin-bottom: 50px;
        position: relative;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        bottom: -45px;
        left: 50%;
        transform: translateX(-50%);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        font-size: 2.5rem;
        color: #2563eb;
        border: 4px solid white;
    }

    .action-card {
        cursor: pointer;
        height: 100%;
        border: 1px solid #e2e8f0;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        border-color: #2563eb;
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .bg-soft-blue {
        background-color: #eef2ff;
        color: #2563eb;
    }

    .bg-soft-green {
        background-color: #ecfdf5;
        color: #10b981;
    }

    .bg-soft-orange {
        background-color: #fff7ed;
        color: #f59e0b;
    }

    .welcome-banner {
        background-color: #eef2ff;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        border-left: 5px solid #2563eb;
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

    .animate-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>

<div class="container py-4">
    <div class="row g-4 animate-in">
        <!-- Cột trái: Thông tin cá nhân -->
        <!-- <div class="col-lg-4">
            <div class="card shadow-sm dashboard-card profile-card h-100">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                <div class="card-body pt-2 text-center">
                    <h4 class="fw-bold mb-1">{{ $user['name'] ?: 'Sinh viên' }}</h4>
                    <p class="text-muted small mb-4">Mã số: {{ $user['studentid'] ?? 'N/A' }}</p>

                    <div class="text-start px-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="bi bi-envelope text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Email</div>
                                <div class="fw-semibold small text-truncate" style="max-width: 180px;">{{ $user['email'] ?? 'Chưa cập nhật' }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="bi bi-building text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Lớp & Ngành</div>
                                <div class="fw-semibold small">{{ $user['classid'] ?? 'N/A' }} - {{ $user['facultyid'] ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Hành động chính -->
        <div class="col-12">
            @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card dashboard-card action-card p-4" onclick="window.location.href='{{ route('thitracnghiem.quiz.list') }}'">
                        <div class="action-icon bg-soft-blue">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <h5 class="fw-bold">Đề thi</h5>
                        <p class="text-muted small mb-0">Xem danh sách các bài thi đang diễn ra và bắt đầu làm bài ngay.</p>
                        <div class="mt-3 text-primary fw-semibold small">Truy cập ngay <i class="bi bi-arrow-right ms-1"></i></div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card dashboard-card action-card p-4" onclick="window.location.href='{{ route('thitracnghiem.history') }}'">
                        <div class="action-icon bg-soft-green">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5 class="fw-bold">Lịch sử</h5>
                        <p class="text-muted small mb-0">Xem lại các bài thi đã làm, điểm số và chi tiết các lần thi trước.</p>
                        <div class="mt-3 text-success fw-semibold small">Xem chi tiết <i class="bi bi-arrow-right ms-1"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection