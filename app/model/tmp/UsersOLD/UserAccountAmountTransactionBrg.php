<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserAccountAmountTransactionBrg extends Model
{
    //
    use Notifiable;

    protected $table = "user_account_amount_transaction_brg";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

       'amount_transaction_id', 'last_remaining_debit','last_remaining_credit'

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
