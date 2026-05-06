<?php

namespace Modules\XacNhanSV\Models;

use Illuminate\Database\Eloquent\Model;

class EtpFormDetail extends Model
{
    protected $table      = 'etp_form_detail';
    protected $primaryKey = 'fdetailid';
    public $timestamps    = false;

    protected $fillable = ['formid', 'label', 'fdetail_order'];

    public function form()
    {
        return $this->belongsTo(EtpForm::class, 'formid', 'formid');
    }
}