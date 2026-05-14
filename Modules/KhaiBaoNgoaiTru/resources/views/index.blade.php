@extends('layouts.master')

@section('title', 'Khai Báo')

@section('content')
{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Khai Báo</li>
    </ol>
</nav>

<div class="row g-4">
    {{-- Khai báo ngoại trú --}}
    <div class="col-md-12">
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('khai_bao_ngoai_tru.index') }}" class="text-decoration-none">
            @else
                <a href="{{ route('sinh_vien.index') }}" class="text-decoration-none">
            @endif
        @else
            <a href="{{ route('login') }}" class="text-decoration-none">
        @endauth
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden h-100 hover-lift">
                <div class="card-body text-center p-5">
                    <div class="bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Khai Báo Ngoại Trú</h4>
                    <p class="text-muted mb-0 small">Quản lý thông tin sinh viên ở ngoại trú</p>
                </div>
                <div class="card-footer bg-white border-0 text-center pb-4">
                    <span class="badge bg-primary rounded-pill px-4">
                        <i class="fas fa-arrow-right me-1"></i> Truy cập
                    </span>
                </div>
            </div>
        </a>
    </div>
</div>


<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,0.15) !important;
}
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
