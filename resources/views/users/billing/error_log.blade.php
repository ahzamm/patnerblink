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
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT START -->
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="header_view">
        <h2>Invalid Login
          <span class="info-mark" onmouseenter="popup_function(this, 'invalid_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      @include('users.layouts.session')
      <table id="example-1" class="table table-striped dt-responsive display" style="width:100%">
        <thead>
          <tr>
            <th>Serial#</th>
            <th>Consumer (ID)</th>
            <th>Mobile Number</th>
            <th>Assigned (MAC Address) </th>
            <th>Requesting (MAC Address)</th>
            <th>Valid Reason</th>
            <th>Attempted Password</th>
            <th>Login Attempt</th>
            <th>Login Time</th>
            <th>Actions</th>
          </tr>
        </thead>
        @php
        $sno=1;
        @endphp
        <tbody>
          @foreach($error_log as $value)
          @php
          $radcheckmac = App\model\Users\RadCheck::where(['username'=> $value->username])->where(['attribute'=>'Calling-Station-Id'])->first();
          $check_mac = $radcheckmac->value;
          $radacctmac = App\model\Users\RadPostauth::where(['username' => $value->username])->orderBy('id','DESC')->first();
          if($radacctmac){
          $act_mac = $radacctmac->mac;
        }else{
        $act_mac ='Not Yet Login';
      }
      $count=App\model\Users\RadPostauth::where(['reply'=>'Access-Reject','username'=>$value->username])->count();
      $user_data=App\model\Users\UserInfo::where(['status'=>'user','username'=>$value->username])->first();
      $mac  =App\model\Users\RadCheck::where(['username' => $user_data->username, 'attribute'=>'Calling-Station-Id'])->first();
      @endphp
      <tr>
        <td>{{$sno++}}</td>
        <td class="td__profileName">{{$value->username}}</td>
        <td>{{$user_data->mobilephone}}</td>
        @php 
        $user_data_id=App\model\Users\UserInfo::where(['status'=>'user','username'=>$value->username])->first();
        @endphp
        <td>{{$check_mac}}</td>
        <td>{{$act_mac}}</td>
        <td>{{$radacctmac->rejectreason}}</td>
        <td>{{$radacctmac->pass}}</td>
        <td>{{$count}}</td>
        <td>{{ date('M d,Y H:i:s',strtotime($radacctmac->authdate)) }}</td>
        <td>
          <a href="{{url('users/users',['status'=>'user'])}}/{{$user_data->id}}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a>
          <div class="dropdown action-dropdown">
            <button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
            <div class="dropdown-menu action-dropdown_menu">
              <ul>
                <li class="dropdown-item">
                  <a href="{{url('users/users',['status'=>'user'])}}/{{$user_data->id}}"><i class="la la-edit"></i> Edit</a>
                </li>
                <hr style="margin-top: 0">
                <li class="dropdown-item">
                  @if($mac->value!='NEW')
                  <form action="{{route('users.billing.user_detail')}}" method="POST" >
                    @csrf
                    <input type="hidden" name="clearmac" value="{{$user_data->username}}">
                    <input type="hidden" name="userid" value="{{$user_data->id}}">
                    <button type="submit">Clear Mac Address</button>
                  </form>
                  @endif
                </li>
                <li class="dropdown-item">
                  <button class="userpaswd" onclick="change_pass('{{$user_data_id->id}}','{{$user_data_id->username}}')"> Change Password</button>
                </li>
              </ul>
            </div>
          </div>
          {{-- @if($mac->value!='NEW')
          <form action="{{route('users.billing.user_detail')}}" method="POST" >
            @csrf
            <input type="hidden" name="clearmac" value="{{$user_data->username}}">
            <input type="hidden" name="userid" value="{{$user_data->id}}">
            <button type="submit" class="btn btn-xs btn-success" style="margin-bottom:5px">Clear Mac Address</button>
          </form>
          @endif 
          <button class="btn btn-xs btn-primary userpaswd" onclick="change_pass('{{$user_data_id->id}}','{{$user_data_id->username}}')" style="margin-bottom:5px"> Change Password</button> --}}
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div aria-hidden="true"  role="dialog" tabindex="-1" id="changeuserPass" class="modal fade" style="display: none;">
    <div class="col-md-4"></div>
    <div class="col-md-4"> 
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
            <h4 class="modal-title" style="text-align: center;color: white">Change User Password</h4>
          </div>
          <div class="modal-body" style="padding-top:15px;padding-bottom:0px;">
            <div class="row">
              <div class="col-md-12" >
                <h3 class="text-center" style="font-weight: bold" id="user-name-data"></h2>
                  <hr style="margin-top: 20px;background-color: #0d4dab87">
                  <form action="{{route('users.billing.change.user.pass')}}" method="POST" class="form-group">
                    @csrf
                    <input type="hidden" name="user" value="" id="user-id-data">
                    <div class="form-group" style="position:relative">
                      <label for="pass">New Password <span style="color:red">*</span></label>
                      <input type="password" name="pass" id="password" class="form-control" placeholder="Password must be 8 characters long"> <i class="fa fa-eye-slash password" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('password');" > </i>
                    </div>
                    <div class="form-group" style="position:relative">
                      <label for="pass">Retype Password <span style="color:red">*</span></label>
                      <input type="password" name="repass" id="confirm_password" class="form-control" placeholder="Password must be 8 characters long"> <i class="fa fa-eye-slash confirm_password" style="position: absolute;top: 35px;right: 12px;" onclick="togglePassword('confirm_password');" > </i>
                      <span id='message'></span>
                    </div>
                    <div class="form-group pull-right">
                      <button type="submit" id="btnPass" class="btn btn-primary" >Update</button>
                      <button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
              </div> --}} -->
            </div>
          </div>
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
<script type="text/javascript">
  function change_pass(id,username){
    $('#changeuserPass').modal('show');
    var id_user = $('#user-id-data').val(username);
    var username = $('#user-name-data').text(username);
  }
</script>
@endsection
<!-- Code Finalize -->