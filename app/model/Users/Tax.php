<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Tax extends Model
{
    //


    protected $table = "taxation";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
	
}
