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
@section('content')
@section('owncss')
<style type="text/css">
  .th{
    color: white !important;
  }
  .modal-body{
    padding: 8px !important;
  }
  .bottom {
    box-shadow: 0px 15px 10px -15px #111;    
  }
  .modal-dialog{
    overflow-y: initial !important
  }
  .modal-body{
    height: 68vh;
    overflow-y: auto;
  }
</style>
@endsection
@section('title','User Access')
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="">
        <div class="col-lg-12">
          <div class="header_view text-center">
            <h2>Trader Access Controls </h2>
          </div>
          <div class="table-responsive scrol">
            <table id="example-1" style="text-align: center !important;" class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="th">Serial#</th>
                  <th class="th">Username</th>
                  <th class="th">Status</th>
                  <th class="th">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $key => $user)
                <tr>
                  <td>{{$key+1}}</td>
                  <td>{{$user->username}}</td>
                  <td>{{$user->status}}</td>
                  <td>
                    <button class="btn btn-sm btn-primary btnShowAccessModal" data-value="{{$user->id}}"><i class="fa fa-lock"></i> Access Management</button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="chart-container " style="display: none;">
        <div class="" style="height:200px" id="platform_type_dates"></div>
        <div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
        <div class="" style="height:200px" id="user_type"></div>
        <div class="" style="height:200px" id="browser_type"></div>
        <div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
        <div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
      </div>
    </section>
  </section>
  <div class="modal fade" id="modalShowAccess" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bottom">
          <h3 class="modal-title" id="exampleModalScrollableTitle" style="color: white;font-weight: bold">User Access</h3>
        </div>
        <div class="modal-body" id="modalBody">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- CONTENT END -->
@endsection()
@section('ownjs')
<script>
  $(document).on('click','.btnShowAccessModal',function(){
    $('#modalShowAccess').modal('show');
    id = $(this).attr('data-value');
    $.ajax({
      type: 'GET',
      url: "{{route('users.manage.subdealerAccess')}}",
      data: {id:id},
      dataType:'html',
      success:function(res){
        $('#modalBody').html(res);
        $('.lcs_check').lc_switch();
      },
      error:function(jhxr,status,err){
        console.log(jhxr);
      },
      complete:function(){
      }
    })
  });
  $(document).on('lcs-statuschange','.lcs_check',function(){
    status =0;
    accessId = $(this).attr('data-value');
    submenuid = $(this).attr('data-id');
    if($(this).prop("checked") == true){
      status = 1;
      console.log(submenuid);
    }
    else if($(this).prop("checked") == false){
      status = 0;
      console.log(submenuid);
    }
    $.ajax({
      type: 'POST',
      url: "{{route('useraccess.update')}}",
      data:{
        access_status : status, id:accessId,submenuid:submenuid
      },
      dataType:'json',
      beforeSend:function(){
      },
      success:function(res){
        if(res.status)
        {
          Messenger().post({message:"Access Status Change Successfully.. !",type:"success"});
        }
      },
      error:function(jhxr,status,err){
        console.log(jhxr);
      },
      complete:function(){
      }
    })
  })
</script>
@endsection
<!-- Code Finalize -->