<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserAmount extends Model
{
    //
    use Notifiable;

    protected $table = "user_amount";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

       'username','status','amount'

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
