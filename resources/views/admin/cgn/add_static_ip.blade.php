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
    .table>thead>tr>th{white-space:nowrap}
</style>
<div class="page-container row-fluid container-fluid">
    <section id="main-content">
        <section class="wrapper main-wrapper">
            <div class="content-body">
                <div class="">
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
                    <div class="header_view">
                        <h2>Add Static Ips</h2>
                    </div>
                    <form  method="post" enctype="multipart/data" id="add-static-ip">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dynamicTable"> 
                                <thead>
                                    <tr>
                                        <th>G-ID <span style="color: red">*</span></th>
                                        <th>(NAS) SERVER IP <span style="color: red">*</span></th>
                                        <th>IP ADDRESS <span style="color: red">*</span></th>
                                        <th>Select IPs TYPE <span style="color: red">*</span></th>
                                        <th>Select NAS <span style="color: red">*</span></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>  
                                        <td>
                                            <select name="addmore[0][gid]" class="form-control" id="gid">
                                                @foreach($get_gid_data as $key => $gids)
                                                <option value="{{$gids}}">{{$gids}}</option>
                                                @endforeach
                                            </select>
                                        </td>  
                                        <td><input type="text" name="addmore[0][serverip]" placeholder="Type Your (NAS) Server IP Here" class="form-control" id="serverip"/></td>  
                                        <td><input type="text" name="addmore[0][ipaddress]" placeholder="Type Your Ip Address Here" class="form-control" id="ipaddress"/></td>  
                                        <td>
                                            <select name="addmore[0][type]" class="form-control">
                                                <option value="static">Static</option>
                                                <option value="gaming">Gaming</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="addmore[0][bras]" class="form-control" id="bras">
                                                @foreach($get_nas_data as $key => $nas)
                                                <option value="{{$nas}}">{{$nas}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <input type="hidden" name="addmore[0][status]">
                                        <input type="hidden" name="addmore[0][added_by]">
                                        <input type="hidden" name="addmore[0][addede_by_ip]">  
                                        <td><button type="button" name="addmore[0][add]" id="add" class="btn btn-primary">Add More</button></td>
                                    </tr>  
                                </tbody>
                            </table> 
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group pull-right mt-3"  style="margin-top: 10px">
                                    <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <span id="output22"></span>
                </div>
            </div>
        </section>
    </section>
</div>
@endsection
@section('ownjs')
<script>
    var i = 0;
    var nas = <?php echo json_encode($get_nas_data); ?>;
    var gids = <?php echo json_encode($get_gid_data); ?>;
    $("#add").click(function(){
        ++i;
        $html = '<tr><td><select name="addmore['+i+'][gid]" class="form-control">';
        $.each(gids, function(index, value) {
            $html += '<option>'+value+'</option>';
        });
        $html += '</select></td><td><input type="text" name="addmore['+i+'][serverip]" placeholder="Type Your (NAS) Server IP Here" class="form-control" /></td><td><input type="text" name="addmore['+i+'][ipaddress]" placeholder="Type Your Ip Address Here" class="form-control" /></td><td><select name="addmore['+i+'][type]" class="form-control"><option value="static">Static</option><option value="gaming">Gaming</option></select></td><td><select name="addmore['+i+'][bras]" class="form-control">';
        $.each(nas, function(index, value) {
            $html += '<option>'+value+'</option>';
        });
        $html += '</select></td><input type="hidden" name="addmore['+i+'][status]"><input type="hidden" name="addmore['+i+'][added_by]"><input type="hidden" name="addmore['+i+'][addede_by_ip]"><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
        $("#dynamicTable").append($html);
    });
    $(document).on('click', '.remove-tr', function(){  
        $(this).parents('tr').remove();
    });  
</script>
<script>
    $(document).ready(function() {
        $(".btn-submit").click(function(e){
            e.preventDefault();
            $.ajax({
                url: "{{route('admin.store_ips_data')}}",
                type:'POST',
                data: $( '#add-static-ip' ).serialize(),
                success: function(data) {
                    if($.isEmptyObject(data.error)){
// alert(data.success);
$(".print-error-msg").css('display','none');
$(".success-msg").css('display','block');
$('.success-msg').html(data.success);
// location.reload(3000);
setTimeout(function(){
    window.location.href = "{{route('admin.users.view_static_ip')}}";
}, 2000);
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