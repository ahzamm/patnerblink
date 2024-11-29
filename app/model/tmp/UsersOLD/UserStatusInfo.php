<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;



class UserStatusInfo extends Model
{
    //
    

    protected $table = "user_status_info";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'card_charge_on', 'card_expire_on', 'expire_datetime','card_charge_by', 'card_charge_by_ip'

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
