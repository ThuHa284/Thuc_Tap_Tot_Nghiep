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

    {{-- ✅ Cảnh báo đã có đơn --}}
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

    {{-- ✅ Khoá form nếu không được nộp --}}
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
        $khoaTen = $khoaMap[strtolower($user->facultyid ?? '')] ?? ($user->facultyid ?? '');

        // ✅ Tách ngày/tháng/năm từ $dob (định dạng d/m/Y) do controller truyền xuống
        $dobParts = $dob ? explode('/', $dob) : [null, null, null];
        $dobNgay  = $dobParts[0] ?? '';
        $dobThang = $dobParts[1] ?? '';
        $dobNam   = $dobParts[2] ?? '';
    @endphp

    <form action="{{ route('xacnhansv.ctsv.form.store', $form->formid) }}" method="POST" id="formDon">
        @csrf

    <div class="card shadow" style="font-family:'Times New Roman',serif;font-size:14px;padding:40px 50px;background:#fff;border:1px solid #ccc">

        <div class="row mb-2">
            <div class="col-6 text-center">
                <div>TRƯỜNG ĐH CÔNG NGHỆ SÀI GÒN</div>
                <div class="fw-bold">PHÒNG CÔNG TÁC SINH VIÊN</div>
                <div>———————————</div>
            </div>
            <div class="col-6 text-center">
                <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                <div class="fw-bold"><em>Độc lập – Tự do – Hạnh phúc</em></div>
                <div>———————————</div>
            </div>
        </div>

        <div class="text-center my-3">
            <div class="fw-bold" style="font-size:16px">GIẤY XÁC NHẬN</div>
            <div><em>Ưu đãi trong giáo dục và đào tạo</em></div>
            <div class="mt-1">(Dùng cho Phòng Lao động – Thương binh và Xã hội)</div>
        </div>

        <p class="mb-1">
            Sinh viên:
            <input type="text" name="ho_ten" class="border-0 border-bottom px-1"
                style="width:250px;outline:none;background:transparent"
                value="{{ $user->last_name }} {{ $user->first_name }}" readonly>
            &nbsp;&nbsp; Giới tính:
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nam" checked> Nam</label>
            <label class="ms-2"><input type="radio" name="gioi_tinh" value="Nữ"> Nữ</label>
        </p>

        {{-- ✅ Ngày sinh: lấy từ DB, hiển thị readonly --}}
        <p class="mb-1">
            Sinh ngày:
            <input type="text" name="ngay_sinh" class="border-0 border-bottom px-1"
                style="width:40px;outline:none;background:transparent;color:inherit"
                value="{{ $dobNgay }}" readonly tabindex="-1">
            tháng
            <input type="text" name="thang_sinh" class="border-0 border-bottom px-1"
                style="width:40px;outline:none;background:transparent;color:inherit"
                value="{{ $dobThang }}" readonly tabindex="-1">
            năm
            <input type="text" name="nam_sinh" class="border-0 border-bottom px-1"
                style="width:60px;outline:none;background:transparent;color:inherit"
                value="{{ $dobNam }}" readonly tabindex="-1">
            @if(!$dob)
                <span class="text-danger small ms-2" style="font-family:sans-serif">
                    ⚠️ Chưa có ngày sinh trong hệ thống, vui lòng liên hệ phòng CTSV.
                </span>
            @endif
        </p>

        <p class="mb-1">
            CMND/CCCD số:
            <input type="text" name="cmnd" class="border-0 border-bottom px-1"
                style="width:120px;outline:none;background:transparent"
                placeholder="9 hoặc 12 số"
                pattern="^[0-9]{9}$|^[0-9]{12}$"
                title="CMND 9 số hoặc CCCD 12 số"
                required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            &nbsp; Ngày cấp:
            <input type="number" name="ngay_cap_d" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="dd" min="1" max="31" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            /
            <input type="number" name="ngay_cap_m" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="mm" min="1" max="12" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            /
            <input type="number" name="ngay_cap_y" class="border-0 border-bottom px-1"
                style="width:55px;outline:none;background:transparent"
                placeholder="yyyy" min="1990" max="{{ date('Y') }}" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            &nbsp; Nơi cấp:
            <input type="text" name="noi_cap_cmnd" class="border-0 border-bottom px-1"
                style="width:140px;outline:none;background:transparent" placeholder="Nơi cấp" required>
        </p>

        <p class="mb-1">
            Hộ khẩu thường trú:
            <input type="text" name="ho_khau" class="border-0 border-bottom px-1"
                style="width:380px;outline:none;background:transparent"
                placeholder="Nhập địa chỉ hộ khẩu" required pattern=".*\S.*" title="Không được để trống">
        </p>

        <p class="mb-1">
            Học lớp:
            <input type="text" name="lop" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent" value="{{ $user->classid }}" readonly>
            &nbsp; Khoa:
            <input type="text" name="khoa" class="border-0 border-bottom px-1"
                style="width:200px;outline:none;background:transparent" value="{{ $khoaTen }}" readonly>
            &nbsp; MSSV:
            <input type="text" name="mssv" class="border-0 border-bottom px-1"
                style="width:110px;outline:none;background:transparent" value="{{ $user->studentid }}" readonly>
        </p>

        <p class="mb-1">
            Năm thứ:
            <input type="number" name="nam_thu" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                min="1" max="6" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            &nbsp; Học kỳ:
            <input type="number" name="hoc_ky" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                min="1" max="3" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            &nbsp; Năm học:
            <input type="text" name="nam_hoc" class="border-0 border-bottom px-1"
                style="width:100px;outline:none;background:transparent"
                placeholder="2024-2025" pattern="^\d{4}-\d{4}$" title="Định dạng: yyyy-yyyy" required>
            &nbsp; Khóa:
