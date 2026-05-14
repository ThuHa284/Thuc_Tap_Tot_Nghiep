@extends('layouts.master')

@section('title', 'Sửa kỳ khai báo ngoại trú')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('khai_bao_ngoai_tru.index') }}" class="text-decoration-none text-secondary">Khai báo ngoại trú</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Sửa kỳ khai báo</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-header bg-gradient text-white border-0 py-4">
                <h4 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Sửa kỳ khai báo ngoại trú</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('khai_bao_ngoai_tru.ky_update', $tinTuc->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">
                            <i class="fas fa-heading me-1 text-primary"></i> Tiêu đề kỳ khai báo
                        </label>
                        <input type="text" name="title" class="form-control rounded-3 @error('title') is-invalid @enderror" 
                               id="title" placeholder="VD: Kỳ khai báo ngoại trú HK1 2025-2026" 
                               value="{{ old('title', $tinTuc->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label fw-semibold">
                            <i class="fas fa-file-alt me-1 text-primary"></i> Nội dung thông báo
                        </label>
                        <textarea name="content" class="form-control rounded-3 @error('content') is-invalid @enderror" 
                                  id="content" rows="5" placeholder="Nhập nội dung thông báo cho sinh viên..."
                                  required>{{ old('content', $tinTuc->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="khai_bao_start_at" class="form-label fw-semibold">
                                <i class="fas fa-clock me-1 text-primary"></i> Thời gian bắt đầu
                            </label>
                            <input type="datetime-local" name="khai_bao_start_at" 
                                   class="form-control rounded-3 @error('khai_bao_start_at') is-invalid @enderror" 
                                   id="khai_bao_start_at" value="{{ old('khai_bao_start_at', $tinTuc->khai_bao_start_at ? \Carbon\Carbon::parse($tinTuc->khai_bao_start_at)->format('Y-m-d\TH:i') : '') }}" required>
                            @error('khai_bao_start_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="khai_bao_end_at" class="form-label fw-semibold">
                                <i class="fas fa-clock me-1 text-primary"></i> Thời gian kết thúc
                            </label>
                            <input type="datetime-local" name="khai_bao_end_at" 
                                   class="form-control rounded-3 @error('khai_bao_end_at') is-invalid @enderror" 
                                   id="khai_bao_end_at" value="{{ old('khai_bao_end_at', $tinTuc->khai_bao_end_at ? \Carbon\Carbon::parse($tinTuc->khai_bao_end_at)->format('Y-m-d\TH:i') : '') }}" required>
                            @error('khai_bao_end_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('khai_bao_ngoai_tru.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-1"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
        color: white;
    }
</style>
@endsection
