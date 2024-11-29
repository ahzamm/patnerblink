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
    .welcome__note{
        margin-top: 75px;
        margin-bottom: 50px;
    }
    .custom_tab_wrapper .custom_tab{
        padding: 5px 15px 20px;
        margin-right: 0;
    }
    .custom_tab{
        text-align: center;
    }
    .custom_tab i{
        font-size: 34px !important;
        padding: 10px 0;
        margin-top: 20px;
    }
    .custom_tab_wrapper>ul>li.active>a::before {
        content: '';
        position: absolute;
        border-top: 17px solid #eeeeee;
        border-left: 20px solid #eeeeee;
        left: 50%;
        top: -10px;
        width: 20px;
        height: 20px;
        z-index: 99999;
        display: block;
        transform: translateX(-50%) rotate(321deg);
    }
    /* card */
    .card {
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 10%), 0 2px 4px -1px rgb(0 0 0 / 6%);
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border-radius: 0.75rem;
    }
    .bg-transparent {
        background-color: transparent !important;
    }
    .z-index-2 {
        z-index: 2 !important;
    }
    .card-header {
        padding: 0.5rem 1rem;
        margin-bottom: 0;
        background-color: #fff;
    }
    .card-body {
        flex: 1 1 auto;
        padding: 1rem 1rem;
    }
    .card .card-body {
        font-family: "Roboto", Helvetica, Arial, sans-serif;
        padding: 1rem 1.5rem;
    }
    .bg-gradient-primary {
        background-image: linear-gradient(195deg, #EC407A 0%, #D81B60 100%);
    }
    .bg-gradient-success {
        background-image: linear-gradient(195deg, #66BB6A 0%, #43A047 100%);
    }
    .bg-gradient-dark {
        background-image: linear-gradient(195deg, #42424a 0%, #191919 100%);
    }
    .bg-gradient-blue {
        background-image: linear-gradient(195deg, #5a40ec 0%, #D81B60 100%)
    }
    .shadow-primary {
        box-shadow: 0 4px 20px 0 rgb(0 0 0 / 14%), 0 7px 10px -5px rgb(233 30 99 / 40%) !important;
    }
    .shadow-success {
        box-shadow: 0 4px 20px 0 rgb(0 0 0 / 14%), 0 7px 10px -5px rgb(76 175 80 / 40%) !important;
    }
    .shadow-dark {
        box-shadow: 0 4px 20px 0 rgb(0 0 0 / 14%), 0 7px 10px -5px rgb(64 64 64 / 40%) !important;
    }
    hr.horizontal {
        background-color: transparent;
        margin-top: 20px;
    }
    hr.horizontal.dark {
        background-image: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.4), transparent);
    }
    .user_info_table tr{
        background-color: #fff;
        text-align: left;
    }
    .user_info_table tr, 
    .user_info_table th,
    .user_info_table td {
        text-align: left;
    }
    .user_info_table tr th,
    .user_info_table tr td{
        padding-left: 20px !important;
    }
    .user_info_table tr:hover{
        box-shadow: 0 0 5px 1px rgba(0 0 0 / 40%);
        transition: .2s ease;
    }
    .nav-section{
        background-image: linear-gradient(195deg, #5a40ec 0%, #D81B60 100%);
        padding: 10px 20px;
        border-radius: 10px
    }
    .nav-section h2,
    .nav-section h5 {
        color: #fff;
    }
    button[aria-expanded="false"] .hello i{
        transform: rotate(-180deg);
    }
    .circle-text, .circle-info, .circle-text-half, .circle-info-half {
        /* width: 100%; */
        left: 50%;
        position: absolute;
        text-align: center;
        display: inline-block;
        transform: translateX(-50%);
    }
    .circle-text {
        line-height: 78px!important;
        font-size: 31px!important;
        font-weight: 500;
        letter-spacing: unset;
        color: #0d4dab;
    }
    .knob{
        font-size: 30px !important;
        font-weight: normal !important;
        margin-top: 22px !important;
    }
    input{display: none}
    .knob__input{
        width: 100px;
        height: 100px;
        position: relative;
    }
    .social_services{
        display: flex;
        align-items:center;
        justify-content: center;
        column-gap: 20px
    }
</style>
@endsection
@section('content')
<?php
$fullname = ucfirst(Auth::user()->firstname).' '.ucfirst(Auth::user()->lastname);
$info = DB::table('user_info')->where('username',Auth::user()->username)->first(); 
$chargeInfo = DB::table('user_status_info')->where('username',Auth::user()->username)->first(); 
//
$totalDownload = App\model\Users\RadAcct::where(['username' => Auth::user()->username])->sum('acctoutputoctets');
$tDownMonth= App\model\Users\RadAcct::where('username', Auth::user()->username)->where('acctstarttime','>=',DATE('Y-m-01'))->where('acctstarttime','<=',DATE('Y-m-t'))->sum('acctoutputoctets');
$totalupload = App\model\Users\RadAcct::where(['username' => Auth::user()->username])->sum('acctinputoctets');
$tupMonth= App\model\Users\RadAcct::where('username', Auth::user()->username)->where('acctstarttime','>=',DATE('Y-m-01'))->where('acctstarttime','<=',DATE('Y-m-t'))->sum('acctinputoctets');
?>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-container row-fluid container-fluid">
    <!-- CONTENT START -->
    <section id="main-content" class="">
        <section class="wrapper main-wrapper" style='margin:0; padding:0;'>
            <section class="welcome__note">
                <div class="container">
                    <div class="nav-section">
                        <div class="accordion" id="accordionExample">
                            <div class="">
                                <div class="" id="headingOne">
                                    <h2 class="mb-0" style="position: relative">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;text-align: left;height: 100px;">
                                            <div class="hello">
                                                <h2>Hello,<b> <?= $fullname;?></b></h2>
                                                <h5><?= Auth::user()->email;?> , <?= Auth::user()->address;?> </h5><span style="position: absolute;right: 15px;top: 50%;background: #fff;padding: 12px 15px;border-radius: 20px;transform: translateY(-50%);">
                                                    <i class="fa fa-chevron-up"></i></span>
                                                </div>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseOne" class="collapse in" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @include('users.billing.admin_users_graph')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Card Section -->
                <div class="row" style="margin: 0">
                    <div class="col-md-4">
                        <div class="card z-index-2 ">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent" style="padding: 0; margin-right: 1rem; margin-left: 1rem; margin-top: -2rem; position: relative;">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1" style="border-radius: 0.5rem; padding-right: 0.25rem; padding-top: 1rem; padding-bottom: 1rem">
                                    <div class="chart">
                                        <canvas id="chart-bars" class="chart-canvas" height="170" width="488" style="display: block; box-sizing: border-box; height: 170px; width: 488.7px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-0 " style="margin-bottom: 0; font-size: 1rem; line-height: 1.625; ">Total Download (Monthly)</h6>
                                <p class="text-sm " style="font-size: 1.5rem; font-weight: bold;margin-top: 10px;color: #000"><?= number_format($tDownMonth/1048576);?> MBs</p>
                                <hr class="dark horizontal">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card z-index-2 ">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent" style="padding: 0; margin-right: 1rem; margin-left: 1rem; margin-top: -2rem; position: relative;">
                                <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1" style="border-radius: 0.5rem; padding-right: 0.25rem; padding-top: 1rem; padding-bottom: 1rem">
                                    <div class="chart">
                                        <canvas id="chart-line" class="chart-canvas" height="170" width="488" style="display: block; box-sizing: border-box; height: 170px; width: 488.7px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-0 " style="margin-bottom: 0; font-size: 1rem; line-height: 1.625; ">Total Upload (Monthly)</h6>
                                <p class="text-sm " style="font-size: 1.5rem; font-weight: bold;margin-top: 10px;color: #000"><?= number_format($tupMonth/1048576);?> MBs</p>
                                <hr class="dark horizontal">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card z-index-2 ">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent" style="padding: 0; margin-right: 1rem; margin-left: 1rem; margin-top: -2rem; position: relative;">
                                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1" style="border-radius: 0.5rem; padding-right: 0.25rem; padding-top: 1rem; padding-bottom: 1rem">
                                    <div class="chart">
                                        <canvas id="chart-line-tasks" class="chart-canvas" height="170" width="488" style="display: block; box-sizing: border-box; height: 170px; width: 488.7px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-0 " style="margin-bottom: 0; font-size: 1rem; line-height: 1.625; ">Monthly Internet Data Usage</h6>
                                <p class="text-sm " style="font-size: 1.5rem; font-weight: bold;margin-top: 10px;color: #000"><?= number_format(($tupMonth + $tDownMonth)/1048576);?> MBs</p>
                                <hr class="dark horizontal">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Tab Section -->
                <div class="row" style="margin: 0">
                    <div class="col-xs-12">
                        <div class="uprofile-content row">
                            <div class="col-lg-12">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="custom_tab_wrapper">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#home" class="custom_tab"><i class="fa fa-user"></i> <br>Consumer Details</a></li>
                                                <li><a data-toggle="tab" href="#menu2" class="custom_tab"><i class="fa fa-download"></i> <br>Internet Package</a></li>
                                                <li><a data-toggle="tab" href="#menu4" class="custom_tab"><i class="fa fa-clock"></i> <br>Package Expiry</a></li>
                                                <li><a data-toggle="tab" href="#menu3" class="custom_tab"><i class="fas fa-user"></i> <br>Your Contractor Detail</a></li>
                                                <li><a data-toggle="tab" href="#cnic" class="custom_tab"><i class="fas fa-id-card"></i> <br>CNIC (Picture)</a></li>
                                                <li><a data-toggle="tab" href="#menu5" class="custom_tab"><i class="fas fa-file"></i> <br>Your Billing History</a></li>
                                            </ul>
                                            <div class="tab-content" style="background-color: transparent">
                                                <div id="home" class="tab-pane fade in active">
                                                    <div class="user__detail" style="margin-top: 20px">
                                                        <div class="row" style="margin: 0">
                                                            <div class="col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <p>Consumer ID</p>
                                                                        <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;"><?= Auth::user()->username;?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <p>Full Name</p>
                                                                        <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;"><?= $fullname;?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <p>Mobile Number</p>
                                                                        <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;"><?= Auth::user()->mobilephone;?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <p>Address</p>
                                                                        <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;"><?= Auth::user()->address;?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $pkgDetail = App\model\Users\Profile::where('name',$info->name)->first();
                                                if($pkgDetail){
                                                    $bandwidth = $pkgDetail->bw_show/1024;
//
                                                    if($pkgDetail->profile_type == 'cdn'){
                                                        $intpkg = $pkgDetail->soc_internet;
                                                        $facebook = $pkgDetail->soc_facebook;
                                                        $youtube = $pkgDetail->soc_youtube;
                                                        $netflix = $pkgDetail->soc_netflix;
                                                    }else{
                                                        $intpkg = 0;
                                                        $facebook = 0;
                                                        $youtube = 0;
                                                        $netflix = 0;
                                                    }
                                                    ?>
                                                    <div id="menu2" class="tab-pane fade ">
                                                        <div class="row">
                                                            <div class="" style="display:flex;align-items:center;justify-content:center" >
                                                                <div class="card">
                                                                    <div class="card-body" style="padding:.5rem">
                                                                        <div>
                                                                            <h2 style="text-align: center; font-weight: bold;color: #0d4dab"><?= $info->name;?></h2>
                                                                        </div>
                                                                        <hr style="margin-top: 12px;">
                                                                        <p style="text-align: center; font-weight: bold; margin-bottom: 20px;font-size: 18px;">Unlimited <span style="font-size: 28px; color: #0d4dab"><?= $bandwidth;?></span> Mbps</p>
                                                                        <div class="social_services">
                                                                            <div class="internet_bandwidth">
                                                                                <p style="margin-bottom: 0; text-align: center; color: #5491e9" title="Bandwidth"><i class="fa fa-cloud"></i></p>
                                                                                <?php if($pkgDetail->profile_type == 'cdn'){?>
                                                                                    <p><?= $intpkg;?> Mb</p>
                                                                                <?php } ?>
                                                                            </div>
                                                                            <p style="font-weight: bold; font-size: 20px">+</p>
                                                                            <div class="internet_bandwidth">
                                                                                <p style="margin-bottom: 0; text-align: center; color: #185cc1" title="Facebook"><i class="fab fa-facebook"></i></p>
                                                                                <?php if($pkgDetail->profile_type == 'cdn'){?>
                                                                                    <p><?= $facebook;?> Mb</p>
                                                                                <?php } ?>
                                                                            </div>
                                                                            <p style="font-weight: bold; font-size: 20px">+</p>
                                                                            <div class="internet_bandwidth">
                                                                                <p style="margin-bottom: 0; text-align: center; color: #ab0d0d" title="Youtube"><i class="fab fa-youtube"></i></p>
                                                                                <?php if($pkgDetail->profile_type == 'cdn'){?>
                                                                                    <p><?= $youtube;?> Mb</p>
                                                                                <?php } ?>
                                                                            </div>
                                                                            <p style="font-weight: bold; font-size: 20px">+</p>
                                                                            <div class="internet_bandwidth">
                                                                                <p style="margin-bottom: 0; text-align: center; color: #0d4dab" title="Netflix"><img src="https://partner.logon.com.pk/images/netflix.png" style="height: 15px;filter: hue-rotate(121deg);"></p>
                                                                                <?php if($pkgDetail->profile_type == 'cdn'){?>
                                                                                    <p><?= $netflix;?> Mb</p>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                        <hr style="background-color: #ededed;height: 2px;margin-top: 12px;width: 65%;">
                                                                        <div style="display: flex;align-items: center; justify-content: center; column-gap: 30px;">
                                                                            <div class="knob-circle">
                                                                                <div class="knob__input">
                                                                                    <input type="text" class="knob" value="0" data-rel="<?= $bandwidth;?>" data-linecap="round"data-width="100" data-height="100" data-bgcolor="#e8e8eb" data-fgcolor="#0d4dab" data-thickness=".15" data-readonly="true"disabled>
                                                                                    <span class="circle-text" style="line-height: 100px; font-size: 25px;"><?= $bandwidth;?></span>
                                                                                    <span class="circle-info" style="top: 50px">Mbps</span>
                                                                                </div>
                                                                            </div>
                                                                            <div style="display: inline-block">
                                                                                <p>Bandwidth: <?= $bandwidth;?> Mbps</p>
                                                                                <p style="color: #000;">Download: <span style="color: #2b582b">Unlimited</span></p>
                                                                                <p><a href="https://speedtest.net" target="_blank" class="btn btn-primary">Check Speed Now</a></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div id="menu4" class="tab-pane fade">
                                                    <div class="row" style="margin-top: 20px">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <p>Package Charge On</p>
                                                                    <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;">{{date('M d,Y',strtotime($user_status__data->card_charge_on))}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <p>Package Expire On</p>
                                                                    <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;">{{date('M d,Y',strtotime($user_status__data->card_expire_on))}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="menu3" class="tab-pane fade in ">
                                                    <div class="row" style="margin-top: 20px">
                                                        <div class="col-md-3">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <p>Contractor Name</p>
                                                                    <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;">{{$get_parent_data->firstname}} {{$get_parent_data->lastname}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <p>Contractor Number</p>
                                                                    <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;">{{$get_parent_data->mobilephone}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <p>Contractor Address</p>
                                                                    <p style="text-align: center;font-weight: bold;color: #000;font-size: 20px;">{{$get_parent_data->address}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="menu5" class="tab-pane fade in ">
                                                    <div class="row" style="margin-top: 20px">
                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <table id="example-1" class="table table-striped  display">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="th-color">Serial#</th>
                                                                                <th class="th-color">Billing Month</th>
                                                                                <th class="th-color">Billing Date</th>
                                                                                <th class="th-color">Action</th>              
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach($invoice as $key => $invVal){
                                                                                $key = $key+1;
                                                                                $get_url = url('users/bill/view/'.$invVal->username.'/'.$invVal->date);
                                                                                $diff = (strtotime($invVal->date) - strtotime(date('2023-06-20')));
                                                                                $diff = (round($diff / 86400));
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?= $key;?></td>
                                                                                    <td><?= date('M,Y',strtotime($invVal->date));?></td>
                                                                                    <td><?= date('M d,Y',strtotime($invVal->date));?></td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <?php if($diff > 0){?>
                                                                                                <a href="<?=  $get_url;?>" target="_blank" >
                                                                                                    <button class="btn btn-default btn-xs" style="color:red;border:none"><i class="fa fa-file-pdf-o"> </i> Invoice</button>
                                                                                                </a>
                                                                                            <?php }else { echo 'Not Available';} ?>
                                                                                        </center>
                                                                                    </td>                
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="cnic" class="tab-pane fade in ">
                                                    <div class="" style="display:flex; align-items:center; justify-content:center;column-gap:20px">
                                                        <div class="card" style="width: 350px;height:250px;">
                                                            <div class="card-body">
                                                                <p>CNIC (Front Image)</p>
                                                                <div style="width:100%; height:180px;">
                                                                    <?php if(file_exists(public_path().'/UploadedNic/'.Auth::user()->username.'-front.jpg')){ ?>
                                                                        <a href="{{asset('UploadedNic/'.Auth::user()->username.'-front.jpg')}}" target="_blank">
                                                                            <img src="{{asset('UploadedNic/'.Auth::user()->username.'-front.jpg')}}" style="width: 100%;height:100%" />
                                                                        </a>
                                                                    <?php }else{ echo 'Image not available';}?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card" style="width: 350px;height:250px;">
                                                            <div class="card-body">
                                                                <p>CNIC (Back Image)</p>
                                                                <div style="width:100%; height:180px;">
                                                                    <?php if(file_exists(public_path().'/UploadedNic/'.Auth::user()->username.'-back.jpg')){ ?>
                                                                        <a href="{{asset('UploadedNic/'.Auth::user()->username.'-back.jpg')}}" target="_blank">
                                                                            <img src="{{asset('UploadedNic/'.Auth::user()->username.'-back.jpg')}}" style="width: 100%;height:100%" />
                                                                        </a>
                                                                    <?php }else{ echo 'Image not available';}?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </section>
                        </div>
                        <script src="{{asset('js/chartjs.min.js')}}" ></script>
                        <script>
                            var ctx = document.getElementById("chart-bars").getContext("2d");
                            new Chart(ctx, {
                                type: "bar",
                                data: {
                                    labels: ["M", "T", "W", "T", "F", "S", "S"],
                                    datasets: [{
                                        label: "Sales",
                                        tension: 0.4,
                                        borderWidth: 0,
                                        borderRadius: 4,
                                        borderSkipped: false,
                                        backgroundColor: "rgba(255, 255, 255, .8)",
                                        data: [50, 20, 10, 22, 50, 10, 40],
                                        maxBarThickness: 6
                                    }, ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false,
                                        }
                                    },
                                    interaction: {
                                        intersect: false,
                                        mode: 'index',
                                    },
                                    scales: {
                                        y: {
                                            grid: {
                                                drawBorder: false,
                                                display: true,
                                                drawOnChartArea: true,
                                                drawTicks: false,
                                                borderDash: [5, 5],
                                                color: 'rgba(255, 255, 255, .2)'
                                            },
                                            ticks: {
                                                suggestedMin: 0,
                                                suggestedMax: 500,
                                                beginAtZero: true,
                                                padding: 10,
                                                font: {
                                                    size: 14,
                                                    weight: 300,
                                                    family: "Roboto",
                                                    style: 'normal',
                                                    lineHeight: 2
                                                },
                                                color: "#fff"
                                            },
                                        },
                                        x: {
                                            grid: {
                                                drawBorder: false,
                                                display: true,
                                                drawOnChartArea: true,
                                                drawTicks: false,
                                                borderDash: [5, 5],
                                                color: 'rgba(255, 255, 255, .2)'
                                            },
                                            ticks: {
                                                display: true,
                                                color: '#f8f9fa',
                                                padding: 10,
                                                font: {
                                                    size: 14,
                                                    weight: 300,
                                                    family: "Roboto",
                                                    style: 'normal',
                                                    lineHeight: 2
                                                },
                                            }
                                        },
                                    },
                                },
                            });
var ctx2 = document.getElementById("chart-line").getContext("2d");
new Chart(ctx2, {
    type: "line",
    data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0,
            borderWidth: 0,
            pointRadius: 5,
            pointBackgroundColor: "rgba(255, 255, 255, .8)",
            pointBorderColor: "transparent",
            borderColor: "rgba(255, 255, 255, .8)",
            borderColor: "rgba(255, 255, 255, .8)",
            borderWidth: 4,
            backgroundColor: "transparent",
            fill: true,
            data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
            maxBarThickness: 6
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            }
        },
        interaction: {
            intersect: false,
            mode: 'index',
        },
        scales: {
            y: {
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5],
                    color: 'rgba(255, 255, 255, .2)'
                },
                ticks: {
                    display: true,
                    color: '#f8f9fa',
                    padding: 10,
                    font: {
                        size: 14,
                        weight: 300,
                        family: "Roboto",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
            x: {
                grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    color: '#f8f9fa',
                    padding: 10,
                    font: {
                        size: 14,
                        weight: 300,
                        family: "Roboto",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
        },
    },
});
var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");
new Chart(ctx3, {
    type: "line",
    data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0,
            borderWidth: 0,
            pointRadius: 5,
            pointBackgroundColor: "rgba(255, 255, 255, .8)",
            pointBorderColor: "transparent",
            borderColor: "rgba(255, 255, 255, .8)",
            borderWidth: 4,
            backgroundColor: "transparent",
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            }
        },
        interaction: {
            intersect: false,
            mode: 'index',
        },
        scales: {
            y: {
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5],
                    color: 'rgba(255, 255, 255, .2)'
                },
                ticks: {
                    display: true,
                    padding: 10,
                    color: '#f8f9fa',
                    font: {
                        size: 14,
                        weight: 300,
                        family: "Roboto",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
            x: {
                grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    color: '#f8f9fa',
                    padding: 10,
                    font: {
                        size: 14,
                        weight: 300,
                        family: "Roboto",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
        },
    },
});
var ctx4 = document.getElementById("chart-bars-internet").getContext("2d");
new Chart(ctx4, {
    type: "bar",
    data: {
        labels: ["M", "T", "W", "T", "F", "S", "S"],
        datasets: [{
            label: "Sales",
            tension: 0.4,
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            backgroundColor: "rgba(255, 255, 255, .8)",
            data: [50, 20, 10, 22, 50, 10, 40],
            maxBarThickness: 6
        }, ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            }
        },
        interaction: {
            intersect: false,
            mode: 'index',
        },
        scales: {
            y: {
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5],
                    color: 'rgba(255, 255, 255, .2)'
                },
                ticks: {
                    suggestedMin: 0,
                    suggestedMax: 500,
                    beginAtZero: true,
                    padding: 10,
                    font: {
                        size: 14,
                        weight: 300,
                        family: "Roboto",
                        style: 'normal',
                        lineHeight: 2
                    },
                    color: "#fff"
                },
            },
            x: {
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5],
                    color: 'rgba(255, 255, 255, .2)'
                },
                ticks: {
                    display: true,
                    color: '#f8f9fa',
                    padding: 10,
                    font: {
                        size: 14,
                        weight: 300,
                        family: "Roboto",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
        },
    },
});
</script>
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
<script src="https://raw.githubusercontent.com/aterrien/jQuery-Knob/master/dist/jquery.knob.min.js" ></script>
<script>
    $(function() {
        var knob = $(".knob").knob();
        $('.knob').each(function () {
            var $this = $(this),
            knobVal = $this.attr('data-rel');
            console.log(knobVal);
            $({animatedVal: 0}).animate({animatedVal: knobVal}, {
                duration: 2000,
                easing: "swing", 
                step: function() { 
                    $this.val(Math.ceil(this.animatedVal)).trigger("change"); 
                }
            });
        })
    });
</script>
@endsection
<!-- Code Finalize -->