<input type="text" name="khoa_hoc" class="border-0 border-bottom px-1"
    style="width:100px;outline:none;background:transparent"
    value="{{ $user->academic_year }}" readonly>
        </p>

        <p class="mb-1">
            Ngành học:
            <input type="text" name="nganh_hoc" class="border-0 border-bottom px-1"
                style="width:200px;outline:none;background:transparent" placeholder="Tên ngành" required>
        </p>

        <p class="mb-1">
            Thời gian đào tạo: từ tháng
            <input type="number" name="thang_bat_dau" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="mm" min="1" max="12" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            năm
            <input type="number" name="nam_bat_dau" class="border-0 border-bottom px-1"
                style="width:55px;outline:none;background:transparent"
                placeholder="yyyy" min="2000" max="{{ date('Y') }}" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            đến tháng
            <input type="number" name="thang_ket_thuc" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="mm" min="1" max="12" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            năm
            <input type="number" name="nam_ket_thuc" class="border-0 border-bottom px-1"
                style="width:55px;outline:none;background:transparent"
                placeholder="yyyy" min="{{ date('Y') }}" max="2050" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
        </p>

        <hr class="my-3">
        <p class="mb-2 fw-bold">Đối tượng ưu đãi:</p>
        <div class="ms-3 mb-3">
            <label class="d-block mb-1"><input type="checkbox" name="doi_tuong[]" value="con_liet_si"> &nbsp; Con liệt sĩ</label>
            <label class="d-block mb-1"><input type="checkbox" name="doi_tuong[]" value="con_thuong_binh"> &nbsp; Con thương binh / bệnh binh (≥ 61%)</label>
            <label class="d-block mb-1"><input type="checkbox" name="doi_tuong[]" value="con_anh_hung"> &nbsp; Con Anh hùng lực lượng vũ trang / Anh hùng lao động</label>
            <label class="d-block mb-1"><input type="checkbox" name="doi_tuong[]" value="nguoi_co_cong"> &nbsp; Người có công với cách mạng được hưởng trợ cấp hàng tháng</label>
            <label class="d-block mb-1"><input type="checkbox" name="doi_tuong[]" value="chat_doc"> &nbsp; Con của người bị nhiễm chất độc hoá học</label>
            <div class="mt-1">
                Đối tượng khác:
                <input type="text" name="doi_tuong_khac" class="border-0 border-bottom px-1"
                    style="width:280px;outline:none;background:transparent" placeholder="Ghi rõ nếu có">
            </div>
        </div>

        <p class="mb-1">
            Giấy chứng nhận số:
            <input type="text" name="so_gcn" class="border-0 border-bottom px-1"
                style="width:150px;outline:none;background:transparent" placeholder="Số GCN" required>
            &nbsp; Cấp ngày:
            <input type="number" name="gcn_ngay" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="dd" min="1" max="31" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            /
            <input type="number" name="gcn_thang" class="border-0 border-bottom px-1"
                style="width:35px;outline:none;background:transparent"
                placeholder="mm" min="1" max="12" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
            /
            <input type="number" name="gcn_nam" class="border-0 border-bottom px-1"
                style="width:55px;outline:none;background:transparent"
                placeholder="yyyy" min="1990" max="{{ date('Y') }}" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
        </p>

        <p class="mb-1">
            Do cơ quan:
            <input type="text" name="co_quan_cap" class="border-0 border-bottom px-1"
                style="width:300px;outline:none;background:transparent" placeholder="Tên cơ quan cấp" required>
            cấp.
        </p>

        <div class="d-flex justify-content-between mt-4">
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
                <p class="fw-bold mb-0">Người làm đơn</p><br><br><br>
                <p>{{ $user->last_name }} {{ $user->first_name }}</p>
            </div>
            <div class="text-center" style="width:40%">
                {{-- ✅ Dùng $form->signtitle / $form->signname --}}
                <p class="fw-bold mb-0">{{ strtoupper($form->signtitle ?? 'TRƯỞNG PHÒNG CTSV') }}</p>
                <br><br><br>
                <p>(Ký, ghi rõ họ tên, đóng dấu)</p>
                @if($form->signname ?? null)
                    <p>{{ $form->signname }}</p>
                @endif
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

    @endif {{-- end @if(!$cannotSubmit) --}}
</div>

<script>
function toggleDiaChi(val) {
    const box   = document.getElementById('dia-chi-buu-dien');
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
</script>
@endsection