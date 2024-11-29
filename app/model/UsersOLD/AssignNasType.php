<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AssignNasType extends Model
{
    //
    use Notifiable;

    protected $table = "assigned_nas_type";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'dealerid', 'resellerid', 'manager_id','sub_dealer_id', 'nas_type'

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
