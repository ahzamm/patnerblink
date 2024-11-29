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
        /* border-bottom: var(--bs-card-border-width) solid var(--bs-card-border-color); */
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
    /* knob */
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
$cdnDivClass = $stDivClass = null;
if($straightCount > 0){
    $stDivClass = 'active';
}else{
    $cdnDivClass = 'active';
}
?>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-container row-fluid container-fluid">
    <!-- CONTENT START -->
    <section id="main-content" class="">
        <section class="wrapper main-wrapper" style='padding:0;'>
            <div class="row" style="margin: 0">
                <div class="col-xs-12">
                    <div class="header_view">
                        <h2>Internet Packages
                            <span class="info-mark" onmouseenter="popup_function(this, 'internet_packages');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                        </h2>
                    </div>
                    <div class="uprofile-content row">
                        <div class="col-lg-12">
                            <div class="">
                                <ul class="nav nav-tabs" style="display:flex;align-itmes:center;justify-content:center">
                                    <?php if($straightCount > 0){ ?>
                                        <li class="<?= $stDivClass;?>"><a data-toggle="tab" href="#straight">Straight (IPT) Internet Packages</a></li>
                                    <?php } if($cdnCount > 0){ ?>
                                        <li class="<?= $cdnDivClass;?>"><a data-toggle="tab" href="#cdn">Shared (CDN) Internet Packages</a></li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content" style="background-color:transparent;padding: 0">
                                    <div id="straight" class="tab-pane fade in <?= $stDivClass;?>">
                                        <div class="row">
                                            <div class="user__detail" style="margin-top: 20px">
                                                <div class="row" style="margin: 0">
                                                    <?php foreach ($user_packages_data as $key => $value) {
// $bandwidth = $value->groupname/1024;
                                                        $pkgDetail = App\model\Users\Profile::where('name',$value->name)->first();
                                                        $bandwidth = @$pkgDetail->bw_show/1024;
                                                        if($pkgDetail && $pkgDetail->profile_type == 'straight'){
                                                            $intpkg = 0;
                                                            $facebook = 0;
                                                            $youtube = 0;
                                                            $netflix = 0;
                                                            ?>
                                                            <!-- Card 2 -->
                                                            <div class="col-md-6 col-lg-4" style="margin-bottom:20px;">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div>
                                                                            <h2 class="theme-color" style="text-align: center; font-weight: bold;"><?= $value->name;?></h2>
                                                                        </div>
                                                                        <hr style="margin-top: 12px;">
                                                                        <p style="text-align: center; font-weight: bold; margin-bottom: 20px;font-size: 18px;">Unlimited <span class="theme-color" style="font-size: 28px; "><?= $bandwidth;?></span> Mbps</p>
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
                                                                        <div style="display: flex;align-items: center; justify-content: center; gap: 30px;flex-wrap:wrap">
                                                                            <div class="knob-circle">
                                                                                <div class="knob__input">
                                                                                    <input type="text" class="knob" value="0" data-rel="<?= $bandwidth;?>" data-linecap="round"data-width="100" data-height="100" data-bgcolor="#e8e8eb" data-fgcolor="#0d4dab" data-thickness=".15" data-readonly="true"disabled>
                                                                                    <span class="circle-text theme-color" style="line-height: 100px; font-size: 25px;"><?= $bandwidth;?></span>
                                                                                    <span class="circle-info" style="top: 50px">Mbps</span>
                                                                                </div>
                                                                            </div>
                                                                            <div style="display: inline-block">
                                                                                <p>Bandwidth: <?= $bandwidth;?>Mbps</p>
                                                                                <p style="color: #000;">Download: <span style="color: #2b582b">Unlimited</span></p>
                                                                                <p><a href="https://speedtest.net" target="_blank" class="btn btn-primary">Check Speed Now</a></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="cdn" class="tab-pane fade in <?= $cdnDivClass;?>">
                                            <div class="row">
                                                <div class="user__detail" style="margin-top: 20px">
                                                    <div class="row" style="margin: 0">
                                                        <?php foreach ($user_packages_data as $key => $value) {
// $bandwidth = $value->groupname/1024;
                                                            $pkgDetail = App\model\Users\Profile::where('name',$value->name)->first();
                                                            $bandwidth = @$pkgDetail->bw_show/1024;
                                                            if($pkgDetail && $pkgDetail->profile_type == 'cdn'){
                                                                $intpkg = $pkgDetail->soc_internet;
                                                                $facebook = $pkgDetail->soc_facebook;
                                                                $youtube = $pkgDetail->soc_youtube;
                                                                $netflix = $pkgDetail->soc_netflix;
                                                                ?>
                                                                <!-- Card 2 -->
                                                                <div class="col-md-6 col-lg-4" style="margin-bottom:20px;">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div>
                                                                                <h2 class="theme-color" style="text-align: center; font-weight: bold;"><?= $value->name;?></h2>
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
                                                                            <div style="display: flex;align-items: center; justify-content: center; gap: 30px;flex-wrap:wrap">
                                                                                <div class="knob-circle">
                                                                                    <div class="knob__input">
                                                                                        <input type="text" class="knob" value="0" data-rel="<?= $bandwidth;?>" data-linecap="round"data-width="100" data-height="100" data-bgcolor="#e8e8eb" data-fgcolor="#0d4dab" data-thickness=".15" data-readonly="true"disabled>
                                                                                        <span class="circle-text" style="line-height: 100px; font-size: 25px;"><?= $bandwidth;?></span>
                                                                                        <span class="circle-info" style="top: 50px">Mbps</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div style="display: inline-block">
                                                                                    <p>Bandwidth: <?= $bandwidth;?>Mbps</p>
                                                                                    <p style="color: #000;">Download: <span style="color: #2b582b">Unlimited</span></p>
                                                                                    <p><a href="https://speedtest.net" target="_blank" class="btn btn-primary">Check Speed Now</a></p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } } ?>
                                                        </div>
                                                    </div>
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
        <script>
            $(function() {
            });
        </script>
        @endsection
<!-- Code Finalize -->