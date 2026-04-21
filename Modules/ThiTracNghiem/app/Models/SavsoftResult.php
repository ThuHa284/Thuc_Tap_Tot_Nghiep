<?php

namespace Modules\ThiTracNghiem\Models;

use Illuminate\Database\Eloquent\Model;

class SavsoftResult extends Model
{
    protected $table = 'savsoft_result';
    
    // Nếu bảng có khóa chính tên khác, bạn sửa lại nhé (thường là 'rid')
    protected $primaryKey = 'rid'; 
    
    public $timestamps = false;
    protected $guarded = [];

    // Tạo quan hệ với bảng savsoft_quiz
    public function quiz()
    {
        return $this->belongsTo(SavsoftQuiz::class, 'quid', 'quid');
    }
}