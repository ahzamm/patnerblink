<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class ResellerProfileRate extends Model
{
    //
    use Notifiable;

    protected $table = "reseller_profile_rate";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $fillable = [
        'groupname', 'resellerid', 'rate', 'sst', 'adv_tax', 'final_rates', 'ip_rates',
        'billing_type', 'verify'

    ];

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
