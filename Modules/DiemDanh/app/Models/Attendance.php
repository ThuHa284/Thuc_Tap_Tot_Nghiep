<?php

namespace Modules\DiemDanh\Models;

use Modules\DiemDanh\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'savsoft_attendance';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'studentid', 'student_name', 'class_id',
        'faculty_id', 'date_class', 'subject',
        'time1', 'info_status', 'info_staff', 'course_name', 'cid', 'point'
    ];

    protected $casts = [
        'time1' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cid', 'cid');
    }
}


