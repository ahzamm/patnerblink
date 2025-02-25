@extends('users.layouts.app',[
'profileCollection' => $profileCollection
])
@section('title') Dashboard @endsection
@section('owncss')

@endsection
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-container row-fluid container-fluid">
  
   <!-- SIDEBAR - START -->
   <section id="main-content" class="">
      <section class="wrapper main-wrapper" style='margin:0; padding: 5px 0px 0px 0px;'>
      <section class="wrapper main-wrapper" style='margin:0; padding:0;'>
      <section class="home-section">
      <div class="bord"></div>
               <nav>
                <div class="container">
                    <div class="nav-section">
                        <div class="hello">
                            <h4>Changing World with technology..!</h4>
                            <h2>Hello,<b>  {{$currentUser->username}}</b></h2>
                            <h5>{{$currentUser->email}} , {{$currentUser->permanent_address }} </h5>
                        </div>
                            <!-- <div class="parttwo">
                                <div class="amount">
                                    <div class="icon-box"><i class="fa-sharp fa-solid fa-wallet"></i> </div>
                                    <h2>Wallet</h2>
                                    <h6>147,390</h6>
                                </div>
                        </div>
                    </div> -->
                    <div class="parttwo" >
                <div class="icon-box"><i class="fa-sharp fa-solid fa-wallet"></i></div>
                <div class="amount">
                <h4>Wallet</h4>
                @if(count($wallet)>0)
                <h3>PKR  {{ $wallet[0]->amount }}</h3>
                @else 
                <h3>PKR 0 </h3>
                @endif
</div>
                </div>
            </nav>
            <div class="bread-crumbs">
               <!-- <ul>
<li a href=""><i class="fa-solid fa-house-user"></i></a></li>
<li a href=""><i class="fa-solid fa-house-user"></i></a></li>
<li a href=""><i class="fa-solid fa-house-user"></i></a></li>
<li a href=""><i class="fa-solid fa-house-user"></i></a></li>
<li a href=""><i class="fa-solid fa-house-user"></i></a></li>
</ul>
            </div> -->
            <div class="container">
            <ul class="breadcrumb">
  <li><a href="#">About us</a></li>
  <li><a href="#">Contact us</a></li>
 
</ul>
            </div>

	 </section>
	 
	  @if(Auth::user()->status != 'user')
	  
      <section class="box-section">
         <div class="container"> 
            <div class="box1">
                <div class="icon-box"><i class="fa-solid fa-users"></i></div>
                <div class="amount">
                <h4 class="sec-one">All Users</h4>
                <h3 class="sec-two">
				{{$all_users=App\model\Users\UserInfo::where(['status' => 'user','manager_id'=> Auth::user()->manager_id])->count()}}
                </h3>
</div>
<div class="hrr">
<div class="border"></div>
</div>

@php

	 $user_display="red";
	 $sign_all_users="-";
	 
	 if($all_users>$totalyesterdayusers){
		  $user_display="green";
		   $sign_all_users="+";
	 }
	 
 
@endphp



<h5 style=""><b style="color:{{ $user_display}}" > {{ $sign_all_users }} {{ number_format($all_users/$totalyesterdayusers) }}%</b> Than Yesterday</h5>
            </div>
            <div class="box2">
                <div class="icon-box"><i class="fa-solid fa-handshake"></i></div>
                <div class="amount">
                <h4 class="sec-one">Dealer</h4>
                <h3 class="sec-two">
			    	{{$all_dealers=App\model\Users\UserInfo::where(['status' => 'dealer','resellerid'=> Auth::user()->resellerid])->count()}}
                 </h3>
</div>
<div class="hrr">
<div class="border"></div>
</div>

@php

     $dearler_display="red";
     $sign_all_dealers="-";
	
	if($all_dealers>0 && $totalyesterdaydealers>0){
     if($all_dealers>$totalyesterdaydealers){
		   $dearler_display="green";
		   $sign_all_dealers="+";
	   } 
     }
 
