<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class DisabledUser extends Model
{


    protected $table = "disabled_users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
     'username', 'status','updated_by' ,'last_update'

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
