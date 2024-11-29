<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserHistory extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "userhistory";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'radacctid', 'username', 'acctstarttime', 'acctstoptime', 'acctinputoctets', 'acctoutputoctets', 'last_update_time', 'updated', 'total_uploading', 'total_downloading'

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
