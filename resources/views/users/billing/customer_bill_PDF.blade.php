<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
	<style type="">
		div{
			font-size: 70%;
		}
		.div{
			width: 45%;
			height: 100%;
		}
		.div h3{
			text-align: center;
		}
		.box{
			border-style: solid;
			border-width: 1.5px;
			padding: 5px;
		}
		.small{
			width:50%;
			position:absolute;
		}
	</style>
</head>
<body>
	<?php
	function space($num){
		$txt='';
		for ($i=0; $i < $num ; $i++) { 
			$txt=$txt.'&nbsp;';
		}
		echo $txt;
	}
	?>
	<div class="container-fluid">
		<div class="row"> 
			<div class="div">
				<h3>CUSTOMER RECEIPT</h3> 
				<div class="box">
					<b>Contractor Name (PoC): {{$dealername}}</b>
				</div>
				<div class="box">
					<b>Contractor Mobile No: {{$dealernum}}</b>
				</div>
				<div class="box">
					<br>
					Receipt # : {{$receipt_num}} <?php space(63);?>
					Date : <u><?php echo date('d-m-Y',strtotime($charge_date))?></u><br>
					Customer ID :     <u>{{$username}}</u> <?php space(40);?>                                         
					CNIC / STR # :      <u>{{$nic}}</u><br>
					Customer Name : <u>{{$fn}}{{$ln}}</u><br>
					Customer Address : <u>{{$add}}</u><br>
					Customer Phone # : <u>{{$mobilephone}}</u><?php space(28);?> 
					City : <u>Karachi</u><br>
					Package Name  :   <u>{{$name}}</u> 
					<br><br>
				</div>
				<div class="box">
					Internet charges : <?php space(53);?> <u>{{$charges}}</u><br>
					Static IP charges : <?php space(52);?> <u>0.00</u><br>
					<b>Total <?php space(73);?> <u>{{$charges}}</u></b>
					<br><br><br>
					Sales Tax 19.50% : <?php space(52);?><u>{{$sst}}</u><br>
					Advance Income Tax ** 12.50% :<?php space(30);?> <u>{{$adv_tax}}</u><br>
					<b>Grand Total<?php space(63);?> {{$invoice}}</b><br>
				</div>
				<div class="box">
					<br> 
					<img src="{{asset('img/billLogo.png')}}" width="30%" style="float: right;">
					Issued By :<br>
					** Advance Income tax as per Income Tax Ordinance, 2001.<br><br>
					<span style="float:right;text-align:right;">Service Sales Tax/NTN No. 3950561-8</span>
					<br>
				</div>
				<div class="box" style="background-color: #225094">
					<center style="color:white;">Services provided by LOGON BROADBAND</center>
				</div>
			</div>
			<div class="div" style="margin-left: 500px;">
				<h3>TERMS AND CONDITIONS</h3>
				<div class="box">
					1) Subscription fees, usage charges and other fees for the Service will be as stated in LOGON tariff information, which is subject to change from time to time. User shall pay the monthly service charges in accordance with the Subscription Plan.<br>
					2) Service Sales Tax on internet service is applicable at the rates defined in relevant Provincial Sales Tax laws. <br>
					3) Advance Income Tax is applicable at the rate of 12.5% on internet services. Please note that user can take credit of this tax against their annual Income Tax Liability. Please use tax registered details while subscribing for services.  <br>
					4) Any new Federal / Provincial Government taxes/rates/levies or any change in the same shall have an impact on subscription plan.<br>
					5) User acknowledge to use the service in compliance with the applicable laws, rules and regulations including but not limited to those prescribed by Pakistan Telecommunication Authority. LOGON reserve the right to suspend or terminate in case of any non-compliance identified.<br>
					6) User shall abide by the generally accepted standards of conduct and usage of the Service by not sending any message or material that is defamatory, invasive of privacy, obscene or offensive, or which contains viruses, worms and or any other computer code, files or programs designed to interrupt, destroy or limit the functionality of the any software or hardware, or of LOGON's network.<br>
					7) User shall not resell or re-distribute the service, whether temporary or permanent or for no value.<br>
					8) LOGON reserves the right at any time and without assigning any reasons to terminate this service.<br>
					9) All packages are subject to Fair Usage Policy (FUP), LOGON reserves the right to suspend or downgrade the connection if it does not comply with FUP.<br>
					10) Dealer will be responsible in case of any misuse / fraud.<br>
					11) This receipt is valid for services provided by LOGON exclusively and will be considered as null and void if used for any other services and/or purpose.
				</div>
			</div>
		</div>
	</div>
</body>