<?php

namespace App\model\admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Access extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "Access";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parentModule','childModule'
    ];

   
}
