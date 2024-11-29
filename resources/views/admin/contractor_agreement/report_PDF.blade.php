<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
	<style type="">
		@page { margin: 0px;
			margin-top: 40px; }
			body { margin: 0px; }
			div{
				font-size: 93%;
			}
		</style>
	</head>
	<div class="container">
		<div class="card">
			<div class="card-body">
				<div class="row mb-4">
					<div class="">
						<p style="font-weight: bold; word-spacing: 3px;color: #0a007a"> SERVICE LEVEL AGREEMENT FOR MAINTENANCE AND DEPLOYMENT OF TELECOMMUNICATION INFRASTRUCTURE, AND COLLECTION OF MONTHLY CHARGES FROM LOGON BROADBAND CUSTOMERS</p>
						<hr>
						<div>
							<table style="width: 100%">
								<tbody>
									<tr>
										<td style="width: 33%"></td>
										<td style="width: 33%; text-align: center">
											@php
											if(!empty($company_name)){
											if($company_name=="logonbroadband"){
											$first_para="M/S Logon Broadband Pvt. Ltd";
											$second_line="";
											$sister_concern="";
											$sister_company="M/S Logon Broadband Pvt. Limited";
										}
										else
										$logo_new=$company_logo;
										$first_para=$company_name;
										$second_line=", a brand of M/s Logon Broadband for home subscribers";
										$sister_concern=$company_name;
										$sister_company="LOGON / ". strtoupper($company_name);
									}
									@endphp
									<img src="logo/{{$company_logo}}" width="180px" alt="Logo" style="">
								</td>
								<td style="width: 33%"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<p>This agreement is being made on this day
					@php 
					$str = $company_date;
					echo date('M d , Y  ', strtotime($str));
					@endphp
				by and between:</p>
				<p><strong>{{ucfirst($first_para) }} </strong> {{ $second_line }}, having its registered office at Suite no. E-1, Glass Tower, Near Teen Talwar, Clifton Karachi (hereinafter referred to as “Logon Broadband”) provides internet service to its home subscribers, which expression shall be deemed to include its successors-in-interest and assigns;</p>
				<p class="text-center">AND</p>
				<p><strong><u>{{ strtoupper($contractor_name) }} </u></strong>bearing CNIC/ NTN <strong><u>{{ $cnic }} , </u></strong>having its office/ presence at <strong><u> {{  strtoupper($dealer_area) }}. </u></strong><!--  {{$content_name}} --></p>
				<p><strong>WHEREAS </strong>M/S Logon Broadband Pvt. Limited has been duly authorized in terms of license issued by Pakistan Telecommunication Authority (hereinafter referred to as “PTA”) to offer Data Communication Network Services in Pakistan </p>
				<!-- Sister Concern Companys -->
				@php
				if($sister_concern!="") {
				@endphp
				<p><strong>WHEREAS </strong>{{ $sister_concern }} broadband wants to obtain services of the Contractor for maintenance and deployment of Telecommunication Infrastructure, and collection of monthly charges from {{$sister_concern }} broadband’s customers in the areas assigned to the Contractor;</p>
				@php
			}
			@endphp
			<!-- Sister Concern Companys -->
			<p><strong>AND WHEREAS </strong>the Contractor has expressed its willingness to undertake the services and abide by the terms and conditions detailed herein;
			</p>
			<p><strong>NOW THEREFORE </strong>both Parties agree as follows:</p>
			<ul>
				<li><p><strong>AREAS OF OPERATIONS: </strong>The Contractor has been assigned following areas for maintenance and deployment of telecommunication infrastructure, and collection of monthly charges from {{$sister_company}} customers in the assigned areas <strong><u>{{ $dealer_area }}.</u></strong></p></li>
				@php
				$data = explode('{{ $sister_company }}',$content_name);
				for($i = 0 ;$i< count($data) ; $i++)
				{
					$c = implode($sister_company,$data);
				}
				print_r($c);
				@endphp
				<table style="width: 100%">
					<tbody>
						<tr style="">
							<td colspan="2" style="padding-left: 60px;font-weight: bold; color: #00f">On behalf of LOGON Broadband</td>
							<td colspan="2" style="font-weight: bold;color: #00f">On behalf of Contractor</td>
						</tr>
						<tr>
							<td style="text-align: left;padding-left: 60px; width: 60px;">Name </td>
							<td style="width: 300px;"><u>{{$behalf_name}}</u></td>
							<td style="width: 60px;">Name </td>
							<td><u>{{$contractor_name}}</u></td>
						</tr>
						<tr>
							<td style="text-align: left;padding-left: 60px;">Designation </td>
							<td><u>{{ $behalf_designation }}</u></td>
							<td style="">CNIC </td>
							<td><u>{{$contractor_cnic }}</u></td>
						</tr>
						<tr>
							<td style="text-align: left;padding-left: 60px;"></td>
							<td></td>
							<td style="">Mobile </td>
							<td><u> {{$contractor_mobile}}</u></td>
						</tr>
						<tr><td style="height: 40px;"></td></tr>
						<tr>
							<td style="text-align: left;padding-left: 60px;">Signature </td>
							<td>_______________</td>
							<td style="">Signature</td>
							<td>_______________</td>
						</tr>
					</tbody>
				</table>
			</tbody>
		</table>
		<p style="padding-left: 60px;padding-top: 20px;margin-bottom: 0"><strong>Note:</strong></p>
		<ul style="padding-left: 60px;padding-top: 0; margin-top: 0; list-style: none">
			<li>a)  Please attach a copy of CNIC of Contractor</li>
			<li>b)  Please attach a copy of CVAS license (if any)</li>
			<li>c)  Please attach a copy of NTN and company registration certificate (if any)</li>
		</ul>
	</div>
</div>
</div>
</div>