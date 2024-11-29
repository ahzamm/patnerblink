<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Marketing extends Model
{
    //


    protected $table = "marketings";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'reseller_id','created_at','category','file_name','short_description','status','updated_at'
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
