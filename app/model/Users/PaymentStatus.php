<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PaymentStatus extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "payments_status";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoiceid', 'username', 'dealerid', 'resellerid', 'transfer_to','transfer_from'
        ,'transfer_type', 'total_payment', 'remaining_payment', 'balance', 'expiry_date'
        ,'status', 'transfer_ip', 'transfer_date', 'transfer_time'

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
