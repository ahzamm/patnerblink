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
			<div class="">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12">
						<h4 class="card-header" style="
						margin-left: -2%; text-align: center;">Monthly Billing Report
						<span class="info-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h4>
				</div>
				<div class="col-md-12">
					<div class="col-md-12 col-lg-12">
						<strong><p style="font-size: 15px;"> {{ucwords($userCollection1->status)}} - {{ucwords($userCollection1->username)}}</p></strong>
						<span style=" float: right;">
							From : {{date("d-m-Y H:i:s", strtotime($from1))}}<br>
							To   : {{ date("d-m-Y H:i:s", strtotime($to1))}}
						</span>
						Date : {{date('d-m-Y')}} <br>
						Time : {{date('H:i:s')}}<br>
						Mobile: {{$userCollection1->mobilephone}}
						<p>
							Address:{{$userCollection1->address}}</p>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive-sm">
				<table class="table table-striped">
					<thead>
						<tr style="background-color:#225094d1; color: white" >
							<th>Dealer-Name</th>
							<th></th>
							@foreach($monthlyBillingEntries1 as $data)
							<th>{{$data->groupname/1024}}MBs</th>
							@php
							$groupname = $data->groupname;
							@endphp
							@endforeach
						</tr>
					</thead>
					<tbody>	
						@php 
						$text=array('Dealer-Amount','Reseller-Amount','Commision','Profit');
						$field =array('d_acc_rate','r_acc_rate','commision','profit');
						$profit = array(1,2,3,4,5,6,7,8,9);
						@endphp
						@foreach($dealerCollection as $value)
						<tr>
							<td>{{$value->dealerid}}</td>
							@php foreach($text as $key => $value1){ 
							if($key > 0){
							@endphp
						</tr>
						<tr>
							<td></td>
							@php } @endphp
							<td>{{$value1}}</td>
							@foreach($monthlyBillingEntries1 as $data)
							@php
							$total_amount1 = App\model\Users\AmountBillingInvoice::where('dealerid' , $value->dealerid)
							->where('charge_on','>=',DATE($from1))->where('charge_on','<=',DATE($to1))->where('profile','=',$data->groupname)->sum($field[$key]);
							@endphp
							<td>{{$total_amount1}}</td>
							@endforeach
						</tr>
						@php } @endphp
						@endforeach	
						@php
						$reseller_amount = App\model\Users\AmountBillingInvoice::where('resellerid' , $userCollection1->resellerid)
						->where('charge_on','>=',DATE($from1))->where('charge_on','<=',DATE($to1))->sum('r_acc_rate');
						$dealer_amount = App\model\Users\AmountBillingInvoice::where('resellerid' , $userCollection1->resellerid)
						->where('charge_on','>=',DATE($from1))->where('charge_on','<=',DATE($to1))->sum('d_acc_rate');
						$profit_amount = App\model\Users\AmountBillingInvoice::where('resellerid' , $userCollection1->resellerid)
						->where('charge_on','>=',DATE($from1))->where('charge_on','<=',DATE($to1))->sum('profit');
						@endphp
					</tbody>
					<tfoot class="tfoot">
						<tr class="btn-default" style="background-color:#d8d8d8;">
							<th colspan="3" style="text-align: right;">Reseller Amount:{{$reseller_amount}}</th>
							<th colspan="3" style="text-align: right;">Dealer Amount:{{$dealer_amount}}</th>
							<th colspan="3" style="text-align: right;">Profit Amount:{{$profit_amount}}</th>
							<th colspan=""></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>