@extends('layouts.master') 

@section('content')
{{-- Menu ngang --}}
<div class="row">
    <div class="col-12">
        @include('tintuc::components.tintuc-menu')
    </div>
</div>

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white shadow-sm rounded-3 px-3 py-2 mb-0">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary"><i class="fas fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('loaitin.index') }}" class="text-decoration-none text-secondary">Loại Tin</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Thêm Mới</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-header bg-gradient text-white border-0 py-4">
                <h4 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Thêm Loại Tin Mới</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('loaitin.store') }}" method="POST">
                    @csrf 
                    <div class="form-floating mb-4">
                        <input type="text" name="name" class="form-control rounded-3 border-0 shadow-sm @error('name') is-invalid @enderror" 
                               id="name" placeholder="Tên loại tin" value="{{ old('name') }}" required>
                        <label for="name"><i class="fas fa-folder me-2 text-muted"></i>Tên Loại Tin</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('loaitin.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-1"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-save me-1"></i> Lưu Danh Mục
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
        color: white;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
        color: white;
    }
    .form-floating > .form-control:focus,
    .form-floating > .form-control:not(:placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    .form-floating > label {
        padding: 1rem 1rem;
    }
</style>
@endsection
