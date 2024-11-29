<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\AmountTransactions;
use App\model\Users\PaymentsTransactions;
use App\model\Users\UserAmount;
use App\model\Users\UserAccount;
use App\model\Users\UserAmountBillingBrg;
use App\model\Users\UserAccountAmountTransactionBrg;
use App\model\Users\UserAccountPaymentTransactionBrg;
use App\model\admin\Admin;
class BillingController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

    }

     public function index($status){

        switch($status){
        	case "payments" :{

        		$paymentTransactions = PaymentsTransactions::where(['action_by_admin' => Auth::user()->username])->get();
        		$userAccount = UserAccount::where(['status' => 'reseller'])->get();
                return view('admin.users.view_payment',[
                'paymentTransactions' => $paymentTransactions,
                'userAccount' => $userAccount
                ]);
            }break;
            case "transactions" : {
                return view('admin.users.view_transaction');
            }break;
            case "transfer" : {
				$userCollection = UserInfo::select('username')->where('status', 'reseller')->get();
                $amountTransactions = AmountTransactions::all();
                return view('admin.users.transfer_amount',[
					'managerCollection' => $userCollection,
                    'amountTransactions' => $amountTransactions
				]);
            }break;
            case "viewtransfer" : {
				
                $amountTransactions = AmountTransactions::where(['action_by_admin' => Auth::user()->username])->get();
                return view('admin.users.view_transfer',[
					
                    'amountTransactions' => $amountTransactions
				]);
            }break;
            case "recieve" : {
				$userCollection = UserInfo::select('username')->where('status', 'reseller')->get();
				$paymentTransactions = PaymentsTransactions::where(['action_by_admin' => Auth::user()->username])->get();
                return view('admin.users.recieve_amount',[
					'managerCollection' => $userCollection,
					'paymentTransactions' => $paymentTransactions
				]);
            }break;
            case "revert" : {
                return view('admin.users.revert_amount');
            }break;
            default :{
                return redirect()->route('admin.dashboard');
            }
        }
    }

    public function store(Request $request ,$status)
    {
    	   switch($status){
            case "payments" : {
                return view('admin.users.view_payment');
            }
            case "transactions" : {
                return view('admin.users.view_transaction');
            }
            case "transfer" : {
				return $this->storeTransfer($request, $status);
            }

            case "recieve" : {
				return $this->storeRecieve($request, $status);
            }
            case "amount" : {
                return view('admin.users.amount_transaction');
            }
            default :{
                return redirect()->route('admin.dashboard');
            }
        }
    }
	
	public function storeTransfer(Request $request, $status){
		// data from request[sender(admin),reciever(user)]
			$transferAmount = $request->get('amount');
			$usernameReciever = $request->get('username');

			$usernameSender = Auth::user()->username; // here it's admin
		    $amountTransferuser = UserInfo::where(['username'=> $usernameReciever])->first();
		    $amountStatus = $amountTransferuser->status;
		//
		
		// store amount
			
	   $amountUser = UserAmount::where('username',$usernameReciever)->first();
	   if($amountUser){
		   $lastRemainingAmount = $amountUser->amount;
		   // user has amount: then update entry
			$amountUser->amount += $transferAmount;

			$amountUser->save();
	   }else{
		   $lastRemainingAmount = 0.0;
		   // user has no amount yet: than: add new entry
			$amountUser = new UserAmount();
			$amountUser->username = $usernameReciever;
			$amountUser->status = $amountStatus;
			$amountUser->amount = $transferAmount; // initial amount
			$amountUser->save();
	   } 
		
		// amount transaction
		$transactionAmount = new AmountTransactions();
		$transactionAmount->receiver = $usernameReciever;
		$transactionAmount->admin = $usernameSender; //username will be store
		$transactionAmount->amount = $transferAmount;
		$transactionAmount->last_remaining_amount = $lastRemainingAmount;
		$transactionAmount->date = Date('Y-m-d h:m:s');
		$transactionAmount->action_by_admin = $usernameSender; // here sender is admin
		$transactionAmount->action_ip = $request->ip();
		$transactionAmount->save();

		// entry in userAccountAmountTransactionBrg
		$userAccountAmountTransactionBrg = new UserAccountAmountTransactionBrg();
		$userAccountAmountTransactionBrg->amount_transaction_id = $transactionAmount->id;
		
		// tranfered amount: wiil be added as credir in user account
		$userAccount = UserAccount::where('username',$usernameReciever)->first();

		if($userAccount){
			//checking if transfer amount is greater then last debit
			// user account exists
			$lastDebit = $userAccount->debit;
			$lastCredit = $userAccount->credit;

			if($lastDebit > 0){
			 
				$adjustAmmount = $lastDebit - $transferAmount;

				if($adjustAmmount <= 0){
					$userAccount->debit = 0;
					$userAccount->credit += abs($adjustAmmount);
				}else{
					$userAccount->debit = abs($adjustAmmount);
				}

			

			}else{
				$userAccount->debit = 0;
				$userAccount->credit += $transferAmount;
			}
			// if($adjustAmmount > 0){
			// 	$userAccount->debit = $adjustAmmount;
			// 	$userAccount->credit = 0;
			// }elseif($adjustAmmount < 0){
			// 	$userAccount->credit = abs($adjustAmmount);
			// 	$userAccount->debit = 0;
			// }elseif($adjustAmmount == 0){
			// 	$userAccount->credit += 0;
			// 	$userAccount->debit = 0;
			// }
			
			$userAccount->action_by = $usernameSender; // here sender is admin
			$userAccount->action_ip = $request->ip();
			$userAccount->action_at = Date('Y-m-d h:m:s');
			$userAccount->save();

			// last debit amount will be added in userAccountAmountTransactionBrg
			$userAccountAmountTransactionBrg->last_remaining_debit = $lastDebit;
			$userAccountAmountTransactionBrg->last_remaining_credit = $lastCredit;
			$userAccountAmountTransactionBrg->save();
		}else{
			// create new account for user
			$userAccount = new UserAccount();
			$userAccount->username = $usernameReciever;
			$userAccount->status = $amountStatus;
			// the transfer amount will be credited into userAccount
			$userAccount->credit = $transferAmount;
			$userAccount->action_by = $usernameSender; // here sender is admin
			$userAccount->action_ip = $request->ip();
			$userAccount->action_at = Date('Y-m-d h:m:s');
			$userAccount->save();
			
			// last debit amount will be added in userAccountAmountTransactionBrg: that will be 0 here
			$userAccountAmountTransactionBrg->last_remaining_debit = 0.0;
			$userAccountAmountTransactionBrg->last_remaining_credit = 0.0;
			$userAccountAmountTransactionBrg->save();
		}
		session()->flash('success',' You have successfully transfered.');
		return redirect()->route('admin.billing.index',['status' => 'transfer']);
	}
	
	private function storeRecieve(Request $request, $status){
		// data from request[sender(admin),reciever(user)]
			$recieveAmount = $request->get('amount');
//			$discountAmount = $request->get('discount');
			// $usernameReciever1 = Admin::where('status','super')->get();
			// $user = $usernameReciever1->username;
			 // get username of superadmin
			$usernameSender = $request->get('username');
			//getting status for sender

			$userSender = UserInfo::where(['username' => $usernameSender])->first();
			$statusSender = $userSender->status; 

			$paidBy = $request->get('paidBy');
			$comment = $request->get('comment');
			$paymentType = $request->get('paymentType');
			$deposit_num = $request->get('deposit_num');
			if($paymentType == 'cheque'){
				$bankName = $request->get("bankname");
				$chequeNo = $request->get("checkNo");
				$deposit_num = $request->get('deposit_num');
			}
			if($paymentType == 'online'){
				$bankName = $request->get("onlinebankname");
				$deposit_num = $request->get('deposit_num');
			}

		//
		
		// adjust amount: discount will be added from recieved amount
//		$adjustedAmount = $recieveAmount + $discountAmount;
		$adjustedAmount = $recieveAmount;
		
		// recieveAmount: wiil be added as debit in user account but credit will be 0 if not then dredit will be adjusted first
		$userAccount = UserAccount::where('username',$usernameSender)->first();
		
		if($userAccount){
			// user account exists
			$lastCredit = $userAccount->credit;
			$lastDebit = $userAccount->debit;
			$adjustedAmount -= $lastCredit;
			if($adjustedAmount > 0){
				$userAccount->debit += $adjustedAmount;
				$userAccount->credit = 0;
			}elseif($adjustedAmount < 0){
				$userAccount->credit = abs($adjustedAmount); // change -123456 to 123456
				$userAccount->debit = 0;
			}elseif($adjustedAmount == 0){
				$userAccount->credit = 0;
				$userAccount->debit += 0;
			}
			$userAccount->status	= $statusSender;
			$userAccount->action_by = Auth::user()->username; // here  is admin
			$userAccount->action_ip = $request->ip();
			$userAccount->action_at = Date('Y-m-d h:m:s');
			$userAccount->save();
		}else{
			$lastCredit = 0.0;
			$lastDebit = 0.0;
			// create new account for user
			$userAccount = new UserAccount();
			$userAccount->username = $usernameSender;
			$userAccount->status	= $statusSender;
			$userAccount->debit = $adjustedAmount;
			$userAccount->action_by = Auth::user()->username; // here is admin
			$userAccount->action_ip = $request->ip();
			$userAccount->action_at = Date('Y-m-d h:m:s');
			$userAccount->save();
		}
		
		// payment transation
		$paymentsTransaction = new PaymentsTransactions();
		$paymentsTransaction->sender = $usernameSender;
		$paymentsTransaction->admin = Auth::user()->username;// admin
		$paymentsTransaction->amount = $recieveAmount;
//		$paymentsTransaction->discount = $discountAmount;
		$paymentsTransaction->current_credit = $userAccount->credit; // 
		$paymentsTransaction->current_debit = $userAccount->debit; // 
		$paymentsTransaction->is_cash = ($paymentType == 'chash') ? 1 : 0;
		$paymentsTransaction->deposit_num = $deposit_num;
		if($paymentType == 'cheque'){
			$paymentsTransaction->check_number = $chequeNo;
			$paymentsTransaction->bank_name = $bankName;
			$paymentsTransaction->deposit_num = $deposit_num;
		}
		if($paymentType == 'online'){
			$paymentsTransaction->bank_name = $bankName;
			$paymentsTransaction->deposit_num = $deposit_num;
		}
		$paymentsTransaction->paid_by = $paidBy;
		$paymentsTransaction->detail = $comment;
		$paymentsTransaction->date = Date('Y-m-d h:m:s');
		$paymentsTransaction->action_by_admin = Auth::user()->username; // here is admin
		$paymentsTransaction->action_ip = $request->ip();
		$paymentsTransaction->save();
		
		// userAccountPaymentTansactionBrg: storing last status of userAccount
		$userAccountPaymentTransactionBrg = new UserAccountPaymentTransactionBrg();
		$userAccountPaymentTransactionBrg->payment_transaction_id = $paymentsTransaction->id;
		$userAccountPaymentTransactionBrg->last_remaining_credit = $lastCredit;
		$userAccountPaymentTransactionBrg->last_remaining_debit = $lastDebit;
		$userAccountPaymentTransactionBrg->save();
		session()->flash('success',' Successfully Payment Recieved .');
		return redirect()->route('admin.billing.index',['status' => 'recieve']);
	}

	/*
	* load the list of users: their status wise
	* return the list[username]: of selected user status
	*/
	public function loadUser(Request $request){
			$status = $request->get("status");
			$userCollection = UserInfo::select('username')->where('status', $status)->get();
		return $userCollection; 
	}
	
}
