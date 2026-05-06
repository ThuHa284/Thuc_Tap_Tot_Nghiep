<?php

namespace Modules\DiemDanh\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\DiemDanh\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    
    protected $table = 'savsoft_category';

    
    protected $primaryKey = 'cid';

    
    public $timestamps = false;

    
    protected $fillable = ['category_name', 'ctxh_days'];
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'cid', 'cid');
    }
}

