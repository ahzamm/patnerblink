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
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="">
        <div class="">
          <a href="{{('#myModal-2')}}" data-toggle="modal">  <button class="btn btn-primary" style="border: 1px solid black"><i class="fa fa-user-tie"></i> Add Reseller </button></a>
          <div class="header_view">
            <h2>Resellers
              <span class="info-mark" onmouseenter="popup_function(this, 'reseller');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div class="">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
              {{session('success')}}
            </div>
            @endif
            @if(count($errors) > 0)
            <div class="alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <table id="example-1" class="table table-striped dt-responsive display w-100">
              <thead>
                <tr>
                  <th>Serial#</th>  
                  <th>Username</th>
                  <th>Full Name</th>
                  <th>Number of Contractors </th>
                  <th>Number of Traders  </th>
                  <th>Number of Consumers</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @php
                $count=1;
                @endphp
                @foreach($resellerCollection as $data)
                <?php
                $userCount  = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->where('user_status_info.card_expire_on', '>', DATE('Y-m-d H:i:s'))
                ->where('user_info.resellerid',$data->username)
                ->where('user_info.status','=','user')
                ->count();
                ?>
                <tr>
                  <td>{{$count++}}</td>
                  <td class="td__profileName">{{$data->username}}</td>
                  <td>{{$data->firstname}}</td>
                  <td>{{$data->reseller_dealer->count()}}</td>
                  <td>{{$data->reseller_sub_dealer->count()}}</td>
                  <td>{{$userCount}}</td>
                  <td>
                    <center><a href="{{route('users.user.show',['status' => 'reseller','id' => $data->id])}}" ><button class="btn btn-primary btn-xs">
                      <i class="fa fa-eye"> </i> View</button></a>
                      <a href="{{route('users.user.edit',['status' => 'reseller','id' => $data->id])}}">  <button class="btn btn-info mb1 bg-olive btn-xs">
                        <i class="fa fa-edit"> </i> Edit
                      </button></a>
                      {{-- @php
                        @$freezeCheck = App\model\Users\FreezeAccount::where('username',$data->username)->select('freeze')->first();
                        @endphp
                        @if(@$freezeCheck['freeze'] == 'yes')
                        <button class="btn btn-danger mb1 bg-olive btn-xs freeze"   data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{isset($freezeCheck) ? $freezeCheck['freeze'] :'no'}}"><i class="fa fa-check"> </i> Active Account </button>
                        @elseif(@$freezeCheck['freeze'] == 'yes')
                        <button class="btn btn-success mb1 bg-olive btn-xs freeze" data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{isset($freezeCheck) ? $freezeCheck['freeze'] :'no'}}"><i class="fa fa-check"> </i> Freeze Account </button>
                        @else
                        <button class="btn btn-success mb1 bg-olive btn-xs freeze" data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{isset($freezeCheck) ? $freezeCheck['freeze'] :'no'}}"><i class="fa fa-check"> </i> Freeze Account </button>
                        @endif  --}}
                        <div class="dropdown action-dropdown">
                          <button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                          <div class="dropdown-menu action-dropdown_menu">
                            <ul>
                              <li><a class="dropdown-item" href="{{route('users.user.show',['status' => 'reseller','id' => $data->id])}}"><i class="la la-eye"></i> View</a></li>
                              <li><a class="dropdown-item" href="{{route('users.user.edit',['status' => 'reseller','id' => $data->id])}}"><i class="la la-edit"></i> Edit</a></li>
                              <hr style="margin-top:0">
                              <li><a class="dropdown-item" href="{{route('users.my.sheetdownload',['managerid' => $data->manager_id,'resellerid' => $data->resellerid])}}"><i class="la la-download"></i> Download (CSV)</a></li>
                              <li><a class="dropdown-item" href="{{route('useraccess.show',$data->id)}}"><i class="la la-lock"></i> Access Management</a></li>
                              <li>
                                @php
                                @$freezeCheck = App\model\Users\FreezeAccount::where('username',$data->username)->select('freeze')->first();
                                @endphp
                                @if(@$freezeCheck['freeze'] == 'yes')
                                <button class="dropdown-item freeze"   data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{isset($freezeCheck) ? $freezeCheck['freeze'] :'no'}}"><i class="la la-check"> </i> Unfreeze </button>
                                @else
                                <button class="dropdown-item freeze" data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{isset($freezeCheck) ? $freezeCheck['freeze'] :'no'}}"><i class="la la-times"> </i> Freeze </button>
                                @endif
                              </li>
                              <li>
                                <button class="dropdown-item agent-account" data-id="{{$data->id}}" data-username="{{$data->username}}" data-status="{{$data->account_disabled}}">
                                  @if($data->account_disabled==1)
                                  <i class="las la-user-check"> </i> Active 
                                  @else 
                                  <i class="las la-user-alt-slash"> </i> Deactive 
                                @endif</button> 
                              </li>
                            </ul> 
                          </div>
                        </div>
                      </center>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </section>
  </div>
  @include('users.manager.model_reseler')
  @endsection
  @section('ownjs')
  <script>
    $(document).ready(function(){
      setTimeout(function(){ 
        $('.alert').fadeOut(); }, 3000);
    });
  </script>
  <script>
    $(document).on('click','.freeze',function(){
      status =0;
      id = $(this).attr('data-id');
      username = $(this).attr('data-username');
      isChecked = $(this).attr('data-check');
      var status = 'Do you want to Freeze the Account Please Click on Freeze Now Button.';
      if(isChecked == 'yes')
      {
        var status = "Do you want to Active the Account Please Click on Active Now Button."
      }
      var user_status ="reseller";
      $('#status').text(status);
      $.ajax({
        type: 'GET',
        url : "{{route('users.freeze.subdealershow')}}",
        data:{
          username:username,id:id,isChecked:isChecked,user_status:user_status,
        },
        dataType:'json',
        beforeSend:function(){
        },
        success:function(res){
          if(res == 'parentFreezed'){
            alert("You can't unFreeze your subdealer");
          }else if(res.freeze == 'yes'){
            $('#subIdActive').text(res.username);
            $('#subIdTextActive').text(res.subdealerid);
            $('#freezeDate').text(res.freezeDate);
            $("#usernameActive").val(res.username);
            $('#activeModel').show();
            $('#freezeModal').hide();
            $('#subdealerfreeze').modal('show');
          }else if(res.freeze == 'no'){
            $('#subId').text(res.username);
            $('#subIdText').text(res.subdealerid);
            $("#username").val(res.username);
            $('#activeModel').hide();
            $('#freezeModal').show();
            $('#subdealerfreeze').modal('show');
          }else{
            $('#subId').text(res.username);
            $('#subIdText').text(res.sub_dealer_id);
            $("#username").val(res.username);
            $('#activeModel').hide();
            $('#freezeModal').show();
            $('#subdealerfreeze').modal('show');
          }
        }
      })
    })
  </script>
  @endsection
  @include('users.dealer.subDealerFreeze')
<!-- Code Finalize -->