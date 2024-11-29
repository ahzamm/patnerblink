<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Error extends Model
{
    //


    protected $table = "error_log";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       // 'id', 'city_name','created_at','deleted_at'
      'id','username', 'message', 'trace', 'created_on', 'route_name', 'route_action' , 'consumer'
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
