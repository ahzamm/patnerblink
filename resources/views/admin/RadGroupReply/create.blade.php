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
@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<style>
    table.table>thead>tr>th{
        white-space:nowrap;
    }
</style>
<div class="page-container row-fluid container-fluid">
    <section id="main-content">
        <section class="wrapper main-wrapper">
            <div class="uprofile-content">
                <div class="row">
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
                        <ul></ul>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger print-error-msg">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(Session::has('message'))
                    <div class="alert alert-{{ Session::get('message') }} alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <i class="glyphicon glyphicon-{{ Session::get('message') == 'success' ? 'ok' : 'remove'}}"></i> {{ Session::get('message') }}
                    </div>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block success-msg">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    <div class="">
                        <div class="header_view">
                            <h2>MANAGE (RAD-GROUP-REPLY ATTRIBUTES)
                                <span class="info-mark" onmouseenter="popup_function(this, 'radusergroup_manage_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                            </h2>
                        </div>
                        <section class="box">
                            <div class="content-body">
                                <form  method="post" enctype="multipart/data" id="store-rad-reply">
                                    @csrf
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dynamicTable" style="min-width: 1000px"> 
                                            <thead>
                                                <tr>
                                                    <th>Select Reseller <span style="color: red">*</span></th>
                                                    <th>Select BRAS <span style="color: red">*</span></th>     
                                                    <th>Assgin Bandwidth in (kbps) <span style="color: red">*</span></th>
                                                    <th>Attribute <span style="color: red">*</span></th>
                                                    <th>OP <span style="color: red">*</span></th>
                                                    <th>Value <span style="color: red">*</span></th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>  
                                                    <td>
                                                        <select name="addmore[0][reseller]" class="form-control" id="reseller">
                                                            @foreach($resellerid as $key => $reseller)
                                                            <option value="{{$reseller}}">{{$reseller}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="addmore[0][brands]" class="form-control" id="brands">
                                                            @foreach($brands as $key => $brand)
                                                            <option value="{{$brand}}">{{$brand}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="addmore[0][groupname]" placeholder="Type Bandwidth like 1024" class="form-control" id="groupname"/></td>  
                                                    <td><input type="text" name="addmore[0][attribute]" placeholder="Type Your Attribute here" class="form-control" id="attribute"/></td>  
                                                    <td><input type="text" name="addmore[0][op]" placeholder="Type Your Op here" class="form-control" id="op"/></td> 
                                                    <td><input type="text" name="addmore[0][value]" placeholder="Type Value here" class="form-control" id="value"/></td> 
                                                    <td><button type="button" name="addmore[0][add]" id="add" class="btn btn-primary">Add More</button></td>
                                                </tr>  
                                            </tbody>
                                        </table> 
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group pull-right" style="margin-top: 20px;">
                                                <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                        <span id="output22"></span>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        var i = 0;
        var reseller = <?php echo json_encode($resellerid); ?>;
        var brands = <?php echo json_encode($brands); ?>;
        $("#add").click(function(){
            ++i;
            $html = '<tr><td><select name="addmore['+i+'][reseller]" class="form-control">';
            $.each(reseller, function(index, value) {
                $html += '<option>'+value+'</option>';
            });
            $html += '<td><select name="addmore['+i+'][brands]" class="form-control">';
            $.each(brands, function(index, value) {
                $html += '<option>'+value+'</option>';
            });
            $html += '</select></td><td><input type="text" name="addmore['+i+'][groupname]" placeholder="Type Your Group Name" class="form-control" /></td><td><input type="text" name="addmore['+i+'][attribute]" placeholder="Type Your Attribute here" class="form-control" /></td><td><input type="text" name="addmore['+i+'][op]" class="form-control" placeholder="Type Your Op here"></td><td><input type="text" name="addmore['+i+'][value]" class="form-control" placeholder="Type Value here"><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>';
            $("#dynamicTable").append($html);
        });
        $(document).on('click', '.remove-tr', function(){  
            $(this).parents('tr').remove();
        });  
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".btn-submit").click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{route('admin.store.rad')}}",
                    type:'POST',
                    data: $( '#store-rad-reply' ).serialize(),
                    success: function(data) {
                        if($.isEmptyObject(data.error)){
// alert(data.success);
$(".print-error-msg").css('display','none');
$(".success-msg").css('display','block');
$('.success-msg').html(data.success);
location.reload(3000);
}else{
    printErrorMsg(data.error);
}
}
});
            }); 
            function printErrorMsg (msg) {
                $(".print-error-msg").find("ul").html('');
                $(".success-msg").css('display','none');
                $(".print-error-msg").css('display','block');
                $.each( msg, function( key, value ) {
                    $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                });
            }
        });
    </script>
    @endsection
<!-- Code Finalize -->