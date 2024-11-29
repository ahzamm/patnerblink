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
@section('content')
<style type="text/css">
    .row {
        margin-left: 0px !important;
        margin-right: 0px !important
    }
</style>
<div class="page-container row-fluid container-fluid pt-2">
    <!-- CONTENT START -->
    <section id="main-content" class=" ">
        <section class="wrapper main-wrapper row">
            <div class="clear-fix"></div>
            <div class="header_view">
                <h2>PTA Marking
                    <span class="info-mark" onmouseenter="popup_function(this, 'pta_marking');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                </h2>
            </div>
            <div class="row justify-content-center">
                <div class="card bg-white">
                    <div class="card-body" style="padding-top: 20px">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert"><span
                                class="fa fa-close"></span></button>
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif
                            @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert"><span
                                    class="fa fa-close"></span></button>
                                    <strong>{{ $message }}</strong>
                                </div>
                                @endif
                                @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <form action="{{route('users.pta_marking_upload')}}" enctype="multipart/form-data"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-lg-3 col-md-6">
                                        <div class="form-group position-relative">
                                            <label>Select File <span style="color: red">*</span></label>
                                            <span class="helping-mark" onmouseenter="popup_function(this, 'select_file_upload_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                                            <input type="file" class="form-control" name="file" id="file" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <br>
                                        <input type="submit" class="btn btn-primary" name="submit" value="Submit"
                                        id="btn-submit">
                                        <button type="button" class="btn btn-default ml-4"><a
                                            href="{{asset('PTA_mark_sample_sheet.csv')}}"><span class="fa fa-download"></span>
                                        Download Sample (.csv) File</a></button>
                                    </div>
                                </div>
                            </form>
                            <div style="padding: 2px 5px 5px 20px">
                                <b>Instructions:</b>
                                <ul style="padding-inline-start: 20px">
                                    <li>File must be <b>.csv</b> format.</li>
                                    <li>File must contain exactly <b>2</b> columns.</li>
                                    <li>File must contain heading of column.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <!-- CONTENT END -->
    </div>
    @endsection
    @section('ownjs')
    <script>
        $(document).ready(function () {
/*------------------------------------------
--------------------------------------------
Manager Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#manger-dropdown').on('change', function () {
    var manager_id = this.value;
    $("#reseller-dropdown").html('');
    $.ajax({
        url: "{{route('admin.reseler')}}",
        type: "POST",
        data: {
            manager_id: manager_id,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (result) {
            $('#reseller-dropdown').html('<option value="">--- Select Reseller ---</option>');
            $.each(result.reseller, function (key, value) {
                $("#reseller-dropdown").append('<option value="' + value
                    .resellerid + '">' + value.resellerid + '</option>');
            });
            $('#dealer-dropdown').html('<option value="">--- First Select Reseller ---</option>');
            $('#trader-dropdown').html('<option value="">--- First Select Contractor ---</option>');
        }
    });
});
/*------------------------------------------
--------------------------------------------
Reseller Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#reseller-dropdown').on('change', function () {
    var reseller_id = this.value;
    $("#dealer-dropdown").html('');
    $("#trader-dropdown").html('');
    $.ajax({
        url: "{{route('admin.dealer')}}",
        type: "POST",
        data: {
            reseller_id: reseller_id,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (result) {
            $('#dealer-dropdown').html('<option value="">--- Select Contractor --</option>');
            $.each(result.dealer, function (key, value) {
                $("#dealer-dropdown").append('<option value="' + value
                    .dealerid + '">' + value.dealerid + '</option>');
            });
            $('#trader-dropdown').html('<option value="">--- First Select Contractor ---</option>');
        }
    });
});
$('#dealer-dropdown').on('change', function () {
    var dealer_id = this.value;
    $("#trader-dropdown").html('');
    $.ajax({
        url: "{{route('get.trader')}}",
        type: "POST",
        data: {
            dealer_id: dealer_id,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (result) {
            $('#trader-dropdown').html('<option value="">--- Select Trader ---</option>');
            $.each(result.subdealer, function (key, value) {
                $("#trader-dropdown").append('<option value="' + value
                    .username + '">' + value.username + '</option>');
            });
        }
    });
});
});
</script>
@endsection
<!-- Code Finalize -->