<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TransactionHistory extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "transaction_history";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'invoice_id', 'tran_id', 'Payment_Remaining', 'Remaining_balance',
        'payment_tansfer', 'payment_recieve', 'payment_deducted', 'discount',
        'deduct_detail', 'datetime', 'action_ip', 'action_by', 'Paid_by', 'recv_type',
        'cheque_no', 'comment'

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
