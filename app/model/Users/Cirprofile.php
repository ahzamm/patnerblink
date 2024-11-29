<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Cirprofile extends Model
{
    //


    protected $table = "ciruserprofile";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'username','status','profile','name'
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
