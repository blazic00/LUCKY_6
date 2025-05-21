<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = ['username', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function getAuthIdentifierName()
    {
       // return 'username';
        return 'id';
    }
}
