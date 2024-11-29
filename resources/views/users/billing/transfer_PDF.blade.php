<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
	<style type="">
		@page { margin: 0px; }
		body { margin: 0px; }
		div{
			font-size: 95%;
		}
	</style>
</head>
<div class="">
	<div class="card">
		<div class="card-body">
			<table class="table" style="border: 1px solid #0d4dab">
				<thead>
					<tr style="text-align: center;">
						<th colspan="3" style="background-color: #0d4dab;color: #fff;font-size: 15px;">Transfer Report</th>
					</tr>
				</thead>
				<tbody>
					<tr style="text-align: left">
						@if($data->status == 'manager') 
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">{{ucwords($data->username)}} <small style="color: #0d4dab">({{ucwords($data->status)}})</small></strong></td>
						@elseif($data->status == 'reseller') 
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">{{ucwords($data->username)}} <small style="color: #0d4dab">({{ucwords($data->status)}})</small></strong></td>
						@elseif($data->status == 'dealer') 
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">{{ucwords($data->username)}} <small style="color: #0d4dab">(Contractor)</small></strong></td>
						@else
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">{{ucwords($data->username)}} <small style="color: #0d4dab">(Trader)</small></strong></td>
						@endif
						<td style="width: 160px;padding-top:4px;padding-bottom:4px">
							<b>From</b> : {{date("d-M-Y", strtotime($from1))}}<br/>
							<b>To</b> : &nbsp;&nbsp;&nbsp;&nbsp; {{date("d-M-Y", strtotime($to1))}}
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
				<table class="table">
					<thead>
						<tr style="background-color:#0d4dab; color: white">
							<th width="8%">#</th>
							<th width="14%">Date</th>
							<th width="28%">Particular</th>
							<th width="15%">Debit</th>
							<th width="15%">Credit</th>
							<th width="15%">Balance</th>
						</tr>
					</thead>
					<tbody style="border: 1px solid #0d4dab;">
						@php
						$openBalance=$sumDebit-$sumCredit;
						@endphp
						<tr>
							<td class="center" style="padding-top:4px; padding-bottom:4px;"></td>
							<td class="left strong" style="padding-top:4px; padding-bottom:4px;"></td>
							<td class="left" style="padding-top:4px; padding-bottom:4px;"><b>Opening Balance</b></td>
							<td class="right" style="padding-top:4px; padding-bottom:4px;"> </td>
							<td class="right" style="padding-top:4px; padding-bottom:4px;"></td>
							<td class="right" style="padding-top:4px; padding-bottom:4px;"> {{number_format($openBalance)}}</td>
						</tr>
						@php
						$tdebit=0;
						$tcredit=0;
						$openbalance=$openBalance;
						$num=0;
						@endphp
						@foreach($paymentTranssaction as $entry)
						@php
						$num++;
						$debit1=$entry->amount;
						$credit = $entry->cash_amount;
						$openbalance=$openbalance+$debit1-$credit;
						@endphp
						<tr>
							<td class="center" style="padding-top:4px; padding-bottom:4px;">{{$num}}</td>
							<td class="left strong" style="padding-top:4px; padding-bottom:4px;">{{date('d-m-Y' ,strtotime($entry->date)) }}</td>
							<td class="left">{{$entry->comments}}</td>
							<td class="right" style="padding-top:4px; padding-bottom:4px;"> {{number_format($debit1)}}</td>
							<td class="right" style="padding-top:4px; padding-bottom:4px;"> {{number_format($credit)}}</td>
							<td class="right" style="padding-top:4px; padding-bottom:4px;"> {{number_format($openbalance)}}</td>
						</tr>
						@endforeach
						<tr>
							<td class="center" style="padding-top:4px; padding-bottom:4px;"></td>
							<td class="left strong" style="padding-top:4px; padding-bottom:4px;"></td>
							<td class="left" style="padding-top:4px; padding-bottom:4px;"><b>Closing Balance</b></td>
							<td class="center" style="padding-top:4px; padding-bottom:4px;"></td>
							<td class="right" style="padding-top:4px; padding-bottom:4px;"> </td>
							<td class="right" style="padding-top:4px; padding-bottom:4px;"> <b>{{number_format($openbalance)}}</b></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="col-lg-12 col-sm-12 col-md-12">
					<hr>
					<h6 style="text-align: center;color: #0d4dab"> Thank you for your valued business. We value your trust and confidence in us and sincerely appreciate you! </h6>
				</div>
			</div>
		</div>
	</div>
</div>