<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
	<style type="">
		@page { margin: 0px; }
		body { margin: 0px; }
		div{
			font-size: 96%;
		}
	</style>
</head>
<div class="">
	<div class="card">
		<div class="card-body">
			<table class="table" style="border: 1px solid #0d4dab">
				<thead>
					<tr style="text-align: center;">
						<th colspan="3" style="background-color: #0d4dab;color: #fff;font-size: 15px;">Contractor Balance Matching</th>
					</tr>
				</thead>
				<tbody>
					<tr style="text-align: left">
						@if($data->status == 'manager')
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">
							<p style="font-size: 15px;"> {{ucwords($data->username)}} <small style="color: #0d4dab">(Manager)</small></p>
						</strong></td>
						@elseif($data->status == 'reseller')
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">
							<p style="font-size: 15px;"> {{ucwords($data->username)}} <small style="color: #0d4dab">(Reseller)</small></p>
						</strong></td>
						@elseif($data->status == 'dealer')
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">
							<p style="font-size: 15px;"> {{ucwords($data->username)}} <small style="color: #0d4dab">(Contractor)</small></p>
						</strong></td>
						@elseif($data->status == 'subdealer')
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">
							<p style="font-size: 15px;"> {{ucwords($data->username)}} <small style="color: #0d4dab">(Trader)</small></p>
						</strong></td>
						@endif
						<td style="width: 160px;padding-top:4px;padding-bottom:4px">
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
						<td colspan="2" style="padding-top: 0px;padding-bottom:0px"> {{$data->mobilephone}}</td>
					</tr>
					<tr style="text-align: left">
						<td style="padding-top: 0px;padding-bottom:0px"><b>Address</b> : </td>
						<td colspan="2" style="padding-top: 0px;padding-bottom:0px">{{$data->address}}</td>
					</tr>
				</tbody>
			</table>
			<div class="table-responsive-sm">
				<table class="table" style="border:1px solid #0d4dab">
					<thead style="font-size: 8px">
						<tr style="background-color:#0d4dab; color: white">
							<th width="23%">Particular</th>
							<th width="15%">Balance Amount (PKR)</th>
						</tr>
					</thead>
					<tbody style="font-size: 8px">
						@php
						$openBalance=$sumDebit-$sumCredit;
						@endphp
						<tr>
							<td class="left" style="color: green"><b>Ledger Report</b></td>
							<td class="right" style="text-align:center"> {{number_format($openBalance)}}</td>
						</tr>
						<tr>
							<td class="left" style="color: green"><b>After Bill Date Used Payment</b></td>
							<td class="right" style="text-align:center">{{number_format($netPayable)}}</td>
						</tr>
						<tr>
							<td class="left" style="color: green"><b>Available Balance Dealer Panel</b></td>
							<td class="right" style="text-align:center">{{number_format($dealer_Amount['amount'])}}</td>
						</tr>
						<tr>
							<td class="left" style="color: green"><b>Available Balance sub-Dealer Panel</b></td>
							<td class="right" style="text-align:center"> {{number_format($sub_dealer_Amount)}}</td>
						</tr>
						<tr>
							<td class="left" style="color: green"><b>Transfer Amount</b></td>
							<td class="right" style="text-align:center">-{{number_format($amount__Balance_Dealer['amount'])}}</td>
						</tr>
						<tr>
							<td class="left" style="color: green"><b>Unpaid Commission Amount</b></td>
							<td class="right" style="text-align:center"> {{number_format($netProfitMargin)}}</td>
						</tr>
						<tr>
							<td class="left" style="color: green"></td>
							<td class="right" style="text-align:center"> </td>
						</tr>
						<tr>
							@php
							$openBalance=$netProfitMargin + $openBalance + $netPayable + $dealer_Amount['amount'] + $sub_dealer_Amount - $amount__Balance_Dealer['amount'] ;
							@endphp
							<td class="left" style="color: green"><b>Total Amount</b></td>
							<td class="right" style="text-align:center">{{number_format($openBalance)}} </td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="col-lg-12 col-sm-12 col-md-12">
					<hr>
				</div>
			</div>
		</div>
	</div>
</div>