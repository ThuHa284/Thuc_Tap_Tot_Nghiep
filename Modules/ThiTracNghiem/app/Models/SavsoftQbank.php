<?php

namespace Modules\ThiTracNghiem\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftQbank extends Model
{
    protected $table = 'savsoft_qbank';
    protected $primaryKey = 'qid';
    public $timestamps = false;
    protected $guarded = [];
}