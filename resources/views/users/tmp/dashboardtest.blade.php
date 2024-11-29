@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- Searchbar CSS -->
<style>
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
     background-color: transparent;
     /* border-radius:45px; */
     position: relative;
     width:180px;
     -webkit-transition: all 0.3s ease-in-out;
     -moz-transition: all 0.3s ease-in-out;
     transition: all 0.3s ease-in-out;
 }
 .search-wrapper.active .input-holder {
     /* border-radius: 50px; */
     width:650px;
     height: 45px;
     background-color: transparent;
     border-bottom: 1px solid #000;

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
     width:180px;
     height:70px;
     border:none;
     border-radius:6px;
     background-color: transparent;
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
 .search-wrapper .input-holder .search-icon #adv_search{
  display:inline-block;
  color: #000;
  font-size: 18px;
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
.search-wrapper.active .input-holder .search-icon #adv_search{
	display:none
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
 opacity: 0;
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
 opacity:.8;
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
 top:50px;
 left:0px;
 text-align: center;
 font-family: "Open Sans", Arial, Verdana;
 font-size: 14px;
 display:none;
 color:#B7B7B7;
 background-color: #adadad63; border-radius: 3px;height: fit-content;max-height: 225px;
 backdrop-filter: blur(10px);
}
.scrolled {
 height:150px;
 overflow-y: scroll;
}
.scrolled::-webkit-scrollbar {
 display: none;
}
/* Card Design */

.c-dashboardInfo .wrap {
  background: #ffffff;
  box-shadow: 2px 10px 20px rgba(0, 0, 0, 0.1);
  border-radius: 7px;
  text-align: center;
  position: relative;
  overflow: hidden;
  padding: 20px 5px;
  height: 100%;
}
.c-dashboardInfo__title {
  color: #6c6c6c;
  font-size: 1.18em;
}
.c-dashboardInfo span {
  display: block;
}
.c-dashboardInfo__count {
  font-weight: 600;
  font-size: 2.3em;
  line-height: 30px;
  color: #323c43;
}
.c-dashboardInfo .wrap:after {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 10px;
  content: "";
}

