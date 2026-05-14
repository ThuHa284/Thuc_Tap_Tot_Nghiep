@extends('layouts.master')

@section('content')
<style>
    /* Reading Progress Bar */
    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        z-index: 9999;
        transition: width 0.1s ease;
    }

    /* Page Title Bar */
    .page-title-bar {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 15px 0;
        margin-bottom: 0;
    }

    /* Breadcrumb */
    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin-bottom: 0;
    }

    .breadcrumb-custom .breadcrumb-item a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        font-size: 0.9rem;
    }

    .breadcrumb-custom .breadcrumb-item a:hover {
        color: white;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: white;
        font-weight: 500;
    }

    .breadcrumb-custom .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255,255,255,0.5);
    }

    /* Main Layout - Two Column */
    .article-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
    }

    .article-layout > * {
        min-width: 0;
    }

    /* Article Hero Section */
    .article-hero-section {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 40px 0 0 0;
    }

    .article-hero-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Hero Image */
    .article-hero {
        position: relative;
        width: 100%;
        height: 500px;
        border-radius: 16px 16px 0 0;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .article-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .article-hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 40px;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
    }

    .article-hero-overlay .article-category {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 18px;
        width: fit-content;
    }

    .article-hero-overlay .article-category.khai-bao {
        background: #11998e;
        color: white;
    }

    .article-hero-overlay .article-category.tin-tuc {
        background: #1976d2;
        color: white;
    }

    .article-hero-overlay .article-category.thong-bao {
        background: #e65100;
        color: white;
    }

    .article-hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        line-height: 1.2;
        margin-bottom: 20px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        max-width: 900px;
    }

    .article-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        color: rgba(255,255,255,0.9);
    }

    .article-hero-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1rem;
    }

    .article-hero-meta-item i {
        color: #667eea;
    }

    /* Topic Colors */
    .topic-khai-bao { --topic-color: #11998e; }
    .topic-tin-tuc { --topic-color: #1976d2; }
    .topic-thong-bao { --topic-color: #e65100; }

    /* Main Content Area */
    .article-main-wrapper {
        padding: 30px 0;
        background: #f8f9fa;
    }

    .article-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }

    .article-main {
        padding: 40px 50px 40px 50px;
        min-width: 0;
    }

    /* Article Content */
    .article-content-body {
        padding-left: 10px;
        max-width: 100%;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    /* Article Meta Bar */
    .article-meta-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding-bottom: 20px;
        margin-bottom: 25px;
        border-bottom: 1px solid #eee;
    }

    .article-meta-left {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 20px;
    }

    .article-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #888;
        font-size: 0.9rem;
    }

    .article-meta-item i {
        color: #999;
        font-size: 0.85rem;
    }

    /* Share Bar */
    .share-bar {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .share-label {
        font-size: 0.9rem;
        color: #888;
        font-weight: 500;
    }

    .share-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: none;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        font-size: 0.85rem;
    }

    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: white;
    }

    .share-btn.facebook { background: #3b5998; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.linkedin { background: #0077b5; }
    .share-btn.copy { background: #6c757d; }

    /* Article Content */
    .article-content-body {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #333;
    }

    .article-content-body p {
        margin-bottom: 1.5rem;
        max-width: 100%;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    .article-content-body img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 2rem 0;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .article-content-body h1, 
    .article-content-body h2, 
    .article-content-body h3, 
    .article-content-body h4, 
    .article-content-body h5, 
    .article-content-body h6 {
        color: #1a1a2e;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
        line-height: 1.3;
        max-width: 100%;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    .article-content-body h2 { font-size: 1.7rem; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .article-content-body h3 { font-size: 1.4rem; }

    .article-content-body ul, 
    .article-content-body ol {
        padding-left: 1.8rem;
        margin-bottom: 1.5rem;
        max-width: 100%;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    .article-content-body li {
        margin-bottom: 0.8rem;
        max-width: 100%;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    .article-content-body table {
        max-width: 100%;
        overflow-x: auto;
        display: block;
    }

    .article-content-body pre,
    .article-content-body code {
        max-width: 100%;
    }

    .article-content-body blockquote {
        border-left: 5px solid #667eea;
        padding: 25px 30px;
        margin: 2rem 0;
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
        border-radius: 0 16px 16px 0;
        font-style: italic;
        font-size: 1.2rem;
        color: #444;
        position: relative;
    }

    .article-content-body blockquote::before {
        content: '"';
        font-size: 5rem;
        color: #667eea;
        opacity: 0.2;
        position: absolute;
        top: -15px;
        left: 20px;
        font-family: Georgia, serif;
        line-height: 1;
    }

    /* Key Points Box */
    .key-points-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 16px;
        margin: 2rem 0;
    }

    .key-points-box h4 {
        color: white !important;
        margin-top: 0 !important;
        margin-bottom: 15px;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .key-points-box ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }

    .key-points-box li {
        margin-bottom: 0.6rem;
    }

    /* Article Tags */
    .article-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 1px solid #eee;
    }

    .tag-item {
        padding: 8px 16px;
        background: #f0f2f5;
        color: #666;
        border-radius: 25px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        font-weight: 500;
    }

    .tag-item:hover {
        background: #667eea;
        color: white;
    }

    /* Article Attachments */
    .article-attachments {
        background: #f8f9fa;
        padding: 25px 30px;
        border-radius: 16px;
        margin-top: 30px;
        border: 1px solid #e8e8e8;
    }

    .attachments-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e0e0e0;
    }

    .attachments-title i {
        color: #667eea;
    }

    .attachment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 18px;
        background: white;
        border-radius: 12px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .attachment-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .attachment-item:last-child {
        margin-bottom: 0;
    }

    .attachment-info {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
        min-width: 0;
    }

    .attachment-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .attachment-name {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 0.95rem;
        line-height: 1.4;
    }

    .attachment-label {
        font-size: 0.8rem;
        color: #888;
        margin-top: 2px;
    }

    .attachment-download {
        padding: 10px 18px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: opacity 0.2s, transform 0.2s;
        flex-shrink: 0;
    }

    .attachment-download:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        color: white;
    }

    /* Article Footer */
    .article-footer {
        padding: 25px 30px;
        background: #fafafa;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: white;
        color: #1a1a2e;
        border: 2px solid #ddd;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #f5f5f5;
        border-color: #667eea;
        color: #667eea;
    }

    .btn-khai-bao {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s, transform 0.2s;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
    }

    .btn-khai-bao:hover {
        opacity: 0.95;
        transform: translateY(-2px);
        color: white;
    }

    /* Admin Actions */
    .admin-actions {
        display: flex;
        gap: 12px;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #ffc107;
        color: #000;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-edit:hover {
        background: #e0a800;
        color: #000;
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #c82333;
        color: white;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-badge.active {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.hidden {
        background: #e2e3e5;
        color: #383d41;
    }

    /* Sidebar */
    .article-sidebar {
        position: sticky;
        top: 20px;
    }

    /* Related Articles Card */
    .sidebar-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .sidebar-card-header {
        padding: 18px 22px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-card-header i {
        color: #667eea;
        font-size: 1.2rem;
    }

    .sidebar-card-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a2e;
    }

    .sidebar-card-body {
        padding: 15px;
    }

    /* Related Article Item */
    .related-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        border-radius: 12px;
        text-decoration: none;
        transition: background 0.2s;
        margin-bottom: 10px;
    }

    .related-item:last-child {
        margin-bottom: 0;
    }

    .related-item:hover {
        background: #f8f9fa;
    }

    .related-item-img {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        overflow: hidden;
        flex-shrink: 0;
    }

    .related-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-item-content {
        flex: 1;
        min-width: 0;
    }

    .related-item-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a2e;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .related-item-date {
        font-size: 0.8rem;
        color: #888;
    }

    /* Newsletter Box */
    .newsletter-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 25px;
        color: white;
        text-align: center;
    }

    .newsletter-box i {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .newsletter-box h4 {
        margin: 0 0 10px 0;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .newsletter-box p {
        margin: 0 0 20px 0;
        font-size: 0.9rem;
        opacity: 0.9;
        line-height: 1.5;
    }

    .newsletter-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .newsletter-form input {
        padding: 12px 15px;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .newsletter-form button {
        padding: 12px 20px;
        background: #1a1a2e;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .newsletter-form button:hover {
        background: #0a0a15;
    }

    /* Topics Box */
    .topics-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 15px;
    }

    .topic-tag {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: #f8f9fa;
        color: #333;
        border-radius: 10px;
        font-size: 0.9rem;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        border-left: 4px solid transparent;
    }

    .topic-tag:hover {
        transform: translateX(5px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .topic-tag i {
        font-size: 1rem;
        width: 20px;
        text-align: center;
    }

    .topic-tag.khai-bao { border-left-color: #11998e; }
    .topic-tag.khai-bao:hover { background: #e8f5f3; color: #11998e; }

    .topic-tag.tin-tuc { border-left-color: #1976d2; }
    .topic-tag.tin-tuc:hover { background: #e3f2fd; color: #1976d2; }

    .topic-tag.thong-bao { border-left-color: #e65100; }
    .topic-tag.thong-bao:hover { background: #fff3e0; color: #e65100; }

    /* Responsive */
    @media (max-width: 1024px) {
        .article-layout {
            grid-template-columns: 1fr;
        }

        .article-sidebar {
            position: static;
        }

        .article-hero {
            height: 350px;
        }

        .article-hero-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 768px) {
        .article-hero {
            height: 250px;
            border-radius: 0;
        }

        .article-hero-overlay {
            padding: 20px;
        }

        .article-hero-title {
            font-size: 1.4rem;
        }

        .article-meta-bar {
            flex-direction: column;
            align-items: flex-start;
        }

        .article-main {
            padding: 25px 20px;
        }

        .article-content-body {
            font-size: 1rem;
        }

        .share-label {
            display: none;
        }
    }
</style>

{{-- Reading Progress Bar --}}
<div class="reading-progress" id="readingProgress"></div>

{{-- Hero Section --}}
<section class="article-hero-section">
    <div class="article-hero-container">
        <nav aria-label="breadcrumb" class="breadcrumb-custom mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('tintuc.index') }}">Tin Tức</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>

        @php
            $imgSrc = $tinTuc->img ? (str_starts_with($tinTuc->img, 'http') ? $tinTuc->img : asset($tinTuc->img)) : null;
            $topicClass = $tinTuc->is_khai_bao_noi_tru ? 'khai-bao' : (($tinTuc->loaitin && str_contains(strtolower($tinTuc->loaitin->name ?? ''), 'thông báo')) ? 'thong-bao' : 'tin-tuc');
        @endphp
        
        <div class="article-hero">
            @if($imgSrc)
            <img src="{{ $imgSrc }}" alt="{{ $tinTuc->title }}">
            @else
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-newspaper" style="font-size:5rem;color:rgba(255,255,255,0.3);"></i>
            </div>
            @endif
            <div class="article-hero-overlay">
                <span class="article-category {{ $topicClass }}">
                    @if($tinTuc->is_khai_bao_noi_tru)
                    <i class="fas fa-home"></i> Khai báo ngoại trú
                    @else
                    <i class="fas fa-tag"></i> {{ $tinTuc->loaitin->name ?? 'Tin tức' }}
                    @endif
                </span>
                <h1 class="article-hero-title">{{ $tinTuc->title }}</h1>
                <div class="article-hero-meta">
                    <div class="article-hero-meta-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Ban Biên tập</span>
                    </div>
                    <div class="article-hero-meta-item">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ $tinTuc->created_at ? \Carbon\Carbon::parse($tinTuc->created_at)->format('d/m/Y') : '' }}</span>
                    </div>
                    <div class="article-hero-meta-item">
                        <i class="far fa-clock"></i>
                        <span>{{ $tinTuc->created_at ? \Carbon\Carbon::parse($tinTuc->created_at)->format('H:i') : '' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="article-main-wrapper">
    <div class="article-layout container">
        {{-- Main Article --}}
        <div class="article-main-column">
            {{-- Admin Actions --}}
            @if(auth()->check() && auth()->user()->isAdmin())
            <div class="d-flex justify-content-end gap-2 mb-3">
                <span class="status-badge {{ $tinTuc->status == 1 ? 'active' : 'hidden' }}">
                    <i class="fas fa-{{ $tinTuc->status == 1 ? 'eye' : 'eye-slash' }} me-1"></i>
                    {{ $tinTuc->status == 1 ? 'Công khai' : 'Ẩn' }}
                </span>
                <a href="{{ route('tintuc.edit', $tinTuc->id) }}" class="btn-edit">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <form action="{{ route('tintuc.destroy', $tinTuc->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Xóa tin tức này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </form>
            </div>
            @endif

            <div class="article-container">
                {{-- Meta Bar --}}
                <div class="article-meta-bar">
                    <div class="article-meta-left">
                        <div class="article-meta-item">
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ $tinTuc->created_at ? \Carbon\Carbon::parse($tinTuc->created_at)->format('d/m/Y') : '' }}</span>
                        </div>
                        <div class="article-meta-item">
                            <i class="far fa-clock"></i>
                            <span>{{ $tinTuc->created_at ? \Carbon\Carbon::parse($tinTuc->created_at)->format('H:i') : '' }}</span>
                        </div>
                    </div>

                    <div class="share-bar">
                        <span class="share-label">Chia sẻ:</span>
                        <button class="share-btn facebook" onclick="shareFacebook()" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button class="share-btn twitter" onclick="shareTwitter()" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="share-btn linkedin" onclick="shareLinkedIn()" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </button>
                        <button class="share-btn copy" onclick="copyLink()" title="Sao chép link" id="copyBtn">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>

                {{-- Content --}}
                <div class="article-content-body">
                    {!! $tinTuc->content !!}
                </div>

                {{-- Tags --}}
                @if($tinTuc->loaitin)
                <div class="article-tags">
                    <a href="{{ route('tintuc.index', ['loai_tin' => $tinTuc->loaitin->id]) }}" class="tag-item">
                        <i class="fas fa-tag me-1"></i> {{ $tinTuc->loaitin->name }}
                    </a>
                </div>
                @endif

                {{-- Attachments --}}
                @php
                    $attachmentItems = $tinTuc->attachment_items;
                @endphp
                @if(!empty($attachmentItems))
                <div class="article-attachments">
                    <div class="attachments-title">
                        <i class="fas fa-paperclip"></i>
                        <span>Tệp đính kèm ({{ count($attachmentItems) }})</span>
                    </div>
                    @foreach($attachmentItems as $index => $attachmentItem)
                    <div class="attachment-item">
                        <div class="attachment-info">
                            <div class="attachment-icon">
                                @php
                                    $filePath = $attachmentItem['path'] ?? '';
                                    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                @endphp
                                @if(in_array($extension, ['doc', 'docx']))
                                    <i class="fas fa-file-word" style="color: #2b579a;"></i>
                                @elseif(in_array($extension, ['xls', 'xlsx', 'csv']))
                                    <i class="fas fa-file-excel" style="color: #217346;"></i>
                                @elseif(in_array($extension, ['ppt', 'pptx']))
                                    <i class="fas fa-file-powerpoint" style="color: #d24726;"></i>
                                @elseif(in_array($extension, ['pdf']))
                                    <i class="fas fa-file-pdf" style="color: #e53935;"></i>
                                @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <i class="fas fa-file-image" style="color: #43a047;"></i>
                                @elseif(in_array($extension, ['zip', 'rar', '7z']))
                                    <i class="fas fa-file-archive" style="color: #f9a825;"></i>
                                @else
                                    <i class="fas fa-file-alt"></i>
                                @endif
                            </div>
                            <div>
                                <div class="attachment-name">{{ $attachmentItem['label'] ?? 'Tệp đính kèm' }}</div>
                                <div class="attachment-label">Tệp đính kèm • Tối đa 2MB</div>
                            </div>
                        </div>
                        @php
                            $filePath = $attachmentItem['path'];
                            $isMainFile = ($index === 0 && !empty($tinTuc->attachment_name));
                            $downloadUrl = $isMainFile
                                ? route('tintuc.download', $tinTuc->id)
                                : route('tintuc.downloadFile', ['tin_tuc_id' => $tinTuc->id, 'path' => $filePath]);
                        @endphp
                        <a href="{{ $downloadUrl }}" class="attachment-download">
                            <i class="fas fa-download"></i> Tải xuống
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Footer --}}
                <div class="article-footer">
                    <a href="{{ route('tintuc.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>

                    @if(!auth()->check() || !auth()->user()->isAdmin())
                        @if($tinTuc->is_khai_bao_noi_tru)
                        <a href="{{ route('sinh_vien.form_khai_bao', $tinTuc->id) }}" class="btn-khai-bao">
                            <i class="fas fa-edit"></i> Khai báo ngoại trú
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="article-sidebar">
            {{-- Topics --}}
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fas fa-bookmark"></i>
                    <h4>Danh mục</h4>
                </div>
                <div class="topics-list">
                    <a href="{{ route('tintuc.index') }}" class="topic-tag tin-tuc">
                        <i class="fas fa-newspaper"></i> Tin tức
                    </a>
                    <a href="{{ route('tintuc.index', ['type' => 'thong-bao']) }}" class="topic-tag thong-bao">
                        <i class="fas fa-bullhorn"></i> Thông báo
                    </a>
                    <a href="{{ route('tintuc.index', ['type' => 'khai-bao']) }}" class="topic-tag khai-bao">
                        <i class="fas fa-home"></i> Khai báo ngoại trú
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fas fa-link"></i>
                    <h4>Liên kết nhanh</h4>
                </div>
                <div class="topics-list">
                    <a href="/" class="topic-tag" style="border-left-color: #667eea;">
                        <i class="fas fa-home"></i> Trang chủ
                    </a>
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <a href="/admin" class="topic-tag" style="border-left-color: #11998e;">
                        <i class="fas fa-user-shield"></i> Quản trị
                    </a>
                    @endif
                    @if($tinTuc->is_khai_bao_noi_tru)
                    <a href="{{ route('sinh_vien.form_khai_bao', $tinTuc->id) }}" class="topic-tag" style="border-left-color: #e65100;">
                        <i class="fas fa-file-alt"></i> Khai báo
                    </a>
                    @endif
                </div>
            </div>
        </aside>
    </div>
</section>

<script>
    // Reading Progress Bar
    window.onscroll = function() {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        document.getElementById("readingProgress").style.width = scrolled + "%";
    };

    // Share Functions
    function shareFacebook() {
        var url = encodeURIComponent(window.location.href);
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '_blank', 'width=600,height=400');
    }

    function shareTwitter() {
        var url = encodeURIComponent(window.location.href);
        var text = encodeURIComponent(document.title);
        window.open('https://twitter.com/intent/tweet?url=' + url + '&text=' + text, '_blank', 'width=600,height=400');
    }

    function shareLinkedIn() {
        var url = encodeURIComponent(window.location.href);
        window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + url, '_blank', 'width=600,height=400');
    }

    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(function() {
            var btn = document.getElementById('copyBtn');
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.background = '#28a745';
            setTimeout(function() {
                btn.innerHTML = '<i class="fas fa-link"></i>';
                btn.style.background = '#6c757d';
            }, 2000);
        });
    }
</script>
@endsection
