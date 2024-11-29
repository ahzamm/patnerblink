<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Brand extends Model
{
    //


    protected $table = "brands";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'brand_name','created_at','deleted_at','image','creation_by'
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
