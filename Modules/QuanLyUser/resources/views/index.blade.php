@extends('layouts.master')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Lỗi!</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-primary">QUẢN LÝ ADMIN & CTV</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">Thêm tài khoản</button>
</div>

<table class="table table-hover border">
    <thead class="table-light">
        <tr>
            <th>Tên đăng nhập</th>
            <th>Họ và Tên</th>
            <th>Quyền hạn</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td class="fw-bold">{{ $user->studentid }}</td>
            <td>{{ $user->full_name }}</td>
            <td>
                @if($user->su == 1) <span class="badge bg-danger">Admin</span>
                @else <span class="badge bg-info">Cộng tác viên</span>
                @endif
            </td>
            <td class="text-center">
                <form action="{{ route('quanlyuser.destroy', $user->uid) }}" method="POST" onsubmit="return confirm('Xóa tài khoản này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('quanlyuser.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Thêm tài khoản mới</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="studentid" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Họ và tên lót</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tên</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Quyền hạn</label>
                    <select name="role" class="form-select">
                        <option value="1">Quản trị viên (Admin)</option>
                        <option value="-1">Cộng tác viên (CTV)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>
@endsection