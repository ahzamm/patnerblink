<!--
* This file is part of the SQUADCLOUD project.
*
* (c) SQUADCLOUD TEAM
*
* This file contains the configuration settings for the application.
* It includes database connection details, API keys, and other sensitive information.
*
* IMPORTANT: DO NOT MODIFY THIS FILE UNLESS YOU ARE AN AUTHORIZED DEVELOPER.
* Changes made to this file may cause unexpected behavior in the application.
*
* WARNING: DO NOT SHARE THIS FILE WITH ANYONE OR UPLOAD IT TO A PUBLIC REPOSITORY.
*
* Website: https://squadcloud.co
* Created: September, 2024
* Last Updated: 01th September, 2024
* Author: SquadCloud Team <info@squadcloud.co>
*-->
<!-- Code Onset -->
@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
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
        background-color: transparent;
        position: relative;
        width:180px;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
    .search-wrapper.active .input-holder {
        width:650px;
        height: 45px;
        background-color: transparent;
        border-bottom: 1px solid #000;
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
        -webkit-transition: all .4s cubic-bezier(0.650, -0.600, 0.240, 1.650);
        -moz-transition: all .4s cubic-bezier(0.650, -0.600, 0.240, 1.650);
        transition: all .4s cubic-bezier(0.650, -0.600, 0.240, 1.650);
    }
    .search-wrapper.active .input-holder .search-icon #adv_search{
        display:none
    }
    .search-wrapper.active .input-holder .search-icon span {
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
    }
    .search-wrapper .input-holder .search-icon span::after {
        width: 14px;
        height: 14px;
        left: 0px;
        top: 0px;
        border-radius: 16px;
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
    /* Card Design Start */
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
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-top: 5px;
    }
    .grid-container.three{
        grid-template-columns: repeat(3, 1fr);
    }
    .grid-container.two{
        grid-template-columns: repeat(2, 1fr);
    }
    .grid-container.one{
        grid-template-columns: repeat(1, 1fr);
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
    /* Card Design End */
    .custom-dropdown{
        position: fixed;
        z-index: 1999;
        background: rgb(203 200 200 / 62%);
        backdrop-filter: blur(10px);
        width: 500px;
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
    .dashboardLoader{
        font-size: 15px;
        font-weight: normal;
    }
</style>
@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-container row-fluid container-fluid">
    <!-- Right Sidebar Online Consumers -->
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
            <img src="/img/support-bg.png" alt="" style="position: absolute;left: 57%;bottom: 30px;transform: translateX(-50%);user-select: none;z-index: 0;opacity: .1;width:40%;">
            <section class="wrapper main-wrapper">
                <div class="dark" style="background-color: lightgray">
                    <div class="p-4 p-md-5" style="padding: 20px; position:relative">
                        <p style="color: black">We Are Changing The World With Technology...!</p>
                        <div class="row">
                            <div class="col-md-8">
                                <h1 class="display-4 l-s-n-1x"><span style="color: #fff">Hello, </span><span class="text-theme _700" style="color: #000">{{ucfirst($currentUser->username)}}</span></span></h1>
                                <div>
                                    <span style="color: black">{{$currentUser->email}} |  UAN : {{$domainDetails->bm_helpline_number}} </span>
                                </div>
                            </div>
                        </div>
                        <!-- Recently Online Consumer -->
                        <!-- Not functional -->
                        <?php if(App\MyFunctions::check_access('Online Consumers',Auth::user()->id)){ ?>
                            <div style="position:absolute;bottom:8px;right:6px;width:300px">
                                <button class="btn btn-primary w-100" data-toggle="dropdown" onclick="dropdownFunction();"><i class="fa fa-wifi"></i> Recently Online Consumers </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- Card Design Start -->
                <div class="grid-container">
                    <?php if(App\MyFunctions::check_access('Offline Consumers',Auth::user()->id)){ ?>
                        <div class="c-dashboardInfo">
                            <div class="wrap">
                                <h4 class="c-dashboardInfo__title">Offline Consumers</h4>
                                <span class="c-dashboardInfo__count" id="dashboardOfflineConsumer"><span class="dashboardLoader">loading...</span></span>
                            </div>
                        </div>
                    <?php } ?>
                    <?php  if(App\MyFunctions::check_access('Upcoming Expires',Auth::user()->id)){ ?>
                        <div class="c-dashboardInfo">
                            <div class="wrap">
                                <h4 class="c-dashboardInfo__title">Upcoming Expiry</h4>
                                <span class="c-dashboardInfo__count" id="dashboardUpComingExpiry"><span class="dashboardLoader">loading...</span></span>
                            </div>
                        </div>
                    <?php } if(App\MyFunctions::check_access('Suspicious Consumers',Auth::user()->id)){ ?>
                        <div class="c-dashboardInfo">
                            <div class="wrap">
                                <h4 class="c-dashboardInfo__title">Suspecious Consumers</h4>
                                <span class="c-dashboardInfo__count" id="dashboardSuspiciousConsumer"><span class="dashboardLoader">loading...</span></span>
                            </div>
                        </div>
                    <?php } if(App\MyFunctions::check_access('Expired Consumers',Auth::user()->id)){ ?>
                        <div class="c-dashboardInfo">
                            <div class="wrap">
                                <h4 class="c-dashboardInfo__title">Expired Consumers</h4>
                                <span class="c-dashboardInfo__count" id="dashboardExpiredConsumer"><span class="dashboardLoader">loading...</span></span>
                            </div>
                        </div>
                    <?php } if(App\MyFunctions::check_access('Invalid Login',Auth::user()->id)){ ?>
                        <div class="c-dashboardInfo">
                            <div class="wrap">
                                <h4 class="c-dashboardInfo__title">Invalid Login</h4>
                                <span class="c-dashboardInfo__count" id="dashboardInvalidLogins"><span class="dashboardLoader">loading...</span></span>
                            </div>
                        </div>
                    <?php }  ?>
                    {{--
                        <div class="c-dashboardInfo">
                            <div class="wrap">
                                <h4 class="c-dashboardInfo__title">CNIC Verified</h4>
                                <span class="c-dashboardInfo__count" id="dashboardVerifiedCNIC"><span class="dashboardLoader">loading...</span></span>
                            </div>
                        </div>
                        <div class="c-dashboardInfo">
                            <div class="wrap">
                                <h4 class="c-dashboardInfo__title">Mobile Verified</h4>
                                <span class="c-dashboardInfo__count" id="dashboardVerifiedMobile"><span class="dashboardLoader">loading...</span></span>
                            </div>
                        </div>
                        <div class="c-dashboardInfo">
                            <div class="wrap">
                                <h4 class="c-dashboardInfo__title">Disabled Consumers</h4>
                                <span class="c-dashboardInfo__count" id="dashboardDisabledConsumer"><span class="dashboardLoader">loading...</span></span>
                            </div>
                        </div>
                        --}}
                    </div>
                    <!-- Card Design End -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 70px;z-index: 99;">
                        <section class="container">
                            <form onkeyup="submitFn(this, event);">
                                <div class="search-wrapper">
                                    <div class="input-holder">
                                        <input type="text" class="search-input" placeholder="What are you looking for?" style="font-family: serif;font-size: 22px;margin-top: -11px"/>
                                        <button class="search-icon" onclick="searchToggle(this, event);"><span><i class="fa fa-search" style="color: black;font-size: 24px;"></i> </span> <span id="adv_search" style="white-space:nowrap;width:auto"> Search Engine</button>
                                        </div>
                                        <span class="close" onclick="searchToggle(this, event);"></span>
                                        <div class="result-container scrolled" id="resultbox" style="overflow-y:auto">
                                        </div>
                                    </div>
                                </form>
                            </section>
                        </div>
                        <!-- Result Container -->
                        <div class="container emp-profile" id="resultDIV" style="display: none;">
                            <div class="row" style="margin-top: 125px;margin-bottom:50px;position:relative">
                                <button style="position:absolute; right:15px;top:0;border: none;font-size: 24px;font-family: cursive;cursor: pointer;z-index:9;color: #e97676" onclick="$('#resultDIV').css('display','none')">X</button>
                                <div class="col-md-3">
                                    <div class="profile-img">
                                        <p style="text-align: center"><img src="{{asset('img/avatar/user_detail_avatar.png')}}" style="width: 150px;margin:auto" alt=""/></p>
                                    </div>
                                    <div class="uprofile-name">
                                        <h3 class="fulname"></h3>
                                        <p class="uprofile-title" id="status"></p>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="tab-content">
                                        <div id="home" class="tab-pane fade in active">
                                            <div style="overflow-x: auto;">
                                                <table class="table table-bordered user_detail_table">
                                                    <tbody>
                                                        <tr>
                                                            <th class="td__profileName" style="">Consumer (ID)</th>
                                                            <td><span class="" id="user__id" style="color: black;font-size:18px; font-weight:bold"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="td__profileName" style="">Internet Profile</th>
                                                            <td><span id="user___pkg" style="color: black;font-size:18px; font-weight:bold"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="td__profileName" style="">Current Profile</th>
                                                            <td><span id="user___currpkg" style="color: black;font-size:18px; font-weight:bold"></span></td>
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
                                                        <tr>
                                                            <th class="td__profileName">Password</th>
                                                            <td class="position-relative">
                                                                <input type="password" id="helpline_password" class="form-control" readonly disabled style="text-align:center">
                                                                <i class="fa fa-eye" style="position:absolute; right:20px; top: 18px; cursor:pointer"></i>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><span class="user"></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </section>
            </div>
            <script type="text/javascript">
                function dropdownFunction() {
                    $('.custom-dropdown').toggleClass('visible');
                }
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
                        container.find('.search-input').val('');
                        container.find('.result-container').fadeOut(100, function(){$(this).empty();});
                    }
                }
            </script>
            <script>
                function submitFn(obj, evt){
                    value = $(obj).find('.search-input').val().trim();
                    evt.preventDefault();
                    if(value.length){
                        $.ajax({
                            type: "POST",
                            url: "{{route('users.Supportsearch')}}",
                            data:'user='+value,
                            success: function(data){
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
            <script type="text/javascript">
                $('.fa-eye').click(function(){
                    var x = document.getElementById("helpline_password");
                    if (x.type == 'password') {   
                        x.type = "text";
                    } else {
                        x.type = "password";
                    }
                });
            </script>
            <script>
                function searchResult(username){
                    $.ajax({
                        type: "post",
                        url: "{{route('users.SupportsearchResult')}}",
                        data:'username='+username,
                        dataType: "json",
                        success: function(data){
                            var mob = data.mobile_status;
                            var nic = data.cnic;
                            var fnic = data.nic_front;
                            var bnic = data.nic_back;
                            $('#user__id').html(data.username);
                            $(".user").html('<a target="_blank" class="btn btn-primary w-100" href="/users/user/'+data.status+'?id='+data.id+'">More Info</a>');
                            $("#dealerids").html(data.dealerid);
                            if(data.status == 'user'){
                                $("#status").html('Consumer');
                            }
                            $("#expire").html(data.card_expire_on);
                            $("#helpline_password").val(data.radcheck.value);
                            $("#user___pkg").html(data.name);
                            $("#user___currpkg").html(data.radusergroup.name);
                            console.log(data.radusergroup.name);
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
                        }
                    });
                }
            </script>
            <script>
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
                                var markup ='';
                                $.each(data, function(index, value) {
                                    markup += "<tr><td class='text-primary'><b>"+value.username+"</b></td><td style='color: #e875b9;'>"+value.acctstarttime+"</td><td>"+value.framedipaddress+"</td></tr>";
                                });
                                $(".liveUsers").html(markup);
                            }
                        });
                    }
                    setInterval(function(){showLiveUser();}, 10000);
                });
            </script>
            <script>
                $(document).ready(function(){
////////////////////////////////////////////////////////////////////////
$.ajax({
    method: 'POST',
    url: "{{route('users.dashboardData.upcoming_expire_consumer')}}",
    data: {
        _token: '{{csrf_token()}}'
    },
    success: function(data){
        $('#dashboardUpComingExpiry').html(data);
    },
    complete: function(){
    }   
});
////////////////////////////////////////////////////////////////////////
$.ajax({
    method: 'POST',
    url: "{{route('users.dashboardData.verified_mobile')}}",
    data: {
        _token: '{{csrf_token()}}'
    },
    success: function(data){
        $('#dashboardVerifiedMobile').html(data);
    },
    complete: function(){
    }   
});
////////////////////////////////////////////////////////////////////////
$.ajax({
    method: 'POST',
    url: "{{route('users.dashboardData.verified_cnic')}}",
    data: {
        _token: '{{csrf_token()}}'
    },
    success: function(data){
        $('#dashboardVerifiedCNIC').html(data);
    },
    complete: function(){
    }   
});
////////////////////////////////////////////////////////////////////////
$.ajax({
    method: 'POST',
    url: "{{route('users.dashboardData.invalid_login')}}",
    data: {
        _token: '{{csrf_token()}}'
    },
    success: function(data){
        $('#dashboardInvalidLogins').html(data);
    },
    complete: function(){
    }   
});
////////////////////////////////////////////////////////////////////////
$.ajax({
    method: 'POST',
    url: "{{route('users.dashboardData.disabled_consumer')}}",
    data: {
        _token: '{{csrf_token()}}'
    },
    success: function(data){
        $('#dashboardDisabledConsumer').html(data);
    },
    complete: function(){
    }   
});
////////////////////////////////////////////////////////////////////////
$.ajax({
    method: 'POST',
    url: "{{route('users.dashboardData.offline_consumer')}}",
    data: {
        _token: '{{csrf_token()}}'
    },
    success: function(data){
        $('#dashboardOfflineConsumer').html(data);
    },
    complete: function(){
    }   
});
////////////////////////////////////////////////////////////////////////
$.ajax({
    method: 'POST',
    url: "{{route('users.dashboardData.expired_consumer')}}",
    data: {
        _token: '{{csrf_token()}}'
    },
    success: function(data){
        $('#dashboardExpiredConsumer').html(data);
    },
    complete: function(){
    }   
});
////////////////////////////////////////////////////////////////////////
$.ajax({
    method: 'POST',
    url: "{{route('users.dashboardData.suspicious_consumer')}}",
    data: {
        _token: '{{csrf_token()}}'
    },
    success: function(data){
        $('#dashboardSuspiciousConsumer').html(data);
    },
    complete: function(){
    }   
});
});
</script>
@endsection
<!-- Code Finalize -->