<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;


class AmountUserRechargeLog extends Model
{
    //
  

    protected $table = "amount_user_recharge_log";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

      'id', 'dealerid', 'sub_dealer_id', 'username', 'timestamp', 'activity', 'error', 'ipaddress', 'profile'

    ];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
