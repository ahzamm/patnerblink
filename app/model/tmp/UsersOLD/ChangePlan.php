<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class ChangePlan extends Model
{


    protected $table = "change_plan";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
     'username', 'change_plan','request_date','amount','plan_name','request_by'

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
