<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'CTSV')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .header {
            background: #0d6efd;
            color: white;
            padding: 10px 20px;
            font-weight: bold;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            background: #f8f9fa;
            border-right: 1px solid #ddd;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            color: #333;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #e9ecef;
        }

        .main {
            flex: 1;
            padding: 20px;
        }

        .banner img {
            width: 100%;
            border-radius: 10px;
        }

        .card-box {
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header d-flex align-items-center">
        <img src="https://via.placeholder.com/40" class="me-2">
        TRƯỜNG ĐẠI HỌC CÔNG NGHỆ SÀI GÒN
    </div>

    <div class="d-flex">

        <div class="sidebar p-3">
            <h5>Tài khoản</h5>
            @auth
            <p>Xin chào, {{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100 mb-3">Đăng xuất</button>
            </form>
            @else
            <p>Chưa đăng nhập</p>
            @endauth


            <hr>

            <!-- Links module luôn hiển thị -->
            <a href="#">🏠 Trang chủ</a>
            <a href="#">📝 Thi trắc nghiệm</a>
            <a href="#">📷 Điểm danh</a>
            <a href="#">📰 Tin tức</a>

        </div>

        <!-- CONTENT -->
        <div class="main">

            <!-- Banner -->
            <div class="banner mb-4">
                <img src="https://via.placeholder.com/1200x300">
            </div>

            <!-- Section card -->
            <div class="card card-box mb-4">
                <div class="card-header fw-bold">THÔNG TIN QUẢN LÝ ĐÀO TẠO</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card text-center p-3">
                                <h6>Quy chế</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center p-3">
                                <h6>Kiểm định</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center p-3">
                                <h6>Biểu đồ học tập</h6>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center p-3">
                                <h6>Thông tin đào tạo</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')

        </div>

    </div>

</body>

</html>