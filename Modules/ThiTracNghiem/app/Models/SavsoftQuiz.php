<?php

namespace Modules\ThiTracNghiem\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftQuiz extends Model
{
    protected $table = 'savsoft_quiz';
    protected $primaryKey = 'quid';
    public $timestamps = false;
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(SavsoftGroup::class, 'gids', 'gid');
    }
}