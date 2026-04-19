<?php

namespace Modules\TinTuc\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\TinTuc\Database\Factories\TinTucFactory;

class TinTuc extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tintuc';
    protected $fillable = ['title','img','content','status','date1','loaitin_id'];

    public function loaitin(){
        return $this->belongsTo(LoaiTin::class,'loaitin_id','id');
    }

    // protected static function newFactory(): TinTucFactory
    // {
    //     // return TinTucFactory::new();
    // }
}
