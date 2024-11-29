<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class UserIPStatus extends Model
{
    //
    use Notifiable;

    protected $table = "user_ip_status";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'ip', 'type'

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