.c-dashboardInfo:nth-child(1) .wrap:after, 
.c-dashboardInfo:nth-child(5) .wrap:after {
  background: linear-gradient(82.59deg, #00c48c 0%, #00a173 100%);
}
.c-dashboardInfo:nth-child(2) .wrap:after, 
.c-dashboardInfo:nth-child(6) .wrap:after{
  background: linear-gradient(81.67deg, #0084f4 0%, #1a4da2 100%);
}
.c-dashboardInfo:nth-child(3) .wrap:after, 
.c-dashboardInfo:nth-child(7) .wrap:after {
  background: linear-gradient(69.83deg, #0084f4 0%, #00c48c 100%);
}
.c-dashboardInfo:nth-child(4) .wrap:after,
.c-dashboardInfo:nth-child(8) .wrap:after {
  background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
}
.grid-container{
    display: grid;
    place-content: center;
    grid-template-columns: repeat(8, 1fr);
    gap: 10px;
    margin-top: 5px;
}
@media (max-width:1300px) {
    .grid-container{
        grid-template-columns: repeat(4, 1fr);
    }
}
@media (max-width:992px) {
    .grid-container{
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width:576px) {
    .grid-container{
        grid-template-columns: repeat(1, 1fr);
    }
}
/* Card design end */

.custom-dropdown{
    position: fixed;
    z-index: 1999;
    background: rgb(203 200 200 / 62%);
    backdrop-filter: blur(10px);
    width: 500px;
    /* display:none; */
    right: 0;
    top: 0;
    width: 450px;
    overflow-y: auto;
    height:100%;
    transform: translateX(500px);
    transition: .5s ease-in-out;
    opacity: 0;
}
.custom-dropdown.visible{
    /* display:block; */
    transform: translateX(0px);
    transition: .5s ease-in-out;
    opacity: 1;

}

.custom-dropdown td{
    padding: 5px 0;
    font-weight: bold;
}
.custom-dropdown .close_btn{
    position: absolute;
    left: -8px;
    top: 0;
    background: transparent;
    font-size: 40px;
    color: #fff;
}
</style>
@endsection
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-container row-fluid container-fluid">

    <!-- right sidebar online-users -->
    <div class="custom-dropdown">
        <button class="btn close_btn" onclick="dropdownFunction();">&times;</button>
        <table class="table">
            <thead class="text-center">
                <tr class="text-center">
                    <th class="text-center">Username</th>
                    <th class="text-center">Login Date & Time</th>
                    <th class="text-center">IP Address</th>
                </tr>
            </thead>
            <tbody class="liveUsers">
            </tbody>
        </table>
    </div>

    <div class="page-container row-fluid container-fluid">
        <section id="main-content" class="">






        </section>
    </div>

    <div style="float:right;" id="chartContainer">
   <!--  <h1>Total Consumer <span id="dashboardConsumerCount">loading...</span></h1>
    <h1>Active Consumer <span id="dashboardActiveConsumerCount">loading...</span></h1>
    <h1>Online Consumer <span id="dashboardOnlineConsumerCount">loading...</span></h1>
    <h1>Online Percentage <span id="dashboardOnlinePercentage">loading...</span></h1>
    <h1>Upcoming Expiry <span id="dashboardUpComingExpiry">loading...</span></h1>
    <h1>Mobile Verified <span id="dashboardVerifiedMobile">loading...</span></h1>
    <h1>Mobile CNIC <span id="dashboardVerifiedCNIC">loading...</span></h1>
    <h1>Invalid Login <span id="dashboardInvalidLogins">loading...</span></h1>
    <h1>Reseller <span id="dashboardResellerCount">loading...</span></h1>
    <h1>Contractor <span id="dashboardContractorCount">loading...</span></h1>
    <h1>Trader <span id="dashboardTraderCount">loading...</span></h1>
    <h1>Sub Trader <span id="dashboardSubTraderCount">loading...</span></h1>
    <h1>Disabled <span id="dashboardDisabledConsumer">loading...</span></h1>
    <h1>Offline <span id="dashboardOfflineConsumer">loading...</span></h1>
    <h1>Expired <span id="dashboardExpiredConsumer">loading...</span></h1>
    <h1>Suspicious <span id="dashboardSuspiciousConsumer">loading...</span></h1> -->
</div>
<script>


     // $('#dealer-dropdown').on('change', function () {
        // $(document).ready(function(){
        //         //
        //     var dealer_id = this.value;
        //     $("#trader-dropdown").html('');
        //     $.ajax({
        //         url: "{{route('admin.trader')}}",
        //         type: "POST",
        //         data: {
        //             dealer_id: dealer_id,
        //             _token: '{{csrf_token()}}'
        //         },
        //         dataType: 'json',
        //         success: function (result) {
        //             $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
        //             $.each(result.subdealer, function (key, value) {
        //                 $("#trader-dropdown").append('<option value="' + value
        //                     .sub_dealer_id + '">' + value.sub_dealer_id + '</option>');
        //             });
        //         }
        //     });
        // });



</script>


<script>
    $(document).ready(function(){
     ////////////////////////////////////////////////////////////////////////
        $.ajax({
            method: 'POST',
            dataType : 'json',
            url: "{{route('users.dashboardData.profile_wise_user_count_graph')}}",
            data: {
                _token: '{{csrf_token()}}'
            },

            success: function(res){
                //
                var dataArray = [];
                //
                $.each(res.profile, function( index, value ) {
                    dataArray[index] = { label: value, y: res.count[index] };
                });
                //
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
                        indexLabel: "{y}", //Shows y value on all Data Points
                        yValueFormatString: "#,##0#",
                        dataPoints: dataArray

                    }]

                });
                chart.render();

            },
            complete: function(){

            }   
        });

    });
</script>
@endsection