@extends('layouts.master')
@section('title', 'Admin CTSV - Dashboard')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">🏠 Dashboard CTSV</h4>
            <p class="text-muted mb-0 small">Quản lý đơn xin giấy tờ của sinh viên</p>
        </div>
        <a href="{{ route('xacnhansv.ctsv.admin.requests') }}" class="btn btn-primary btn-sm rounded-pill">
            <i class="bi bi-list-ul"></i> Xem tất cả đơn
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-4">
                <div class="fs-1 fw-bold text-warning">{{ $stats['pending'] }}</div>
                <div class="text-muted small mt-1">⏳ Chờ duyệt</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-4">
                <div class="fs-1 fw-bold text-success">{{ $stats['approved'] }}</div>
                <div class="text-muted small mt-1">✅ Đã duyệt</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-4">
                <div class="fs-1 fw-bold text-danger">{{ $stats['rejected'] }}</div>
                <div class="text-muted small mt-1">❌ Từ chối</div>
            </div>
        </div>
        {{-- ✅ Thêm card Đã in --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-4">
                <div class="fs-1 fw-bold text-info">{{ $stats['printed'] }}</div>
                <div class="text-muted small mt-1">🖨️ Đã in</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold d-flex justify-content-between">
            <span>📥 Đơn mới nhất</span>
            <a href="{{ route('xacnhansv.ctsv.admin.requests') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Sinh viên</th>
                        <th>Loại đơn</th>
                        <th>Ngày nộp</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $s)
                    @php $st = (int) $s->status; @endphp
                    <tr>
                        <td class="text-muted small">#{{ $s->id }}</td>
                        <td>
                            <div class="fw-semibold small">
                                {{ $s->user ? $s->user->last_name.' '.$s->user->first_name : $s->studentid }}
                            </div>
                            <div class="text-muted" style="font-size:11px">{{ $s->studentid }}</div>
                        </td>
                        <td class="small">{{ $s->form->name ?? '—' }}</td>
                        <td class="small text-muted">
                            {{ $s->created_at ? $s->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td>
                            {{-- ✅ Thêm case 3: Đã in --}}
                            <span class="badge bg-{{ match($st){ 0=>'warning text-dark', 1=>'success', 2=>'danger', 3=>'info', default=>'secondary' } }}">
                                {{ match($st){ 0=>'⏳ Chờ duyệt', 1=>'✅ Đã duyệt', 2=>'❌ Từ chối', 3=>'🖨️ Đã in', default=>'?' } }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('xacnhansv.ctsv.admin.requests.show', $s->id) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Chưa có đơn nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <a href="{{ route('xacnhansv.ctsv.admin.requests', ['status' => '0']) }}"
               class="card border-warning text-decoration-none shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="fs-3">⏳</span>
                    <div>
                        <div class="fw-bold">Đơn chờ duyệt</div>
                        <div class="text-muted small">{{ $stats['pending'] }} đơn cần xử lý</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('xacnhansv.ctsv.admin.forms') }}"
               class="card border-primary text-decoration-none shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="fs-3">📝</span>
                    <div>
                        <div class="fw-bold">Quản lý mẫu giấy tờ</div>
                        <div class="text-muted small">Thêm, sửa, xóa mẫu đơn</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection