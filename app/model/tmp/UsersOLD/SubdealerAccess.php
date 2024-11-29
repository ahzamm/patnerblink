<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SubdealerAccess extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "subdealeraccess";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parentModule','childModule'
    ];

   
}
