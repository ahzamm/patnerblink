<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class exceedData extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "quota_log";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data','msg','datetime','username','quota_profile'
    ];

   
}
