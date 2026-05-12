<?php

namespace Modules\DiemDanh\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\DiemDanh\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;


    protected $table = 'savsoft_category';


    protected $primaryKey = 'cid';


    public $timestamps = false;


    protected $fillable = [
        'category_name',
        'ctxh_days',
        'training_points',
        'attendance_start_at',
        'attendance_end_at',
    ];

    protected $casts = [
        'attendance_start_at' => 'datetime',
        'attendance_end_at' => 'datetime',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'cid', 'cid');
    }
}
