@extends('layouts.master')

@section('title', 'Trang chủ - CTSV')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-th-large me-2"></i>THÔNG TIN ĐÀO TẠO</h5>
    </div>
    <div class="card-body p-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 text-center">

            <div class="col">
                <a href="https://stu.edu.vn/vi/1/trang-chu.html" class="text-decoration-none">
                    <div class="card h-100 p-4 shadow-sm card-feature">
                        <div class="mb-3">
                            <i class="fas fa-university fa-3x text-primary"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Trường Đại học Công nghệ Sài Gòn</h6>

                    </div>
                </a>
            </div>

            <div class="col">
                <a href="https://stu.edu.vn/vi/304/chuong-trinh-dao-tao.html" class="text-decoration-none">
                    <div class="card h-100 p-4 shadow-sm card-feature">
                        <div class="mb-3">
                            <i class="fas fa-book fa-3x text-success"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Chương trình đào tạo</h6>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="https://stu.edu.vn/vi/302/bieu-do-giang-day-hoc-tap.html" class="text-decoration-none">
                    <div class="card h-100 p-4 shadow-sm card-feature">
                        <div class="mb-3">
                            <i class="fas fa-chart-line fa-3x text-info"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Biểu đồ Giảng dạy - Học tập</h6>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="https://thuvien.stu.edu.vn/" class="text-decoration-none">
                    <div class="card h-100 p-4 shadow-sm card-feature">
                        <div class="mb-3">
                            <i class="fas fa-university fa-3x text-warning"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Thư viện</h6>
                    </div>
                </a>
            </div>

        </div>
    </div>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-danger"><i class="fas fa-university me-2"></i>THÔNG TIN CÁC KHOA</h5>
        </div>
        <div class="card-body p-4">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 text-center">

                <div class="col">
                    <a href="https://stu.edu.vn/vi/388/gioi-thieu-khoa.html" class="text-decoration-none">
                        <div class="card h-100 p-3 shadow-sm card-faculty border-start border-primary border-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-faculty me-2 text-primary">
                                    <i class="fas fa-laptop-code fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 small">Công nghệ Thông tin</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="https://stu.edu.vn/vi/445/gioi-thieu.html" class="text-decoration-none">
                        <div class="card h-100 p-3 shadow-sm card-faculty border-start border-dark border-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-faculty me-2 text-dark">
                                    <i class="fas fa-tools fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 small">Cơ khí</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="https://stu.edu.vn/vi/425/gioi-thieu.html" class="text-decoration-none">
                        <div class="card h-100 p-3 shadow-sm card-faculty border-start border-warning border-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-faculty me-2 text-warning">
                                    <i class="fas fa-microchip fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 small">Điện - Điện tử</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="https://stu.edu.vn/vi/395/gioi-thieu-khoa.html" class="text-decoration-none">
                        <div class="card h-100 p-3 shadow-sm card-faculty border-start border-success border-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-faculty me-2 text-success">
                                    <i class="fas fa-chart-bar fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 small">Kinh tế - Quản trị</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="https://stu.edu.vn/vi/419/gioi-thieu.html" class="text-decoration-none">
                        <div class="card h-100 p-3 shadow-sm card-faculty border-start border-info border-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-faculty me-2 text-info">
                                    <i class="fas fa-building fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 small">Xây dựng</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="https://stu.edu.vn/vi/433/gioi-thieu.html" class="text-decoration-none">
                        <div class="card h-100 p-3 shadow-sm card-faculty border-start border-danger border-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-faculty me-2 text-danger">
                                    <i class="fas fa-flask fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 small">Công nghệ Thực phẩm</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="https://stu.edu.vn/vi/439/gioi-thieu.html" class="text-decoration-none">
                        <div class="card h-100 p-3 shadow-sm card-faculty border-start border-secondary border-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-faculty me-2 text-secondary">
                                    <i class="fas fa-palette fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 small">Design</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="https://stu.edu.vn/vi/411/gioi-thieu.html" class="text-decoration-none">
                        <div class="card h-100 p-3 shadow-sm card-faculty border-start border-primary-subtle border-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-faculty me-2 text-primary-subtle">
                                    <i class="fas fa-calculator fa-lg"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-0 small">Khoa học cơ bản</h6>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection