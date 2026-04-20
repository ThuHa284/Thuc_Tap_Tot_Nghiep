<?php

namespace Modules\XacNhanSV\Models;

use Illuminate\Database\Eloquent\Model;

class EtpFormStudentDetail extends Model
{
    protected $table = 'etp_form_student_detail';
    public $timestamps = false;

    // thêm các cột mới vào fillable
    protected $fillable = [
        'form_student_id',
        'filename',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function submission()
    {
        return $this->belongsTo(EtpFormStudent::class, 'form_student_id', 'id');
    }
}
