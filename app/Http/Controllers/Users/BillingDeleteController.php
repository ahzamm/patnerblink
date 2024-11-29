<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;
use App\model\Users\UserInfo;
use App\model\Users\AmountTransactions;
use App\model\Users\UserAmount;
use App\model\Users\UserStatusInfo;
use App\model\Users\ExpireUser;
use App\model\Users\RaduserGroup;
use App\model\Users\AmountBillingInvoice;


class BillingDeleteController extends Controller
{
	public function deleteTransferPost(Request $request)
	{
		// 
		$id = $request->id;
		// 
		$amount_transactions_detail = DB::table('amount_transactions')->where('id', $id)->first();
		$amount = $amount_transactions_detail->amount + $amount_transactions_detail->cash_amount;
		//
		$starttimestamp = strtotime($amount_transactions_detail->date);
		$endtimestamp = strtotime(date('Y-m-d H:i:s'));
		$difference = abs($endtimestamp - $starttimestamp)/3600;
		//
		if($difference >= 48){
			return json_encode('This can not be deleted.');
		}
		//
		if($amount_transactions_detail->type == 'transfer'){
			//
			$receiverWallet = UserAmount::where('username',$amount_transactions_detail->receiver)->first();
			//
			if($receiverWallet->amount < $amount){
				return json_encode('Available amount is less then deleted amount');
			}
			//
			$receiverWallet->amount -= $amount;
			$receiverWallet->save();
			//
			$senderWallet = UserAmount::where('username',$amount_transactions_detail->sender)->first();
			$senderWallet->amount += $amount;
			$senderWallet->save();
			//
		}
		//
		if($amount_transactions_detail->type == 'transfer' || $amount_transactions_detail->type == 'reversal'){
			$amountTransaction = AmountTransactions::find($id);
			$amountTransaction->delete(); // Delete Entry from amount transaction table	
		}
		//
		return json_encode('deleted');
		// 
	}
	//
	public function billingDeleteUserFetch()
	{
		return view('users.Expired_id_10_25.id_expired_page');
	}
	public function billingDeleteUserPost(Request $request)
	{
		$count = 0;
		$notExist = array();
		$usernames = explode(',',$request->username);
		// dd($usernames);
		$usernames = array_filter($usernames);
		$usernames = array_values($usernames);
		
		foreach($usernames as $username){
			$checkUserExist = UserInfo::where('username',$username)->first();
			if(empty($checkUserExist)){
				array_push($notExist,$username); 
			}
		}
		
		if(!empty($notExist)){
			// dd($notExist);
			return json_encode(['users' => $notExist]);
		}
		foreach($usernames as $username){
		$chargeDate = '';
		$date=date('Y-m-d');
 		$today=date('d',strtotime(date($date))); // current Date

		 if($today == 10){
			$chargeDate=date("Y-m-25", strtotime("-1 month", strtotime($date)));
			$chargeDate2=date('Y-m-10');
		}elseif($today == 25){
			$chargeDate=date('Y-m-10');
			$chargeDate2=date('Y-m-25');
		}

		if($today == 10 || $today == 25){ // condition working on 10 and 25 date of every month
		$yesterday = date('Y-m-d',strtotime(date('Y-m-d') . "-1 days")); // one date minus from current date
		$user_status_info = UserStatusInfo::where('username',$username);
		$user_status_info->update(['card_expire_on' => $yesterday,'expire_datetime' => $yesterday.' 12:00:00']);
		$expired_user = ExpireUser::where('username',$username);
		$expired_user->update(['status' => 'expire']);
		$radusergroup = RaduserGroup::where('username',$username);
		$radusergroup->update(['groupname' => 'EXPIRED', 'name' => 'EXPIRED']);

		$amount_billing_invoice = AmountBillingInvoice::where('username',$username)->where('charge_on','>=',$chargeDate.' 12:00:00')->where('charge_on','<',$chargeDate2.' 12:00:00');
		// dd($amount_billing_invoice);
		$amount_billing_invoice->delete();
		// dd($amount_billing_invoice);
		}else{
			return json_encode(['error' => 'You can not expire any user before 10 or 25 date of every month']);
			// return redirect()->route('billingDeleteUserFetch')->with('errors', '');
		}
		$count++;
		}// end here of loop
		// redirect after complete all id expired
		return json_encode(['success' => $count.' ID`s Successfully Expired']);
	}
}
