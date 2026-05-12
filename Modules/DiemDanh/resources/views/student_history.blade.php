@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">DANH SÁCH SỰ KIỆN SINH VIÊN ĐÃ THAM GIA</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sự kiện</th>
                            <th style="width: 220px;">Thời gian tham gia</th>
                            <th style="width: 130px;">CTXH</th>
                            <th style="width: 170px;">Điểm rèn luyện</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>{{ $event->category_name ?: 'Sự kiện không xác định' }}</td>
                                <td>
                                    @if($event->time1)
                                        {{ \Carbon\Carbon::parse($event->time1)->format('H:i:s - d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ number_format((float) ($event->ctxh_days ?? 0), 1) }}</td>
                                <td>{{ (int)($event->training_points ?? 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Bạn chưa tham gia sự kiện nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
