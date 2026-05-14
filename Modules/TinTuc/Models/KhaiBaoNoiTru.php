<?php

namespace Modules\TinTuc\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KhaiBaoNoiTru extends Model
{
    use HasFactory;

    protected $table = 'khai_bao_noi_tru';

    protected $fillable = [
        'ho_ten',
        'mssv',
        'so_dien_thoai_sv',
        'dia_chi_hien_tai',
        'loai_dia_chi',
        'ten_chu_tro',
        'so_dien_thoai_chu_tro',
        'ngay_vao_tro',
        'ghi_chu',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_vao_tro' => 'date',
    ];

    public function getTrangThaiTextAttribute(): string
    {
        return match ($this->trang_thai) {
            0 => '<span class="badge bg-danger rounded-pill"><i class="fas fa-times-circle me-1"></i>Từ chối</span>',
            1 => '<span class="badge bg-warning text-dark rounded-pill"><i class="fas fa-clock me-1"></i>Chờ duyệt</span>',
            2 => '<span class="badge bg-success rounded-pill"><i class="fas fa-check-circle me-1"></i>Đã duyệt</span>',
            default => '<span class="badge bg-secondary rounded-pill">Không xác định</span>',
        };
    }
}
