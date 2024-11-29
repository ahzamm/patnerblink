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
          <div class="header_view">
            <h2>Traders
              <span class="info-mark" onmouseenter="popup_function(this, 'traders');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div class="">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
              {{session('success')}}
            </div>
            @endif
            @if(count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <table id="example1" class="table table-striped dt-responsive display w-100">
              <thead>
                <tr>
                  <th>Serial#</th>
                  <th>Username</th>
                  <th>Full Name</th>
                  <th>Mobile Number</th>
                  <th>Assigned Area</th>
                  <?php if(Auth::user()->status == 'manager' || Auth::user()->status == 'inhouse'){?>
                    <th>Reseller (ID)</th>
                  <?php } ?>
                  <th>Contractor (ID)</th>
                  <th>Trader (ID)</th>
                  <th>Number of Consumers</th>
                  <th style="width:12%">Actions</th>
                </tr>
              </thead>
              @php
              $count=1;
              @endphp
              <tbody>
                @foreach($subdealerCollection as $data)
                <?php
                $activeUserCount  = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                ->where('sub_dealer_id',$data->sub_dealer_id)
                ->where('user_info.status','=','user')
                ->count();
                ?>
                <tr>
                  <td>{{$count++}}</td>
                  <td class="td__profileName">{{$data->username}}</td>
                  <td>{{$data->firstname}} {{$data->lastname}}</td>
                  <td style="white-space:nowrap">
                    @if(!empty($data->mobilephone))
                    <a href="tel:{{$data->mobilephone}}" class="cta_btn"> <i class="fa fa-phone"></i> {{$data->mobilephone}}</a>
                    @endif
                  </td>
                  <td>{{$data->area}}</td>
                  <?php if(Auth::user()->status == 'manager' || Auth::user()->status == 'inhouse'){?>
                    <td>{{$data->resellerid}}</td>
                  <?php } ?>
                  <td>{{$data->dealerid}}</td>
                  <td>{{$data->sub_dealer_id}}</td>
                  <td>{{$activeUserCount}}</td>
                  <td>
                    <center>
                      <a href="{{route('users.user.show',['status' => 'subdealer','id' => $data->id])}}">
                        <button class="btn btn-primary btn-xs">
                          <i class="fa fa-eye"> </i> View</button></a>
                          <?php if(Auth::user()->status != 'inhouse'){ ?>
                            <a href="{{route('users.user.edit',['status' => 'subdealer','id' => $data->id])}}"><button class="btn btn-info mb1 bg-olive btn-xs">
                              <i class="fa fa-edit"> </i> Edit
                            </button>
                          </a> 
                        <?php } ?>
                        <!-- Dropdown -->
                        <?php if(Auth::user()->status != 'inhouse'){ ?>
                          <div class="dropdown action-dropdown">
                            <button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                            <div class="dropdown-menu action-dropdown_menu">
                              <ul>
                                <li class="dropdown-item">
                                  <a href="{{route('users.user.show',['status' => 'subdealer','id' => $data->id])}}">
                                    <i class="la la-eye"> </i> View</a>
                                  </li>
                                  <li class="dropdown-item">
                                    <a href="{{route('users.user.edit',['status' => 'subdealer','id' => $data->id])}}"><i class="la la-edit"></i> Edit</a> 
                                  </li>
                                  <hr style="margin-top:0">
                                  <li class="dropdown-item">
                                    <a href="{{route('useraccess.show',$data->id)}}"><i class="la la-lock"></i> Access Management</a>
                                  </li>
                                  <li class="dropdown-item">
                                    @php
                                    @$freezeCheck = App\model\Users\FreezeAccount::where('username',$data->username)->select('freeze')->first();
                                    @endphp
                                    @if(@$freezeCheck['freeze'] == 'yes')
                                    <button class="freeze-trader"   data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{$freezeCheck['freeze']}}"><i class="la la-check"> </i> Unfreeze </button>
                                    @else
                                    <button class="freeze-trader" data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{isset($freezeCheck) ? $freezeCheck['freeze'] :'no'}}"><i class="la la-check"> </i> Freeze </button>
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
                          <?php } ?>
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
    @endsection
    @section('ownjs')
    <script type="text/javascript">
      $(document).ready(function() {
        var table = $('#example1').DataTable();
      } );
    </script>
    <script>
      function showFreezeModel($id){
        $.ajax({
          type : 'get',
          url : "{{route('users.freeze.subdealershow')}}",
          data:'id='+$id,
          success:function(res){
            if(res == 'parentFreezed'){
              alert("You can't unFreeze your subdealer");
            }else if(res.freeze == 'yes'){
              $('#subIdActive').text(res.username);
              $('#subIdTextActive').text(res.subdealerid);
              $('#freezeDate').text(res.freezeDate);
              $("#subdealerfreeze #usernameActive").val(res.username);
              $('#activeModel').show();
              $('#freezeModal').hide();
              $('#subdealerfreeze').modal('show');
            }else if(res.freeze == 'no'){
              $('#subId').text(res.username);
              $('#subIdText').text(res.subdealerid);
              $("#subdealerfreeze #username").val(res.username);
              $('#activeModel').hide();
              $('#freezeModal').show();
              $('#subdealerfreeze').modal('show');
            }else{
              $('#subId').text(res.username);
              $('#subIdText').text(res.sub_dealer_id);
              $("#subdealerfreeze #username").val(res.username);
              $('#activeModel').hide();
              $('#freezeModal').show();
              $('#subdealerfreeze').modal('show');
            }
          }
        });
      }
    </script>
    <script>
      $(document).on('click','.freeze-trader',function(){ 
        status =0;
        id = $(this).attr('data-id');
        username = $(this).attr('data-username');
        isChecked = $(this).attr('data-check');
        var status = 'Do you want to Freeze the Account Please Click on Freeze Now Button.';
        if(isChecked == 'yes')
        {
          var status = "Do you want to Active the Account Please Click on Active Now Button."
        }
        $('#status').text(status);
        $.ajax({
          type: 'GET',
          url : "{{route('users.freeze.tradershow')}}",
          data:{
            username:username,id:id,isChecked:isChecked,
          },
          dataType:'json',
          beforeSend:function(){
          },
          success:function(res){
            console.log(res.username);
            if(res == 'parentFreezed'){
              alert("You can't unFreeze your subdealer");
            }else if(res.freeze == 'yes'){
              $('#subIdActive').text(res.username);
              $('#subIdTextActive').text(res.subdealerid);
              $('#freezeDate').text(res.freezeDate);
              $("#usernameActive").val(res.username);
              $('#activeModel').show();
              $('#freezeModal').hide();
              $('#traderfreeze').modal('show');
            }else if(res.freeze == 'no'){
              $('#subId').text(res.username);
              $('#subIdText').text(res.subdealerid);
              $("#username").val(res.username);
              $('#activeModel').hide();
              $('#freezeModal').show();
              $('#traderfreeze').modal('show');
            }else{
              $('#subId').text(res.username);
              $('#subIdText').text(res.sub_dealer_id);
              $("#username").val(res.username);
              $('#activeModel').hide();
              $('#freezeModal').show();
              $('#traderfreeze').modal('show');
            }
          }
        })
      })
    </script>
    @endsection
    @include('users.dealer.TraderFreeze')
<!-- Code Finalize -->