@endphp
<h5><b style="color:{{ $dearler_display}}">
    @php 
      if($all_dealers>0 &&  $totalyesterdaydealers>0){
    @endphp
    {{$sign_all_dealers}} {{ number_format($all_dealers/$totalyesterdaydealers) }} %</b> Than Yesterday</h5>
    @php 
    } else {

        echo '<h5><b style="">0 %</b> Than Yesterday</h5>';
    }
    @endphp
            </div>
            <div class="box3">
                <div class="icon-box"><i class="fa-sharp fa-solid fa-money-bill-trend-up"></i></div>
                <div class="amount">
                <h4 class="sec-one">Trader</h4>
                <h3 class="sec-two">
				{{$all_traders=App\model\Users\UserInfo::where(['status' => 'trader','resellerid'=> Auth::user()->resellerid])->count()}}
                </h3>
</div>
<div class="hrr">
<div class="border"></div>
</div>

<!-- test -->
<!-- test -->

@php

	 $trader_display="red";
	 $sign_all_trader="-";
	
	 if($all_traders>$totalyesterdaytraders){
		   $trader_display="green";
		   $sign_all_trader="+";
	 } 
   if($all_traders>0) {
@endphp
<h5><b style="color:{{ $trader_display}}">{{$sign_all_trader}}{{ number_format($all_traders/$totalyesterdaytraders) }}%</b> Than Yesterday</h5>
@php    
   } else {

     echo '<h5 class="trade_stats"><b>0%</b> Than Yesterday</h5>';

   }

 

@endphp
  
		 </div>
            <div class="box4">
                <div class="icon-box"><i class="fa-regular fa-user"></i></div>
                <div class="amount">
                <h4 class="sec-one">Active Users</h4>
                <h3 class="sec-two">
				 {{$userStatus}}
                </h3>
</div>
<div class="hrr">
<div class="border"></div>
</div>

@php

	 $all_active_users_display="red";
	 $sign_all_active_users="-";
	 
	// echo $totalyesterdayactiveusers;
	if($userStatus>0 && $totalyesterdayactiveusers>0){
	 if($userStatus>$totalyesterdayactiveusers){
		   $all_active_users_display="green";
		   $sign_all_active_users="+";
	 } 
   }

if($totalyesterdayactiveusers!=0 && $userStatus!=0) {
   if($userStatus>0) {

@endphp
<h5><b style="color:{{ $all_active_users_display}}">{{$sign_all_active_users}}{{ number_format($userStatus/$totalyesterdayactiveusers) }}%</b> Than Yesterday</h5>
@php    
   }
} else {

     echo '<h5 class="trade_stats"><b>0%</b> Than Yesterday</h5>';

   }  

@endphp
            </div>
            
        </div>
      </section>
<section class="box-section">
         <div class="container"> 
            <div class="box1">
                <div class="icon-box2"><i class="fa-solid fa-x"></i></div>
                <div class="amount2">
                <h4 id="sec-h4">
				  <strong id = "errorLog"><strong>
				</h4>
                <div class="border"></div>
                <h3 id="sec-head">Invalid Login
</h3>
</div>
            </div>
            <div class="box2">
                <div class="icon-box2"><i class="fa-solid fa-user-slash"></i></div>
                <div class="amount2">
				
                <!-- <h4 id="sec-h4">1486</h4> -->
				
				<h4 id="sec-h4"><strong id="disableData"></strong></h4>
                 {{-- <img src="/images/loading.gif" id="loading-indicator" style="display:none;position: absolute; left: 30px; top: 10px; width:80px; height:80px;"/> --}}
			   <div>
				  <img src="/images/Ripple.gif" id="loading-image1" style="display:none;position: absolute; left: 30px; top: 10px; width:80px; height:80px;"/>
			   </div>
                <div class="border"></div>
                <h3 id="sec-head">Disable User
</h3>
</div>
            </div>
            <div class="box3">
                <div class="icon-box2"><i class="fa-solid fa-ban"></i></div>
                <div class="amount2">
                @if($upcoming_expiry_users>0 )
                <h4 id="sec-h4">{{$upcoming_expiry_users}}</h4>
                @else
                <h4 id="sec-h4">0</h4>
                @endif
                <div class="border"></div>
                <h3 id="sec-head">Upcoming Expire
</h3>
</div>
            </div>
            <div class="box4">
                <div class="icon-box2"><i class="fa-solid fa-address-card"></i></div>
                <div class="amount2">
                <h4 id="sec-h4">{{$verified_users}}</h4>
                <div class="border"></div>
                <h3 id="sec-head">NIC verified
