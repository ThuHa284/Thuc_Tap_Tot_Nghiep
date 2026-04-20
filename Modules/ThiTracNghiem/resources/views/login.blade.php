@extends('layouts.master')

@section('title', 'Đăng nhập Thi Trắc Nghiệm')

@section('content')
<style>
    /* 1. Tối ưu không gian và hiệu ứng */
    .main { 
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 80vh;
        padding-top: 20px !important; 
    }
    
    .login-card {
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        border: none;
        overflow: hidden;
        animation: fadeIn 0.5s ease-in-out;
        max-width: 450px;
        width: 100%;
    }

    .login-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
    }

    .login-body {
        padding: 40px;
        background-color: white;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px 15px 12px 45px;
        border: 1px solid #e2e8f0;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        color: #94a3b8;
    }

    .btn-login {
        background-color: #2563eb;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
    }

    .btn-login:hover {
        background-color: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        color: white;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="login-card mx-auto">
    <div class="login-header">
        <i class="bi bi-person-circle display-4 mb-3"></i>
        <h3 class="fw-bold mb-0">Hệ Thống Thi</h3>
        <p class="opacity-75 mb-0">Vui lòng đăng nhập để bắt đầu</p>
    </div>

    <div class="login-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('thitracnghiem.login') }}">
            @csrf
            
            <div class="mb-4">
                <label class="form-label fw-semibold text-dark small ms-1">MSSV hoặc Email</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" 
                        class="form-control @error('username') is-invalid @enderror" 
                        value="{{ old('username') }}" 
                        placeholder="Nhập mã số hoặc email"
                        required>
                </div>
                @error('username')
                    <div class="invalid-feedback d-block mt-2 small ms-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark small ms-1">Mật khẩu</label>
                <div class="position-relative">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="••••••••"
                        required>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block mt-2 small ms-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 mt-2">
                <button type="submit" class="btn btn-primary btn-login">
                    Đăng Nhập <i class="bi bi-arrow-right-short ms-1"></i>
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('thitracnghiem.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-house-door me-1"></i> Quay lại trang chủ
                </a>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection