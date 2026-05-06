<?php
// ===== EtpForm.php =====
namespace Modules\XacNhanSV\Models;

use Illuminate\Database\Eloquent\Model;

class EtpForm extends Model
{
    protected $table   = 'etp_form';
    protected $primaryKey = 'formid';
    public $timestamps = false;

    protected $fillable = [
        'name', 'description', 'url',
        'signtitle', 'signname', 'schoolid', 'schoolname',
    ];

    public function details()
    {
        return $this->hasMany(EtpFormDetail::class, 'formid', 'formid')
                    ->orderBy('fdetail_order');
    }

    public function submissions()
    {
        return $this->hasMany(EtpFormStudent::class, 'formid', 'formid');
    }
}