</h3>
</div>
            </div>
           
</div>
</section>
@endif
   <section class="box-section">
    <div class="container">
    <div class="box5">
                <div class="icon-box3"><i class="fa-solid fa-signal"></i></div>
                <div class="amount3">
					@php
					$totalUser=$userStatus;
					$onlineUserr=$onlineUser->count();
					
					$Online=round((@$onlineUserr/$totalUser)*100);
					
					$user=array();
					$dealercount=array();
					
					
					foreach($fewonlineUser as $v){
					  
				     $userdata=App\model\Users\UserInfo::where(['username'=>$v->username])->get();
					
						foreach($userdata as $v){							
							$dearlerdatacount=App\model\Users\UserInfo::where(['dealerid'=>$v->dealerid])->count();	
							$user[]=$v->username;
							$dealercount[]=$dearlerdatacount;
						}
					 
					}

                    $dataPoints=array();

                    if(count($dealercount)>0 && count($user)>0 ){
					
    					$dataPoints = array(
    						array("y" => $dealercount[0], "label" => $user[0]),
    						array("y" => $dealercount[1], "label" => $user[1]),
    						array("y" => $dealercount[2], "label" => $user[2]),
    						array("y" => $dealercount[3], "label" => $user[3]),
    						array("y" => $dealercount[4], "label" => $user[4]),
    						array("y" => $dealercount[5], "label" => $user[5]),
    						array("y" => $dealercount[6], "label" => $user[6]),
    						array("y" => $dealercount[7], "label" => $user[7]),
    						array("y" => $dealercount[8], "label" => $user[8])
    					);

                    }
					
					@endphp
                <h4 id="sec-h43">{{$onlineUserr}} Online Users</h4>
                <label for="file" style="color:#225094  ;">Online Users Ratio</label>

</h3>
</div> 
    </div>  
</section>       
<hr>
      <section class="Chart-section">
                <div class="container">
                    <h2>Profile Wise User</h2>
                    <div class="bar-graph">
                        <div id="chartContainer" style="height: 400px; width: 75%; display: inline-block;">
</div>
                        <div class="table-section">
    <div class="scroll">
					<table class="table text-center">
					<thead class="text-center">
					   <tr class="text-center">
						  <th class="text-center">User Name</th>
						  <th class="text-center">Login Time</th>
						  <th class="text-center">IP</th>
					   </tr>
					</thead>
					<tbody class="liveUsers">
					</tbody>
				 </table>
    </div>
</div>
</div>
</section>
<hr>
<section class="slider2">
<div class="container">
                  <div class="marquee-sec">
                  
                  <p class="moving-texturdu">تمام  ڈیلرز اور سبڈیلر  کو مطلع کیا جاتا ہے کہ وہ اپنے تمام یوزرز کو تصدیق کریں بصورت دیگر آپ اپنے غیر تصدیق شدہ یوزرز کی پروفائل تبدیل نہیں کر سکتے 
                    </p>
                    <p class="moving-text">
                        It is to inform all dealers/sub-dealers to Verify your users, Otherwise you will not be able to change unverified user profile.
                    </p>
                    </p>
</div>
</div>
             </section>
             <footer>
             <div class="container">
                    <div class="last-section">
                        <div class="social-media">
                            <div class="facebook">
                               <a href="https://www.facebook.com/logon.com.pk"> <i class="fa-brands fa-facebook-f" title="facebook"> </i></a>
                            </div>
                            <div class="twitter">
                                <a href="https://twitter.com/logon_broadband"><i class="fa-brands fa-twitter" title="twitter"></i> </a>   
                            </div>
                            <div class="google" style="display:none">
                                <i class="fa-brands fa-google-plus-g" title="googleplus"></i> 
                            </div>
                            <div class="linked">
								<a href="https://www.linkedin.com/company/logon-broadband-pvt-ltd/"><i class="fa-brands fa-linkedin-in" title="linkedin"></i></a>
                            </div>
                        </div>
                        <div class="copyright">
                            Copyright © 2020 All Rights Reserved by Logon Broadband (pvt) Ltd
                        </div>
                    </div>
                </div>
             </footer>
         <!-- card end  -->
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
<!-- <script> -->

<!-- 
// $(function() {
//   $('.chart').easyPieChart({
//     size: 160,
//     barColor: "red",
//     scaleLength: 0,
//     lineWidth: 15,
//     trackColor: "red",
//     lineCap: "circle",
//     animate: 2000,
//   });
// }); -->
<script>
        window.onload = function () {
            
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            
            title:{
                text:""
            },
            axisX:{
                interval: 0
            },
            axisY2:{
                interlacedColor: "rgba(2,44,101,.2)",
                gridColor: "rgba(1,77,101,.1)",
                title: "No. of Users"
            },
            data: [{
                type: "bar",
                name: "companies",
                axisYType: "secondary",
                color: "#225094",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();
        
        }
        </script>
    <!-- </script>  -->
	
	<script>
   $(document).ready(function(){
       $('#loading-image2').show();
       $.ajax({
         method: 'get',
         url: "{{route('getErrorLog')}}",
         dataType: 'json',
         success: function(res){
           $('#errorLog').text(res)
         },
         complete: function(){
         $('#loading-image2').hide();
         }   
       })
   })
</script>

<script>
   $(document).ready(function(){
	   
       $('#loading-image1').show();
       $.ajax({
         method: 'get',
         url: "{{route('getDisabledUser')}}",
         dataType: 'json',
         success: function(res){
           
            $('#disableData').text(res)
         },
         complete: function(){
         $('#loading-image1').hide();
         } 
       })
   })
</script>
<script>
function timeSince(date) {

  var seconds = Math.floor((new Date() - date) / 1000);

  var interval = seconds / 31536000;

  if (interval > 1) {
    return Math.floor(interval) + " years";
  }
  interval = seconds / 2592000;
  if (interval > 1) {
    return Math.floor(interval) + " months";
  }
  interval = seconds / 86400;
  if (interval > 1) {
    return Math.floor(interval) + " days";
  }
  interval = seconds / 3600;
  if (interval > 1) {
    return Math.floor(interval) + " hours";
  }
  interval = seconds / 60;
  if (interval > 1) {
    return Math.floor(interval) + " minutes";
  }
  return Math.floor(seconds) + " seconds";
}
   $(document).ready(function (){
      showLiveUser();
      function showLiveUser(){
      $.ajax({
         type: "POST",
         url: "{{route('users.liveusers')}}",
         dataType: "json",
		 headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         success: function(data){
			 //alert(data);
            var markup ='';
        $.each(data, function(index, value) {
		   // mins ago
		   
			// var aDay = 24*60*60*1000;
			// var aDay = value.acctstarttime;

            //mins ago 		   
		
           markup += "<tr><td class='text-primary'><b>"+value.username+"</b></td><td style='color: #e875b9;'>"+value.acctstarttime+"</td><td>"+value.framedipaddress+"</td></tr>";
        });
        $(".liveUsers").html(markup);
         }
      });
      }
      setInterval(function(){showLiveUser();}, 10000);
   });
</script>

@endsection
<!-- 
   .bord{
    border-bottom: 3px solid black;
}
.nav-section{
    display: flex;
    align-items: center;
    height: 300px;
}
.hello{
width: 70%;
}
.parttwo{
    width: 30%;
    display: flex;
    flex-direction: column;
    align-items: center;
   
}
nav{
    background-color: rgb(194, 205, 215 ,1);
    color: black;
    margin: 0;
   
    height: 300px;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
}
nav h4{
    font-size: 16px;
    font-weight: 300;
    margin: 0;
}
nav h5{
    font-size: 16px;
    font-weight: 300;
}
nav h2{
    font-size: 50px;
    margin: 0;
    font-weight: 600;
}
nav h2 b{
    color: rgb(0, 45, 109,1);
}
.head-section{
    display: flex;
    height: 60px;
    justify-content: space-between;
    align-items: center;
}
.partone{
    display: inline-flex;
   
}

.partone i{
    color: white;
    cursor: pointer;
    padding: 0px 10px;
}
.parttwo .amount h6{
    margin: 10px 0px;
    font-size: 20px;
}
.parttwo .amount{
     width: 150px;
    height: 80px; 
    font-family: 'Open Sans', Arial, Helvetica, sans-serif;
    font-weight: 500;
   color:rgb(194, 205, 215 ,1) ; 
    background-color:  rgb(0, 45, 109,1);
    border-radius: 20px;
    box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
 -->
 
 
