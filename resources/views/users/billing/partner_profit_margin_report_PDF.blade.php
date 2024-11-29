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
			<!-- <div class="row mb-4">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12">
						<h4 class="card-header" style="width: 100%;
						margin-left: -2%; text-align: center;background-color: #0d4dab;color: #fff;">Monthly Billing Report</h4>
					</div>
					<div class="col-md-12">
						<div class="col-md-12 col-lg-12">
							<strong><p style="font-size: 15px;"> {{ucwords($user_data->status)}} - {{ucwords($user_data->username)}} </p></strong>
							<span style=" float: right;"> 
								Billing Cycle : {{date("d-m-Y", strtotime($bildate))}} <br>
								Due Date : <b style="color: #f40303"> {{date("d-m-Y", strtotime($dueDate))}}</b>
							</span>
							Date : {{date('d-m-Y')}} <br>
							Time : {{date('H:i:s')}}<br>
							Mobile: {{$user_data->mobilephone}}
							<p>Address:Address:{{$user_data->address}}</p>
						</div>
					</div>
				</div>
			</div> -->
			<table class="table" style="border: 1px solid #0d4dab">
				<thead>
					<tr style="text-align: center;">
						<th colspan="3" style="background-color: #0d4dab;color: #fff;font-size: 15px;">Partner Profit Margin Report</th>
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
						<th colspan="5" style="color: #0d4dab;font-size: 15px;">Internet Profiles <small>(Billing Report)</small></th>
					</tr>
					<tr style="background-color:#0d4dab; color: white;width:100%" >
						<th> Serial#</th>
						<th colspan="2">Internet Profile Name</th>
						<th>Number Of Consumers</th>
						<th>Total Amount (PKR)</th>
					</tr>
				</thead>
				<tbody style="border-left: 1px solid #0d4dab;border-right: 1px solid #0d4dab;">	
           
					<?php 
					$totalnumberofconsumer = 0;
					$num = 1;
					foreach($rows as $row){
						
						?>    
						<tr style="height: 20px;">
							<td style="padding-top:4px; padding-bottom:4px;"><?= $num;?></td>
							<td colspan="2" style="padding-top:4px; padding-bottom:4px"><?= $row;?></td>
							<td style="text-align: center;padding-top:4px;padding-bottom:4px"><?= count($count[$row]['amount']);?></td>
							<td style="text-align:right;padding-top:4px;padding-bottom:4px"><?= number_format(array_sum($count[$row]['amount']),2);?></td>
						</tr>
						<?php 
						$totalnumberofconsumer +=  count($count[$row]['amount']);
						$num++;
					} ?>
		
				</tbody>

				<tfoot class="tfoot" style="border-bottom: 1px solid #0d4dab;border-left: 1px solid #0d4dab;border-right: 1px solid #0d4dab;">
					<tr class="btn-default" style="background-color:transparent; ">
						<th colspan="3" style="text-align:right;"> Total </th>
						<!-- <th colspan=""></th> -->
						<th style="text-align:center;border-top: 1px solid #0d4dab;"><?= ($totalnumberofconsumer);?></th>
						<th style="text-align:right;border-top: 1px solid #0d4dab;"><?= number_format($total,2);?></th>
					</tr>
				</tfoot>
			</table>
					<!-- Static Ip -->
				
				
			
			</div>
			

		</div>
	</div>
</div>