<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserAmountBillingBrg extends Model
{
    //
    use Notifiable;

    protected $table = "user_amount_billing_invoice_brg";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

       'billing_invoice_id', 'last_remaining_amount'

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
