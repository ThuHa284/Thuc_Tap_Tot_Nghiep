@extends('layouts.master')
@section('title', 'Thêm mẫu giấy tờ mới')

@section('content')
<div class="container py-4" style="max-width: 760px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-success mb-1">➕ Thêm mẫu giấy tờ mới</h4>
            <p class="text-muted mb-0 small">Tạo loại đơn mới cho sinh viên</p>
        </div>
        <a href="{{ route('xacnhansv.ctsv.admin.forms') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('xacnhansv.ctsv.admin.forms.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold bg-light">📋 Thông tin chung</div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên giấy tờ <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="VD: Đơn xin xác nhận hoãn nghĩa vụ quân sự" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Mô tả ngắn về loại giấy tờ này">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Ảnh mẫu (url)</label>
                    <input type="text" name="url" class="form-control"
                           value="{{ old('url') }}" placeholder="VD: frm1.jpg">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Người ký <span class="text-danger">*</span></label>
                    <select name="leader_id" class="form-select @error('leader_id') is-invalid @enderror" required>
                        <option value="">-- Chọn người ký --</option>
                        @foreach($leaders as $leader)
                            <option value="{{ $leader->id }}" {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                {{ $leader->title }} – {{ $leader->fullname }}
                            </option>
                        @endforeach
                    </select>
                    @error('leader_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

       

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('xacnhansv.ctsv.admin.forms') }}" class="btn btn-outline-secondary">Hủy</a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-floppy"></i> Lưu mẫu giấy tờ
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let fieldCount = 1;

document.getElementById('btnAddField').addEventListener('click', function () {
    fieldCount++;
    const wrapper = document.getElementById('fields-wrapper');
    const div = document.createElement('div');
    div.className = 'input-group mb-2 field-row';
    div.innerHTML = `
        <span class="input-group-text bg-light text-muted small">${fieldCount}</span>
        <input type="text" name="fields[]" class="form-control" placeholder="VD: Họ và tên, Ngày sinh, Lý do...">
        <button type="button" class="btn btn-outline-danger btn-remove-field">
            <i class="bi bi-x-lg"></i>
        </button>`;
    wrapper.appendChild(div);
    updateNumbers();
});

document.getElementById('fields-wrapper').addEventListener('click', function (e) {
    if (e.target.closest('.btn-remove-field')) {
        const rows = document.querySelectorAll('.field-row');
        if (rows.length > 1) {
            e.target.closest('.field-row').remove();
            updateNumbers();
        }
    }
});

function updateNumbers() {
    document.querySelectorAll('.field-row').forEach((row, i) => {
        row.querySelector('.input-group-text').textContent = i + 1;
    });
    fieldCount = document.querySelectorAll('.field-row').length;
}
</script>
@endpush
@endsection