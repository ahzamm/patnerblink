<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class DataAgreement extends Model
{
    //


    protected $table = "data_agreement";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
      
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
