@extends('layouts.master')
@section('title', 'Đơn xin xác nhận khác')

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

    <form action="{{ route('xacnhansv.ctsv.form.store', $form->formid) }}" method="POST" enctype="multipart/form-data">
        @csrf

    <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">

        <div class="text-center mb-3">
            <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
            <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
            <div>———————————</div>
            <div class="fw-bold mt-2" style="font-size:16px">ĐƠN XIN XÁC NHẬN 2</div>
            <div>Kính gửi: Phòng Công tác Sinh viên</div>
        </div>

        <p class="mb-1">
            Tôi tên: <input type="text" name="ho_ten" class="border-0 border-bottom px-1"
                style="width:250px;outline:none;background:transparent"
                value="{{ $user->first_name }} {{ $user->last_name }}" readonly>
        </p>

        <p class="mb-1">
            Sinh ngày:
            <input type="text" name="ngay" class="border-0 border-bottom px-1" style="width:30px;outline:none;background:transparent" placeholder="dd">
            tháng <input type="text" name="thang" class="border-0 border-bottom px-1" style="width:30px;outline:none;background:transparent" placeholder="mm">
            năm <input type="text" name="nam" class="border-0 border-bottom px-1" style="width:55px;outline:none;background:transparent" placeholder="yyyy">
            &nbsp;&nbsp; Giới tính:
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nam" checked> Nam</label>
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nữ"> Nữ</label>
        </p>

        <p class="mb-1">
            Học lớp: <input type="text" name="lop" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent" value="{{ $user->classid }}" readonly>
            &nbsp; Khoa: <input type="text" name="khoa" class="border-0 border-bottom px-1"
                style="width:180px;outline:none;background:transparent" value="{{ $user->facultyid }}">
            &nbsp; MSSV: <input type="text" name="mssv" class="border-0 border-bottom px-1"
                style="width:110px;outline:none;background:transparent" value="{{ $user->studentid }}" readonly>
        </p>

        <p class="mb-1">
            Hộ khẩu thường trú: <input type="text" name="ho_khau" class="border-0 border-bottom px-1"
                style="width:400px;outline:none;background:transparent" placeholder="Nhập địa chỉ hộ khẩu">
        </p>

        <p class="mb-1">
            Bậc đào tạo: <input type="text" class="border-0 border-bottom px-1"
                style="width:80px;outline:none;background:transparent" value="Đại học" readonly>
            &nbsp; Hệ đào tạo: chính quy của Trường Đại học Công nghệ Sài Gòn.
        </p>

        <p class="mb-1">
            Số điện thoại liên lạc: <input type="text" name="sdt" class="border-0 border-bottom px-1"
                style="width:150px;outline:none;background:transparent" placeholder="Số điện thoại">
        </p>

        <p class="mb-2">Nay tôi làm đơn này xin nhà trường cấp giấy chứng nhận tôi là Sinh viên đang theo học tại trường để bổ túc hồ sơ xin:</p>

        <p class="mb-1">
            <label><input type="checkbox" name="xin_giam_tru" value="1"> &nbsp; Xác nhận giảm trừ gia cảnh</label>
        </p>

        <p class="mb-3">
            Xác nhận khác (Ghi rõ yêu cầu cần xác nhận):
            <input type="text" name="xac_nhan_khac" class="border-0 border-bottom px-1"
                style="width:300px;outline:none;background:transparent" placeholder="Nhập yêu cầu xác nhận">
        </p>

        <p class="mb-4">Trân trọng kính chào.</p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>Tp.Hồ Chí Minh, ngày
                    <input type="text" name="ngay_ky" class="border-0 border-bottom px-1" style="width:30px;outline:none;background:transparent">
                    tháng <input type="text" name="thang_ky" class="border-0 border-bottom px-1" style="width:30px;outline:none;background:transparent">
                    năm <input type="text" name="nam_ky" class="border-0 border-bottom px-1" style="width:50px;outline:none;background:transparent" value="{{ date('Y') }}">
                </p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">Người làm đơn</p><br><br><br>
                <p>{{ $user->first_name }} {{ $user->last_name }}</p>
            </div>
        </div>

        <hr class="my-3">
        <div class="text-center fw-bold mb-2">XÁC NHẬN CỦA TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN</div>
        <p>Xác nhận sinh viên: {{ $user->first_name }} {{ $user->last_name }}</p>
        <p class="mb-1">
            Hiện là sinh viên năm thứ
            <input type="text" name="nam_thu" class="border-0 border-bottom px-1" style="width:30px;outline:none;background:transparent">
            &nbsp; Học kỳ: <input type="text" name="hoc_ky" class="border-0 border-bottom px-1" style="width:30px;outline:none;background:transparent">
            &nbsp; Năm học: <input type="text" name="nam_hoc" class="border-0 border-bottom px-1" style="width:100px;outline:none;background:transparent">
            &nbsp; Khóa học: <input type="text" name="khoa_hoc" class="border-0 border-bottom px-1" style="width:100px;outline:none;background:transparent">
        </p>
        <p>MSSV: {{ $user->studentid }} &nbsp;&nbsp; Khoa: {{ $user->facultyid }}</p>
        <p>Hệ đào tạo: chính quy của Trường Đại học Công nghệ Sài Gòn.</p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>Tp.Hồ Chí Minh, ngày &nbsp;&nbsp;&nbsp; tháng &nbsp;&nbsp;&nbsp; năm {{ date('Y') }}</p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">HIỆU TRƯỞNG</p><br><br><br>
                <p>PGS. TS. Cao Hào Thi</p>
            </div>
        </div>
    </div>

    <div class="card mt-3 p-4">
        <div class="mb-3">
            <label class="fw-semibold">Phương thức nhận hồ sơ:</label>
            <div class="mt-1">
                <label class="me-3"><input type="radio" name="get_at" value="truc_tiep" checked> Phòng CTSV</label>
                <label class="me-3"><input type="radio" name="get_at" value="email"> Email</label>
                <label><input type="radio" name="get_at" value="buu_dien"> Bưu điện</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="fw-semibold">Ghi chú</label>
            <textarea name="note" class="form-control mt-1" rows="3" placeholder="Ghi chú nếu có..."></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4"><i class="bi bi-send"></i> Lưu</button>
            <a href="{{ route('xacnhansv.index') }}" class="btn btn-outline-secondary px-4">Đóng</a>
        </div>
    </div>

    </form>
</div>
@endsection