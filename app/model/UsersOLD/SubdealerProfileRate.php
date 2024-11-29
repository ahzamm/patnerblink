<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;


class SubdealerProfileRate extends Model
{


    protected $table = "subdealer_profile_rate";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_dealer_id','dealerid','groupname','rate', 'sst', 'adv_tax', 'final_rates'

    ];
     public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
     public function profile(){
        return $this->hasOne(Profile::class,'groupname','groupname'); // model,f_k,p_k
    }
}
