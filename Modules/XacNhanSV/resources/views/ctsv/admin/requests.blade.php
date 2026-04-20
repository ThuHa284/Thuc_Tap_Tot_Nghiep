@extends('layouts.master')
@section('title', 'Danh sách đơn - Admin CTSV')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">📋 Danh sách đơn xin giấy tờ</h4>
            <p class="text-muted mb-0 small">Quản lý và duyệt đơn của sinh viên</p>
        </div>
        <a href="{{ route('xacnhansv.ctsv.admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('xacnhansv.ctsv.admin.requests') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Trạng thái</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Tất cả --</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>⏳ Chờ duyệt</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>✅ Đã duyệt</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>❌ Từ chối</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Loại đơn</label>
                    <select name="formid" class="form-select form-select-sm">
                        <option value="">-- Tất cả loại --</option>
                        @foreach($forms as $f)
                            <option value="{{ $f->formid }}"
                                {{ request('formid') == $f->formid ? 'selected' : '' }}>
                                {{ $f->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="MSSV hoặc tên sinh viên..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">
            Kết quả: {{ $submissions->total() }} đơn
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Sinh viên</th>
                        <th>Loại đơn</th>
                        <th>Ngày nộp</th>
                        <th>Hình thức nhận</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $s)
                    @php $st = (int) $s->status; @endphp
                    <tr>
                        <td class="text-muted small">#{{ $s->id }}</td>
                        <td>
                            <div class="fw-semibold small">
                                {{ $s->user ? $s->user->first_name.' '.$s->user->last_name : '—' }}
                            </div>
                            <div class="text-muted" style="font-size:11px">{{ $s->studentid }}</div>
                        </td>
                        <td class="small">{{ $s->form->name ?? '—' }}</td>
                        <td class="small text-muted">
                            {{ $s->created_at ? $s->created_at->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td class="small">
                            @switch($s->get_at)
                                @case('truc_tiep') 🏢 Trực tiếp @break
                                @case('email')     📧 Email @break
                                @case('buu_dien')  📮 Bưu điện @break
                                @default —
                            @endswitch
                        </td>
                        <td>
                            <span class="badge bg-{{ match($st){ 0=>'warning text-dark', 1=>'success', 2=>'danger', default=>'secondary' } }}">
                                {{ match($st){ 0=>'⏳ Chờ duyệt', 1=>'✅ Đã duyệt', 2=>'❌ Từ chối', default=>'?' } }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('xacnhansv.ctsv.admin.requests.show', $s->id) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                            @if($st === 0)
                                <form action="{{ route('xacnhansv.ctsv.admin.requests.approve', $s->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Duyệt đơn #{{ $s->id }}?')">
                                        <i class="bi bi-check"></i> Duyệt
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Không có đơn nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($submissions->hasPages())
        <div class="card-footer bg-white">
            {{ $submissions->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection