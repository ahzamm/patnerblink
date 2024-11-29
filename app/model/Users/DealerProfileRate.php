<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DealerProfileRate extends Model
{

    protected $table = "dealer_profile_rate";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'groupname', 'dealerid', 'rate', 'sst', 'adv_tax', 'final_rates', 'ip_rates', 'physical_cards', 
        'show_sub_dealer', 'billing_type', 'verify','sstpercentage','advpercentage','discount','username'

    ];

     public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      
    ];

    // 
    public function profile(){
        return $this->hasOne(Profile::class,'groupname','groupname'); // model,f_k,p_k
    }
}
