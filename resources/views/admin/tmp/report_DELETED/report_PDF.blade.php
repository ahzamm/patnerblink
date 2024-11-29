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
		<!-- <div class="card-header">
			<h3>Invoice</h3>
		</div> -->
		<div class="card-body">
			<div class="row mb-4">
				<!-- <div class="col-sm-12 col-md-12 col-lg-12"> -->
					<!-- <h6 class="mb-6">From:</h6> -->
					<!-- <div class="col-sm-12 col-md-12">
						<h4 class="card-header" style="width: 100%;
						margin-left: -2%; text-align: center;">uterm sfjlsk</h4>
					</div> -->
					<!-- <div class="col-md-12">
						<div class="col-md-12 col-lg-12">
							<strong><p style="font-size: 15px;"> </p></strong>
							<span style=" float: right;">
								<br>
								
							</span>
							 <br>
							<br>
							
							<p>
								</p>
							</div>
						</div>
					</div> -->
				<!-- </div> -->
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
									 $logo_new="newlogo.png";
									
									 $first_para="M/S Logon Broadband Pvt. Ltd";
									 $second_line="";
									 $sister_concern="";
									 $sister_company="M/S Logon Broadband Pvt. Limited";
									 
									 									 
									 if($company_name=="logonbroadband"){
										$logo_new="newlogo.png";
										 $first_para="M/S Logon Broadband Pvt. Ltd";
									 } 
									 elseif($company_name=="spark"){
										 $logo_new="Spark Logo.png";
										 $first_para="Spark Broadband";
									     $second_line=", a brand of M/s Logon Broadband for home subscribers";
									     $sister_concern="Spark";
										 $sister_company="LOGON/SPARK BROADBAND";
									 }
									 elseif($company_name=="blackoptic"){
										 $logo_new="Black Optic Logo.png";
										 $first_para="Black Optic";
										 $second_line=", a brand of M/s Logon Broadband for home subscribers";
									     $sister_concern="Black Optic";
										 $sister_company="LOGON/BLACK OPTIC BROADBAND";
									 }

									@endphp
									
									<img src="logo/{{$company_logo}}" width="180px" alt="Logo" style="">
									</td>
									<td style="width: 33%"></td>
								</tr>
							</tbody>
						</table>
						
					</div>
					<p>This agreement is being made on this 
					
					 @php 
					 $str = $company_date;
                      echo date('M d , Y  ', strtotime($str));
					 @endphp

					by and between:</p>

					<p><strong>{{$first_para }} </strong> {{ $second_line }}, having its registered office at Suite no. E-1, Glass Tower, Near Teen Talwar, Clifton Karachi (hereinafter referred to as “Logon Broadband”) provides internet service to its home subscribers, which expression shall be deemed to include its successors-in-interest and assigns;</p>
					<p class="text-center">AND</p>
					<p><strong><u>{{ strtoupper($company_name) }} </u></strong>bearing CNIC/ NTN <strong><u>{{ $cnic }} , </u></strong>having its office/ presence at <strong><u> {{  strtoupper($dealer_name) }}. </u></strong><!--  {{$content_name}} --></p>
					<p><strong>WHEREAS </strong>M/S Logon Broadband Pvt. Limited has been duly authorized in terms of license issued by Pakistan Telecommunication Authority (hereinafter referred to as “PTA”) to offer Data Communication Network Services in Pakistan </p>
					
					<!-- sister concern -->
					@php
					     if($sister_concern!="") {
					@endphp

					  <p><strong>WHEREAS </strong>{{ $sister_concern }} Broadband wants to obtain services of the Contractor for maintenance and deployment of Telecommunication Infrastructure, and collection of monthly charges from {{$sister_concern }} BROADBAND’s customers in the areas assigned to the Contractor;</p>
             
					@php
					     }
					@endphp
					<!-- sister concern -->
					
					<p><strong>AND WHEREAS </strong>the Contractor has expressed its willingness to undertake the services and abide by the terms and conditions detailed herein;
					</p>
					<p><strong>NOW THEREFORE </strong>both Parties agree as follows:</p>
					<ul>
						<li><p><strong>AREAS OF OPERATIONS: </strong>The Contractor has been assigned following areas for maintenance and deployment of telecommunication infrastructure, and collection of monthly charges from {{$sister_company}} customers in the assigned areas <strong><u>{{ $dealer_area }}.</u></strong></p></li>

						<!-- <li><p style="padding-bottom: 0px;margin-bottom: 0"><strong>TERM AND TERMINATION OF THE AGREEMENT: </strong> -->
						<!-- 	{!!$content_name!!} -->
						@php
						$data = explode('{{ $sister_company }}',$content_name);
						
						for($i = 0 ;$i< count($data) ; $i++)
						{
								$c = implode($sister_company,$data);
						}
					
						print_r($c);
						@endphp
						<!-- 	
						<ul style="padding-top: 0px;">
							<li>This agreement has been signed for a term of 12 months from the above-mentioned Effective Date. The agreement will be renewed for successive 12 months unless any of the Parties gives notice for termination of the agreement or if the agreement is terminated pursuant to clauses below.</li>
							<li>{{ $sister_company }} shall terminate the agreement if the Contractor fails to perform its obligation under this agreement or the Contractor fails to adhere to any of the terms and conditions outlined herein.</li>
							<li> {{$sister_company}} shall have exclusive right to terminate this agreement, without any liability to the Contractor, where, due to actions and/or omissions of Contractor, {{$sister_company}} faces any action from PTA under any of its licenses.</li>
							<li>{{$sister_company}} shall also have the right to claim indemnification against any losses occurred due to such actions and/or omissions of the Contractor.</li>
						</ul>
						</p></li>
						<li style="padding-top: 20px; padding-bottom: 0"><p style="padding-bottom: 0px;margin-bottom: 0"><strong>CONFIDENTIALITY: </strong>
						<ul style="padding-top: 0px;">
							<li>Any confidential information exchanged between the parties shall be considered as privileged information and the Contractor shall ensure confidentiality of the same. The Contractor shall not disclose, under any circumstances, following information relating to {{$sister_company}} to its competitors:
								<ul>
									<li>Network route of {{$sister_company}} under the scope of this agreement;</li>
									<li>Details of customers; </li>
									<li>Prices of packages offered to the customers; </li>
									<li>Number of customers assigned in an area to a Contractor;</li>
									<li>Remuneration offered to the Contractor for its services; </li>
									<li>Any other information conveyed by {{$sister_company}} or any of its other divisions to the Contractor with intimation that the information is confidential and needs protection; and </li>
									<li>Any information that comes to knowledge of Maintenance Contractor and is not conveyed by Logon or any of its divisions shall also be deemed confidential.</li>
								</ul>
							</li>
						<li>It is clarified that the Contractor shall be liable to maintain confidentiality of above-stated information not only of area of operation but in other areas also where {{$sister_company}} has presence.</li>
						</ul>
						</p></li>
						<li style="padding-top: 20px; padding-bottom: 0"><p style="padding-bottom: 0px;margin-bottom: 0"><strong>REMUNERATION OF THE CONTRACTOR: </strong>
						<ul style="padding-top: 0px;">
							<li>The Parties shall mutually agree the amount to be paid to the Contractor for its services.</li>
							<li>If the Contractor terminates the agreement without notices and customers suffer due to any issue requiring maintenance, deployment or collection of charges, {{$sister_company}} shall have the right to deduct remuneration against any such business loss suffered.</li>
							<li>If the Contractor shows satisfactory performance and offers its maintenance, deployment and recovery services in other areas as well, it shall have the right to negotiate remuneration for the extended services.</li>
							<li>If Parties cannot agree to the terms of remuneration for new or existing services, the Parties shall try to amicably resolve issues within fourteen (14) working days before availing any other remedy.</li>
						</ul></p></li>
						<li style="padding-top: 20px; padding-bottom: 0"><p style="padding-bottom: 0px;margin-bottom: 0"><strong>TERMS AND CONDITIONS: </strong>
						<ul style="padding-top: 0px;">
							<li>The Contractor shall be liable to maintain Telecommunication Infrastructure (Fiber, Switches, etc.) of {{$sister_company}} deployed in his area of operation.</li>
							<li>The Contractor is also obligated to lay/ deploy the Telecommunication Infrastructure in the area of operation.</li>
							<li>The Contractor shall deploy the customer who subscribes for the {{$sister_company}} services within the area assigned.</li>
							<li>The Contractor shall be responsible to manage customer complaints related to the physical media only.</li>
							<li>The Contractor shall also be liable to recover monthly charges from the customers in his area(s) of operations. It is clarified that the customers include the customers operational in the area even before the Contractor was hired for the services.</li>
							<li>The Contractor shall use the collection receipts by {{$sister_company}} only to bill the end-users. The terms and conditions mentioned on receipts shall be read as an integral part of this agreement and the Contractor shall comply with the terms and conditions in letter and spirit.</li>
							<li>The Contractor shall not do marketing of LOGON ’s brand(s) without prior and specific approval.</li>
							<li>The Contractor shall also make sure that no other business activity is carried out under name/ brand of {{$sister_company}}.</li>
							<li>The end-users shall be provisioned services and be billed under name of {{$sister_company}} only.</li>
							<li>{{$sister_company}} reserves the right to appoint another Contractor if the performance of the existing Contractor is not up to the mark.</li>
							<li>The Telecommunication Infrastructure is and will be ownership of {{$sister_company}}, and the Contractor will only manage the network of {{$sister_company}}</li>
							<li>The Contractor will never, in any case, use {{$sister_company}} Infrastructure to provide services to end users on its own or in any agreement with other parties or even PTA licensees.</li>
						</ul></p></li>
					</ul> -->
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
							<!-- </tr> -->
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