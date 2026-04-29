@extends('layouts.master')
@section('title', 'Giấy xác nhận vay vốn sinh viên')

@section('content')
<div class="container py-4" style="max-width: 800px">

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

    <div class="card shadow" style="font-family: 'Times New Roman', serif; font-size: 14px; padding: 40px 50px; background: #fff; border: 1px solid #ccc">

        <div class="text-center mb-3">
            <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
            <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
            <div>———————————</div>
            <div class="fw-bold mt-2" style="font-size:16px">GIẤY XÁC NHẬN</div>
        </div>

        <p class="mb-1">
            Họ và tên:
            <input type="text" name="ho_ten" class="border-0 border-bottom px-1"
                style="width:250px; outline:none; background:transparent"
                value="{{ $user->first_name }} {{ $user->last_name }}" readonly>
        </p>

        <p class="mb-1">
            Sinh ngày:
            <input type="text" name="ngay" class="border-0 border-bottom px-1"
                style="width:30px; outline:none; background:transparent" placeholder="dd">
            tháng
            <input type="text" name="thang" class="border-0 border-bottom px-1"
                style="width:30px; outline:none; background:transparent" placeholder="mm">
            năm
            <input type="text" name="nam" class="border-0 border-bottom px-1"
                style="width:55px; outline:none; background:transparent" placeholder="yyyy">
            &nbsp;&nbsp; Giới tính:
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nam" checked> Nam</label>
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nữ"> Nữ</label>
        </p>

        <p class="mb-1">
            CMND số:
            <input type="text" name="cmnd" class="border-0 border-bottom px-1"
                style="width:110px; outline:none; background:transparent" placeholder="Số CMND/CCCD">
            ngày cấp:
            <input type="text" name="ngay_cap_cmnd" class="border-0 border-bottom px-1"
                style="width:100px; outline:none; background:transparent" placeholder="dd/mm/yyyy">
            Nơi cấp:
            <input type="text" name="noi_cap_cmnd" class="border-0 border-bottom px-1"
                style="width:150px; outline:none; background:transparent" placeholder="Nơi cấp">
        </p>

        <p class="mb-1">
            Mã trường theo học (mã quy ước trong tuyển sinh ĐH, CĐ, TCCN): DSG
        </p>
        <p class="mb-1">Tên trường: Trường Đại học Công nghệ Sài Gòn</p>

        <p class="mb-1">
            Ngành học:
            <input type="text" name="nganh_hoc" class="border-0 border-bottom px-1"
                style="width:200px; outline:none; background:transparent" placeholder="Ngành học">
        </p>

        <p class="mb-1">
            Hệ đào tạo:
            <select name="he_dao_tao" class="border-0 border-bottom px-1"
                style="outline:none; background:transparent">
                <option>Đại học</option>
                <option>Cao đẳng</option>
            </select>
            &nbsp; Khóa:
            <input type="text" name="khoa" class="border-0 border-bottom px-1"
                style="width:80px; outline:none; background:transparent" placeholder="2022-2026">
            &nbsp; Loại hình đào tạo: Chính quy
        </p>

        <p class="mb-1">
            Lớp:
            <input type="text" name="lop" class="border-0 border-bottom px-1"
                style="width:100px; outline:none; background:transparent"
                value="{{ $user->classid }}" readonly>
            &nbsp; Mã SV:
            <input type="text" name="mssv" class="border-0 border-bottom px-1"
                style="width:110px; outline:none; background:transparent"
                value="{{ $user->studentid }}" readonly>
        </p>

        <p class="mb-1">
            Khoa:
            <input type="text" name="khoa_sv" class="border-0 border-bottom px-1"
                style="width:200px; outline:none; background:transparent"
                value="{{ $user->facultyid }}">
        </p>

        <p class="mb-1">
            Ngày nhập học:
            <input type="text" name="ngay_nhap_hoc" class="border-0 border-bottom px-1"
                style="width:100px; outline:none; background:transparent" placeholder="dd/mm/yyyy">
            &nbsp; Thời gian ra trường (dự kiến 25 tháng 08 năm:
            <input type="text" name="nam_ra_truong" class="border-0 border-bottom px-1"
                style="width:50px; outline:none; background:transparent" placeholder="yyyy">
            )
        </p>

        <p class="mb-1">(Thời gian tại trường: 46 tháng)</p>

        <p class="mb-1">
            - Số tiền học phí hàng tháng (Học phí học kỳ/5):
            <input type="text" name="hoc_phi" class="border-0 border-bottom px-1"
                style="width:150px; outline:none; background:transparent" placeholder="Số tiền"> đồng.
        </p>

        <p class="mb-1">Thuộc diện:</p>
        <div class="ms-3 mb-2">
            <label class="d-block"><input type="radio" name="thuoc_dien" value="khong_mien_giam"> - Không miễn giảm</label>
            <label class="d-block"><input type="radio" name="thuoc_dien" value="giam_hoc_phi"> - Giảm học phí</label>
            <label class="d-block"><input type="radio" name="thuoc_dien" value="mien_hoc_phi"> - Miễn học phí</label>
        </div>

        <p class="mb-1">Thuộc đối tượng:</p>
        <div class="ms-3 mb-2">
            <label class="d-block"><input type="radio" name="doi_tuong" value="mo_coi"> Mồ côi</label>
            <label class="d-block"><input type="radio" name="doi_tuong" value="khong_mo_coi" checked> Không mồ côi</label>
        </div>

        <p class="mb-2">
            - Trong thời gian theo học tại trường, anh (chị)
            <input type="text" class="border-0 border-bottom px-1"
                style="width:150px; outline:none; background:transparent"
                value="{{ $user->first_name }} {{ $user->last_name }}" readonly>
            không bị xử phạt hành chính trở lên về các hành vi: cờ bạc, nghiện hút, trộm cắp, buôn lậu.
        </p>

        <p class="mb-3">
            - Số tài khoản của nhà trường: 8770199, tại ngân hàng Á Châu (ACB).
        </p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>
                    Tp.Hồ Chí Minh, ngày
                    <input type="text" name="ngay_ky" class="border-0 border-bottom px-1"
                        style="width:30px; outline:none; background:transparent">
                    tháng
                    <input type="text" name="thang_ky" class="border-0 border-bottom px-1"
                        style="width:30px; outline:none; background:transparent">
                    năm
                    <input type="text" name="nam_ky" class="border-0 border-bottom px-1"
                        style="width:50px; outline:none; background:transparent"
                        value="{{ date('Y') }}">
                </p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">Hiệu trưởng</p>
                <br><br><br>
                <p>PGS. TS. Cao Hào Thi</p>
            </div>
        </div>

    </div>

    {{-- Phần phụ --}}
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
            <label class="fw-semibold">Ghi chú các giấy tờ bổ sung còn thiếu</label>
            <textarea name="note" class="form-control mt-1" rows="3"
                      placeholder="Thông báo của P.CTSV"></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-send"></i> Lưu
            </button>
            <a href="{{ route('xacnhansv.index') }}" class="btn btn-outline-secondary px-4">Đóng</a>
        </div>
    </div>

    </form>
</div>
@endsection