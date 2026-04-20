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
        'gid'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin()
    {
        return $this->gid == 0;
    }

    public function isStudent()
    {
        return in_array($this->gid, [1, 2, 3]);
    }


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}