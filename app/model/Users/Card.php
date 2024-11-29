<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Card extends Model
{
    //


    protected $table = "card";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'card_no','status','m_rate','rate','dealerrate','subdealerrate','s_acc_rate','sst','adv_tax','traderrate','t_acc_rate','commision','m_acc_rate','r_acc_rate','d_acc_rate','c_sst','c_adv','c_charges','c_rates','profit','profile','name','taxname','trader_id','dealerid','resellerid','sub_dealer_id','final_rate_acc','manager_id','date','created_at'
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
