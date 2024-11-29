<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class City extends Model
{
    //


    protected $table = "cities";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'city_name','created_at','deleted_at'
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
