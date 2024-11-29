<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CardsTransaction extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "card_transaction";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'tran_id', 'invoice_id', 's_serial', 'e_serial', 'totalcard', 'cardprofile', 
       'totalamount', 'recievedamount', 'detail', 'datetime', 'ip', 'tranby', 'card_type'

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
