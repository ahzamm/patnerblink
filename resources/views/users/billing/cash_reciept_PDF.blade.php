<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
	<style type="">
		@page { margin: 0px; }
		body { margin: 0px; }
	</style>
</head>
<div class="card">
	<div class="card-body">
		<table class="table" style="border: 1px solid #0d4dab;">
			<thead>
				<tr style="text-align: center;">
					<th colspan="3" style="background-color: #0d4dab;color: #fff;font-size: 15px;"><h2>RECEIPT</h2></th>
				</tr>
			</thead>
			<tbody>
				<tr style="text-align: left">
					{{-- @if($userCollection1->status == 'manager')
					<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size: 15px;"> {{ucwords($userCollection1->username)}} <small style="color: #0d4dab">(Manager)</small></p></strong></td>
					@elseif($userCollection1->status == 'reseller')
					<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size: 15px;"> {{ucwords($userCollection1->username)}} <small style="color: #0d4dab">(Reseller)</small></p></strong></td>
					@elseif($userCollection1->status == 'dealer')
					<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size: 15px;"> {{ucwords($userCollection1->username)}} <small style="color: #0d4dab">(Contractor)</small></p></strong></td>
					@elseif($userCollection1->status == 'subdealer')
					<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size: 15px;"> {{ucwords($userCollection1->username)}} <small style="color: #0d4dab">(Trader)</small></p></strong></td>
					@endif --}}
					<td colspan="2"><strong style="text-align:left;padding-top: 4px;padding-bottom:4px"><p style="font-size:20px"> {{strtoupper($usernameSender)}} <small style="color: #0d4dab">(Contractor)</small></p></strong></td>
					<td style="width: 200px;padding-top:4px;padding-bottom:4px">
						<b>Receipt Number</b> : {{$id}}
					</td>
				</tr>
				<tr style="text-align: left;">
					<td style="width: 190px;"><b>Date</b> : </td>
					<td colspan="2" style="width:75%;">{{date('F d,Y',strtotime($date))}}</td>
				</tr>
				<tr style="text-align: left">
					<td><b>Time</b> : </td>
					<td colspan="2">{{date('H:i:s', strtotime($date))}}</td>
				</tr>
				<tr style="text-align: left">
					<td><b>Receive Amount (PKR)</b> : </td>
					<td colspan="2"> <span style="color:green;font-weight:bold;font-size:18px">{{$recieveAmount}}/-</span> &nbsp;&nbsp; <span style="font-size:14px">({{ucwords($test)}} Rupees Only)</span></td>
				</tr>
			</tbody>
		</table>
		<hr/>
		<p style="text-align:center">This is system generated receipt and does not require any signature</p>
	</div>
</div>
{{--
	@php
	$tag = '';
	for($i = 0; $i<1; $i++){
	$tag = $i == 0 ? 'Original' : 'Copy';
	$html = '
	<div class="container">
		<div class="card">
			<div class="card-body">
				<div class="row mb-4">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<!-- <h6 class="mb-6">From:</h6> -->
						<div class="col-sm-12 col-md-12">
							<h3 class="card-header" style="width: 100%;
							margin-left: -2%; text-align: center;">RECEIPT  </h3>
						</div>
					</div>
				</div>
				<div class="table-responsive-sm">
					<table style="padding-bottom: 40px;">
						<tr>
							<td style="font-size: 14px;width: 50px;">Date: <th style="border-bottom: solid 1px black;width: 300px;"><span style="font-size: 14px;">' .date('F d,Y H:i:s',strtotime($date)). '</span></th></td>
							<td style="font-size: 14px;width: 580x;">Receipt #<th style="border-bottom: solid 1px black;width: 255px;text-align:center"><span style="font-size: 14px;">' .$id. '</span></th></td>
						</tr>
					</table>
					<table style="padding-bottom: 40px;">
						<tr>
							<td style="font-size: 14px;width: 70px;">Dealer Name:
								<th style="border-bottom: solid 1px black;width: 560px;"><span style="font-size: 14px;">' .strtoupper($usernameSender). '</span></th></td>
							</tr>
						</table>
						<table style="padding-bottom: 40px;">
							<tr>
								<td style="font-size: 14px;width: 500px;">Received with thanks from Mr/Mrs/Ms</td>
							</tr>
							<tr>
								<th style="border-bottom: solid 1px black;width: 660px;"><span style="font-size: 14px;">' .strtoupper($usernameSender). ' (' .strtoupper($paidBy). ')</span></th>
							</tr>
						</table>
						<table style="padding-bottom: 40px;">
							<tr>
								<td style="font-size: 14px;width: 500px;">Bill Amount </td>
							</tr>
							<tr>
								<th style="border-bottom: solid 1px black;width: 660px;"><span style="font-size: 14px;"> Rs. ' .number_format($recieveAmount,2). '</span></th>
							</tr>
						</table>
						<table style="padding-bottom: 40px;">
							<tr>
								<td style="font-size: 14px;width: 500px;">Amount In Words</td>
							</tr>
							<tr>
								<th style="border-bottom: solid 1px black;width: 660px;"><span style="font-size: 14px;">RUPEES ' .strtoupper($test). ' ONLY</span></th>
							</tr>
						</table>
					</div>
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-md-12">
							<center><h3>Your payment has been successfully received – Thank You<br>
							This is system generated receipt and does not require any signature</h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>';
		echo $html;
	}
	@endphp
	--}}