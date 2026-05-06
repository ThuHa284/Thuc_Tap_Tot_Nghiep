<?php

namespace Modules\TinTuc\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\TinTuc\Database\Factories\LoaiTinFactory;

class LoaiTin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table ='loaitin';
    protected $fillable = ['name'];

    public function tintucs(){
        return $this->hasMany(TinTuc::class,'loaitin_id','id');
    }

    // protected static function newFactory(): LoaiTinFactory
    // {
    //     // return LoaiTinFactory::new();
    // }
}
