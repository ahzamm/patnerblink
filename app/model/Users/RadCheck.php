<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class RadCheck extends Model
{
    //
    use Notifiable;

    protected $connection = 'mysql1'; // define second database for this model
   
    protected $table = "radcheck";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'attribute', 'op', 'value', 'dealerid', 'sub_dealer_id', 'svlan','status','manager_id','resellerid'

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
