<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BillingPayments extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "billing_payments";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoiceid', 'amount_payed', 'paid_by', 'date', 'time', 'received_by', 
        'status', 'by_ip'
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
