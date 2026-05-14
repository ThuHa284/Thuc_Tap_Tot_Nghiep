@extends('layouts.master')
@section('title', 'Quản lý mẫu giấy tờ')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">📝 Quản lý mẫu giấy tờ</h4>
            <p class="text-muted mb-0 small">Danh sách các loại giấy tờ sinh viên có thể xin</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('xacnhansv.ctsv.admin.forms.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Thêm mẫu mới
            </a>
            <a href="{{ route('xacnhansv.ctsv.admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên giấy tờ</th>
                        <th>Mô tả</th>
                        <th class="text-center">Số đơn đã nộp</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms as $form)
                    <tr>
                        <td class="text-muted small">{{ $form->formid }}</td>
                        <td class="fw-semibold">{{ $form->name }}</td>
                        <td class="small text-muted">
                            {{ $form->description ? Str::limit($form->description, 80) : '—' }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('xacnhansv.ctsv.admin.requests', ['formid' => $form->formid]) }}"
                               class="badge bg-primary text-decoration-none">
                                {{ $form->submissions_count }} đơn
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Nút Sửa --}}
                                <a href="{{ route('xacnhansv.ctsv.admin.forms.edit', $form->formid) }}"
                                   class="btn btn-sm btn-outline-warning" title="Sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- Nút Xóa --}}
                                <form action="{{ route('xacnhansv.ctsv.admin.forms.destroy', $form->formid) }}"
                                      method="POST"
                                      onsubmit="return confirm('Xóa mẫu «{{ addslashes($form->name) }}»?\n⚠️ {{ $form->submissions_count }} đơn liên quan cũng sẽ bị xóa!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-folder-x fs-2 d-block mb-2"></i>
                            Chưa có mẫu giấy tờ nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection