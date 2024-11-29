<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AmountTransactions extends Model
{
    //
    use Notifiable;

    protected $table = "amount_transactions";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

       'sender', 'receiver', 'admin', 'amount', 'last_remaining_amount','date', 'action_by_user'
       ,'action_by_admin', 'action_ip'

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
