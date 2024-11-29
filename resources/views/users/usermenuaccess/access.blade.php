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
  .bottom {
    box-shadow: 0px 15px 10px -15px #111;    
  }
  .slider:before{
    height: 15px;
    left: 2px;
    bottom: 3px;
  }
  input:checked + .slider:before{
    transform: translateX(16px);
  }
</style>
@endsection
@section('title','User Access')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view text-center">
        <h2>Access Management 
          <span class="info-mark" onmouseenter="popup_function(this, 'access_management');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
        <small><h3><?= strtoupper($username);?></h3> </small>
        {{-- <small>(<?= strtoupper($userStatus)?>)</small> --}}
        @if($userStatus == 'dealer')
        <small>CONTRACTOR</small>
        @elseif($userStatus == 'subdealer')
        <small>TRADER</small>
        @else
        <small>(<?= strtoupper($userStatus)?>)</small>
        @endif
      </div>
      <div style="">
        <table class="table table-condensed dt-responsive display w-100" style="text-align: center;" id="allowAccessTable">
          <thead>
            <tr>
              <th>Serial#</th>
              <th style="text-align:left">Menu</th>
              <th style="text-align:left">Sub Menu</th>
              <th>Access</th>
            </tr>
          </thead>
          <tbody>
            @php 
            $num = 1;
            @endphp
            @foreach($userAccesses as $key => $userAccess)
            <tr>
              <td>
                {{$num}}
              </td>
              <td style="text-align: left !important; ">
                @php
                $menu =  App\model\Users\Menu::where('id',$userAccess->menu_id)->first();
                @endphp
                {{$menu->menu}}
              </td>
              <td style="text-align: left !important; ">
                {{$userAccess->submenu}}
              </td>
              <td>
                <?php
                $check = NULL; $status = 0;
                if(in_array($userAccess->sbID, $allow)){
                  $check = 'checked'; 
                  $status = 1;
                }
                ?>
                <div style="float: left; width: 100%;">
                  <label class="switch" style="width: 46px;height: 20px;">
                    <input type="checkbox" class="lcss_check"  {{$check}} data-value="{{$userAccess->id}}" data-id="{{$userAccess->sbID}}" user-id="{{$id}}" status="{{$status}}">
                    <span class="slider square" ></span>
                  </label>
                </div>
              </td>
            </tr>
            @php $num++; @endphp
            @endforeach
          </tbody>
        </table>
      </div>
    </section>
  </section>
</div>
@endsection
@section('ownjs')
<script>
  $('#allowAccessTable').DataTable();
  $(document).on('change','.lcss_check',function(){
    status =0;
    accessId = $(this).attr('data-value');
    submenuid = $(this).attr('data-id');
    user_id = $(this).attr('user-id');
    if($(this).prop("checked") == true){
      status = 1;
      console.log(status);
    }
    else if($(this).prop("checked") == false){
      status = 0;
      console.log(status);
    }
    $.ajax({
      type: 'POST',
      url: "{{route('useraccess.update')}}",
      data:{
        access_status : status, id:accessId,submenuid:submenuid,userid:user_id
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