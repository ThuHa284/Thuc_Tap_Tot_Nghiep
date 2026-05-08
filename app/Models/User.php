<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'savsoft_users';
    protected $primaryKey = 'uid';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'studentid',
        'first_name',
        'last_name',
        'gid',
        'su',
        'note',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin()
    {
        return $this->su == 1;
    }

    public function isStudent()
    {
        return $this->su == 0;
    }
    public function isCollaborator()
    {
        return $this->su == -1;
    }


    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name;
    }
}
