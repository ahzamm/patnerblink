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
						<th colspan="3" style="background-color: #0d4dab;color: #fff;font-size: 15px;">Commission Report</th>
					</tr>
				</thead>
				<tbody>
					<tr style="text-align: left">
						@if($user_data->status == 'manager') 
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">{{ucwords($user_data->username)}} <small style="color: #0d4dab">({{ucwords($user_data->status)}})</small></strong></td>
						@elseif($user_data->status == 'reseller') 
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">{{ucwords($user_data->username)}} <small style="color: #0d4dab">({{ucwords($user_data->status)}})</small></strong></td>
						@elseif($user_data->status == 'dealer') 
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">{{ucwords($user_data->username)}} <small style="color: #0d4dab">(Contractor)</small></strong></td>
						@else
						<td colspan="2"><strong style="font-size: 15px;text-align:left;padding-top: 4px;padding-bottom:4px">{{ucwords($user_data->username)}} <small style="color: #0d4dab">(Trader)</small></strong></td>
						@endif
						<td style="width: 160px;padding-top:4px;padding-bottom:4px">
							<b>Billing Cycle</b> : {{date("d-M-Y", strtotime($bildate))}}<br/>
							<b>Due Date</b> : <b style="color: #f40303">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{date("d-M-Y", strtotime($dueDate))}}</b>
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
						<td colspan="2" style="padding-top: 0px;padding-bottom:0px"> {{$user_data->mobilephone}}</td>
					</tr>
					<tr style="text-align: left">
						<td style="padding-top: 0px;padding-bottom:0px"><b>Address</b> : </td>
						<td colspan="2" style="padding-top: 0px;padding-bottom:0px">{{$user_data->address}}</td>
					</tr>
				</tbody>
			</table>
			<div class="">
				<table class="table">
					<thead>
						<tr style="text-align: center;">
							<th colspan="5" style="color: #0d4dab;font-size: 15px;">Internet Profiles</th>
						</tr>
						<tr style="background-color:#0d4dab; color: white;width:100%" >
							<th> Serial#</th>
							<th colspan="2">Internet Profile Name</th>
							<th>Number Of Consumers</th>
							<th>Commission (PKR)</th>
						</tr>
					</thead>
					<tbody style="border-left: 1px solid #0d4dab;border-right: 1px solid #0d4dab;">	
						<?php 
						$num = 1;
						foreach($rows as $row){
							?>    
							<tr style="height: 20px;">
								<td style="padding-top:4px; padding-bottom:4px;"><?= $num;?></td>
								<td colspan="2" style="padding-top:4px; padding-bottom:4px"><?= $row;?></td>
								<td style="text-align: center;padding-top:4px;padding-bottom:4px"><?= count($count[$row]['amount']);?></td>
								<td style="text-align:right;padding-top:4px;padding-bottom:4px"><?= number_format(array_sum($count[$row]['amount']));?></td>
							</tr>
							<?php $num++; } ?>
						</tbody>
						<tfoot class="tfoot" style="border-bottom: 1px solid #0d4dab;border-left: 1px solid #0d4dab;border-right: 1px solid #0d4dab;">
							<tr class="btn-default" style="background-color:transparent; ">
								<th colspan="4" style="text-align:right;"> Total Amount <span style="color: green">(PKR)</span></th>
								<th style="text-align:right;border-top: 1px solid #0d4dab;"><?= number_format($total);?></th>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="row">
					<div class="col-lg-12 col-sm-12 col-md-12">
						<hr>
						<h6 style="text-align: center;color: #0d4dab"> Thank you for your partnership. We’re honored by your trust in our services...! </h6>
					</h6>
				</div>
			</div>
		</div>
	</div>
</div>