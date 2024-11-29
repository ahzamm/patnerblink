<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserAccount extends Model
{
    //
    use Notifiable;

    protected $table = "user_account";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

       'username','status', 'credit', 'debit','action_by'
       ,'action_ip','action_at'

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
