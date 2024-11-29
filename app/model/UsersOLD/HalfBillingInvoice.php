<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;


class HalfBillingInvoice extends Model
{
    //


    protected $table = "half_billing_invoice";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'username', 'rate','dealerrate','subdealerrate','sst','adv_tax', 'profile', 'charge_on', 'sub_dealer_id', 'dealerid', 'resellerid', 'date'
    ];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

	public function user_info(){
		return $this->hasOne(UserInfo::class,'username','username')->select('username','creationdate')->with('user_status_info');
	}
	
	public function profileR(){
		return $this->hasOne(Profile::class,'groupname','profile');
	}
	
}
