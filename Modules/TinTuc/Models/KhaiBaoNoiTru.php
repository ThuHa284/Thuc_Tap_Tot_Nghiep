<?php

namespace Modules\TinTuc\Models;

use Illuminate\Database\Eloquent\Model;

class KhaiBaoNoiTru extends Model
{
    protected $table = 'khai_bao_noi_tru';
    protected $fillable = [
        'ho_ten',
        'mssv',
        'so_dien_thoai_sv',
        'dia_chi_hien_tai',
        'ten_chu_tro',
        'so_dien_thoai_chu_tro',
        'ngay_vao_tro',
        'ghi_chu',
        'trang_thai',
    ];

    public function getTrangThaiTextAttribute()
    {
        switch ($this->trang_thai) {
            case 0:
                return '<span class="badge bg-danger">Từ chối</span>';
            case 1:
                return '<span class="badge bg-warning text-dark">Chờ duyệt</span>';
            case 2:
                return '<span class="badge bg-success">Đã duyệt</span>';
            default:
                return '<span class="badge bg-secondary">Không xác định</span>';
        }
    }
}
