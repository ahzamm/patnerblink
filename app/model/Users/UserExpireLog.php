<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserExpireLog extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "user_expire_log";
    public $timestamp = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'profile_on_expiry', 'expired_on', 'log_date'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    
}
