<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
	<style type="">
		@page { margin: 0px; }
		body { margin: 0px; }

		div{

			font-size: 70%;
		}
		tr{

			text-align: center;
		}
		

	</style>
</head>
<div class="container">
	<div class="card">
<!-- <div class="card-header">
<h3>Invoice</h3>


</div> -->
<div class="card-body">
	<div class="row mb-4">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<!-- <h6 class="mb-6">From:</h6> -->
			<div class="col-sm-12 col-md-12">
				<h3 class="card-header" style="text-align: center;">Monthly Billing Report</h3>
			</div>

			<div class="col-md-12">
				
				<div class="col-md-12 col-lg-12">

					<strong><p style="font-size: 15px;"> Reseller-Logonbroadband</p></strong>
					

					<span style=" float: right;"> 
						Billing Cycle : {{date("d-m-Y", strtotime($bildate))}}<br>

						


					</span>

					Date : {{date('d-m-Y')}} <br>



					Time : {{date('H:i:s')}}<br>
					


				</div>



			</div>
		</div>


	</div>

	<div class="table-responsive-sm">
		<table class="table table-striped" style="margin-left: -60px;">
			<thead>
				<tr style="background-color:#225094d1; color: white" >
					<th style="width: 70px;">DealerID</th>
					@foreach($profileList as $pro)
					<th style="width: 50px;"> {{$pro->name}} </th>
					@endforeach
					<th style="width: 60px;"> Total </th>
				</tr>
			</thead>
			<tbody>	
				@php $gtotal=0; @endphp
				@foreach($dealerList as $dealer)
				<tr>
					<td>{{$dealer->dealerid}}</td>
					@php $total=0; @endphp
					@foreach($profileList as $pro)
					@php


					$receipt = App\model\Users\AmountBillingInvoice::select('receipt')->where('dealerid' , $dealer->dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->where('profile','=',$pro->groupname)->first();

					if($receipt == 'cyber'){

					$lite = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealer->dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->where('profile','=',$pro->groupname)->sum('m_rate');

				

					
				
											$totalsst1 = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealer->dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->where('profile','=',$pro->groupname)->sum('c_sst');
											$totalsst2 = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealer->dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->where('profile','=',$pro->groupname)->sum('c_adv');
											$totalRate = $totalsst1 + $totalsst2;

											$totalsst = $totalRate + $lite; 

				}else{

				$lite = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealer->dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->where('profile','=',$pro->groupname)->sum('m_rate');

				

					
				
											$totalsst1 = App\model\Users\AmountBillingInvoice::where('dealerid' , $dealer->dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->where('profile','=',$pro->groupname)->sum('c_sst');
											$totalsst2 = 0;
											$totalRate = $totalsst1 + $totalsst2;

											$totalsst = $totalRate + $lite; 

											
			}

					@endphp
					<td>

						{{number_format($totalsst)}}
						@php $total=$total+$totalsst;
						
						@endphp
					</td>
					@endforeach
					<td>{{number_format($total)}}</td>
				</tr>
				@endforeach
				@php

				    $totalAmount = App\model\Users\AmountBillingInvoice::where('manager_id' , $username)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->sum('m_rate');

					$totalsst = App\model\Users\AmountBillingInvoice::where('manager_id' , $username)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->where('receipt','=','cyber')->sum('c_sst');

					$totaladv = App\model\Users\AmountBillingInvoice::where('manager_id' , $username)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',DATE($lastbilldate.' 12:00:00'))->where('receipt','=','cyber')->sum('c_adv');

					
					


					$finaltotal = $totalsst + $totaladv;

					$totalAmount1 = $finaltotal + $totalAmount;
				@endphp
						
					</tbody>

					<tfoot class="tfoot">
						


						<tr class="btn-default" style="background-color:#d8d8d8;">
							<th colspan="9" style="text-align: center;text-align: right;">Total Amount</th>
							
							<th colspan="2">{{number_format($totalAmount1)}}</th>




							
						</tr>
					</tfoot>
					
				</table>


			</div>
			

		</div>
	</div>
</div>