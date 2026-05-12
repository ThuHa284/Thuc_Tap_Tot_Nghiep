@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">CHI TIẾT ĐIỂM DANH</h5>
        </div>
        <div class="card-body">
            <h4 class="card-title text-primary">{{ $category->category_name }}</h4>
            <p class="card-text">
                Tổng số lượt điểm danh:
                <span class="badge bg-success">{{ $attendances->count() }}</span>
                <span class="ms-2 badge bg-warning text-dark">CTXH: {{ number_format((float) $category->ctxh_days, 1) }}</span>
                @if((int)($category->training_points ?? 0) > 0)
                    <span class="ms-2 badge bg-info text-dark">Điểm rèn luyện: {{ (int) $category->training_points }}</span>
                @endif
                @if($category->attendance_start_at && $category->attendance_end_at)
                    <span class="ms-2 badge bg-secondary">Khung: {{ $category->attendance_start_at->format('H:i d/m/Y') }} - {{ $category->attendance_end_at->format('H:i d/m/Y') }}</span>
                @endif
            </p>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col" style="width: 20%;">Mã sinh viên</th>
                            <th scope="col">Họ và tên</th>
                            <th scope="col" style="width: 25%;">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $attendance->studentid }}</td>
                                <td>
                                    @if(!empty($attendance->display_name))
                                        {{ $attendance->display_name }}
                                    @else
                                        <span class="text-danger fw-bold">Chưa có tên</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->time1->format('H:i:s - d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Chưa có sinh viên nào điểm danh cho sự kiện này.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <hr>
            <a href="{{ route('diemdanh.create_event') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>
@endsection
