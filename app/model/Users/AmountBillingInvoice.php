<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AmountBillingInvoice extends Model
{
    //


    protected $table = "amount_billing_invoice";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'username','userid','m_rate','rate','dealerrate','subdealerrate','s_acc_rate','sst','adv_tax','traderrate','t_acc_rate','commision','m_acc_rate','r_acc_rate','d_acc_rate','c_sst','c_adv','c_charges','c_rates','profit','receipt','receipt_num','profile','name','taxname','charge_on','trader_id','dealerid','resellerid','sub_dealer_id','manager_id','date','billing_type','card_no'
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
		return $this->hasOne(UserInfo::class,'username','username')->select('username','creationdate','firstname','lastname')->with('user_status_info');
	}
	
	public function profileR(){
		return $this->hasOne(Profile::class,'groupname','profile');
	}

     // For amount billing table
    public static function  amountBilling($username,$userid,$m_rate,$rate,$dealerrate,$subdealerrate,$s_acc_rate,$sst,$adv_tax,$traderrate,$t_acc_rate,$commision,$m_acc_rate,$r_acc_rate,$d_acc_rate,$c_sst,$c_adv,$c_charges,$c_rates,$profit,$receipt,$receipt_num,$profile,$name,$dasti_amount,$taxname,$charge_on,$trader_id,$dealerid,$resellerid,$sub_dealer_id,$manager_id,$date,$billing_type,$card_no)
    {
        
      $multyply = $d_acc_rate*2 + $sst + $adv_tax;
      $divide = $multyply/2;

      if($subdealerrate ==0){
        $dividesub=0;
      }else{
        $multyplysub = $s_acc_rate*2 + $sst + $adv_tax;
        $dividesub = $multyplysub/2;
      }
      
      $resellermultyply = $r_acc_rate*2 + $sst + $adv_tax;
      $resellerdivide = $resellermultyply/2;




      $data = DB::table('amount_billing_invoice')->insert([
           "username" => $username,
           "userid" => $userid,
           "m_rate" => $m_rate,
           "rate" => $resellerdivide,
           "dealerrate" => $divide,
           "subdealerrate" => $dividesub,
           "s_acc_rate" => $s_acc_rate,
           "sst" => $sst,
           "adv_tax" => $adv_tax,
            "traderrate" => $traderrate,
           "t_acc_rate" => $t_acc_rate,
           "commision" => $commision,
           "m_acc_rate" => $m_acc_rate,
           "r_acc_rate" => $r_acc_rate,
           "d_acc_rate" => $d_acc_rate,
           "c_sst" => $c_sst,
           "c_adv" => $c_adv,
            "c_charges" => $c_charges,
           "c_rates" => $c_rates,
           "profit" => $profit,
           "receipt" => $receipt,
           "receipt_num" => $receipt_num,
           "profile" => $profile,
           "name" => $name,
           "taxname" => $taxname,
            "trader_id" => $trader_id,
            "dealerid" => $dealerid,
           "resellerid" => $resellerid,
           "manager_id" => $manager_id,
           "sub_dealer_id" => $sub_dealer_id,
           "date" => $date,
           "charge_on" => $charge_on,
           "dasti_amount" => $dasti_amount,
           "billing_type" => $billing_type,
           "card_no" => $card_no
       ]);
            
    }
	
}
