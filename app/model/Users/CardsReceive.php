<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CardsReceive extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "card_recieve";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoiceid', 'tranid', 'payment_recieve', 'payment_deducted', 
        'remaining_balance', 'datetime', 'ip', 'recievrby', 'detail'

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
