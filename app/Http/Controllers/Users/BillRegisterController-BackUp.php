<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\UserStatusInfo;
use App\model\Users\RadAcct;
use App\model\Users\RadCheck;
use App\model\Users\CsvFileLogs;
use App\model\Users\DealerProfileRate;
use App\model\Users\SubdealerProfileRate;



class BillRegisterController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct(){

}
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
public function index(){

	// $dealerUserCount = $this->count_ids('saquibiqbal',NULL,'2023','11');
	// $this->current_month_count_ids($dealerUserCount,'2023','11');
	//
	$selectedReseller = UserInfo::where('status','reseller')->where('manager_id',Auth::user()->manager_id)->get();
	return view('users.bill-register.index',compact('selectedReseller'));
	//
}
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
public function get_bill_register_data(request $request){
	//
	$dealerid = $request->get('dealer_data');   
	$year = $request->get('year');   
	$month = $request->get('month');
	//
	if(empty($dealerid) || empty($year) || empty($month)){
		return abort(403, 'Error : Kindly apply filter first');
	}   
    //
	$subdealers = UserInfo::where('dealerid',$dealerid)->where('status','subdealer')->get();
	//
	$dealerUserCount = $this->count_ids($dealerid,NULL,$year,$month,'lastmonth');
	$currMonthdealerUserCount = $this->count_ids($dealerid,NULL,$year,$month,'currmonth');
	// $currMonthdealerUserCount = $this->current_month_count_ids($dealerUserCount,$year,$month);
	?>
	<tr>
		<td><?= $dealerid; ?></td>
		<td>dealer</td>
		<td><?= count($dealerUserCount);?></td>
		<td><?= count($currMonthdealerUserCount);?></td>
		<td>
			<input type="hidden" name="status[]" value="dealer" >
			<input type="hidden" name="id[]" value="<?=$dealerid;?>" >
			<input type="hidden" name="oldcount[]" value="<?= count($dealerUserCount);?>" >
			<input type="hidden" name="curroldcount[]" value="<?= count($currMonthdealerUserCount);?>" >
			<input type="number" name="newcount[]" class="form-control" value="0" required>
		</td>
	</tr>
	<?php

        //
	foreach($subdealers as $subdealer){
		$subdealerUserCount = $this->count_ids($dealerid,$subdealer->sub_dealer_id,$year,$month,'lastmonth');
		$currMonthsubdealerUserCount = $this->count_ids($dealerid,$subdealer->sub_dealer_id,$year,$month,'currmonth');
		// $currMonthsubdealerUserCount = $this->current_month_count_ids($subdealerUserCount,$year,$month);
		?>
		<tr>
			<td><?= $subdealer->username; ?></td>
			<td><?= $subdealer->status; ?></td>
			<td><?= count($subdealerUserCount);?></td>
			<td><?= count($currMonthsubdealerUserCount);?></td>
			<td>
				<input type="hidden" name="status[]" value="subdealer" >
				<input type="hidden" name="id[]" value="<?= $subdealer->sub_dealer_id ;?>" >
				<input type="hidden" name="oldcount[]" value="<?= count($subdealerUserCount);?>" >
				<input type="hidden" name="curroldcount[]" value="<?= count($currMonthsubdealerUserCount);?>" >
				<input type="number" name="newcount[]" class="form-control" value="0" required>
			</td>
		</tr>
		<?php
	}

}

/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

public function count_ids($dealerid,$subdealerid,$year,$month,$when){
	//
	$time = strtotime($year.'-'.$month.'-01');
	//
	if($when == 'lastmonth'){
		$start = date('Y-m-01',strtotime("-1 month", $time));
		$end = date('Y-m-t',strtotime("-1 month", $time));
	}else{
		$start = date('Y-m-01',$time);
		$end = date('Y-m-t',$time);
	}
	//
	$query = DB::table('user_info')
	->select('user_info.username','user_info.dealerid','user_info.firstname','user_info.lastname','user_info.profile','user_info.address','user_info.name', 'user_info.mobilephone')
	->where('user_info.dealerid',$dealerid)
	->where('user_info.sub_dealer_id',$subdealerid)
	->where('user_info.pta_reported',1)
	->where('user_info.status','=','user')
	->where('user_info.status','=','user');
  	//
	$userCount = $query->whereIn('username', function($query2) use ($start,$end){
		$query2->select('username')
		->from('amount_billing_invoice')
		->where('amount_billing_invoice.date','>=',$start)
		->where('amount_billing_invoice.date','<=',$end);
	})->get();
	//
	return $userCount;
}

