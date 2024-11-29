<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserInfo extends Authenticatable
{
    //
    use Notifiable;

    protected $table = "user_info";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','username', 'password', 'firstname', 'lastname', 'mac_address', 'email', 'nic', 'passport', 'overseas_cnic', 'homephone', 'mobilephone', 'address', 'creationdate', 'creationby', 'creationbyip', 'dealerid', 'sub_dealer_id', 'resellerid', 'manager_id', 'status', 'profile', 'area', 'disabled', 'active','disabled_old_profile', 'disabled_expired', 'disabled_date','qt_total','qt_used', 'verified','never_expire'
    ];

    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

	//
//	public function getPasswordAttribute(){
//	return $this->attributes['password'];
//	}
//	public function setPasswordAttribute($value){
//	return $this->attributes['password'] = md5($value);
//	}	
	//

  

    ///// for manager
    public function manager_reseller(){
       $instance =  $this->hasMany(UserInfo::class,'manager_id','manager_id'); // class, fk,pk
       $instance->where('status', '=' ,'reseller');
       return $instance;
    }
    ///


    ///// for reseller
    public function reseller_dealer(){
       $instance =  $this->hasMany(UserInfo::class,'resellerid','resellerid'); // class, fk,pk
       $instance->where('status', '=' ,'dealer');
       return $instance;
    }
    public function reseller_sub_dealer(){
       $instance =  $this->hasMany(UserInfo::class,'resellerid','resellerid'); // class, fk,pk
       $instance->where('status', '=' ,'subdealer');
       return $instance;
    }
    ////

    /// for dealer
    public function dealer_profile_rates(){
       $instance =  $this->hasMany(DealerProfileRate::class,'dealerid','dealerid')->orderby('groupname'); // class, fk,pk
       return $instance;      
    }
	
	public function dealer_profile_wise_expired_users($profileGroupname){
		$instance =  $this->hasMany(UserInfo::class,'dealerid','dealerid'); // class, fk,pk
		//$instance = $instance->where('status', '=' ,'user');
		$instance = $instance->where([['status', '=' ,'user'],['never_expire', '=' ,NULL],['profile','!=','DISABLED'],['profile','!=','NEW'],['profile','!=','EXPIRED']]);
		$instance = $instance->select('username')->where('profile' , 'LIKE', '%-'.$profileGroupname.'k')->with('user_status_info_expired');
		$instance = $instance->where('sub_dealer_id' , '=', NULL);
       return $instance;
	}
	public function sub_dealer_profile_wise_expired_users($profileGroupname){
		$instance =  $this->hasMany(UserInfo::class,'sub_dealer_id','sub_dealer_id'); // class, fk,pk
		//$instance = $instance->where('status', '=' ,'user');
		$instance = $instance->where([['status', '=' ,'user'],['never_expire', '=' ,NULL],['profile','!=','DISABLED'],['profile','!=','NEW'],['profile','!=','EXPIRED']]);
		$instance = $instance->select('username')->where('profile' , 'LIKE', '%-'.$profileGroupname.'k')->with('user_status_info_expired');
       return $instance;
	}
  public function trader_profile_wise_expired_users($profileGroupname){
    $instance =  $this->hasMany(UserInfo::class,'trader_id','trader_id'); // class, fk,pk
    //$instance = $instance->where('status', '=' ,'user');
    $instance = $instance->where([['status', '=' ,'user'],['never_expire', '=' ,NULL],['profile','!=','DISABLED'],['profile','!=','NEW'],['profile','!=','EXPIRED']]);
    $instance = $instance->select('username')->where('profile' , 'LIKE', '%-'.$profileGroupname.'k')->with('user_status_info_expired');
       return $instance;
  }
	public function dealer_expired_users(){
		$instance =  $this->hasMany(UserInfo::class,'dealerid','dealerid'); // class, fk,pk
		$instance = $instance->where([['status', '=' ,'user'],['never_expire', '=' ,NULL],['profile','!=','DISABLED'],['profile','!=','NEW'],['profile','!=','EXPIRED']]);
		$instance = $instance->where('sub_dealer_id' , '=', '');
       return $instance;
	}
	public function sub_dealer_expired_users(){
		$instance =  $this->hasMany(UserInfo::class,'sub_dealer_id','sub_dealer_id'); // class, fk,pk
		$instance = $instance->where([['status', '=' ,'user'],['never_expire', '=' ,NULL],['profile','!=','DISABLED'],['profile','!=','NEW'],['profile','!=','EXPIRED']]);
       return $instance;
	}
  public function trader_expired_users(){
    $instance =  $this->hasMany(UserInfo::class,'trader_id','trader_id'); // class, fk,pk
    $instance = $instance->where([['status', '=' ,'user'],['never_expire', '=' ,NULL],['profile','!=','DISABLED'],['profile','!=','NEW'],['profile','!=','EXPIRED']]);
       return $instance;
  }

     /// for manager
     public function manager_profile_rate(){
       $instance =  $this->hasMany(ManagerProfileRate::class,'manager_id','manager_id')->orderby('groupname'); // class, fk,pk
       return $instance;      
    }

    /// for Reseller
     public function reseller_profile_rate(){
       $instance =  $this->hasMany(ResellerProfileRate::class,'resellerid','resellerid')->orderby('groupname'); // class, fk,pk
       return $instance;      
    }


    // for sub-dealer
     public function subdealer_profile_rates(){
       $instance =  $this->hasMany(SubdealerProfileRate::class,'sub_dealer_id','sub_dealer_id')->orderby('groupname'); // class, fk,pk
       return $instance;      
    }

     // for traders
     public function trader_profile_rates(){
       $instance =  $this->hasMany(TraderProfileRate::class,'trader_id','trader_id')->orderby('groupname'); // class, fk,pk
       return $instance;      
    }

    //for get expiry relationship
     public function user_status_info(){
       $instance =  $this->hasOne(ExpireUser::class,'username','username'); // class, fk,pk
       return $instance;      
    }
	// public function user_status_info_expired(){
 //       $instance =  $this->hasOne(UserStatusInfo::class,'username','username'); // class, fk,pk
	//      $instance = $instance->where('card_expire_on', '<', date('Y/m/d'));
 //       return $instance;      
 //    }

    public function user_status_info_expired(){
       $instance =  $this->hasOne(ExpireUser::class,'username','username'); // class, fk,pk
	     $instance = $instance->where('status', '=', 'expire');
       return $instance;      
    }
	
	// for RaduserGroup:
	public function rad_user_group(){
       $instance =  $this->hasOne(RaduserGroup::class,'username','username'); // class, fk,pk
       return $instance;		
	}


    public function Online()
    {
          $instance =  $this->hasMany(RadAcct::class,'username','username'); // class, fk,pk
       return $instance;
    }

  public function rad_acct_user(){
     $instance =  $this->hasMany(RadAcct::class,'username','username'); // class, fk,pk
     $instance = $instance->where('acctstoptime','=','');
       return $instance;   
  }
	

	
	//dealer
	public function dealer_users(){
    $instance =  $this->hasMany(UserInfo::class,'dealerid','dealerid'); // class, fk,pk
	  $instance = $instance->select('username')->where('status', '=', 'user')->with('rad_acct_user');
       return $instance;		
	}

	public function dealer_radAcct_users(){
    $instance =  $this->hasMany(UserInfo::class,'dealerid','dealerid'); // class, fk,pk
	  $instance = $instance->select('username')->where('status', '=', 'user');
       return $instance;		
	}
	
	
	// sub dealer
	public function sub_dealer_users(){
    $instance =  $this->hasMany(UserInfo::class,'sub_dealer_id','sub_dealer_id'); // class, fk,pk
	  $instance = $instance->select('username')->where('status', '=', 'user');
       return $instance;		
	}
	
	
	////// billing
	public function dealer_billing(){
		$instance = $this->hasMany(AmountBillingInvoice::class,'dealerid','dealerid'); // fk,pk
		return $instance;		
	}
}
