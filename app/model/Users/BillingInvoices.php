<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BillingInvoices extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "billing_invoice";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial', 'code', 'profile', 'assigned_on', 'username', 'dealerid', 
        'resellerid', 'date', 'time', 'invoiceid' 

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
