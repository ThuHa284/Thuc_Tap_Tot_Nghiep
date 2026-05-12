@extends('layouts.master')
@section('title', 'Lịch Sử Đơn Của Tôi')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">📋 Lịch sử đơn của tôi</h4>
            <p class="text-muted mb-0 small">
                {{ auth()->user()->last_name }} {{ auth()->user()->first_name }}
                &nbsp;|&nbsp; MSSV: {{ auth()->user()->studentid }}
            </p>
        </div>
        <a href="{{ route('xacnhansv.index') }}" class="btn btn-primary btn-sm rounded-pill">
            <i class="bi bi-plus-circle"></i> Tạo đơn mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($submissions->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <h6 class="text-muted">Bạn chưa nộp đơn nào</h6>
                    <a href="{{ route('xacnhansv.index') }}"
                       class="btn btn-primary btn-sm rounded-pill mt-2">
                        <i class="bi bi-plus-circle"></i> Tạo đơn ngay
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Loại giấy tờ</th>
                                <th>Ngày nộp</th>
                                <th>Ngày lấy giấy</th>
                                <th>Hết hạn lấy</th>
                                <th>Hình thức nhận</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $s)
                            @php $st = (int) $s->status; @endphp
                            <tr>
                                <td class="text-muted small">#{{ $s->id }}</td>
                                <td class="fw-semibold small">{{ $s->form->name ?? '—' }}</td>
                                <td class="small">
                                    {{ $s->created_at ? $s->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : '—' }}
                                </td>

                                {{-- Ngày lấy giấy = ngày duyệt (st=1) hoặc ngày in (st=3) --}}
                                <td class="small">
                                    @if(in_array($st, [1, 3]) && $s->updated_at)
                                        <span class="text-success fw-semibold">
                                            {{ \Carbon\Carbon::parse($s->updated_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}
                                        </span>
                                    @elseif($st === 0)
                                        <span class="text-muted">Chờ duyệt</span>
                                    @else
                                        —
                                    @endif
                                </td>

                                {{-- Hết hạn lấy = ngày duyệt + 3 ngày --}}
                                <td class="small">
                                    @if(in_array($st, [1, 3]) && $s->updated_at)
                                        @php $hetHan = \Carbon\Carbon::parse($s->updated_at)->setTimezone('Asia/Ho_Chi_Minh')->addDays(3); @endphp
                                        <span class="{{ $hetHan->isPast() ? 'text-danger' : 'text-warning' }} fw-semibold">
                                            {{ $hetHan->format('d/m/Y') }}
                                            @if($hetHan->isPast())
                                                <br><small class="text-danger">(Đã hết hạn)</small>
                                            @endif
                                        </span>
                                    @elseif($st === 0)
                                        <span class="text-muted">—</span>
                                    @else
                                        —
                                    @endif
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
                                    @php
                                        $badge = match($st) {
                                            0 => 'warning text-dark',
                                            1 => 'success',
                                            2 => 'danger',
                                            3 => 'info',
                                            default => 'secondary'
                                        };
                                        $label = match($st) {
                                            0 => '⏳ Chờ duyệt',
                                            1 => '✅ Đã duyệt',
                                            2 => '❌ Từ chối',
                                            3 => '🖨️ Đã in',
                                            default => '?'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                                </td>

                                <td>
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('xacnhansv.ctsv.my-requests.show', $s->id) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i> Chi tiết
                                        </a>

                                        {{-- Nút xóa: chỉ hiện khi đơn đang chờ duyệt (status = 0) --}}
                                        @if($st === 0)
                                            <form action="{{ route('xacnhansv.ctsv.destroy', $s->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa đơn #{{ $s->id }}?\nHành động này không thể hoàn tác.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Xóa đơn này">
                                                    <i class="bi bi-trash"></i> Xóa
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $submissions->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection