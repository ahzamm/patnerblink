@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
	.on_hover{
	}
	#onlineGauge-license-text,#profileGauge-license-text{
		display: none;
	}
	.border{
		border-radius: 5px;
	}
	.progress-bar {
		line-height: 40px;
		font-size: 15px;
	}
	.progress{
		border-radius: 5px;
	}
	.popover-content{
		color: black;
		background-color: #ececec;
	}
	.progress-bar a{
		text-decoration: none;
		color: white;
	}
	#grad1  {
		height: 200px;
		background-color: #225094; /* For browsers that do not support gradients */
		background-image: linear-gradient(to bottom right, #6dd5ed, #225094); /* Standard syntax (must be last) */
	}
	#grad2 {
		color: white;
		background-color: #225094; /* For browsers that do not support gradients */
		background-image: linear-gradient(to bottom right, #225094, #6dd5ed); /* Standard syntax (must be last) */
	}
/*.page-topbar {
	background-color: #225094 !important;
	background-image: linear-gradient(to bottom right, #225094, #6dd5ed) !important;
	}*/
	.tile-counter{
		min-width: 181px;
		border-radius: 25px;
	}
	.sec{
		background: white;
		border-radius: 25px;
		color: #32325d;
		font-weight: 700;
		font-size: 35px;
		text-align: center;
	}
	.content{
		max-height: 115px;
	}
	.tile-counter h2 {
		color:#32325d;
	}
	.tile-counter span{
		color:#32325d;
	}
	.tbodys {
		display:block;
		height:307px;
		overflow:auto;
	}
	.theads, .tbodys tr {
		display:table;
		width:100%;
		table-layout:fixed;
	}
	.theads {
		width: calc( 100% - 1em )
	}

	 /* Search */
	 html,body {height: 100%;}
   /* body {padding: 0px; margin:0px; background:url(../images/image.jpg) ; background-position: center; background-size: cover; background-attachment: fixed; background-repeat: no-repeat;} */
   .search-wrapper {
   position: absolute;
   -webkit-transform: translate(-50%, -50%);
   -moz-transform: translate(-50%, -50%);
   transform: translate(-50%, -50%);
   top:50%;
   left:50%;
   }
   .search-wrapper .input-holder {
   overflow: hidden;
   height: 70px;
   /* background: rgba(255,255,255,0); */
   background-color: #ececec;
   border-radius:45px;
   position: relative;
   width:70px;
   -webkit-transition: all 0.3s ease-in-out;
   -moz-transition: all 0.3s ease-in-out;
   transition: all 0.3s ease-in-out;
   }
   .search-wrapper.active .input-holder {
   border-radius: 50px;
   width:650px;
   height: 45px;
   background-color: white;
   /* background: rgba(0,0,0,0.5); */
   -webkit-transition: all .5s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   -moz-transition: all .5s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   transition: all .5s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   }
   .search-wrapper .input-holder .search-input {
   width:100%;
   height: 50px;
   padding:0px 70px 0 20px;
   opacity: 0;
   position: absolute;
   top:0px;
   left:0px;
   background: transparent;
   -webkit-box-sizing: border-box;
   -moz-box-sizing: border-box;
   box-sizing: border-box;
   border:none;
   outline:none;
   font-family:"Open Sans", Arial, Verdana;
   font-size: 16px;
   font-weight: 400;
   line-height: 20px;
   color:#000;
   -webkit-transform: translate(0, 60px);
   -moz-transform: translate(0, 60px);
   transform: translate(0, 60px);
   -webkit-transition: all .3s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   -moz-transition: all .3s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   transition: all .3s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   -webkit-transition-delay: 0.3s;
   -moz-transition-delay: 0.3s;
   transition-delay: 0.3s;
   }
   .search-wrapper.active .input-holder .search-input {
   opacity: 1;
   -webkit-transform: translate(0, 10px);
   -moz-transform: translate(0, 10px);
   transform: translate(0, 10px);
   }
   .search-wrapper .input-holder .search-icon {
   width:70px;
   height:70px;
   border:none;
   border-radius:6px;
   background-color: black;
   /* background: #FFF; */
   padding:0px;
   outline:none;
   position: relative;
   z-index: 2;
   float:right;
   cursor: pointer;
   -webkit-transition: all 0.3s ease-in-out;
   -moz-transition: all 0.3s ease-in-out;
   transition: all 0.3s ease-in-out;
   }
   .search-wrapper.active .input-holder .search-icon {
   width: 45px;
   height:45px;
   margin-right: 10px;
   border-radius: 30px;
   }
   .search-wrapper .input-holder .search-icon span {
   width:22px;
   height:22px;
   display: inline-block;
   vertical-align: middle;
   position:relative;
   /* -webkit-transform: rotate(45deg);
   -moz-transform: rotate(45deg);
   transform: rotate(45deg); */
   -webkit-transition: all .4s cubic-bezier(0.650, -0.600, 0.240, 1.650);
   -moz-transition: all .4s cubic-bezier(0.650, -0.600, 0.240, 1.650);
   transition: all .4s cubic-bezier(0.650, -0.600, 0.240, 1.650);
   }
   .search-wrapper.active .input-holder .search-icon span {
   /* -webkit-transform: rotate(-45deg);
   -moz-transform: rotate(-45deg);
   transform: rotate(-45deg); */
   }
   .search-wrapper .input-holder .search-icon span::before, .search-wrapper .input-holder .search-icon span::after {
   position: absolute;
   content:'';
   }
   .search-wrapper .input-holder .search-icon span::before {
   width: 4px;
   height: 11px;
   left: 9px;
   top: 18px;
   border-radius: 2px;
   /* background: #974BE0; */
   }
   .search-wrapper .input-holder .search-icon span::after {
   width: 14px;
   height: 14px;
   left: 0px;
   top: 0px;
   border-radius: 16px;
   /* border: 4px solid #974BE0; */
   }
   .search-wrapper .close {
   position: absolute;
   z-index: 1;
   top:12px;
   right:20px;
   width:25px;
   height:25px;
   cursor: pointer;
   -webkit-transform: rotate(-180deg);
   -moz-transform: rotate(-180deg);
   transform: rotate(-180deg);
   -webkit-transition: all .3s cubic-bezier(0.285, -0.450, 0.935, 0.110);
   -moz-transition: all .3s cubic-bezier(0.285, -0.450, 0.935, 0.110);
   transition: all .3s cubic-bezier(0.285, -0.450, 0.935, 0.110);
   -webkit-transition-delay: 0.2s;
   -moz-transition-delay: 0.2s;
   transition-delay: 0.2s;
   }
   .search-wrapper.active .close {
   right:-50px;
   -webkit-transform: rotate(45deg);
   -moz-transform: rotate(45deg);
   transform: rotate(45deg);
   -webkit-transition: all .6s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   -moz-transition: all .6s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   transition: all .6s cubic-bezier(0.000, 0.105, 0.035, 1.570);
   -webkit-transition-delay: 0.5s;
   -moz-transition-delay: 0.5s;
   transition-delay: 0.5s;
   }
   .search-wrapper .close::before, .search-wrapper .close::after {
   position:absolute;
   content:'';
   /* background: #FFF; */
   background-color: black;
   border-radius: 2px;
   background: #000;
   color: #000;
   }
   .search-wrapper .close::before {
   width: 5px;
   height: 25px;
   left: 10px;
   top: 0px;
   }
   .search-wrapper .close::after {
   width: 25px;
   height: 5px;
   left: 0px;
   top: 10px;
   }
   .search-wrapper .result-container {
   width: 100%;
   position: absolute;
   top:80px;
   left:0px;
   text-align: center;
   font-family: "Open Sans", Arial, Verdana;
   font-size: 14px;
   display:none;
   color:#B7B7B7;
   }
   .scrolled {
   height:150px;
   overflow-y: scroll;
   }
   .scrolled::-webkit-scrollbar {
   display: none;
   }
   @media screen and (max-width: 560px) {
   .search-wrapper.active .input-holder {width:200px;}
   .emp-profile{
   padding: 3%;
   margin-top: 3%;
   margin-bottom: 3%;
   border-radius: 0.5rem;
   background: #fff;
   }
   .profile-img{
   text-align: center;
   }
   .profile-img img{
   width: 70%;
   height: 100%;
   }
   .profile-img .file {
   position: relative;
   overflow: hidden;
   margin-top: -20%;
   width: 70%;
   border: none;
   border-radius: 0;
   font-size: 15px;
   background: #212529b8;
   }
   .profile-img .file input {
   position: absolute;
   opacity: 0;
   right: 0;
   top: 0;
   }
   .profile-head h5{
   color: #333;
   }
   .profile-head h6{
   color: #0062cc;
   }
   .profile-edit-btn{
   border: none;
   border-radius: 1.5rem;
   width: 70%;
   padding: 2%;
   font-weight: 600;
   color: #6c757d;
   cursor: pointer;
   }
   .proile-rating{
   font-size: 12px;
   color: #818182;
   margin-top: 5%;
   }
   .proile-rating span{
   color: #495057;
   font-size: 15px;
   font-weight: 600;
   }
   .profile-head .nav-tabs{
   margin-bottom:5%;
   }
   .profile-head .nav-tabs .nav-link{
   font-weight:600;
   border: none;
   }
   .profile-head .nav-tabs .nav-link.active{
   border: none;
   border-bottom:2px solid #0062cc;
   }
   .profile-work{
   padding: 14%;
   margin-top: -15%;
   }
   .profile-work p{
   font-size: 12px;
   color: #818182;
   font-weight: 600;
   margin-top: 10%;
   }
   .profile-work a{
   text-decoration: none;
   color: #495057;
   font-weight: 600;
   font-size: 14px;
   }
   .profile-work ul{
   list-style: none;
   }
   .profile-tab label{
   font-weight: 600;
   }
   .profile-tab p{
   font-weight: 600;
   color: #0062cc;
   }
   }

   li{
	   list-style-type: none;
   }

      /* new css */
	  .r4_counter{
		outline: 1px solid #000;
    	outline-offset: 5px;
		margin-bottom: 18px;
	  }
	  .r4_counter h3{
		margin-bottom: 0;
	  }
	  .r4_counter span{
		color: #000;
	  }
	  .r4_counter i {
		width: 60px;
		height: 60px;
		font-size: 20px;
		/* outline: 1px solid #000;
    	outline-offset: 3px; */

	  }
	  .r4_counter i.la {
		font-size: 24px;
	  }
	  /* .r4_counter i.fa-user-tie{
		background-color: green;
	  } */
	  /* .r4_counter i.fa-hands-helping{
		background-color: #6480db;
	  } */
	  /* .r4_counter i.fa-handshake{
		background-color: #fbcc02;
	  } */
	  /* .r4_counter i.fa-users-cog{
		background-color: #b5409e;
	  } */
	  /* .r4_counter i.fa-users{
		background-color: #ff6500;
	  } */
	  /* .r4_counter i.fa-user-check{
		background-color: #31d3b1;
	  } */
	  /* .r4_counter i.fa-user-slash{
		background-color: #d7064d;
	  } */
	  /* .r4_counter i.fa-calendar-minus{
		background-color: #747474;
	  } */
	section.box,
	.r4_counter{
		border-radius: 4px;
		box-shadow: 1px 3px 8px 0px rgb(0 0 0 / 40%);
	}
	.icon-primary.icon-rounded{
		background-color: #179df7
	}
	.col__padding div[class^="col"]{
		padding-left: 8px;
		padding-right: 8px;
	}
	.online__card--wrapper .card-content {
			height: 100px;
			margin-bottom: 0px;
		}
	@media (max-width: 992px){
		.online__card--wrapper .card-content {
			height: auto
		}
	}
	#chartCpu, #chartRam, #chartHdd{
		box-shadow: 1px 3px 8px 0px rgb(0 0 0 / 40%);
	}
	.canvasjs-chart-canvas{
		width: 100% !important;
	}
</style>
@endsection
@section('content')


<div class="page-container row-fluid container-fluid">
	<!-- SIDEBAR - START -->
	<section id="main-content" class=" ">
	<img src="/img/support-bg.png" alt="" style="position: absolute;left: 57%;bottom: 30px;transform: (-50%);user-select: none;z-index: 0;opacity: .1;width:40%;">
		<section class="wrapper main-wrapper row">
			<!-- ticker -->
						@if((Auth::user()->status == 'support' || Auth::user()->status == 'account' || Auth::user()->status == 'engineering' || Auth::user()->status == 'noc' || Auth::user()->status == 'IT' ) && Auth::user()->username != 'cyber')
			<div class="dark" style="background-color: lightgray" >
			   <div class="p-4 p-md-5" style="padding: 20px;">
				  <p style="color: black">We Are Changing The World With Technology...!</p>
				  <div class="row">
					 <div class="col-md-8">
						<h1 class="display-4 l-s-n-1x"><span style="color: #fff">Hello, </span><span class="text-theme _700" style="color: #000">Logon <span style="color: #000">Broadband</span></span></h1>
						<div>
						   <span style="color: black">@php echo Auth::user()->email; @endphp , @php echo Auth::user()->address  @endphp <i class="fa fa-map-marker mx-2"></i></span>
						</div>
					 </div>
				  </div>
			   </div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 70px;z-index: 99;">
			   	<section class="container">
				  <form onkeyup="submitFn(this, event);">
					 <div class="search-wrapper">
						<div class="input-holder">
						   <input type="text" class="search-input" placeholder="Search With Username" style="font-family: serif;font-size: 22px;margin-top: -11px"/>
						   <button class="search-icon" onclick="searchToggle(this, event);"><span><i class="fa fa-search" style="color: blanchedalmond"></i></span></button>
						</div>
						<span class="close" onclick="searchToggle(this, event);"></span>
						<div class="result-container scrolled" id="resultbox" style="background-color: white; border-radius: 25px;height: fit-content;max-height: 225px;">
						</div>
					 </div>
				  </form>
			   	</section>
			</div>
			<div class="container emp-profile" id="resultDIV" style="display: none">
				
			   <div class="row" style="margin-top: 125px;margin-bottom:50px;position:relative">
			   		<button style="position:absolute; right:15px;top:0;border: none;font-size: 24px;font-family: cursive;cursor: pointer;z-index:9;color: #e97676" onclick="$('#resultDIV').css('display','none')">X</button>
				  <div class="col-md-3">
					 <div class="profile-img">
						<p style="text-align: center"><img src="{{asset('img/avatar/user_detail_avatar.png')}}" style="width: 150px;margin:auto" alt=""/></p>
						<!-- <img src="{{asset('img/supportlogo.png')}}" style="width: 190px;margin-top: -60px;margin-bottom: 20px;" alt=""/> -->
					 </div>
					 <div class="uprofile-name">
						<h3 class="fulname"></h3>
						<p class="uprofile-title" id="status"></p>
					 </div>
				  </div>
				  <div class="col-md-9">
				  	<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#home">Details</a></li>
						<li><a data-toggle="tab" href="#menu2">Internet Usage Statistics</a></li>
						<!-- <li><a data-toggle="tab" href="#menu3">Internet Data Usage Graph</a></li> -->
						<li><a data-toggle="tab" href="#cnic_tab">CNIC (Picture)</a></li>
					</ul>
					<div class="tab-content">
						<div id="home" class="tab-pane fade in active">
							<div style="overflow-x: auto;">
								<table class="table table-bordered user_detail_table">
									<tbody>
										<tr>
											<th class="td__profileName" style="">Consumer (ID)</th>
											<td><span class="user" style="color: black;font-size:18px; font-weight:bold"></span></td>
										</tr>
										<tr>
											<th class="td__profileName" style="">Internet Profile</th>
											<td><span class="" style="color: black;font-size:18px; font-weight:bold"></span></td>
										</tr>
										<tr>
											<th class="td__profileName" style="">Current Profile</th>
											<td><span class="" style="color: black;font-size:18px; font-weight:bold"></span></td>
										</tr>
										<tr>
											<th class="td__profileName" style="text-align: center">Reseller (ID)</th>
							  				<td><span id="reseller" style="color: black;"></span></td>
										</tr>
										<tr>
											<th class="td__profileName" style="text-align: center">Contractor (ID)</th>
							  				<td><span id="dealerids" style="color: black;"></span></td>
										</tr>
										<tr>
											<th class="td__profileName" style="text-align: center">Trader (ID)</th>
							  				<td><span id="sub_dealer_id" style="color: black;"></span></td>
										</tr>
										<tr>
											<th class="td__profileName">Address:</th>
											<td> <span id="address" style="color: black;"></span></td>
										</tr>
										<tr>
											<th class="td__profileName">Mobile Number</th>
											<td><span style="color: black;" class="phone"></span></td>
										</tr>
										<tr>
											<th class="td__profileName">CNIC Number</th>
											<td><span style="color: black;" id="nic"></span></td>
										</tr>
										<tr>
											<th class="td__profileName">Creation Date</th>
											<td> <span style="color: black;" id="createDate"></span></td>
										</tr>
										<tr>
											<th class="td__profileName">Charge on</th>
											<td><span style="color: black;" id="charge"></span></td>
										</tr>
										<tr>
											<th class="td__profileName">Expire on</th>
											<td><span style="color: black;" id="expire"></span></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div id="menu2" class="tab-pane fade">
							<div id="dd" style="background-color: white"></div>
						</div>
						
						<div id="cnic_tab" class="tab-pane fade">
							<div class="" style="display: flex; justify-content: center; column-gap: 20px;">
								<div class="cnic-box">
									<img src="/images/cnic.jpg" alt="CNIC Front" style="width:100%; height:100%">
								</div>
								<div class="cnic-box">
									<img src="/images/cnic-back.jpg" alt="CNIC Back" style="width:100%; height:100%">
								</div>
							</div>
						</div>
					</div>
				  </div>
				  {{-- <div class="col-md-6" style="margin-top: -25px;">
					 <div class="profile-head">
						<table class="table table-bordered" style="overflow: auto;">
						   <tr>
							  <th colspan="2">
								 <h2 style="font-family: serif">User Details</h2>
							  </th>
						   </tr>
						   <tr>
							  <th style="width: 155px;color: black;">User Name:</th>
							  <td></span><span class="user" style="font-weight: bold;font-size: 14px;color: green;"></span></td>
							  <th style="width: 135px;color: black;text-align: center">Reseller:</th>
							  <td></span><span id="reseller" style="font-weight: bold;font-size: 14px;color: green;"></span></td>
						   </tr>
						   <tr>
							  <th style="color: black;">User Status:</th>
							  <td><span id="status" style="font-weight: bold;font-size: 14px;color: green;"></span></td>
							  <th style="width: 135px;color: black;text-align: center">Contractor:</th>
							  <td><span id="dealerids" style="font-weight: bold;font-size: 14px;color: green;"></span></td>
							  
						   </tr>
						   <tr>
							  <th style="color: black;">User Address:</th>
							  <td > <span id="address" style="font-weight: bold;font-size: 14px;color: green;"></span></td>

						   	<th style="width: 135px;color: black;text-align: center">Trader:</th>
							  <td><span id="" style="font-weight: bold;font-size: 14px;color: green;"></span></td>
					
						   </tr>
						   <tr>
							  <th colspan="2">
								 <h2 style="font-family: serif">Data Usage</h2>
							  </th>
						   </tr>
						</table>
					 </div>
				  </div>  --}}
			   </div>
			   {{-- <div class="row">
				  <div class="col-md-4" style="margin-top: -90px;">
					 <div class="profile-work mb-2">
						<table class="table table-bordered" style="overflow: auto;">
						   <tr>
							  <th colspan="2">
								 <h2>All Data About User </h2>
							  </th>
						   </tr>
						   <tr>
							  <th style="color: black;width: 125px;">Full Name:</th>
							  <td><span style="color: green;font-weight: bold" class="fulname"></span></td>
						   </tr>
						   <tr>
							  <th style="color: black;">Mobile Phone:</th>
							  <td><span style="color: green;font-weight: bold" class="phone"></span></td>
						   </tr>
						   <tr>
							  <th style="color: black;">CNIC:</th>
							  <td><span style="color: green;font-weight: bold" id="nic"></span></td>
						   </tr>
						   <tr>
							  <th style="color: black;">Created Date:</th>
							  <td> <span style="color: green;font-weight: bold" id="createDate"></span></td>
						   </tr>
						   <tr>
							  <th colspan="2">
								 <h2>About Profile</h2>
							  </th>
						   </tr>
						   <tr>
							  <th style="color: black;">Charge Date:</th>
							  <td><span style="color: green;font-weight: bold" id="charge"></span></td>
						   </tr>
						   <tr>
							  <th style="color: black;">Expire Date:</th>
							  <td><span style="color: green;font-weight: bold" id="expire"></span></td>
						   </tr>
						</table>
					 </div>
				  </div>
				  <div class="col-md-8">
					 <div class="tab-content profile-tab" id="dd" style="background-color: white">
					 </div>
				  </div>
			   </div> --}}
			</div>
			@endif
			@if(Auth::user()->status != 'account' && Auth::user()->status != 'support' && Auth::user()->status != 'engineering' && Auth::user()->status != 'noc' && Auth::user()->status != 'IT')
			<!-- end ticker -->
			<div class="header">
				<div class="container-fluid">
					<div class="header-body">
						<div class="row">
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div id="chartCpu" style="height: 275px; width: 100%;margin-bottom: 20px;"></div>

							</div>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div id="chartRam" style="height: 275px; width: 100%;margin-bottom: 20px;"></div>

							</div>
							<div class="col-lg-4 col-md-6 col-sm-12">
								<div id="chartHdd" style="height: 275px; width: 100%;margin-bottom: 20px;"></div>

							</div>
						</div>
						<hr style="border-top: 1px solid #999;">
						<div class="row col__padding" style="margin-top: 20px;">
							
							@if(Auth::user()->status == 'super'|| Auth::user()->status == 'admin' || Auth::user()->status == 'administrator')
							
							<!-- 2nd -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								<a href="{{route('admin.user.index',['status' => 'manager'])}}" style="text-decoration:none">
									<div class="r4_counter db_box">
										<i class='pull-left fas fa-user-astronaut icon-md icon-rounded icon-primary'></i>
										<div class="" style="text-align: center;">
											<h3><strong>
												@php
													$manager ='';
													if(Auth::user()->status == "super"){
													$manager =App\model\Users\UserInfo::where(['status' => 'manager'])->count();
												}else{
												$manager =App\model\Users\UserInfo::where(['status' => 'manager','creationby' =>'admin'])->count();
											}
												@endphp
												{{$manager}}</strong></h3>
											<span>Managers</span>
										</div>
									</div>
								</a>
							</div>
							<!-- 3rd -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								<a href="{{route('admin.user.index',['status' => 'reseller'])}}" style="text-decoration:none">
									<div class="r4_counter db_box">
										<i class='pull-left fas fa-user-tie icon-md icon-rounded icon-primary'></i>
										<div class="" style="text-align: center;">
											<h3><strong>

												@php
													$reseller ='';
													if(Auth::user()->status == "super"){
													$reseller =App\model\Users\UserInfo::where(['status' => 'reseller'])->count();
												}else{

												$reseller =App\model\Users\UserInfo::where(['status' => 'reseller','resellerid' =>'logonbroadband'])->count();
											}
												@endphp
											{{$reseller}}</strong></h3>
											<span>Resellers</span>
										</div>
									</div>
								</a>
							</div>
							<!-- 4rd -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								<a href="{{route('admin.user.index',['status' => 'dealer'])}}" style="text-decoration:none">
									<div class="r4_counter db_box">
										<i class='pull-left fas fa-user-friends icon-md icon-rounded icon-primary'></i>
										<div class="" style="text-align: center;">
											<h3><strong>
												@php
													$dealer ='';
													if(Auth::user()->status == "super"){
													$dealer =App\model\Users\UserInfo::where(['status' => 'dealer'])->count();
												}else{
												$dealer =App\model\Users\UserInfo::where(['status' => 'dealer','resellerid' =>'logonbroadband'])->count();
											}
												@endphp

												{{$dealer}}</strong></h3>
											<span>Contractors</span>
										</div>
									</div>
								</a>
							</div>
							<!-- 5th -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								<div class="r4_counter db_box">
									<i class='pull-left fas fa-users-cog icon-md icon-rounded icon-primary'></i>
									<div class="" style="text-align: center;">
										<h3><strong>
											@php
												$subdealer ='';
												if(Auth::user()->status == "super"){
												$subdealer =App\model\Users\UserInfo::where(['status' => 'subdealer'])->count();
											}else{
											$subdealer =App\model\Users\UserInfo::where(['status' => 'subdealer','resellerid' =>'logonbroadband'])->count();
										}
											@endphp

										{{$subdealer}}</strong></h3>
										<span>Traders</span>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-6 col-sm-12">
								<div class="r4_counter db_box">
									<i class='pull-left fa fa-users icon-md icon-rounded icon-primary'></i>
									<div class="" style="text-align: center;">
										<h3><strong>
											@php
												$user ='';
												if(Auth::user()->status == "super"){
												$user =App\model\Users\UserInfo::where(['status' => 'user'])->count();
											}else{
											$user =App\model\Users\UserStatusInfo::count();
										}
											@endphp

											{{$user}}</strong></h3>
										<span>Consumers</span>
									</div>
								</div>
							</div>
							<!-- 6ht -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								<div class="r4_counter db_box">
									<i class='pull-left la la-user-check icon-md icon-rounded icon-primary'></i>
									<div class="" style="text-align: center;">
										<h3><strong>{{$activeUser}}</strong></h3>
										<span> Active Consumers</span>
									</div>
								</div>
							</div>
							<!-- Disabled Consumers -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								<div class="r4_counter db_box">
									<i class='pull-left la la-user-slash icon-md icon-primary icon-rounded '></i>
									<div class="stats" style="text-align: center;">
										<h3><strong>{{$disable_user}}</strong></h3>
										<span>Disabled Consumers</span>
									</div>
								</div>
							</div>
							<!-- Online Users -->
							<div class="col-lg-3 col-md-6 col-sm-12">
								<div class="r4_counter db_box">
									<i class='pull-left la la-users icon-rounded icon-primary' style="font-size: 38px"></i>
									<div class="stats" style="text-align: center;">
										<h3><strong><?= count($onlineUser);?></strong></h3>
										<span><strong><?= $onlinePercentage;?>%</strong> Online Consumers</span>
									</div>
								</div>
							</div>
							<!-- Upcoming Expiries -->
							{{-- <div class="col-lg-3 col-md-6 col-sm-12">
									<div class="r4_counter db_box">
										<i class='pull-left fas fa-calendar-minus icon-md icon-rounded icon-purple' ></i>
										<div class="stats" style="text-align: center;">
											<h3><strong>{{$upcomingExpire}}</strong></h3>
											<span>Upcoming Expiries</span>
										</div>
									</div>
							</div> --}}
							@endif
							@if(Auth::user()->status == 'support')
							<div class="col-lg-4 col-md-6 col-sm-12">
								<a href="" style="text-decoration:none">
									<div class="r4_counter db_box">
										<i class='pull-left fa fa-users icon-md icon-rounded icon-primary'></i>
										<div class="" style="text-align: center;">
											<h3><strong>{{App\model\Users\UserStatusInfo::where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))->count()}}</strong></h3>
											<span> Active Consumers</span>
										</div>
									</div>
								</a>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="">
				<div class="row" style="margin: 0 0 30px">
					<div class="col-md-12">
						<!-- <div class="col-xl-8 col-md-12"> -->
							<!-- <img src="{{asset('img/graph_image.png')}}" style="width: 100%;"> -->
						<!-- </div> -->
						 @if(Auth::user()->status == 'super' || Auth::user()->status == 'admin' || Auth::user()->status == 'administrator')
						
						
						<!--  -->
						{{-- <hr style="border-top:1px solid #999">
						<div class="online__card--wrapper">
                			<div class="consumer_wrapper" style="outline: 1px solid;outline-offset: 5px;">
                    			<div class="card-content">
									<div class="icon__part">
										<i class="fa fa-users"></i>
										<div class="online_status"></div>
									</div>
									<div class="progress_wrapper">
										<h4 class="text-center fw-bold" style="font-weight: bold;">Online Consumers</h4>
										<div class="progress">
											<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:<?= $onlinePercentage;?>%">
											<?= $onlinePercentage;?>%
											</div>
										</div>
									</div>
									<div class="subscriber-count">
										<div>
											<h6 id="sec-h43" class="online_user-count text-center" ><?= count($onlineUser);?></h6>
											<!-- <label for="file" style="color:#225094  ;">Online Subscribers Ratio</label> -->
										</div>
									</div> 
                    			</div>
                			</div>
            			</div> --}}

						<hr style="border-top:1px solid #999; margin-top: 10px">
						<!-- Table Profile -->
						<div class="col-lg-12 col-md-12">
						<div id="chartContainer" style="width: 100% !important"></div>
							<!-- <section class="box">
								<div style="overflow-x: auto;">
									<div class="col-xs-12">
										<div class="" >
										
											<table class="table">
												<thead class="theads" >
													<tr>
														<th>Profile Name</th>
														<th>No Of Users</th>
														<th>Profile Color</th>
													</tr>
												</thead>
												<tbody class="tbodys">
													@foreach($profileCollection as $data)
													
													@php
													// dd($data);
													if(Auth::user()->status == 'super'){
													$newCount  = DB::table('user_info')
													->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
													->where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))
													->where('user_info.name','=',$data->name)
													->where('user_info.status','=','user')
													->count();
												}else{
												$newCount  = DB::table('user_info')
													->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
													->where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))
													->where('user_info.name','=',$data->name)
													->where('user_info.status','=','user')
													->count();
													// dd($newCount);
												}
													@endphp
													@if($data->name != 'lite' && $data->name != 'social' && $data->name != 'smart' && $data->name != 'super' && $data->name != 'turbo' && $data->name != 'mega' && $data->name != 'jumbo' && $data->name != 'sonic' )
													<tr>
														<td>{{ucfirst($data->name)}}</td>
														<td>{{$newCount}}</td>
														<td><center>
															<i class="fa fa-square" style="font-size: 22px;color: {{$data->color}}"></i> </center>
														</td>
													</tr>
													@endif
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</section> -->
						</div>
						
						@endif
						@if(Auth::user()->status == 'support')
						<div class=" col-xl-4 col-md-12 col-lg-12">
							<section class="box nobox marginBottom0">
								<div class="content-body">
									<div class="row">
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<div id="onlinediv">
												<div class="r4_counter db_box" id="">
													@php
													$totalUser=$userStatus;
													$onlineUserr=$onlineUser->count();
													$Online=round(($onlineUserr/$totalUser)*100);
													@endphp
													<span>Online Users<span style="float: right;">{{$onlineUserr}} Users  </span></span>
													<div class="progress" style="height: 27px">
														<div class="progress-bar animated  animated-duration-1s animated-delay-200ms" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$Online}}%; background-color: #fe6501;line-height: 28px;">
															{{$Online}}%
														</div>
													</div>
												</div>
											</div>
											<!--  -->
										</div>
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<div class="r4_counter db_box">
												<i class='pull-left fa fa-exclamation-triangle icon-md icon-rounded icon-primary'></i>
												<div class="stats">
													<h4><strong>0</strong></h4>
													<span>Error Logs</span>
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<div class="r4_counter db_box">
												<i class='pull-left fa fa-table icon-md icon-rounded icon-purple' ></i>
												<div class="stats">
													<h4><strong>{{$upcomingExpire}}</strong></h4>
													<span>Upcoming Expire</span>
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<div class="r4_counter db_box">
												<i class='pull-left fa fa-ban icon-md icon-rounded icon-accent'></i>
												<div class="stats">
													<h4><strong>{{$disable_user}}</strong></h4>
													<span>Disabled Users</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						@endif
					</div>
				</div>
			</div>
			@endif
         @if(Auth::user()->username == 'cyber' &&  Auth::user()->status == 'support')
         <div class="header">
            <div class="container-fluid">
               <div class="header-body">
                  <div class="row" style="margin-top: 6%;">
                     @if(Auth::user()->status == 'super'|| Auth::user()->status == 'admin' || Auth::user()->status == 'administrator')
                     <div class="col-lg-2 col-sm-2 col-xs-12 col-md-2">
                        <div class="r4_counter db_box">
                           <!--  <i class='pull-left fa fa-users icon-md icon-rounded icon-primary'></i> -->
                           <div class="" style="text-align: center;">
                              <h3><strong>{{App\model\Users\UserInfo::where(['status' => 'user'])->count()}}</strong></h3>
                              <span>All Users</span>
                           </div>
                        </div>
                     </div>
                     <!-- 2nd -->
                     <div class="col-lg-2 col-sm-2 col-xs-12 col-md-2">
                        <div class="r4_counter db_box">
                           
                           <div class="" style="text-align: center;">
                              <h3><strong>{{App\model\Users\UserInfo::where(['status' => 'manager'])->count()}}</strong></h3>
                              <span>Manager</span>
                           </div>
                        </div>
                     </div>
                     <!-- 3rd -->
                     <div class="col-lg-2 col-sm-2 col-xs-12 col-md-2">
                        <div class="r4_counter db_box">
                           <!--  <i class='pull-left fa fa-users icon-md icon-rounded icon-primary'></i> -->
                           <div class="" style="text-align: center;">
                              <h3><strong>{{App\model\Users\UserInfo::where(['status' => 'reseller'])->count()}}</strong></h3>
                              <span>Reseller</span>
                           </div>
                        </div>
                     </div>
                     <!-- 4rd -->
                     <div class="col-lg-2 col-sm-2 col-xs-12 col-md-2">
                        <div class="r4_counter db_box">
                           <!--  <i class='pull-left fa fa-users icon-md icon-rounded icon-primary'></i> -->
                           <div class="" style="text-align: center;">
                              <h3><strong>{{App\model\Users\UserInfo::where(['status' => 'dealer'])->count()}}</strong></h3>
                              <span>Dealer</span>
                           </div>
                        </div>
                     </div>
                     <!-- 5th -->
                     <div class="col-lg-2 col-sm-2 col-xs-12 col-md-2">
                        <div class="r4_counter db_box">
                           <!--  <i class='pull-left fa fa-users icon-md icon-rounded icon-primary'></i> -->
                           <div class="" style="text-align: center;">
                              <h3><strong>{{App\model\Users\UserInfo::where(['status' => 'subdealer'])->count()}}</strong></h3>
                              <span>Sub-Dealer</span>
                           </div>
                        </div>
                     </div>
                     <!-- 6ht -->
                     <div class="col-lg-2 col-sm-2 col-xs-12 col-md-2">
                        <div class="r4_counter db_box">
                           <!--  <i class='pull-left fa fa-users icon-md icon-rounded icon-primary'></i> -->
                           <div class="" style="text-align: center;">
                              <h3><strong>{{App\model\Users\UserStatusInfo::where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))->count()}}</strong></h3>



                              
                              <span> Active Users</span>
                           </div>
                        </div>
                     </div>
                     @endif
                     @if(Auth::user()->status == 'support')
                     <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
                        <div class="r4_counter db_box">
                           <!--  <i class='pull-left fa fa-users icon-md icon-rounded icon-primary'></i> -->
                           <div class="" style="text-align: center;">
                              <h3><strong>{{App\model\Users\UserStatusInfo::where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))->count()}}</strong></h3>
                              <span> Active Users</span>
                           </div>
                        </div>
                     </div>
                     @endif
                  </div>
               </div>
            </div>
         </div>
         <div class="">
            <div class="row">
               <div class="col-md-12">
                  <div class="col-xl-8 col-md-12">
                     <!-- <img src="{{asset('img/graph_image.png')}}" style="width: 100%;"> -->
                  </div>
                  @if(Auth::user()->status == 'super' || Auth::user()->status == 'admin' || Auth::user()->status == 'administrator')
                  <div class="col-xl-4 col-md-6">
                     <section class="box">
                        <div style="overflow-x: auto; overflow-y: ">
                           <div class="col-lg-12 col-sm-6 col-xs-12">
                              <div class="" >
                                 <table class="table">
                                    <thead class="theads" >
                                       <tr>
                                          <th>Profile Name</th>
                                          <th>No Of Consumers</th>
                                          <th>Profile Color</th>
                                       </tr>
                                    </thead>
                                    <tbody class="tbodys">
                                       @foreach($profileCollection as $data)
                                       @php
                                       $newCount  = DB::table('user_info')
                                       ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                                       ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))
                                       ->where('user_info.name','=',$data->name)
                                       ->where('user_info.status','=','user')
                                       ->count();

                                       
                                       @endphp
                                       @if($data->name != 'lite' && $data->name != 'social' && $data->name != 'smart' && $data->name != 'super' && $data->name != 'turbo' && $data->name != 'mega' && $data->name != 'jumbo' && $data->name != 'sonic' )
                                       <tr>
                                          <td>{{ucfirst($data->name)}}</td>
                                          <td>{{$newCount}}</td>
                                          <td><center>
                                             <i class="fa fa-square" style="font-size: 22px;color: {{$data->color}}"></i> </center>
                                          </td>
                                       </tr>
                                       @endif
                                       @endforeach
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </section>
                  </div>
                  
                  <div class=" col-xl-4 col-md-6">
                     <section class="box nobox marginBottom0">
                        <div class="content-body">
                           <div class="row">
                              <div class="col-lg-12 col-sm-6 col-xs-12">
                                 <!--  -->
                                 <div id="onlinediv">
                                    <div class="r4_counter db_box" id="">
                                       @php
                                       $totalUser=$userStatus;
                                       $onlineUserr=$onlineUser->count();
                                       $Online=round(($onlineUserr/$totalUser)*100);
                                       @endphp
                                       <span>Online Users<span style="float: right;">{{$onlineUserr}} Users  </span></span>
                                       <div class="progress" style="height: 40px">
                                          <div class="progress-bar animated  animated-duration-1s animated-delay-200ms" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$Online}}%; background-color: #fe6501;">
                                             {{$Online}}%
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--  -->
                              </div>
                              <div class="col-lg-6 col-sm-6 col-xs-12">
                                 <div class="r4_counter db_box">
                                    <i class='pull-left fa fa-exclamation-triangle icon-md icon-rounded icon-primary'></i>
                                    <div class="stats">
                                       <h4><strong>0</strong></h4>
                                       <span>Error Logs</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6 col-sm-6 col-xs-12">
                                 <div class="r4_counter db_box">
                                    <i class='pull-left fa fa-ban icon-md icon-rounded icon-accent'></i>
                                    <div class="stats">
                                       <h4><strong>{{$disable_user}}</strong></h4>
                                       <span>Disabled Users</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12 col-sm-12 col-xs-12">
                                 <div class="r4_counter db_box">
                                    <i class='pull-left fa fa-table icon-md icon-rounded icon-purple' ></i>
                                    <div class="stats">
                                       <h4><strong>{{$upcomingExpire}}</strong></h4>
                                       <span>Upcoming Expire</span>
                                    </div>
                                 </div>
                              </div>
                           </div> <!-- End .row -->
                        </div>
                     </section>
                  </div>
                  @endif
                  @if(Auth::user()->status == 'support')
                  <div class=" col-xl-4 col-md-12 col-lg-12">
                     <section class="box nobox marginBottom0">
                        <div class="content-body">
                           <div class="row">
                              <div class="col-lg-6 col-sm-6 col-xs-12">
                                 <div id="onlinediv">
                                    <div class="r4_counter db_box" id="">
                                       @php
                                       $totalUser=$userStatus;
                                       $onlineUserr=$onlineUser->count();
                                       $Online=round(($onlineUserr/$totalUser)*100);
                                       @endphp
                                       <span>Online Users<span style="float: right;">{{$onlineUserr}} Users  </span></span>
                                       <div class="progress" style="height: 27px">
                                          <div class="progress-bar animated  animated-duration-1s animated-delay-200ms" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$Online}}%; background-color: #fe6501;line-height: 28px;">
                                             {{$Online}}%
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--  -->
                              </div>
                              <div class="col-lg-6 col-sm-6 col-xs-12">
                                 <div class="r4_counter db_box">
                                    <i class='pull-left fa fa-exclamation-triangle icon-md icon-rounded icon-primary'></i>
                                    <div class="stats">
                                       <h4><strong>0</strong></h4>
                                       <span>Error Logs</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6 col-sm-6 col-xs-12">
                                 <div class="r4_counter db_box">
                                    <i class='pull-left fa fa-table icon-md icon-rounded icon-purple' ></i>
                                    <div class="stats">
                                       <h4><strong>{{$upcomingExpire}}</strong></h4>
                                       <span>Upcoming Expire</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6 col-sm-6 col-xs-12">
                                 <div class="r4_counter db_box">
                                    <i class='pull-left fa fa-ban icon-md icon-rounded icon-accent'></i>
                                    <div class="stats">
                                       <h4><strong>{{$disable_user}}</strong></h4>
                                       <span>Disabled Users</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </section>
                  </div>
                  @endif
               </div>
            </div>
         </div>
         @endif
			<div class="chart-container " style="display: none;">
				<div class="" style="height:200px" id="platform_type_dates"></div>
				<div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
				<div class="" style="height:200px" id="user_type"></div>
				<div class="" style="height:200px" id="browser_type"></div>
				<div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
				<div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
			</div>
			<div class="clearfix"></div>
			<div class="clearfix"></div>
		</section>
	</section>
	<!-- END CONTENT -->
