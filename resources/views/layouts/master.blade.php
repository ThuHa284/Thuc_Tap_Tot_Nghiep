<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CTSV - STU')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm" style="background-color: #004a99;">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="/">

                <img src="{{ asset('static/imgs/logostu.png') }}"
                    alt="Logo STU"
                    height="40"
                    class="d-inline-block align-top me-1"> <span class="text-white fw-bold">CTSV</span>
            </a>

            @auth
            <div class="ms-auto d-flex align-items-center text-white">
                <span class="me-3">Chào, <strong>{{ auth()->user()->name }}</strong></span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">Đăng xuất</button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-white sidebar shadow-sm p-3" style="min-height: 100vh;">
                <ul class="nav flex-column nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active mb-2" href="/"><i class="fas fa-home me-2"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark mb-2" href="{{ route('diemdanh.index') }}"><i class="fas fa-camera me-2 text-primary"></i>Điểm danh</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark mb-2" href="{{ route('thitracnghiem.index')}}"><i class="fas fa-pencil me-2 text-success"></i> Thi trắc nghiệm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark mb-2" href="{{ route('tintuc.index')}}"><i class="fas fa-bullhorn me-2 text-warning"></i> Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark mb-2" href="{{ route('xacnhansv.index')}}"><i class="fas fa-user-check me-2 text-info"></i> Xác nhận Sinh Viên</a>
                    </li>
                </ul>

            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @if(Route::is('home'))
                <div id="main-banner" class="mb-4 rounded-4 overflow-hidden shadow-sm">
                    <img src="{{ asset('static/imgs/banner_stu.png') }}"
                        class="img-fluid w-100"
                        style="max-height: 350px; object-fit: cover;"
                        alt="STU Banner">
                </div>
                @endif
                <div class="module-content bg-white p-4 rounded-3 shadow-sm" style="min-height: 80vh;">
                    @yield('content')
                </div>

            </main>
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>