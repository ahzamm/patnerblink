<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class ScratchCards extends Model
{
    //
    use Notifiable;

    protected $table = "scratch_cards";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     public $timestamps = false;
    protected $fillable = [
        'serial', 'code', 'batchid', 'createdon', 'createdby', 'assignedto',
        'sub_dealer_id', 'resellerid', 'profile', 'status', 'expirydate',
        'card_charge_on', 'anduser_expiry', 'card_type', 'card_status'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
