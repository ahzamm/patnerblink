<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
	<style type="">
		@page { margin: 0px; }
		body { margin: 0px; }
		div{
			font-size: 90%;
		}
		.center{
			text-align: center;
		}
		.right{
			text-align: right;
		}
		.left{
			text-align: left;
		}
	</style>
</head>
<div class="container">
	<div class="card">
		<div class="card-body">
			<div class="row mb-4">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12">
						<h3 class="" style="margin-top: 20px;"><img src="{{asset('images/logo.png')}}" style="width: 120px;" ></h3>
					</div>
					<div class="col-md-12">
						<div class="col-md-12 col-lg-12">
							<h4 class="" style="width: 100%;
							margin-left: -2%; text-align: center;"><u>Invoice</u></h4>
							<strong><p style="font-size: 15px;"> {{ucwords($user_data->username)}} </p></strong>
							<span style=" float: right;">
								Date : {{date('d-m-Y')}} <br>
								Billing Cycle : {{date("d-m-Y", strtotime($bildate))}} <br>
							</span>
							Mobile: {{$user_data->mobilephone}} <br>
							CNIC # {{$user_data->nic}}
							<p>Address: {{$user_data->address}} </p>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive-sm">
				<table class="table table-striped">
					<thead>
						<tr style="background-color:#225094d1; color: white" >
							<th class="center"> Sn#</th>
							<th class="center">Package Name</th>
							<th class="center">Rate</th>
							<th class="center">No of Users</th>
							<th class="center">Total Amount</th>
						</tr>
					</thead>
					<tbody>
						@php $total = 0;
						$num = 1;
						$gtotal = 0;
						@endphp
						@foreach($details as $data)
						@php
						if($user_data->status == 'subdealer' ){
						$lite = App\model\Users\AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
						->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
						DATE($lastbilldate.' 12:00:00'))->where('profile','=',$data->profile)->count();
						$sum = App\model\Users\AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
						->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
						DATE($lastbilldate.' 12:00:00'))->where('profile','=',$data->profile)->sum('c_rates');
						$total = $total + $sum;
						$rates =	$data['c_rates'];
						$acc_rate = $data->s_acc_rate;
					}else if($user_data->status == 'dealer'){
					$lite = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
					DATE($lastbilldate.' 12:00:00'))->where('profile','=',$data->profile)->count();
					$sum = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
					DATE($lastbilldate.' 12:00:00'))->where('profile','=',$data->profile)->sum('c_rates');
					$total = $total + $sum;
					$rates =	$data['c_rates'];
					$acc_rate = $data->d_acc_rate;
				}
				@endphp
				<tr>
					<td class="center">{{$num++}}</td>
					<td class="center">{{$data->name}}</td>
					<td class="right">{{$rates}}</td>
					<td class="center">{{$lite}}</td>
					<td class="right"> {{$sum}}</td>
				</tr>
			}
			@endforeach
		</tbody>
		<tfoot class="tfoot">
			<tr class="btn-default" style="background-color:#d8d8d8;">
				<th colspan="3" style="text-align: right;">Total Amount</th>
				<th colspan=""></th>
				<th class="right">{{number_format($total)}}</th>
			</tr>
		</tfoot>
	</table>
	<!-- Static IP Address -->
</div>
<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12">
		<hr>
		<center>
			<h5> Thank You For Your Business </h5>
			<i>This is system generated invoice and does not require any signatures</i>
		</center>
		<hr>
		<center>
			<p>Office E-1, Executive Floor, Glass Tower Near PSO Head Office, Clifton, Karachi Pakistan.<br>
			Ph:021-11 11 LOGON, Email: accounts@logon.com.pk , URL http://logon.com.pk/ </p>
		</center>
	</div>
</div>
</div>
</div>
</div>