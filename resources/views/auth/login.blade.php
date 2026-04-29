<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="height:100vh">
        <div class="card p-4 shadow" style="width:350px">
            <h4 class="text-center mb-3">Đăng nhập</h4>

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="mb-3">
                    <input type="text" name="studentid" class="form-control" placeholder="Mã sinh viên">
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                </div>

                <button class="btn btn-primary w-100">Đăng nhập</button>
            </form>

            @if($errors->any())
            <div class="text-danger mt-2">
                {{ $errors->first() }}
            </div>
            @endif
        </div>
    </div>

</body>

</html>