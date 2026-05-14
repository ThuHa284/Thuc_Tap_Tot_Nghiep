@extends('layouts.master')
@section('title', 'Giấy xác nhận vay vốn sinh viên')

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

    {{-- ✅ Cảnh báo đã có đơn trong kỳ --}}
    @if($existingWarning ?? null)
    <div class="alert alert-warning border-warning d-flex align-items-start gap-3 mb-3">
        <span style="font-size:24px">⚠️</span>
        <div>
            <div class="fw-bold mb-1">Lưu ý!</div>
            <div>{!! $existingWarning !!}</div>
            <div class="mt-2 small text-muted">Bạn vẫn có thể xem lại đơn cũ trong
                <a href="{{ route('xacnhansv.ctsv.my-requests') }}">lịch sử đơn</a>.
            </div>
        </div>
    </div>
    @endif

    {{-- ✅ Ẩn form nếu đã nộp trong kỳ --}}
    @if($cannotSubmit ?? false)
        <div class="text-center mt-4">
            <a href="{{ route('xacnhansv.ctsv.my-requests') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Xem đơn của tôi
            </a>
        </div>
    @else

    @php
        $khoaMap = [
            'cntt' => 'Công nghệ Thông tin', 'ckhi' => 'Cơ khí',
            'cntp' => 'Công nghệ Thực phẩm', 'ddtu' => 'Điện - Điện tử',
            'dsgn' => 'Thiết kế', 'kd' => 'Kinh doanh',
            'ktct' => 'Kế toán - Kiểm toán', 'qtkd' => 'Quản trị Kinh doanh',
        ];
        $khoaTen  = $khoaMap[strtolower($user->facultyid ?? '')] ?? ($user->facultyid ?? '');
        $dobParts = $dob ? explode('/', $dob) : ['', '', ''];
        $dobNgay  = $dobParts[0] ?? '';
        $dobThang = $dobParts[1] ?? '';
        $dobNam   = $dobParts[2] ?? '';
    @endphp

    <form action="{{ route('xacnhansv.ctsv.form.store', $form->formid) }}" method="POST">
        @csrf

    <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">

        <div class="text-center mb-3">
            <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
            <div><em>Độc lập – Tự do – Hạnh phúc</em></div>
            <div>———————————</div>
            <div class="fw-bold mt-2" style="font-size:16px">GIẤY XÁC NHẬN VAY VỐN SINH VIÊN</div>
        </div>

        <p class="mb-1">
            Họ và tên: <input type="text" name="ho_ten" class="border-0 border-bottom px-1"
                style="width:250px;outline:none;background:transparent"
                value="{{ $user->last_name }} {{ $user->first_name }}" readonly>
        </p>

        {{-- ✅ Ngày sinh readonly từ DB --}}
        <p class="mb-1">
            Sinh ngày:
            <input type="text" name="ngay" class="border-0 border-bottom px-1"
                style="width:40px;outline:none;background:transparent;color:inherit"
                value="{{ $dobNgay }}" readonly tabindex="-1">
            tháng
            <input type="text" name="thang" class="border-0 border-bottom px-1"
                style="width:40px;outline:none;background:transparent;color:inherit"
                value="{{ $dobThang }}" readonly tabindex="-1">
            năm
            <input type="text" name="nam" class="border-0 border-bottom px-1"
                style="width:60px;outline:none;background:transparent;color:inherit"
                value="{{ $dobNam }}" readonly tabindex="-1">
            &nbsp;&nbsp; Giới tính:
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nam" checked> Nam</label>
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nữ"> Nữ</label>
            @if(!$dob)
                <span class="text-danger small ms-2" style="font-family:sans-serif">⚠️ Chưa có ngày sinh trong hệ thống.</span>
            @endif
        </p>

        <p class="mb-1">
            CMND/CCCD số:
            <input type="text" name="cmnd" class="border-0 border-bottom px-1"
                style="width:120px;outline:none;background:transparent"
                placeholder="9 hoặc 12 số" pattern="^[0-9]{9}$|^[0-9]{12}$"
                title="CMND 9 số hoặc CCCD 12 số"
                required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            &nbsp; Ngày cấp:
            <input type="number" name="ngay_cap_cmnd_d" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="dd" min="1" max="31" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            /
            <input type="number" name="ngay_cap_cmnd_m" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="mm" min="1" max="12" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            /
            <input type="number" name="ngay_cap_cmnd_y" class="border-0 border-bottom px-1"
                style="width:55px;outline:none;background:transparent"
                placeholder="yyyy" min="1990" max="{{ date('Y') }}" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            &nbsp; Nơi cấp:
            <input type="text" name="noi_cap_cmnd" class="border-0 border-bottom px-1"
                style="width:150px;outline:none;background:transparent" placeholder="Nơi cấp" required>
        </p>

        <p class="mb-1">Mã trường: DSG &nbsp;&nbsp; Tên trường: Trường Đại học Công nghệ Sài Gòn</p>

        <p class="mb-1">
            Ngành học:
            <input type="text" name="nganh_hoc" class="border-0 border-bottom px-1"
                style="width:200px;outline:none;background:transparent" placeholder="Ngành học" required>
            &nbsp; Hệ: Đại học &nbsp; Chính quy
        </p>

        <p class="mb-1">
           Khóa:
            <input type="text" name="khoa" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent"
                value="{{ $user->academic_year }}" readonly>
            &nbsp; Lớp:
            <input type="text" name="lop" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent" value="{{ $user->classid }}" readonly>
            &nbsp; Mã SV:
            <input type="text" name="mssv" class="border-0 border-bottom px-1"
                style="width:110px;outline:none;background:transparent" value="{{ $user->studentid }}" readonly>
        </p>

        <p class="mb-1">
            Khoa: <input type="text" name="khoa_sv" class="border-0 border-bottom px-1"
                style="width:200px;outline:none;background:transparent" value="{{ $khoaTen }}" readonly>
        </p>

        <p class="mb-1">
            Ngày nhập học:
            <input type="number" name="ngay_nhap_hoc_d" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="dd" min="1" max="31" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            /
            <input type="number" name="ngay_nhap_hoc_m" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="mm" min="1" max="12" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            /
            <input type="number" name="ngay_nhap_hoc_y" class="border-0 border-bottom px-1"
                style="width:55px;outline:none;background:transparent"
                placeholder="yyyy" min="2000" max="{{ date('Y') }}" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            &nbsp; Năm ra trường dự kiến:
            <input type="number" name="nam_ra_truong" class="border-0 border-bottom px-1"
                style="width:55px;outline:none;background:transparent"
                placeholder="yyyy" min="{{ date('Y') }}" max="2050" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
        </p>

        <p class="mb-1">
            Học phí hàng tháng:
            <input type="text" name="hoc_phi" class="border-0 border-bottom px-1"
                style="width:150px;outline:none;background:transparent"
                placeholder="VD: 3500000" pattern="^[0-9]+$" title="Chỉ nhập số"
                required oninput="this.value=this.value.replace(/[^0-9]/g,'')"> đồng.
        </p>

        <p class="mb-1">Thuộc diện:</p>
        <div class="ms-3 mb-2">
            <label class="d-block"><input type="radio" name="thuoc_dien" value="khong_mien_giam" required> Không miễn giảm</label>
            <label class="d-block"><input type="radio" name="thuoc_dien" value="giam_hoc_phi"> Giảm học phí</label>
            <label class="d-block"><input type="radio" name="thuoc_dien" value="mien_hoc_phi"> Miễn học phí</label>
        </div>

        <p class="mb-1">Thuộc đối tượng:</p>
        <div class="ms-3 mb-3">
            <label class="d-block"><input type="radio" name="doi_tuong" value="mo_coi"> Mồ côi</label>
            <label class="d-block"><input type="radio" name="doi_tuong" value="khong_mo_coi" checked> Không mồ côi</label>
        </div>

        <div class="d-flex justify-content-between mt-2">
            <div style="width:50%">
                <p>Tp.Hồ Chí Minh, ngày
                    <input type="number" name="ngay_ky" class="border-0 border-bottom px-1"
                        style="width:35px;outline:none;background:transparent"
                        min="1" max="31" value="{{ date('d') }}" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    tháng
                    <input type="number" name="thang_ky" class="border-0 border-bottom px-1"
                        style="width:35px;outline:none;background:transparent"
                        min="1" max="12" value="{{ date('m') }}" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    năm
                    <input type="number" name="nam_ky" class="border-0 border-bottom px-1"
                        style="width:55px;outline:none;background:transparent"
                        value="{{ date('Y') }}" readonly>
                </p>
            </div>
            <div class="text-center" style="width:40%">
                <p class="fw-bold mb-0">Hiệu trưởng</p><br><br><br>
                <p>PGS. TS. Cao Hào Thi</p>
            </div>
        </div>
    </div>

    <div class="card mt-3 p-4">
        <div class="mb-3">
            <label class="fw-semibold">Phương thức nhận hồ sơ: <span class="text-danger">*</span></label>
            <div class="mt-1">
                <label class="me-3">
                    <input type="radio" name="get_at" value="truc_tiep" checked onchange="toggleDiaChi(this.value)">
                    🏢 Phòng CTSV
                </label>
                <label>
                    <input type="radio" name="get_at" value="buu_dien" onchange="toggleDiaChi(this.value)">
                    📮 Bưu điện
                </label>
            </div>
            <div id="dia-chi-buu-dien" class="mt-2" style="display:none">
                <input type="text" name="ReceivingAddress" id="ReceivingAddress" class="form-control"
                    placeholder="Nhập địa chỉ nhận hồ sơ qua bưu điện">
            </div>
        </div>
        <div class="mb-3">
            <label class="fw-semibold">Ghi chú</label>
            <textarea name="note" class="form-control mt-1" rows="3" placeholder="Ghi chú nếu có..." maxlength="1000"></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4"><i class="bi bi-send"></i> Lưu</button>
            <a href="{{ route('xacnhansv.index') }}" class="btn btn-outline-secondary px-4">Đóng</a>
        </div>
    </div>
    </form>

    @endif {{-- end cannotSubmit --}}
</div>
<script>
function toggleDiaChi(val) {
    const box = document.getElementById('dia-chi-buu-dien');
    const input = document.getElementById('ReceivingAddress');
    if (val === 'buu_dien') { box.style.display = 'block'; input.required = true; }
    else { box.style.display = 'none'; input.required = false; input.value = ''; }
}
</script>
@endsection