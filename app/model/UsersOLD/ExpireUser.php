<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class ExpireUser extends Model
{


    protected $table = "expired_users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
     'username', 'status','last_update'

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
