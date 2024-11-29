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
@extends('users.layouts.app',[
'profileCollection' => $profileCollection
])
@section('title') Dashboard @endsection
@section('owncss')
@endsection
@section('content')
<style>
    .verify_restricted{
        position:absolute; 
        top:22px;
        right: 0;
        margin-bottom: 0;
        text-align: center;
        width: 100%;
        color: red;
        font-weight: bold;
        animation: 1s ease blinkMe alternate infinite;
        white-space:nowrap;
        font-size: 14px;
    }
    .card__wrapper a{
        color: #000;
        text-decoration: none;
    }
    @keyframes blinkMe {
        0%{
            opacity: 0%
        }
        100%{
            opacity: 100%
        }
    }
    .notif_modal.right .modal-content {
        height: 100%;
        overflow-y: auto;
        border-radius: 0px;
        background-color: #ffffffbd;
        backdrop-filter: blur(10px);
    }
    .notif_modal.right.fade.in .modal-dialog {
        right: 0;
    }
    .notif_modal.right.fade .modal-dialog {
        right: -800px;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }
    .notif_modal.right .modal-dialog {
        position: fixed;
        margin: auto;
        width: 70%;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }
    .drawer_modal{
        position:absolute;
        right:40px;
        bottom:10px;
    } 
    .drawer_modal button{
        padding: 0;
        height: 40px;
        background: transparent;
        font-size: 18px;
        padding-right: 10px;
        border-radius: 7px;
        border: 2px solid #002d6d;
        color: #002d6d
    }
    .drawer_modal .btn__icon-left{
        background: #002d6d;
        color: #fff;
        padding: 8px 12px;
        border-top-left-radius: 7px;
        border-bottom-left-radius: 7px;
    }
    .drawer_modal:focus{
        color: #ddd;
        text-decoration: none;
    }
    @media (max-width: 768px) {
        .notif_modal.right .modal-dialog {
            width: 100%;
        }
        .amount{
            margin: auto;
        }
        .drawer_modal{
            right: 0;
            transform: rotate(270deg) translate(100px, 67px);
        }
    }
    #pwa_prompt{
        width: 400px;
        position: fixed;
        left: 50%;
        top: 0;
        transform: translateX(-50%);
        z-index: 9999;
        background: #fff;
        padding: 10px;
        outline: 1px solid #000;
        outline-offset: 4px;
    }
    .pwa_prompt-title{
        font-size: 20px;
        font-weight: bold;
        color: #002d6d;
    }
    #install_btn{
        border: none;
        background: #002d6d;
        color: #fff;
        padding: 4px 10px;
    }
    @media (max-width:450px) {
        #pwa_prompt{
            width: 100%;
        }
    }
    .dashboardLoader{
        font-size: 15px;
        font-weight: normal;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-container row-fluid container-fluid">
    <div id="pwa_prompt" hidden>
        <p class="pwa_prompt-title">{{ucfirst($domainDetails->main_heading)}}</p>
        <p style="color: #000">Download application in your phone or desktop.</p>
        <div style="float:right">
            <button id="install_btn">Install App</button>
            <button id="cancel_btn" style="background: transparent;border: none;color: red;font-size: 16px;">Cancel</button>
        </div>
    </div>
    <section id="main-content" class="">
        <section class="wrapper main-wrapper" style='margin:0; padding: 5px 0px 0px 0px;' id="dashboard-divparent">
            <section class="wrapper main-wrapper" style='margin:0; padding:0;'>
                <section class="home-section">
                    <div class="bord"></div>
                    <nav class="position-relative">
                        <div class="container">
                            <div class="nav-section" style="position:relative">
                                <div class="hello">
                                    <h2 style="font-size: 32px">Hello,</h2>
                                    <h2><b>  {{ucfirst($domainDetails->main_heading)}} </b></h2>
                                    <h4 style="color: #000 !important;line-height:2;text-align:center"><?= $domainDetails->slogan;?></h4>
                                </div>
                                <h5 style="position:absolute;bottom:0;color:#000;font-weight:400;text-align:center;line-height:1.5">
                                    <i class="la la-envelope"></i> {{$domainDetails->bm_invoice_email}} 
                                    <span class="pipe">|</span> 
                                    <span style="white-space:nowrap"><i class="la la-mobile"></i> UAN : {{$domainDetails->bm_helpline_number}} </span> </h5>
                                    <?php if(Auth::user()->status != 'manager'){
                                        if($wallet[0]->amount <= 1000){
                                            $walletAlertClass = 'btn-danger'; 
                                        }
                                        ?>
                                        <div class="parttwo" >
                                            <div class="amount <?= @$walletAlertClass;?>">
                                                <div>
                                                    <svg id="Capa_1" enable-background="new 0 0 511.851 511.851" viewBox="0 0 511.851 511.851" xmlns="http://www.w3.org/2000/svg" style="width: 30px; display: inline-block; margin-right: 10px;"><g><g><path d="m458.42 52.716-91.054-43.628c-30.614-14.667-68.412-1.366-83.064 29.215l-20.569 42.231-21.467-45.297c-14.662-30.598-52.438-43.94-83.085-29.258l-91.055 43.627c-30.576 14.649-43.949 52.421-29.271 83.056l26.861 56.678c-13.999 9.137-23.277 24.929-23.277 42.856v47.988c-13.957 3.3-24.376 15.855-24.376 30.806v4.819c0 4.143 3.358 7.5 7.5 7.5 4.142 0 7.5-3.357 7.5-7.5v-4.819c0-9.187 7.474-16.661 16.662-16.661h87.26c28.738 0 52.119 23.38 52.119 52.118s-23.38 52.119-52.119 52.119h-87.26c-9.188 0-16.662-7.475-16.662-16.661v-35.458c0-4.143-3.358-7.5-7.5-7.5-4.142 0-7.5 3.357-7.5 7.5v35.458c0 14.951 10.42 27.505 24.376 30.806v47.988c0 28.205 22.947 51.152 51.152 51.152h338.075c28.206 0 51.152-22.947 51.152-51.152v-228.503c0-17.258-8.6-32.537-21.731-41.805l26.589-54.591c14.842-30.973 1.717-68.245-29.256-83.084zm15.751 76.559-26.474 54.355c-5.046-1.67-10.431-2.587-16.03-2.587h-35.672c.108-1.401.18-2.811.18-4.239 0-30.247-24.607-54.854-54.854-54.854s-54.854 24.607-54.854 54.854c0 1.428.072 2.839.18 4.239h-55.184l66.346-136.216c11.115-23.197 39.844-33.345 63.076-22.213l91.054 43.628c23.178 11.107 33.37 39.787 22.232 63.033zm-6.352 102.921v3.238h-410.38v-3.238c0-19.935 16.218-36.152 36.152-36.152h338.075c19.936 0 36.153 16.217 36.153 36.152zm-410.38 228.503v-3.434h410.38v3.434c0 19.935-16.218 36.152-36.152 36.152h-338.075c-19.935 0-36.153-16.217-36.153-36.152zm146.664-114.252c0-37.009-30.109-67.118-67.119-67.118h-79.545v-28.895h410.38v191.831h-410.38v-28.699h79.545c37.01 0 67.119-30.109 67.119-67.119zm-129.495-283.313 91.054-43.627c23.244-11.137 51.943-1.023 63.063 22.183l26.595 56.117-24.567 50.439c-9.648-15.758-27.023-26.296-46.814-26.296-31.511 0-57.087 27.707-54.675 59.094h-35.672c-4.943 0-9.719.719-14.245 2.033l-26.951-56.868c-11.266-23.514-1.302-51.809 22.212-63.075zm226.858 113.67c0-21.976 17.878-39.854 39.854-39.854s39.854 17.879 39.854 39.854c0 1.432-.08 2.846-.228 4.239h-79.253c-.147-1.393-.227-2.806-.227-4.239zm-86.688 4.24h-70.466c-2.464-23.185 16.292-44.094 39.627-44.094 18.143 0 33.482 12.191 38.285 28.808z" fill="#fff"/><path d="m254.676 336.945v11.799c0 4.143 3.358 7.5 7.5 7.5s7.5-3.357 7.5-7.5v-11.799c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5z" fill="#fff"/><path d="m403.517 329.445c-4.142 0-7.5 3.357-7.5 7.5v11.799c0 4.143 3.358 7.5 7.5 7.5s7.5-3.357 7.5-7.5v-11.799c0-4.143-3.358-7.5-7.5-7.5z" fill="#fff"/><path d="m315.693 342.5c-3.449-2.295-8.104-1.36-10.399 2.089-2.294 3.449-1.359 8.104 2.089 10.399 8.524 5.672 16.949 8.507 25.398 8.507 8.437 0 16.897-2.828 25.497-8.485 3.46-2.276 4.421-6.927 2.145-10.388-2.277-3.461-6.929-4.421-10.388-2.144-12.116 7.97-22.386 7.978-34.342.022z" fill="#fff"/><path d="m134.38 374.633c15.542 0 28.185-12.644 28.185-28.186 0-15.541-12.644-28.185-28.185-28.185s-28.185 12.644-28.185 28.185c0 15.542 12.644 28.186 28.185 28.186zm0-41.371c7.271 0 13.185 5.915 13.185 13.185 0 7.271-5.915 13.186-13.185 13.186s-13.185-5.915-13.185-13.186c0-7.27 5.915-13.185 13.185-13.185z" fill="#fff"/></g></g></svg>
                                                    <h4>Wallet</h4>
                                                </div>
                                                @if(count($wallet)>0)
                                                <h3>  {{ $wallet[0]->amount }} <small style="color:#2aff2a">(PKR)</small></h3>
                                                @else 
                                                <h3>PKR 0 </h3>
                                                @endif
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <a href="#notif_modal" data-toggle="modal" class="drawer_modal">
                                <button>
                                    <span class="btn__icon-left"><i class="las la-bullhorn"></i></span>
                                    <span>Announcement</span>
                                </button>
                            </a>
                        </nav>
                        <div class="bread-crumbs">
                        </section>
                        <?php
                        $authStatus = Auth::user()->status;
                        $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
                        $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
                        $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
                        $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
                        $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
                        $whereArray = array();
                        if($authStatus == 'manager'){
                            array_push($whereArray,array('manager_id' , $manager_id));
                            $col = 12/4;  $profileCol = 12; $hideUnhide = 'hidden';
                        }else if($authStatus == 'reseller'){
                            array_push($whereArray,array('manager_id' , $manager_id));
                            array_push($whereArray,array('resellerid' , $resellerid));
                            $col = 12/3; $profileCol = 12; $hideUnhide = 'hidden';
                            if( !App\MyFunctions::check_access('Sub Trader',Auth::user()->id)  ){
                                $col = 12/2;     
                            }if(!App\MyFunctions::check_access('Trader',Auth::user()->id)){
                                $col = 12;
                            }
                        }else if($authStatus == 'dealer'){
                            array_push($whereArray,array('manager_id' , $manager_id));
                            array_push($whereArray,array('resellerid' , $resellerid));
                            array_push($whereArray,array('dealerid' , $dealerid));
                            $col = 12/2; $profileCol = 8; $hideUnhide = '';
                        }else if($authStatus == 'subdealer'){
                            array_push($whereArray,array('manager_id' , $manager_id));
                            array_push($whereArray,array('resellerid' , $resellerid));
                            array_push($whereArray,array('dealerid' , $dealerid));
                            array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));
                            $col = 12/1; $profileCol = 8; $hideUnhide = '';
                        }else if($authStatus == 'trader'){
                            array_push($whereArray,array('manager_id' , $manager_id));
                            array_push($whereArray,array('resellerid' , $resellerid));
                            array_push($whereArray,array('dealerid' , $dealerid));
                            array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));
                            array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));
                            $profileCol = 8; $hideUnhide = '';
                        }
                        $allowedMenuAccess = array();
                        if($authStatus == 'dealer' || $authStatus == 'subdealer' || $authStatus == 'trader'){
                            $dealerInfo = DB::table('user_info')->where('status','dealer')->where('dealerid',$dealerid)->select('id')->first();
                            $allowedMenuAccess = DB::table('user_menu_accesses')->where('user_id',$dealerInfo->id)->where('status',1)->get()->toArray();
                            $allowedMenuAccess = array_column($allowedMenuAccess, 'sub_menu_id');
                        }
                        ?>
                        <!-- Card Section -->
                        <div class="container">
                            <div class="card__wrapper">
                                <div class="row">
                                    <?php if(App\MyFunctions::check_access('Active Consumers',Auth::user()->id)){ ?>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.user.index1',['status' => 'user']) : "" ?>">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="fa fa-users"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.user.index1',['status' => 'user']) : "" ?>"><p>All Consumers</p></a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardConsumerCount"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_all_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } if(App\MyFunctions::check_access('Active Consumers',Auth::user()->id)){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="la la-user-check"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.user.index1',['status' => 'user']) : "" ?>"><p>Active Consumers</p>
                                                        </a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardActiveConsumerCount"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_active_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(App\MyFunctions::check_access('Online Consumers',Auth::user()->id)){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="la la-users"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.user.onlineuser',['status' => 'user']) : "" ?>"><p><strong style="color:green" id="dashboardOnlinePercentage"><span class="dashboardLoader"></span> </strong> Online Consumers</p></a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardOnlineConsumerCount"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_online_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>    
                                            </div>
                                        <?php } ?>
                                        <?php if(App\MyFunctions::check_access('Offline Consumers',Auth::user()->id)){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="la la-user-minus"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.offlineUserView',['status' => 'user']) : "" ?>"><p>Offline Consumer</p></a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardOfflineConsumer"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_offline_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php  if(App\MyFunctions::check_access('Expired Consumers',Auth::user()->id)){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="la la-user-times"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.user.index1',['status' => 'expire']) : "" ?>"><p>Expired Consumer</p></a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardExpiredConsumer"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_expired_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(App\MyFunctions::check_access('Upcoming Expires',Auth::user()->id)){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="la la-user-minus"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.billing.upcoming_expiry') : "" ?>"><p>Up Coming Expiry</p>
                                                        </a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardUpComingExpiry"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_upcoming_expire_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(App\MyFunctions::check_access('Suspicious Consumers',Auth::user()->id)){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="la la-user-ninja"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.abo') : "" ?>"><p>Suspicious Consumer</p></a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardSuspiciousConsumer"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_suspicious_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php  if(App\MyFunctions::check_access('New & Terminate',Auth::user()->id)){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="la la-user-friends"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.user.index1',['status' => 'terminate']) : "" ?>"><p>New Consumer</p></a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardNewConsumer"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_terminate_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="card-content">
                                                <div class="icon__part">
                                                    <i class="la la-user-slash"></i>
                                                </div>
                                                <div class="text__part">
                                                    <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.user.index1',['status' => 'disabled']) : "" ?>"><p>Disable Consumers</p>
                                                    </a>
                                                </div>
                                                <div class="count__part">
                                                    <p id="dashboardDisabledConsumer"><span class="dashboardLoader">loading...</span></p>
                                                </div>
                                                <button type="button" class="btn get_disabled_consumer_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="card-content">
                                                <div class="icon__part">
                                                    <i class="la la-mobile"></i>
                                                </div>
                                                <div class="text__part">
                                                    <p>Mobile Verfied</p>
                                                </div>
                                                <div class="count__part">
                                                    <p id="dashboardVerifiedMobile"><span class="dashboardLoader">loading...</span></p>
                                                </div>
                                                <button type="button" class="btn get_mobile_verified_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="card-content">
                                                <div class="icon__part">
                                                    <i class="la la-id-card"></i>
                                                </div>
                                                <div class="text__part" style="position:relative">
                                                    <p>CNIC Verfied</p>
                                                    <p class="verify_restricted"><?= $verifyRestricted;?></p>
                                                </div>
                                                <div class="count__part">
                                                    <p id="dashboardVerifiedCNIC"><span class="dashboardLoader">loading...</span></p>
                                                </div>
                                                <button type="button" class="btn get_cnic_verified_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                            </div>
                                        </div>
                                        <!-- Invalid Login (Not Functional) -->
                                        <?php if(App\MyFunctions::check_access('Invalid Login',Auth::user()->id)){ ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="la la-user-times"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="<?= ($authStatus != 'manager' && $authStatus != 'reseller') ? route('users.billing.error_log') : "" ?>"><p>Invalid Login</p></a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardInvalidLogins"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_invalid_login_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <?php if($authStatus == 'manager'){?>
                                            <div class="col-lg-<?= $col;?> col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="fas fa-user-tie"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="{{route('users.user.index1',['status' => 'reseller'])}}"><p>Resellers</p>
                                                        </a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardResellerCount"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_reseller_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } if( ($authStatus == 'manager' || $authStatus == 'reseller') && (App\MyFunctions::check_access('Contractor',Auth::user()->id)) ){?>
                                            <div class="col-lg-<?= $col;?> col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="fa fa-user-friends"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="{{route('users.user.index1',['status' => 'dealer'])}}"><p>Contractors</p>
                                                        </a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardContractorCount"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_contractor_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } if( ($authStatus == 'manager' || $authStatus == 'reseller' || $authStatus == 'dealer') &&  (App\MyFunctions::check_access('Trader',Auth::user()->id))  ){
                                            if(!in_array(5, $allowedMenuAccess) && $authStatus != 'reseller' && $authStatus != 'manager'){
                                                $col = 12;
                                            }?>
                                            <div class="col-lg-<?= $col;?> col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="fa fa-users-cog"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="{{route('users.user.index1',['status' => 'subdealer'])}}"><p>Traders</p>
                                                        </a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardTraderCount"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_trader_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } if( ($authStatus == 'manager' || $authStatus == 'reseller' || $authStatus == 'dealer') && App\MyFunctions::check_access('Trader',Auth::user()->id) && App\MyFunctions::check_access('Sub Trader',Auth::user()->id) ) {?>
                                            <div class="col-lg-<?= $col;?> col-md-4 col-sm-6 col-12">
                                                <div class="card-content">
                                                    <div class="icon__part">
                                                        <i class="fa fa-user-cog"></i>
                                                    </div>
                                                    <div class="text__part">
                                                        <a href="{{route('users.user.index1',['status' => 'trader'])}}"><p>Sub Traders</p></a>
                                                    </div>
                                                    <div class="count__part">
                                                        <p id="dashboardSubTraderCount"><span class="dashboardLoader">loading...</span></p>
                                                    </div>
                                                    <button type="button" class="btn get_sub_trader_count" style="position: absolute;right: 6px;top: 1px;background: transparent;"><i class="fa fa-refresh"></i></button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="chart__wrapper">
                                    <div class="row">
                                        <div class="col-lg-<?= $profileCol;?>">
                                            <div class="">
                                                <div id="chartContainer">
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(App\MyFunctions::check_access('Online Consumers',Auth::user()->id)){ ?>
                                            <div class="col-lg-4 <?= $hideUnhide;?>">
                                                <div class="table-responsive">
                                                    <table class="table text-center table-hover" style="margin-top: 0 !important" id="table__olUsers">
                                                        <thead class="text-center">
                                                            <tr class="text-center">
                                                                <th class="text-center" colspan="3">Recently Online Consumers</th>
                                                            </tr>
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
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <section class="slider2">
                                <div class="container">
                                    <div class="marquee-sec">
                                        <?php  print_r($headline['urdu_content']); ?> 
                                        <?php  print_r($headline['english_content']); ?> 
                                    </div>
                                </div>
                            </section>
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
                    <!-- CONTENT END -->
                </div>
                <!-- Notification Update Modal Start -->
                <div class="modal notif_modal right fade" id="notif_modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" style="color: #fff;" >Announcement</h4>
                            </div>
                            <?php
                            $annoucment = DB::table('tickers')->first();
                            ?>
                            <div class="modal-body" style="text-align:right">
                                <!-- Announcment Content Here -->
                                <?php echo $annoucment->announcement_content; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Notification Update Modal End -->
                <!-- <script> -->
                    <script>
                        $(document).ready(function(){
                            $('#chartContainer').html('<img src="https://partner.blinkbroadband.pk/img/graphLoader.jpg" style="width:100%;height:100%;">');
                            $.ajax({
                                method: 'POST',
                                dataType : 'json',
                                url: "{{route('users.dashboardData.profile_wise_user_count_graph')}}",
                                data: {
                                    _token: '{{csrf_token()}}'
                                },
                                success: function(res){
                                    var dataArray = [];
                                    $.each(res.profile, function( index, value ) {
                                        dataArray[index] = { label: value, y: res.count[index] };
                                    });
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
indexLabel: "{y}", //Shows Yes Value On All Data Points
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
                    <!-- </script>  -->
                    <script>
                        $(document).ready(function(){
                            $(".marquee-sec p,h1").first('p').addClass("moving-texturdu");
                            $(".marquee-sec p,h1").eq(1).addClass("moving-text");
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
                                        var markup ='';
                                        $.each(data, function(index, value) {
                                            markup += "<tr><td class='text-primary'><b>"+value.username+"</b></td><td style='color: #e875b9;'>"+value.acctstarttime+"</td><td>"+value.framedipaddress+"</td></tr>";
                                        });
                                        $(".liveUsers").html(markup);
                                    }
                                });
                            }
                            setInterval(function(){showLiveUser();}, 2000);
                        });
                    </script>
                    <script>
                        $(document).on('click','.get_all_consumer_count',function(){
                            get__count("{{route('users.dashboardData.totalConsumer')}}",'#dashboardConsumerCount');
                        });
//
$(document).on('click','.get_active_consumer_count',function(){
    get__count("{{route('users.dashboardData.activeConsumer')}}",'#dashboardActiveConsumerCount');
});
//
$(document).on('click','.get_online_consumer_count',function(){
    get_online_count();
});
//
$(document).on('click','.get_upcoming_expire_consumer_count',function(){
    get__count("{{route('users.dashboardData.upcoming_expire_consumer')}}",'#dashboardUpComingExpiry');
});
//
$(document).on('click','.get_mobile_verified_count',function(){
    get__count("{{route('users.dashboardData.verified_mobile')}}",'#dashboardVerifiedMobile');
});
//
$(document).on('click','.get_cnic_verified_count',function(){
    get__count("{{route('users.dashboardData.verified_cnic')}}",'#dashboardVerifiedCNIC');
});
//
$(document).on('click','.get_invalid_login_count',function(){
    get__count("{{route('users.dashboardData.invalid_login')}}",'#dashboardInvalidLogins');
});
//
$(document).on('click','.get_reseller_count',function(){
    get__count("{{route('users.dashboardData.reseller_count')}}",'#dashboardResellerCount');
});
//
$(document).on('click','.get_contractor_count',function(){
    get__count("{{route('users.dashboardData.contractor_count')}}",'#dashboardContractorCount');
});
//
$(document).on('click','.get_trader_count',function(){
    get__count("{{route('users.dashboardData.trader_count')}}",'#dashboardTraderCount');
});
//
$(document).on('click','.get_sub_trader_count',function(){
    get__count("{{route('users.dashboardData.subtrader_count')}}",'#dashboardSubTraderCount');
});
//
$(document).on('click','.get_disabled_consumer_count',function(){
    get__count("{{route('users.dashboardData.disabled_consumer')}}",'#dashboardDisabledConsumer');
});
//
$(document).on('click','.get_offline_consumer_count',function(){
    get__count("{{route('users.dashboardData.offline_consumer')}}",'#dashboardOfflineConsumer');
});
//
$(document).on('click','.get_expired_consumer_count',function(){
    get__count("{{route('users.dashboardData.expired_consumer')}}",'#dashboardExpiredConsumer');
});
//
$(document).on('click','.get_suspicious_consumer_count',function(){
    get__count("{{route('users.dashboardData.suspicious_consumer')}}",'#dashboardSuspiciousConsumer');
});
//
$(document).on('click','.get_terminate_consumer_count',function(){
    get__count("{{route('users.dashboardData.new_consumer')}}",'#dashboardNewConsumer');
});
function get__count(url,div){
    $(div).html('<span class="dashboardLoader">loading...</span>');
    $.ajax({
        url : url,
        type:'POST',
        success:function(response){
            setTimeout(() => {
                $(div).html(response)
            }, 1000);
        }
    });
}
function get_online_count(){
    $('#dashboardOnlineConsumerCount').html('<span class="dashboardLoader">loading...</span>');
    $('#dashboardOnlinePercentage').html('...');
//
$.ajax({
    url : "{{route('users.dashboardData.onlineConsumer')}}",
    type:'POST',
    success:function(response){
        setTimeout(() => {
            $('#dashboardOnlineConsumerCount').html(response.count);
            $('#dashboardOnlinePercentage').html(response.percentage+'%');
        }, 1000);
    }
});
}
$(document).ready(function(){
    $.ajax({
        method: 'POST',
        url: "{{route('users.dashboardData.totalConsumer')}}",
        data: {
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            $('#dashboardConsumerCount').html(data);
        },
        complete: function(){
        }   
    });
    $.ajax({
        method: 'POST',
        url: "{{route('users.dashboardData.activeConsumer')}}",
        data: {
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            $('#dashboardActiveConsumerCount').html(data);
        },
        complete: function(){
        }   
    });
    $.ajax({
        method: 'POST',
        url: "{{route('users.dashboardData.onlineConsumer')}}",
        data: {
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            $('#dashboardOnlineConsumerCount').html(data.count);
            $('#dashboardOnlinePercentage').html(data.percentage+'%');
        },
        complete: function(){
        }   
    });
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
    $.ajax({
        method: 'POST',
        url: "{{route('users.dashboardData.reseller_count')}}",
        data: {
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            $('#dashboardResellerCount').html(data);
        },
        complete: function(){
        }   
    });
    $.ajax({
        method: 'POST',
        url: "{{route('users.dashboardData.contractor_count')}}",
        data: {
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            $('#dashboardContractorCount').html(data);
        },
        complete: function(){
        }   
    });
    $.ajax({
        method: 'POST',
        url: "{{route('users.dashboardData.trader_count')}}",
        data: {
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            $('#dashboardTraderCount').html(data);
        },
        complete: function(){
        }   
    });
    $.ajax({
        method: 'POST',
        url: "{{route('users.dashboardData.subtrader_count')}}",
        data: {
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            $('#dashboardSubTraderCount').html(data);
        },
        complete: function(){
        }   
    });
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
    $.ajax({
        method: 'POST',
        url: "{{route('users.dashboardData.new_consumer')}}",
        data: {
            _token: '{{csrf_token()}}'
        },
        success: function(data){
            $('#dashboardNewConsumer').html(data);
        },
        complete: function(){
        }   
    });
});
</script>
@endsection
<!-- Code Finalize -->