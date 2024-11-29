<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Invoices extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "invoices";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoiceid', 'dealerid', 'resellerid', 'invoice_data', 'date', 'time', 
        'created_by', 'created_by_ip', 'total', 'balance', 'status'
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
