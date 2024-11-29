<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class StaticipBill extends Model
{


    protected $table = "static_ips_bill";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
     'username', 'numberofips','resellerid','rates','date','amount','status'

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
