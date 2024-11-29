<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MacIPBinding extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "mac_ip_binding";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'mac_address', 'ip_address', 'dealerid', 'sub_dealer_id', 
        'description', 'addedbyip'

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
