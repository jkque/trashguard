<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','first_name','last_name', 'email', 'password', 'type', 'phone_number','token','profile_photo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return ucfirst($this->attributes['first_name']) . ' ' . ucfirst($this->attributes['last_name']);
    }

    public function notifications(){
        return $this->hasMany('\App\Notifications');
    }
}
