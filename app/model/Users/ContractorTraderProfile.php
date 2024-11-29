<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ContractorTraderProfile extends Model
{
    //


    protected $table = "contractor_trader_profiles";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       // 
      'id', 'nas_shortname', 'contractor_profile', 'trader_profile', 'created_on'
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