/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

public function current_month_count_ids_OLD($collection,$year,$month){
	//
	$time = strtotime($year.'-'.$month.'-01');
	$start = date('Y-m-01',$time);
	$end = date('Y-m-t',$time);
	//
	$count = 0;
	//
	foreach($collection as $value){
	//
		$username = $value->username;
		//
		$query = DB::table('amount_billing_invoice')
		->select('username')
		->where('date','>=',$start)
		->where('date','<=',$end)
		->where('username',$username)
		->first();
		//
		if($query){
			$count++;
		}
		//	
  	//
	}	
	return $count;
}



/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

public function download_sheet(request $request){
	$reseller_data = $request->get('reseller_data');   
	$year = $request->get('year');   
	$month = $request->get('month');
	//
	///////////////////////////////////////////////////////////////////////////////////////
	$delimiter = ",";
	$f = fopen('php://memory', 'w');
//
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////
	$lineData = array( 'Date', 'Sales Tax Inv.#', 'Billing Inv.#', 'Customer Name & Address', 'CNIC/NTN', 'Location', 'Package', 'TIP', 'FLL', 'CVAS', 'Total Charges(SST)', 'S.S.Tax', 'Total Charges(ADV)', 'Adv.I.Tax', 'Total TAX.', 'Total Amt', 'Contractor ID', 'Trader ID', 'Cust ID');
	fputcsv($f, $lineData, $delimiter);
	//
	$time = strtotime($year.'-'.$month.'-01');
	$start = date('Y-m-01',$time);
	$end = date('Y-m-t',$time);
	//
	$rows = DB::table('user_info')
	->join('amount_billing_invoice', 'amount_billing_invoice.username', '=', 'user_info.username')

	->select('amount_billing_invoice.username','amount_billing_invoice.date','amount_billing_invoice.tip_charges','amount_billing_invoice.fll_charges','amount_billing_invoice.cvas_charges','amount_billing_invoice.sst','amount_billing_invoice.adv_tax','amount_billing_invoice.dealerid','amount_billing_invoice.sub_dealer_id','amount_billing_invoice.profile', 'user_info.nic','user_info.firstname','user_info.lastname','user_info.address','amount_billing_invoice.sst_percentage')

	->where('user_info.resellerid',$reseller_data)
	->where('user_info.pta_reported',1)
	->where('user_info.status','=','user')
	->where('amount_billing_invoice.date','>=',$start)
	->where('amount_billing_invoice.date','<=',$end)
	->where('user_info.status','=','user')->get();




	//
	foreach ($rows as $row) {
		$nameNaddress = $row->firstname.' '.$row->lastname.' ('.$row->address.')';
		$pkg = explode('-',$row->profile);
		$totalChargesSST =  $row->tip_charges +  $row->fll_charges + $row->cvas_charges;
		$totaltaxes =  $row->adv_tax + $row->sst;
		$totalAmount = $totaltaxes + $totalChargesSST;

		$totalChargesADV = $row->fll_charges + $row->cvas_charges;
		$totalChargesADV = $totalChargesADV + ($totalChargesADV * $row->sst_percentage);
	// 	
		$lineData = array( $row->date, '', '', $nameNaddress, $row->nic, 'Location', $pkg[1], $row->tip_charges, $row->fll_charges, $row->cvas_charges, $totalChargesSST, $row->sst, $totalChargesADV, $row->adv_tax, $totaltaxes, $totalAmount, $row->dealerid, $row->sub_dealer_id, $row->username);

		fputcsv($f, $lineData, $delimiter);
	}				
/////////////////////////////////////////////////////
	$filename='users_detail_report.csv';
	fseek($f, 0);
//set headers to download file rather than displayed
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
//output all remaining data on a file pointer
	fpassthru($f);
// }
	exit;
	//
}
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

