<?php

namespace Modules\XacNhanSV\Models;

use Illuminate\Database\Eloquent\Model;

class EtpFormStudent extends Model
{
    protected $table   = 'etp_form_student';
    public $timestamps = true;

    protected $fillable = [
        'uid', 'studentid', 'formid', 'date1', 'date2',
        'note', 'data', 'status', 'get_at', 'ReceivingAddress',
    ];

    protected $casts = [
        'data'   => 'array',
        'date1'  => 'datetime',
        'date2'  => 'datetime',
        'status' => 'integer',
    ];

    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    public static function statusLabel(int $status): string
    {
        return match ($status) {
            self::STATUS_PENDING  => 'Chờ duyệt',
            self::STATUS_APPROVED => 'Đã duyệt',
            self::STATUS_REJECTED => 'Từ chối',
            default               => 'Không xác định',
        };
    }

    public static function statusBadge(int $status): string
    {
        return match ($status) {
            self::STATUS_PENDING  => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default               => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabel($this->status);
    }

    public function form()
    {
        return $this->belongsTo(EtpForm::class, 'formid', 'formid');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'uid', 'uid');
    }

    public function fileDetails()
    {
        return $this->hasMany(EtpFormStudentDetail::class, 'form_student_id', 'id');
    }
}