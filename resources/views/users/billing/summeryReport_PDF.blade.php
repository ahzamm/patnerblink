<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
	<style type="">
		@page { margin: 0px; }
		body { margin: 0px; }
		div{
			font-size: 90%;
		}
		tr{
			text-align: center;
		}
	</style>
</head>
<div class="">
	<div class="card">
		<div class="card-body">
			<table class="table" style="border: 1px solid #0d4dab">
				<thead>
					<tr style="text-align: center;">
						<th colspan="3" style="background-color: #0d4dab;color: #fff;font-size: 15px;">Profit Summary by Contractor</th>
					</tr>
				</thead>
				<tbody>
					<tr style="text-align: left">
						@if($userCollection1->status == 'manager')
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size: 15px;"> {{ucwords($userCollection1->username)}} <small style="color: #0d4dab">(Manager)</small></p></strong></td>
						@elseif($userCollection1->status == 'reseller')
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size: 15px;"> {{ucwords($userCollection1->username)}} <small style="color: #0d4dab">(Reseller)</small></p></strong></td>
						@elseif($userCollection1->status == 'dealer')
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size: 15px;"> {{ucwords($userCollection1->username)}} <small style="color: #0d4dab">(Contractor)</small></p></strong></td>
						@elseif($userCollection1->status == 'subdealer')
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size: 15px;"> {{ucwords($userCollection1->username)}} <small style="color: #0d4dab">(Trader)</small></p></strong></td>
						@endif
						<td style="width: 160px;padding-top:4px;padding-bottom:4px">
							<b>From</b> : {{date("d-M-Y", strtotime($from))}}<br/>
							<b>To</b> : &nbsp;&nbsp;&nbsp;&nbsp; {{date("d-M-Y", strtotime($to))}}
						</td>
					</tr>
					<tr style="text-align: left;">
						<td style="padding-top: 0px;padding-bottom:0px;width: 60px"><b>Date</b> : </td>
						<td colspan="2" style="padding-top: 0px;padding-bottom:0px;width:75%;">{{date('d-M-Y')}}</td>
					</tr>
					<tr style="text-align: left">
						<td style="padding-top: 0px;padding-bottom:0px"><b>Time</b> : </td>
						<td colspan="2" style="padding-top: 0px;padding-bottom:0px">{{date('H:i:s')}}</td>
					</tr>
					<tr style="text-align: left">
						<td style="padding-top: 0px;padding-bottom:0px"><b>Mobile</b> : </td>
						<td colspan="2" style="padding-top: 0px;padding-bottom:0px"> {{$userCollection1->mobilephone}}</td>
					</tr>
					<tr style="text-align: left">
						<td style="padding-top: 0px;padding-bottom:0px"><b>Address</b> : </td>
						<td colspan="2" style="padding-top: 0px;padding-bottom:0px">{{$userCollection1->address}}</td>
					</tr>
				</tbody>
			</table>
			<div class="table-responsive-sm">
				<table class="table" style="border:1px solid #0d4dab">
					<thead>
						<tr style="background-color:#0d4dab;color: #fff;font-size:8px" >
							<th class=""> S#</th>
							<th>Internet Package Name</th>
							{{-- <th>MBs</th> --}}
							<th colspan="2">Number of Consumers</th>
							<th>Contractor Amount</th>
							<th>Reseller Amount</th>
							<th>Commision</th>
							<th>Profit</th>
						</tr>
					</thead>
					<tbody>	
						@php $sno = 1; @endphp
						@foreach($monthlyBillingEntries as $key => $data )	
						@php
						$profile = App\model\Users\Profile::where(['name'=> $data->name])->first();
						$gtotal=($data->final_rates)*($total[$key]);
						$groupname = $data->name;
						$dealerid = $data->dealerid;
						$resellerid = $data->resellerid;
						$sub_dealer_id = $data->sub_dealer_id;
						$rate=$data->rate;
						if($userCollection1->status == "subdealer"){
						$total_amount1 = App\model\Users\AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
						->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->sum('subdealerrate');
						$noOfUser = App\model\Users\AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
						->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('name','=',$groupname)->count('name');
						$invoicerate = App\model\Users\AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
						->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->first();
						if(empty($invoicerate)){
						$b_rate =0;
						$sst = 0;
						$adv_tax = 0;
					}else{
					$b_rate = $invoicerate->subdealerrate;
					$sst = $invoicerate->sst;
					$adv_tax = $invoicerate->adv_tax;
				}
			}else if($userCollection1->status == "dealer"){
			$total_amount1 = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('name','=',$groupname)->sum('d_acc_rate');
			$noOfUser = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('name','=',$groupname)->count('name');
			$total_amount2 = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('name','=',$groupname)->sum('r_acc_rate');
			$commision = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('name','=',$groupname)->sum('commision');
			STATIC $total_profit ;
			$getprofit = $total_amount1 - $total_amount2;
			$profit = $getprofit - $commision ;
			$total_profit += $profit;
			$invoicerate = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->first();
			if(empty($invoicerate)){
			$b_rate =0;
			$sst = 0;
			$adv_tax = 0;
		}else{
		$b_rate = $invoicerate->subdealerrate;
		$sst = $invoicerate->sst;
		$adv_tax = $invoicerate->adv_tax;
	}
}else if($userCollection1->status == "reseller"){
$total_amount1 = App\model\Users\AmountBillingInvoice::where('resellerid' , $resellerid)
->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->sum('d_acc_rate');
$noOfUser = App\model\Users\AmountBillingInvoice::where('resellerid' , $resellerid)
->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->count();
$total_amount2 = App\model\Users\AmountBillingInvoice::where('resellerid' , $resellerid)
->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->sum('r_acc_rate');
$commision = App\model\Users\AmountBillingInvoice::where('resellerid' , $resellerid)
->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->sum('commision');
STATIC $total_profit ;
$getprofit = $total_amount1 - $total_amount2;
$profit = $getprofit - $commision ;
$total_profit += $profit;
$invoicerate = App\model\Users\AmountBillingInvoice::where('resellerid' , $resellerid)
->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->first();
if(empty($invoicerate)){
$b_rate =0;
$sst = 0;
$adv_tax = 0;
}else{
$b_rate = $invoicerate->subdealerrate;
$sst = $invoicerate->sst;
$adv_tax = $invoicerate->adv_tax;
}
}
@endphp
<tr>
	<td style="padding-top:4px;padding-bottom:4px">{{$sno++}}</td>
	<td style="padding-top:4px;padding-bottom:4px">{{$profile->name}}</td>
	{{-- <td class="right">{{$groupname}}MB</td> --}}
	{{-- <td class="" colspan="2">{{$total[$key]}}</td> --}}
	<td style="padding-top:4px;padding-bottom:4px" colspan="2">{{number_format($noOfUser)}}</td>
	<td style="padding-top:4px;padding-bottom:4px">{{number_format($total_amount1)}}</td>
	<td style="padding-top:4px;padding-bottom:4px">{{number_format($total_amount2)}}</td>
	<td style="padding-top:4px;padding-bottom:4px">{{$commision}}</td>
	<td style="padding-top:4px;padding-bottom:4px">{{$profit}}</td>
</tr>
@endforeach
</tbody>
<tfoot class="tfoot">
	@foreach($monthlyBillingEntries as $key => $data )	
	@php
	$profile = App\model\Users\Profile::where(['groupname'=> $data->groupname])->first();
	$gtotal=($data->final_rates)*($total[$key]);
	@endphp
	@endforeach
	<tr class="btn-default" style="background-color:#d8d8d8;">
		<th colspan="6" style="text-align: right;">Grand Total</th>
		<th colspan=""></th>
		<th>{{$total_profit}}</th>
	</tr>
</tfoot>
</table>
</div>
</div>
</div>
</div>