public function bill_register_update_data(request $request){
	$id = $request->get('id');
	$status = $request->get('status');
	$oldcount = $request->get('oldcount');
	$newcount = $request->get('newcount');
	$curroldcount = $request->get('curroldcount');
	//
	$year = $request->get('year');
	$month = $request->get('month');
	$time = strtotime($year.'-'.$month.'-01');
	$start = date('Y-m-01',$time);
	$end = date('Y-m-t',$time);
	//
	/////////////////////////////  CHECKING /////////////////////////////////////////////
	foreach($id as $key => $value){
		//
		$userStatus = $status[$key];
		$userOldCount = $oldcount[$key];
		$userNewCount = $newcount[$key];
		$userCurrOldcount = $curroldcount[$key];
		$userID = $id[$key];
		$userCountDiff = $userNewCount - $userCurrOldcount;
		//
		if($userNewCount < $userOldCount){
			return abort(403, 'Error : New user count should be greater than or equal to user count.');
		}
		//
		if($userNewCount > $userOldCount){
			//
			if($userStatus == "dealer"){
				$profileRate = DealerProfileRate::where('dealerid' , $userID)->orderBy('rate')->get();
				$dealerid = $userID; $subdealerid = NULL;
			}else if($userStatus == "subdealer"){
				$profileRate = SubdealerProfileRate::where('sub_dealer_id' , $userID)->orderBy('rate')->get();
				$dealerInfo = UserInfo::where('sub_dealer_id' , $userID)->where('status', 'subdealer')->first();
				$dealerid = $dealerInfo->dealerid; $subdealerid = $userID;
			}
			//
			$count = 0;
			//
			foreach($profileRate as $proVal){
				for($i=0; $i<$userCountDiff; $i++){
					$userInfo = UserInfo::where('dealerid',$dealerid)->where('sub_dealer_id',$subdealerid)->where('status','user')->where('name',$proVal->name)->where('pta_reported',0)->first();
					if($userInfo){	
						//
						$count++;
						//
						if($count == $userCountDiff){
							break;
						}
						//
					}else{
						break;
					}

				}
				if($count == $userCountDiff){
					break;
				}
			}
			//
			if($count < $userCountDiff){
				return abort(403, 'Error : No user found to update for '.$userID);
			}
			//
		}
	}
	////////////////////////////////  UPDATION  ////////////////////////////////////////// 
	$totalCount = 0;
	//
	foreach($id as $key => $value){
		//
		$userStatus = $status[$key];
		$userOldCount = $oldcount[$key];
		$userNewCount = $newcount[$key];
		$userCurrOldcount = $curroldcount[$key];
		$userID = $id[$key];
		$userCountDiff = $userNewCount - $userCurrOldcount;
		//
		if($userNewCount >= $userOldCount){
			//
			if($userStatus == "dealer"){
				$profileRate = DealerProfileRate::where('dealerid' , $userID)->orderBy('rate')->get();
				$dealerid = $userID; $subdealerid = NULL;
			}else if($userStatus == "subdealer"){
				$profileRate = SubdealerProfileRate::where('sub_dealer_id' , $userID)->orderBy('rate')->get();
				$dealerInfo = UserInfo::where('sub_dealer_id' , $userID)->where('status', 'subdealer')->first();
				$dealerid = $dealerInfo->dealerid; $subdealerid = $userID;
			}
			//
			$count = 0;
			//
			foreach($profileRate as $proVal){

				for($i=0; $i<$userCountDiff; $i++){

					// dd($start,$end,$dealerid,$subdealerid,$proVal->name);

					$userInfo = UserInfo::where('dealerid',$dealerid)
					->where('sub_dealer_id',$subdealerid)
					->where('status','user')
					->where('name',$proVal->name)
					->where('pta_reported',0)
					->select('username');
					//
					$billing = $userInfo->whereIn('username', function($query2) use ($start,$end){
						$query2->select('username')
						->from('amount_billing_invoice')
						->where('amount_billing_invoice.date','>=',$start)
						->where('amount_billing_invoice.date','<=',$end);
					})->first();
					//
					if($billing){
						// UPDATE 0 to 1
						$forUpdateUser = UserInfo::where('username',$billing->username)->first();
						//
						$forUpdateUser->pta_reported = 1;
						$forUpdateUser->save();
						//
						$count++;
						$totalCount++;
						//
						if($count == $userCountDiff){
							break;
						}
						//
					}else{
						break;
					}

				}
				if($count == $userCountDiff){
					break;
				}
			}
		}
		//
	}

	if($totalCount <= 0){
		return abort(403, 'Error : No user found to update');
	}else{
		return $totalCount.' Consumers Updated Successfully';	
	}
	
	
	
}

}