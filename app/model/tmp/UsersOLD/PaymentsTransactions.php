<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PaymentsTransactions extends Model
{
    //
    use Notifiable;

    protected $table = "payments_transactions";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

       'sender', 'receiver', 'admin', 'amount', 'current_credit', 'current_debit',
//	   'discount',
	   'is_cash', 'check_number', 'bank_name','deposit_num'
       ,'date','action_by_admin', 'paid_by','detail','action_ip'

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