</div>
@endsection
@section('ownjs')
<script>
	$(document).ready(function(){
		$('[data-toggle="popover"]').popover();
	});
	console.clear();
		// $('#onlinediv').load(' #onlinediv');
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			setInterval(function(){
				$('#onlinediv').load(' #onlinediv');
		// $("#mrtg").attr("src", "http://192.168.100.10/cacti/graph_image.php?local_graph_id=274&rra_id=5&graph_width=1000&graph_height=200&graph_nolegend=(E)" + new Date().getTime());
	},1000);
		});
	</script>
	<script type="text/javascript">
		function searchToggle(obj, evt){
			var container = $(obj).closest('.search-wrapper');
		
			if(!container.hasClass('active')){
				  container.addClass('active');
				  evt.preventDefault();
			}
			else if(container.hasClass('active') && $(obj).closest('.input-holder').length == 0){
				  container.removeClass('active');
				  // clear input
				  container.find('.search-input').val('');
				  // clear and hide result container when we press close
				  container.find('.result-container').fadeOut(100, function(){$(this).empty();});
			}
		}
		</script>
	<script>
	  function submitFn(obj, evt){
       value = $(obj).find('.search-input').val().trim();
       evt.preventDefault();
       // _html = "Welcome LOGON Broadband: ";
       if(value.length){
       $.ajax({
       type: "POST",
       url: "{{route('admin.Supportsearch')}}",
       data:'user='+value,
       success: function(data){
		//    alert(data);
		// console.log(data);
        $(obj).find('.result-container').html('<span>' + data + '</span>');
       $(obj).find('.result-container').fadeIn(100);
       }
       });
       }
       else{
           $('.result-container').hide();
       }
      
   }
	</script>
	<script>
		function searchResult(username){
		 
		   $.ajax({
		   type: "post",
		   url: "{{route('admin.SupportsearchResult')}}",
			data:'username='+username,
			dataType: "json",
		   success: function(data){
			  var mob = data.mobile_status;
			  var nic = data.cnic;
			  var fnic = data.nic_front;
			  var bnic = data.nic_back;
			  $(".user").html(data.username);
			  $("#dealerids").html(data.dealerid);
			  if(data.status == 'user'){
				  $("#status").html('Consumer');
			  }
			  $("#expire").html(data.card_expire_on);
			  if(mob == '' || mob == null){
			  $(".phone").html("<span style='color:red'> (Unverified) </span>");
			  }else{
				 $(".phone").html(data.mobile+" <span style='color:green'> (Verified) </span>");
			  }
			  if(nic ==null || fnic == null || bnic == null){
				 $("#nic").html(" <span style='color:red'> (Unverified) </span>");
			  }else{$("#nic").html(data.cnic+" <span style='color:green'> (Verified) </span>");}
			  $("#charge").html(data.card_charge_on);
			  $(".profiless").html(data.profile);
			  $(".fulname").html(data.firstname+" "+data.lastname);
			  $(".email").html(data.email);
			  $("#address").html(data.address);
			  $("#reseller").html(data.resellerid);
			  if(data.sub_dealer_id == '' || data.sub_dealer_id == null){
				$("#sub_dealer_id").html("<span style='color:#5198ff'> Not your Trader's (ID) </span>");
			  }else{
				$("#sub_dealer_id").html(data.sub_dealer_id);
			  }
			  $("#createDate").html(data.creationdate);
			   $("#resultbox").hide();
			   $("#resultDIV").show();
			   showupdownGraph(data.id);
			   
		   }
		   });
		}
		function showupdownGraph(id){
		
		 $.ajax({
		 type: "post",
		 url: "{{route('admin.SupportupdownGraph')}}",
		  data:'id='+id,
		//    dataType: "json",
		 success: function(data){
		   $("#dd").html(data);
			 
		 }
		 });
		}
	 </script>
	 <script>
		$(document).ready(function(){
		   $(document).on("submit", "#macForm", function(e) {
		   var formData = {
		   'clearmac' : $('input[name=clearmac]').val(),
		   'userid' : $('input[name=userid]').val()
		  };
		   $.ajax({
			  type: "post",
			  url: "{{route('admin.SupportSupportclearMac')}}",
			  data: formData,
			  dataType: "json",
			  success: function(data){
			   alert('Your Mac has been cleared');
			  }
		   });
		   e.preventDefault();
	 });
	 
	 });
	 </script>
	 <script>
		// window.onload = function () {
            
			
		// 	chart.render();
		// 	}
	</script>
	<script>
	window.onload = function() {
		var chart = new CanvasJS.Chart("chartContainer", {
				exportEnabled: true,
				animationEnabled: true,
				
				title:{
					text:""
				},
				axisX:{
					title: "Internet Profiles",
					interval: 0
				},
				axisY:{
					interlacedColor: "rgba(2,44,101,.2)",
					gridColor: "rgba(1,77,101,.1)",
					title: "Number of Subscribers"
				},
				data: [{
					type: "column",
					yValueFormatString: "#,##0.0#",
					dataPoints: [
						// { label: "Bitcoin", y: 7.1 }, 
						// { label: "Solana", y: 6.70 },
	
						<?php foreach($profileWiseUser as $key => $pvalue){
							echo '{ label: "'.$profileWiseUser[$key][0].'", y: '.$profileWiseUser[$key][1].' },';
						}?>
						
					]
				}]
				// data: [{
				//     type: "bar",
				//     name: "companies",
				//     axisYType: "secondary",
				//     color: "#225094",
				//     dataPoints: <php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
				// }]
			});
		var hddchart = new CanvasJS.Chart("chartHdd", {
			exportEnabled: true,
			animationEnabled: true,
			title: {
				text: "Hard Disk Usage"
			},
			legend:{
				cursor: "pointer",
				itemclick: explodePie
			},
			data: [{
				type: "pie",
				yValueFormatString: "#,##0",
				showInLegend: true,
				toolTipContent: "{name}: <strong>{y} %</strong>",
				indexLabel: "{name} - {count} GB",
				dataPoints: [
				<?php
    					echo '{ y: '.$sysGraphData['freePer'].', name: "Free Space",exploded: true , count : '.$sysGraphData['diskfree'].' },';
    					echo '{ y: '.$sysGraphData['usedPer'].', name: "Used Space",exploded: true , count : '.$sysGraphData['diskused'].' },';
    				
    				?>
				]
			}]
		});
		var ramchart = new CanvasJS.Chart("chartRam", {
			exportEnabled: true,
			animationEnabled: true,
			title: {
				text: "RAM Usage"
			},
			legend:{
				cursor: "pointer",
				itemclick: explodePie
			},
			data: [{
				type: "pie",
				showInLegend: true,
				toolTipContent: "{name}: <strong>{y} GB</strong>",
				indexLabel: "{name} - {y} GB",
				dataPoints: [
					<?php
    					echo '{ y: '.$sysGraphData['memused'].', name: "Used Space" },';
    					echo '{ y: '.$sysGraphData['memfree'].', name: "Free Space",exploded: true},';
    					echo '{ y: '.$sysGraphData['membuffer'].', name: "Buffer Space"},';
    					echo '{ y: '.$sysGraphData['memcached'].', name: "Cached Space"},';
    				
    				?>
				]
			}]
		});
		var cpuchart = new CanvasJS.Chart("chartCpu", {
			exportEnabled: true,
			animationEnabled: true,
			title: {
				text: "CPU Usage"
			},
			legend:{
				cursor: "pointer",
				itemclick: explodePie
			},
			data: [{
				type: "pie",
				showInLegend: true,
				toolTipContent: "{name}: <strong>{y} %</strong>",
				indexLabel: "{name} - {y} %",
				dataPoints: [
					<?php
    					echo '{ y: '.$sysGraphData['cpuload'].', name: "CPU Usage",exploded: true },';
    					echo '{ y: '.$sysGraphData['totalLoad'].', name: "Free Space"}';
    				?>
				]
			}]
		});
		chart.render();
		hddchart.render();
		ramchart.render();
		cpuchart.render();
	}
	function explodePie (e) {
		if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
			e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
		} else {
			e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
		}
		e.chart.render();
	}
</script>
	@endsection