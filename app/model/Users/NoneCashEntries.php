<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NoneCashEntries extends Model
{
    //
    use Notifiable;

    protected $table = "none_cash_entries";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

       'sender', 'receiver', 'admin', 'amount','date','action_by_admin', 'paid_by','detail','action_ip'

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
