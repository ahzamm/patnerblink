<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsedScratchCardsLog extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "used_scratch_cards_log";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dealerid', 'sub_dealer_id', 'userid', 'timestamp', 'activity', 'error',
        'ipaddress', 'code', 'serial', 'batchid'

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
