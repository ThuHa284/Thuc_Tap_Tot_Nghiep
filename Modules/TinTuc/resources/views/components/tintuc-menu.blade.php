<style>
    .horizontal-menu {
        background: white;
        border-radius: 12px;
        padding: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .horizontal-menu a {
        padding: 10px 20px;
        border-radius: 8px;
        color: #555;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .horizontal-menu a:hover {
        background: #f0f4ff;
        color: #667eea;
    }
    .horizontal-menu a.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .horizontal-menu a i {
        font-size: 13px;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4ff;
        border-radius: 6px;
    }
    .horizontal-menu a:hover i {
        background: #667eea;
        color: white;
    }
    .horizontal-menu a.active i {
        background: rgba(255,255,255,0.2);
        color: white;
    }
    .menu-divider {
        width: 1px;
        background: #e0e0e0;
        margin: 5px 0;
    }
</style>

<div class="horizontal-menu">
    <a href="{{ route('tintuc.index') }}" class="{{ request()->routeIs('tintuc.index') && !request()->routeIs('tintuc.create') && !request()->routeIs('tintuc.edit') && !request()->routeIs('tintuc.show') ? 'active' : '' }}">
        <i class="fas fa-newspaper"></i> Tin tức
    </a>
    @auth
        @if(auth()->user()->isAdmin())
            <span class="menu-divider"></span>
            <a href="{{ route('loaitin.index') }}" class="{{ request()->routeIs('loaitin.*') ? 'active' : '' }}">
                <i class="fas fa-folder-open"></i> Loại tin
            </a>
        @endif
    @endauth
</div>
