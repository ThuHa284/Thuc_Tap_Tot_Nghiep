@extends('layouts.master')
@section('title', $form->name)

@section('content')
<div class="container py-4" style="max-width:800px">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('xacnhansv.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
        <div class="text-muted small">📋 Điền thông tin vào mẫu đơn bên dưới</div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $khoaMap = [
            'cntt' => 'Công nghệ Thông tin',
            'ckhi' => 'Cơ khí',
            'cntp' => 'Công nghệ Thực phẩm',
            'ddtu' => 'Điện - Điện tử',
            'dsgn' => 'Thiết kế',
            'kd'   => 'Kinh doanh',
            'ktct' => 'Kế toán - Kiểm toán',
            'qtkd' => 'Quản trị Kinh doanh',
        ];
        $khoaTen = $khoaMap[strtolower($user->facultyid ?? '')] ?? ($user->facultyid ?? '');
    @endphp

    <form action="{{ route('xacnhansv.ctsv.form.store', $form->formid) }}" method="POST" id="formDon">
        @csrf

        {{-- Giấy tờ chính --}}
        <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">

            {{-- Header --}}
            <div class="row mb-3 text-center" style="font-size:13px">
                <div class="col-6 border-end">
                    <div class="fw-bold">{{ strtoupper($form->schoolname ?? 'TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN') }}</div>
                    <div><em>Phòng Công tác Sinh viên</em></div>
                    <div>———————————</div>
                </div>
                <div class="col-6">
                    <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
                    <div>———————————</div>
                </div>
            </div>

            <div class="text-center mb-4">
                <div class="fw-bold mt-2" style="font-size:16px">{{ strtoupper($form->name) }}</div>
            </div>

            <p class="mb-2">
                Kính gửi: Ban Giám Hiệu {{ $form->schoolname ?? 'Trường Đại học Công nghệ Sài Gòn' }}
            </p>

            {{-- Thông tin sinh viên cố định --}}
            <p class="mb-1">
                Tôi tên:
                <input type="text" name="ho_ten" class="border-0 border-bottom px-1"
                    style="width:220px;outline:none;background:transparent"
                    value="{{ $user->first_name }} {{ $user->last_name }}" readonly>
                &nbsp;&nbsp; Giới tính:
                <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nam" checked> Nam</label>
                <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nữ"> Nữ</label>
            </p>

            <p class="mb-1">
                Học lớp:
                <input type="text" name="lop" class="border-0 border-bottom px-1"
                    style="width:100px;outline:none;background:transparent"
                    value="{{ $user->classid }}" readonly>
                &nbsp; Khoa:
                <input type="text" name="khoa" class="border-0 border-bottom px-1"
                    style="width:200px;outline:none;background:transparent"
                    value="{{ $khoaTen }}" readonly>
                &nbsp; MSSV:
                <input type="text" name="mssv" class="border-0 border-bottom px-1"
                    style="width:110px;outline:none;background:transparent"
                    value="{{ $user->studentid }}" readonly>
            </p>

            {{-- Các trường động từ DB --}}
            @foreach($form->details as $detail)
            <p class="mb-1">
                {{ $detail->label }}:
                <input type="text"
                       name="field_{{ $detail->fdetailid }}"
                       class="border-0 border-bottom px-1"
                       style="width:350px;outline:none;background:transparent"
                       placeholder="Nhập {{ strtolower($detail->label) }}"
                       value="{{ old('field_'.$detail->fdetailid) }}"
                       required>
            </p>
            @endforeach

            <p class="mt-3 mb-4">Trân trọng kính chào.</p>

            {{-- Ngày ký + Chữ ký --}}
            <div class="d-flex justify-content-between mt-2">
                <div style="width:50%">
                    <p>
                        Tp.Hồ Chí Minh, ngày
                        <input type="number" name="ngay_ky" class="border-0 border-bottom px-1"
                            style="width:35px;outline:none;background:transparent"
                            min="1" max="31" value="{{ date('d') }}" required>
                        tháng
                        <input type="number" name="thang_ky" class="border-0 border-bottom px-1"
                            style="width:35px;outline:none;background:transparent"
                            min="1" max="12" value="{{ date('m') }}" required>
                        năm
                        <input type="number" name="nam_ky" class="border-0 border-bottom px-1"
                            style="width:55px;outline:none;background:transparent"
                            value="{{ date('Y') }}" readonly>
                    </p>
                </div>
                <div class="text-center" style="width:40%">
                    <p class="fw-bold mb-0">Người làm đơn</p>
                    <br><br><br>
                    <p>{{ $user->first_name }} {{ $user->last_name }}</p>
                </div>
            </div>

            <hr class="my-4">

            {{-- Phần xác nhận của trường --}}
            <div class="text-center fw-bold mb-3">
                XÁC NHẬN CỦA {{ strtoupper($form->schoolname ?? 'TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN') }}
            </div>

            <p>Xác nhận sinh viên: <strong>{{ $user->first_name }} {{ $user->last_name }}</strong></p>
            <p class="mb-1">
                Hiện là sinh viên năm thứ
                <input type="number" name="nam_thu" class="border-0 border-bottom px-1"
                    style="width:35px;outline:none;background:transparent"
                    min="1" max="6" required>
                &nbsp; Học kỳ:
                <input type="number" name="hoc_ky" class="border-0 border-bottom px-1"
                    style="width:35px;outline:none;background:transparent"
                    min="1" max="3" required>
                &nbsp; Năm học:
                <input type="text" name="nam_hoc" class="border-0 border-bottom px-1"
                    style="width:100px;outline:none;background:transparent"
                    placeholder="2022-2026"
                    pattern="^\d{4}-\d{4}$"
                    title="Định dạng: yyyy-yyyy (VD: 2022-2026)"
                    required>
                &nbsp; Khóa học:
                <input type="text" name="khoa_hoc" class="border-0 border-bottom px-1"
                    style="width:100px;outline:none;background:transparent"
                    placeholder="VD: K2022"
                    required>
            </p>
            <p>MSSV: {{ $user->studentid }} &nbsp;&nbsp; Khoa: {{ $khoaTen }}</p>
            <p>Hệ đào tạo: chính quy của {{ $form->schoolname ?? 'Trường Đại học Công nghệ Sài Gòn' }}.</p>

            <div class="d-flex justify-content-between mt-2">
                <div style="width:50%">
                    <p>Tp.Hồ Chí Minh, ngày &nbsp;&nbsp;&nbsp; tháng &nbsp;&nbsp;&nbsp; năm {{ date('Y') }}</p>
                </div>
                <div class="text-center" style="width:40%">
                    <p class="fw-bold mb-0">{{ strtoupper($form->signtitle ?? 'HIỆU TRƯỞNG') }}</p>
                    <br><br><br>
                    <p>{{ $form->signname ?? 'PGS. TS. Cao Hào Thi' }}</p>
                </div>
            </div>
        </div>

        {{-- Phần phụ --}}
        <div class="card mt-3 p-4">
            <div class="mb-3">
                <label class="fw-semibold">Phương thức nhận hồ sơ: <span class="text-danger">*</span></label>
                <div class="mt-1">
                    <label class="me-3">
                        <input type="radio" name="get_at" value="truc_tiep" checked
                            onchange="toggleDiaChi(this.value)">
                        🏢 Phòng CTSV
                    </label>
                    <label>
                        <input type="radio" name="get_at" value="buu_dien"
                            onchange="toggleDiaChi(this.value)">
                        📮 Bưu điện
                    </label>
                </div>
                <div id="dia-chi-buu-dien" class="mt-2" style="display:none">
                    <input type="text" name="ReceivingAddress" id="ReceivingAddress"
                        class="form-control"
                        placeholder="Nhập địa chỉ nhận hồ sơ qua bưu điện">
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Ghi chú</label>
                <textarea name="note" class="form-control mt-1" rows="3"
                    placeholder="Ghi chú nếu có..." maxlength="1000">{{ old('note') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-send"></i> Nộp đơn
                </button>
                <a href="{{ route('xacnhansv.index') }}" class="btn btn-outline-secondary px-4">Đóng</a>
            </div>
        </div>

    </form>
</div>

<script>
function toggleDiaChi(val) {
    const box = document.getElementById('dia-chi-buu-dien');
    const input = document.getElementById('ReceivingAddress');
    if (val === 'buu_dien') {
        box.style.display = 'block';
        input.required = true;
    } else {
        box.style.display = 'none';
        input.required = false;
        input.value = '';
    }
}

document.querySelectorAll('input[type="number"]').forEach(function(el) {
    el.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
@endsection