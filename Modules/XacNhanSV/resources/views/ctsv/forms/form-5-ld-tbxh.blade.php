@extends('layouts.master')
@section('title', 'Giấy xác nhận ưu đãi trong giáo dục và đào tạo')

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

        <div class="row mb-2">
            <div class="col-6 text-center">
                <div>TRƯỜNG ĐH CÔNG NGHỆ SÀI GÒN</div>
                <div class="fw-bold">PHÒNG CÔNG TÁC SINH VIÊN</div>
                <div>———————————</div>
                <div class="mt-1">Số: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; /GXN-CTSV</div>
            </div>
            <div class="col-6 text-center">
                <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                <div class="fw-bold"><em>Độc lập – Tự do – Hạnh phúc</em></div>
                <div>———————————</div>
                <div class="mt-1 fst-italic">Tp. Hồ Chí Minh, ngày &nbsp; tháng &nbsp; năm {{ date('Y') }}</div>
            </div>
        </div>

        <div class="text-center my-3">
            <div class="fw-bold" style="font-size:16px">GIẤY XÁC NHẬN</div>
            <div><em>Ưu đãi trong giáo dục và đào tạo</em></div>
            <div class="mt-1">(Dùng cho Phòng Lao động – Thương binh và Xã hội)</div>
        </div>

        <p class="mb-2 fw-bold">TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN XÁC NHẬN:</p>

        <p class="mb-1">
            Sinh viên:
            <input type="text" name="ho_ten" class="border-0 border-bottom px-1"
                style="width:250px;outline:none;background:transparent"
                value="{{ $user->first_name }} {{ $user->last_name }}" readonly>
            &nbsp;&nbsp; Giới tính:
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nam" checked> Nam</label>
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nữ"> Nữ</label>
        </p>

        <p class="mb-1">
            Sinh ngày:
            <input type="text" name="ngay_sinh" class="border-0 border-bottom px-1"
                style="width:30px;outline:none;background:transparent" placeholder="dd">
            tháng
            <input type="text" name="thang_sinh" class="border-0 border-bottom px-1"
                style="width:30px;outline:none;background:transparent" placeholder="mm">
            năm
            <input type="text" name="nam_sinh" class="border-0 border-bottom px-1"
                style="width:55px;outline:none;background:transparent" placeholder="yyyy">
        </p>

        <p class="mb-1">
            CMND/CCCD số:
            <input type="text" name="cmnd" class="border-0 border-bottom px-1"
                style="width:120px;outline:none;background:transparent" placeholder="Số CMND/CCCD">
            &nbsp; Ngày cấp:
            <input type="text" name="ngay_cap_cmnd" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent" placeholder="dd/mm/yyyy">
            &nbsp; Nơi cấp:
            <input type="text" name="noi_cap_cmnd" class="border-0 border-bottom px-1"
                style="width:150px;outline:none;background:transparent" placeholder="Nơi cấp">
        </p>

        <p class="mb-1">
            Hộ khẩu thường trú:
            <input type="text" name="ho_khau" class="border-0 border-bottom px-1"
                style="width:380px;outline:none;background:transparent" placeholder="Nhập địa chỉ hộ khẩu">
        </p>

        <p class="mb-1">
            Học lớp:
            <input type="text" name="lop" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent" value="{{ $user->classid }}" readonly>
            &nbsp; Khoa:
            <input type="text" name="khoa" class="border-0 border-bottom px-1"
                style="width:180px;outline:none;background:transparent" value="{{ $user->facultyid }}">
            &nbsp; MSSV:
            <input type="text" name="mssv" class="border-0 border-bottom px-1"
                style="width:110px;outline:none;background:transparent" value="{{ $user->studentid }}" readonly>
        </p>

        <p class="mb-1">
            Hiện là sinh viên năm thứ
            <input type="text" name="nam_thu" class="border-0 border-bottom px-1"
                style="width:30px;outline:none;background:transparent" placeholder="1">
            &nbsp; Học kỳ:
            <input type="text" name="hoc_ky" class="border-0 border-bottom px-1"
                style="width:30px;outline:none;background:transparent" placeholder="1">
            &nbsp; Năm học:
            <input type="text" name="nam_hoc" class="border-0 border-bottom px-1"
                style="width:110px;outline:none;background:transparent" placeholder="2024-2025">
            &nbsp; Khóa:
            <input type="text" name="khoa_hoc" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent" placeholder="2022-2026">
        </p>

        <p class="mb-1">
            Ngành học:
            <input type="text" name="nganh_hoc" class="border-0 border-bottom px-1"
                style="width:200px;outline:none;background:transparent" placeholder="Tên ngành">
            &nbsp; Hệ đào tạo:
            <input type="text" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent" value="Chính quy" readonly>
        </p>

        <p class="mb-1">
            Thời gian đào tạo: từ tháng
            <input type="text" name="thang_bat_dau" class="border-0 border-bottom px-1"
                style="width:30px;outline:none;background:transparent" placeholder="mm">
            năm
            <input type="text" name="nam_bat_dau" class="border-0 border-bottom px-1"
                style="width:50px;outline:none;background:transparent" placeholder="yyyy">
            đến tháng
            <input type="text" name="thang_ket_thuc" class="border-0 border-bottom px-1"
                style="width:30px;outline:none;background:transparent" placeholder="mm">
            năm
            <input type="text" name="nam_ket_thuc" class="border-0 border-bottom px-1"
                style="width:50px;outline:none;background:transparent" placeholder="yyyy">
        </p>

        <hr class="my-3">

        <p class="mb-2 fw-bold">Đối tượng ưu đãi (theo Pháp lệnh Ưu đãi người có công):</p>
        <div class="ms-3 mb-3">
            <label class="d-block mb-1">
                <input type="checkbox" name="doi_tuong[]" value="con_liet_si"> &nbsp; Con liệt sĩ
            </label>
            <label class="d-block mb-1">
                <input type="checkbox" name="doi_tuong[]" value="con_thuong_binh"> &nbsp; Con thương binh / bệnh binh (suy giảm khả năng lao động từ 61% trở lên)
            </label>
            <label class="d-block mb-1">
                <input type="checkbox" name="doi_tuong[]" value="con_anh_hung"> &nbsp; Con Anh hùng lực lượng vũ trang / Anh hùng lao động
            </label>
            <label class="d-block mb-1">
                <input type="checkbox" name="doi_tuong[]" value="nguoi_co_cong"> &nbsp; Người có công với cách mạng được hưởng trợ cấp hàng tháng
            </label>
            <label class="d-block mb-1">
                <input type="checkbox" name="doi_tuong[]" value="chat_doc"> &nbsp; Con của người hoạt động kháng chiến bị nhiễm chất độc hoá học
            </label>
            <div class="mt-1">
                Đối tượng khác:
                <input type="text" name="doi_tuong_khac" class="border-0 border-bottom px-1"
                    style="width:300px;outline:none;background:transparent" placeholder="Ghi rõ nếu có">
            </div>
        </div>

        <p class="mb-1">
            Giấy chứng nhận người có công số:
            <input type="text" name="so_gcn" class="border-0 border-bottom px-1"
                style="width:150px;outline:none;background:transparent" placeholder="Số GCN">
            &nbsp; Cấp ngày:
            <input type="text" name="ngay_cap_gcn" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent" placeholder="dd/mm/yyyy">
        </p>

        <p class="mb-1">
            Do cơ quan:
            <input type="text" name="co_quan_cap" class="border-0 border-bottom px-1"
                style="width:300px;outline:none;background:transparent" placeholder="Tên cơ quan cấp">
            cấp.
        </p>

        <p class="mb-3 mt-3">
            Giấy xác nhận này được cấp để sinh viên làm thủ tục hưởng chế độ
            <span class="fw-bold">ưu đãi trong giáo dục và đào tạo</span>
            tại Phòng Lao động – Thương binh và Xã hội theo quy định hiện hành.
        </p>

        <p class="mb-4 fst-italic text-muted" style="font-size:13px">
            * Giấy xác nhận này có giá trị trong vòng 03 tháng kể từ ngày cấp.
        </p>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>
                    Tp.Hồ Chí Minh, ngày
                    <input type="text" name="ngay_ky" class="border-0 border-bottom px-1"
                        style="width:30px;outline:none;background:transparent">
                    tháng
                    <input type="text" name="thang_ky" class="border-0 border-bottom px-1"
                        style="width:30px;outline:none;background:transparent">
                    năm
                    <input type="text" name="nam_ky" class="border-0 border-bottom px-1"
                        style="width:50px;outline:none;background:transparent" value="{{ date('Y') }}">
                </p>
                <p class="fw-bold mb-0">Người làm đơn</p>
                <br><br><br>
                <p>{{ $user->first_name }} {{ $user->last_name }}</p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">TRƯỞNG PHÒNG CTSV</p>
                <br><br><br>
                <p>(Ký, ghi rõ họ tên, đóng dấu)</p>
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