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
* Created: June, 2023
* Last Updated: 08th June, 2023
* Author: Talha Fahim & Hasnain Raza <info@squadcloud.co>
*-->
<!-- Code Onset -->
@extends('admin.layouts.app')
@section('content')
<div class="page-container row-fluid container-fluid">
    <section id="main-content">
        <section class="wrapper main-wrapper">
            <div class="header_view text-center">
                <h2>Portal Maintenance Mode
                    <span class="info-mark" onmouseenter="popup_function(this, 'maintenance_mode_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                </h2>
            </div>
            <section class="box" style="padding-top:20px;">
                <div class="content-body">
                    <div class="row">
                        @if(!empty($mode))
                        <div class="col-md-12">
                            <form action="{{route('maintenance.store')}}" method="post">
                                @csrf
                                <div class="col-md-4">
                                    <div class="form-group position-relative">
                                        <label>Select Date & Time</label>
                                        <div class='input-group date'>
                                            <input type='text' class="form-control" id="datetime" name="maintenance_time" required value="{{$date_time}}" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group position-relative"> 
                                        <label>Allow (IPs Address)</label>
                                        <input type='text' class="form-control" name="allowed_ip" required value="{{$mode->allowed_ips}}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group position-relative">
                                        <label>Own (IP Address)</label>
                                        <input type='text' class="form-control" name="own_ip" required value="{{$client_ip}}" readonly />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group pull-right">
                                        <input id="" type="hidden" value="enable" name="status">
                                        @if($mode->status === "enable")
                                        <button type="button" class="btn btn-primary text-white deactive">DeActive Mode</button>
                                    </div>
                                    @else
                                    <input type="submit" value="Active Mode" class="btn btn-danger text-white">
                                    @endif
                                </div>
                            </form>
                        </div>
                        @else
                        <div class="col-md-12">
                            <form action="{{route('maintenance.store')}}" method="post">
                                @csrf
                                <div class="col-md-3">
                                    <div class="form-group position-relative">
                                        <label>Select Date & Time <span style="color: red">*</span></label>
                                        <span class="helping-mark" onmouseenter="popup_function(this, 'select_date_time_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                                        <div class='input-group date'>
                                            <input type='text' class="form-control" id="datetime" name="maintenance_time"  value="{{$date_time}}"required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group position-relative">
                                        <label>Own (IP Address) <span style="color: red">*</span></label>
                                        <span class="helping-mark" onmouseenter="popup_function(this, 'own_ip_address_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                                        <input type='text' class="form-control" name="own_ip" required  readonly value="{{$client_ip}}"/>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group position-relative">
                                        <label>Allow (IPs Address) <span style="color: red">*</span></label>
                                        <span class="helping-mark" onmouseenter="popup_function(this, 'allow_ip_address_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                                        <input type='text' class="form-control" id="" name="allowed_ip" placeholder="Example : 1.1.1.1,2.2.2.2" required value="" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group pull-right">
                                        <input id="" type="hidden" value="enable" name="status">
                                        <input type="submit" value="Active Mode" class="btn btn-danger text-white">
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                    <table id="example-1" class="table dt-responsive w-100 display">
                        <thead>
                            <tr>
                                <th>Serial#</th>
                                <th>Allow (IPs Address)</th>
                                <th>Activate Date & Time</th>
                                <th>Deactivate Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($mode_log)) 
                            @foreach($mode_log as $log_data)
                            <tr>
                                <td>{{$log_data->id}}</td>
                                <td class="td__profileName">{{$log_data->allowed_ips}}</td>
                                @if($log_data->activation_time != '')
                                <td>{{date('M d,Y H:i:s',strtotime($log_data->activation_time))}}</td>
                                @else
                                <td>N/A</td>
                                @endif
                                @if($log_data->deactivation_time != '')
                                <td>{{date('M d,Y H:i:s',strtotime($log_data->deactivation_time))}}</td>
                                @else
                                <td>N/A</td>
                                @endif
                                @if($log_data->status == 'enable')
                                <td>Active</td>
                                @else
                                <td>DeActive</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </section>
</div>
@endsection
@section('ownjs')
<script>
    flatpickr("#datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i:ss",
        minDate: "today"
    });
    $('.deactive').on('click',function(e) {
        e.preventDefault();
        var time = $('#datetime').val();
        $.ajax({
            type: 'GET',
            url: "{{route('maintenance.deactivate')}}",
            data: {
                time:time,
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
// alert(data.success);
$(".print-error-msg").css('display', 'none');
$(".success-msg").css('display', 'block');
$('.success-msg').html(data.success);
$('#message_modal').modal('hide');
location.reload(3000);
} else {
    printErrorMsg(data.error);
}
}
})
    });
</script>
@endsection
<!-- Code Finalize -->