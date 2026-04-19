<?php

namespace Modules\DiemDanh\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftAttendance extends Model
{
    protected $table = 'savsoft_attendance'; 
    protected $primaryKey = 'id';
    
    // Các cột cho phép thêm dữ liệu vào
    protected $fillable = [
        'studentid', 'student_name', 'class_id', 
        'faculty_id', 'date_class', 'subject', 
        'time1', 'info_status', 'course_name'
    ];

    public $timestamps = false; 
}