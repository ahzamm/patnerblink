<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserVerification extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "user_verification";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'resellerid', 'dealerid', 'sub_dealer_id', 'mobile', 'cnic','nic_front','nic_back','mobile_status','update_on','ntn','overseas','intern_passport'

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
