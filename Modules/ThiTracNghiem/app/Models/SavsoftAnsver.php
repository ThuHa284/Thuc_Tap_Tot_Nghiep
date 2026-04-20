<?php

namespace Modules\ThiTracNghiem\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftAnsver extends Model
{
    protected $table = 'savsoft_ansvers';
    protected $primaryKey = 'aid';
    public $timestamps = false;
    protected $guarded = [];
}