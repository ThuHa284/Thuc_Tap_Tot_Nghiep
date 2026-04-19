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
}
