<?php

namespace Modules\ThiTracNghiem\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftGroup extends Model
{
    protected $table = 'savsoft_group';
    protected $primaryKey = 'gid';
    public $timestamps = false;
    protected $guarded = [];
}
