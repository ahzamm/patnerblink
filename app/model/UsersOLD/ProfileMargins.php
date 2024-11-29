<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ProfileMargins extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "profile_margins";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'groupname','manager_id','resellerid','dealerid','sub_dealer_id','trader_id','margin'
,    ];

   
}
