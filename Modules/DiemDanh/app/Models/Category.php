<?php

namespace Modules\DiemDanh\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\DiemDanh\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'savsoft_category';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cid';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_name', 'ctxh_days'];
    /**
     * Get the attendances for the category.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'cid', 'cid');
    }
}