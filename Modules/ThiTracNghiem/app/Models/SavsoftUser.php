<?php

namespace Modules\ThiTracNghiem\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftUser extends Model
{
    protected $table = 'savsoft_users';
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $guarded = [];
}
