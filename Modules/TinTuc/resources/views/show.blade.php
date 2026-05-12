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
        <li class="breadcrumb-item"><a href="{{ route('tintuc.index') }}" class="text-decoration-none text-secondary">Tin Tức</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Chi tiết</li>
    </ol>
</nav>

{{-- Hero --}}
@php
    $declarationText = \Illuminate\Support\Str::lower($tinTuc->title . ' ' . $tinTuc->content);
    $isDeclarationPost = (bool) $tinTuc->is_khai_bao_noi_tru || \Illuminate\Support\Str::contains($declarationText, 'khai báo nội trú') || \Illuminate\Support\Str::contains($declarationText, 'khai bao noi tru');
    $declarationOpen = $isDeclarationPost && $tinTuc->khai_bao_start_at && $tinTuc->khai_bao_end_at && now()->between($tinTuc->khai_bao_start_at, $tinTuc->khai_bao_end_at);
@endphp

<div class="text-center mb-4">
    <span class="badge bg-primary rounded-pill px-4 py-2 mb-3">
        <i class="fas fa-tag me-1"></i>{{ $tinTuc->loaitin->name ?? 'Chưa phân loại' }}
    </span>
    @if($isDeclarationPost)
    <span class="badge bg-success rounded-pill px-4 py-2 mb-3 ms-2">
        <i class="fas fa-home me-1"></i>Khai báo nội trú
    </span>
    @endif
    <h1 class="display-6 fw-bold text-dark mb-3">{{ $tinTuc->title }}</h1>
    <div class="d-flex justify-content-center gap-4 text-muted small flex-wrap">
        <span><i class="far fa-calendar-alt me-1"></i>
            {{ $tinTuc->date1 ? \Carbon\Carbon::parse($tinTuc->date1)->format('d/m/Y') : 'N/A' }}
        </span>
        <span><i class="far fa-clock me-1"></i>
            {{ $tinTuc->created_at ? \Carbon\Carbon::parse($tinTuc->created_at)->format('H:i - d/m/Y') : '' }}
        </span>
        @if(auth()->check() && auth()->user()->isAdmin())
        <span>
            <i class="fas fa-toggle-on me-1"></i>
            @if($tinTuc->status == 1) 
                <span class="badge bg-success rounded-pill">Công khai</span> 
            @else 
                <span class="badge bg-secondary rounded-pill">Ẩn</span> 
            @endif
        </span>
        @endif
    </div>
</div>

{{-- Nút hành động cho Admin --}}
@if(auth()->check() && auth()->user()->isAdmin())
<div class="d-flex justify-content-end gap-2 mb-4">
    <a href="{{ route('tintuc.edit', $tinTuc->id) }}" class="btn btn-warning rounded-pill px-4">
        <i class="fas fa-edit me-1"></i> Sửa
    </a>
    <form action="{{ route('tintuc.destroy', $tinTuc->id) }}" method="POST" class="d-inline" 
          onsubmit="return confirm('Xóa tin tức này?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger rounded-pill px-4">
            <i class="fas fa-trash me-1"></i> Xóa
        </button>
    </form>
</div>
@endif

{{-- Card chính --}}
<div class="card border-0 shadow-lg rounded-3 overflow-hidden mb-4">
    @if($isDeclarationPost)
    <div class="border-bottom bg-white p-3 p-md-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="text-muted small mb-1">Kỳ khai báo nội trú</div>
                <div class="fw-semibold text-dark">
                    <i class="fas fa-calendar-check me-1 text-success"></i>
                    {{ $tinTuc->khai_bao_ky ? 'Kỳ ' . $tinTuc->khai_bao_ky . ' - ' : '' }}
                    {{ $tinTuc->khai_bao_start_at ? \Carbon\Carbon::parse($tinTuc->khai_bao_start_at)->format('d/m/Y H:i') : 'Chưa đặt thời gian' }}
                    <span class="text-muted">đến</span>
                    {{ $tinTuc->khai_bao_end_at ? \Carbon\Carbon::parse($tinTuc->khai_bao_end_at)->format('d/m/Y H:i') : 'Chưa đặt thời gian' }}
                </div>
            </div>
            @if($declarationOpen && !auth()->user()->isAdmin())
            <a href="{{ route('khai_bao_noi_tru.kich_hoat', $tinTuc->id) }}" class="btn btn-success rounded-pill px-4">
                <i class="fas fa-arrow-right me-1"></i> Khai báo tại đây
            </a>
            @elseif(!$declarationOpen)
            <span class="badge bg-secondary rounded-pill px-3 py-2">Chưa mở hoặc đã hết hạn</span>
            @endif
        </div>
    </div>
    @endif

    {{-- Hình ảnh --}}
    @if($tinTuc->img)
    <div class="text-center bg-light py-4" style="background: linear-gradient(135deg, #667eea22 0%, #764ba222 100%);">
        <div class="d-inline-block rounded-3 overflow-hidden shadow-lg">
            <img src="{{ asset($tinTuc->img) }}" class="img-fluid rounded-3" style="max-height: 450px; max-width: 100%;" alt="{{ $tinTuc->title }}">
        </div>
    </div>
    @endif

    @php
        $attachmentItems = $tinTuc->attachment_items;
    @endphp
    @if(!empty($attachmentItems))
    <div class="border-top bg-white p-3 p-md-4">
        <div class="d-grid gap-2">
            @foreach($attachmentItems as $index => $attachmentItem)
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 border rounded-3 p-3 bg-light">
                    <div>
                        <div class="text-muted small mb-1">Tệp đính kèm</div>
                        <div class="fw-semibold text-dark">
                            {{ $attachmentItem['label'] ?? 'Tệp đính kèm' }}
                        </div>
                    </div>
                    @php
                        $filePath = $attachmentItem['path'];
                        // Main file (attachment_path) uses download route, extra files use downloadFile
                        $isMainFile = ($index === 0 && !empty($tinTuc->attachment_name));
                        $downloadUrl = $isMainFile
                            ? route('tintuc.download', $tinTuc->id)
                            : route('tintuc.downloadFile', ['tin_tuc_id' => $tinTuc->id, 'path' => $filePath]);
                    @endphp
                    <a href="{{ $downloadUrl }}" class="fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-2">
                        <i class="fas fa-paperclip me-1 text-primary"></i>
                        Xem chi tiết
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Nội dung --}}
    <div class="card-body p-4 p-md-5">
        <div class="content-area lh-lg" style="font-size: 1.1rem;">
            {!! $tinTuc->content !!}
        </div>
    </div>
</div>

{{-- Footer --}}
<div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('tintuc.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
    </a>
    @if(!auth()->check() || !auth()->user()->isAdmin())
    <a href="{{ route('tintuc.index') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-list me-1"></i> Tin khác
    </a>
    @endif
</div>

<style>
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
        color: white;
    }
    .content-area img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        padding: 8px;
        display: block;
        margin: 1.5rem auto;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .content-area p {
        margin-bottom: 1.2rem;
        text-align: justify;
    }
    .content-area h1, .content-area h2, .content-area h3, 
    .content-area h4, .content-area h5, .content-area h6 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
        color: #333;
    }
    .content-area ul, .content-area ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .content-area li {
        margin-bottom: 0.5rem;
    }
    .content-area blockquote {
        border-left: 4px solid;
        border-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%) 1;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #555;
        background: #f8f9fa;
        border-radius: 0 8px 8px 0;
    }
    .content-area pre {
        background: #2d2d2d;
        color: #f8f8f2;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
    }
    .content-area code {
        background: #f4f4f4;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-size: 0.9em;
    }
</style>
@endsection
