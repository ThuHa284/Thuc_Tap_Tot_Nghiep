@extends('layouts.master')

@section('title', 'Lịch sử làm bài')

@section('content')
<style>
    .main {
        padding-top: 20px !important;
    }

    .history-card {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        animation: fadeIn 0.5s ease-out;
    }

    .history-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        padding: 30px;
        color: white;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 15px 20px;
    }

    .table tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .score-badge {
        font-weight: 700;
        color: #2563eb;
        background-color: #eef2ff;
        padding: 5px 10px;
        border-radius: 6px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Kết quả các bài thi</h2>
        <a href="{{ route('thitracnghiem.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm history-card">
        <!-- <div class="history-header">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-20 p-3 rounded-circle me-3">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Danh sách các lượt thi</h5>
                    <p class="opacity-75 mb-0 small">Dưới đây là kết quả của các bài thi bạn đã tham gia.</p>
                </div>
            </div>
        </div> -->

        <div class="card-body p-0">
            @if(count($history) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">STT</th>
                            <th>Tên đề thi</th>
                            <!-- <th class="text-center">Số câu đúng</th> -->
                            <!-- <th class="text-center">Điểm số</th>
                            <th class="text-center">Trạng thái</th> -->
                            <th class="text-center">Ngày thi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $index => $row)
                        <tr>
                            <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $row->quiz->quiz_name ?? 'Đề thi đã bị xóa' }}</div>
                                <div class="small text-muted">Mã đề: #{{ $row->quid }}</div>
                            </td>
                            <!-- <td class="text-center">
                                <span class="text-dark">{{ $row->score_obtained }}</span>
                            </td> -->
                            <!-- <td class="text-center">
                                <span class="score-badge">{{ $row->percentage_obtained }}</span>
                            </td> -->
                            <!-- <td class="text-center">
                                @if($row->result_status == 'Pass')
                                <span class="badge-status bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-check-circle-fill me-1"></i> ĐẠT
                                </span>
                                @else
                                <span class="badge-status bg-danger bg-opacity-10 text-danger">
                                    <i class="bi bi-x-circle-fill me-1"></i> CHƯA ĐẠT
                                </span>
                                @endif
                            </td> -->
                            <td class="text-center text-muted small">
                                {{ \Carbon\Carbon::createFromTimestamp($row->end_time)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
                <h5 class="mt-3 fw-bold text-dark">Bạn chưa tham gia bài thi nào!</h5>
                <p class="text-muted">Kết quả của các bài thi bạn tham gia sẽ được hiển thị tại đây.</p>
                <a href="{{ route('thitracnghiem.quiz.list') }}" class="btn btn-primary rounded-pill px-4 mt-2">Bắt đầu thi ngay</a>
            </div>
            @endif
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection