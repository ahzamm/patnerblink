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
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      @include('users.layouts.session')
      <div class="">
        <div class="col-lg-12">
          <a href="{{('#myModal-4')}}" data-toggle="modal">  <button class="btn btn-primary" style="border: 1px solid black"><i class="fa fa-users-cog"></i> Add Trader </button></a>
          <div class="header_view">
            <h2>Traders
              <span class="info-mark" onmouseenter="popup_function(this, 'traders');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
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
          <table id="example-1" class="table table-striped dt-responsive display" style="width:100%">
            <thead>
              <tr>
                <th>Serial#</th>
                <th>Username</th>
                <th>Full Name</th>
                <th class="desktop">Mobile Number</th>
                <th class="desktop">CNIC Number</th>
                <th class="desktop">Trader (ID)</th>
                <th class="desktop">Total Consumers</th>
                <th>Actions</th>
              </tr>
            </thead>
            @php
            $count=1;
            @endphp
            <tbody>
              @foreach($subdealerCollection as $data)
              <?php
              $mob = '';
              $cnic = '';
              $ntn = '';
              $nicF = '';
              $nicB = '';
              $passport = '';
              $overseas = '';
              $verified = '';
              $isverify = App\model\Users\UserVerification::where('username',$data->username)->select('mobile_status','cnic','ntn','overseas','intern_passport','nic_front','nic_back')->first();
              $mob = @$isverify['mobile_status'];
              $cnic = @$isverify['cnic'];
              $ntn = @$isverify['ntn'];
              $nicF = @$isverify['nic_front'];
              $nicB = @$isverify['nic_back'];
              $passport = @$isverify['intern_passport'];
              $overseas = @$isverify['overseas'];
              if($cnic != ''){
                $verified = $cnic;
              }elseif($ntn != ''){
                $verified = $ntn;
              }elseif($overseas != ''){
                $verified = $overseas;
              }elseif($passport != ''){
                $verified = $passport;
              }
//
              $activeUserCount  = DB::table('user_info')
              ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
              ->where('sub_dealer_id',$data->sub_dealer_id)
              ->where('user_info.status','=','user')
              ->count();
//
              ?>
              <tr>
                <td>{{$count++}}</td>
                <td class="td__profileName">{{$data->username}}<br>
                </td>
                <td>{{$data->firstname}} {{$data->lastname}}</td>
                <td>
                  @if($data->mobilephone != '')
                  <a href="tel:{{$data->mobilephone}}" class="cta_btn"> <i class="fa fa-phone"></i> {{$data->mobilephone}}</a>
                  @endif
                </td>
                <td>{{$data->nic}}</td>
                <td>{{$data->sub_dealer_id}}</td>
                <td>{{$activeUserCount}}</td>
                <td>
                  <center>
                    <a href="{{route('users.user.show',['status' => 'subdealer','id' => $data->id])}}" >
                      <button class="btn btn-primary btn-xs"><i class="fa fa-eye"> </i> View</button>
                    </a>
                    <a href="{{route('users.user.edit',['status' => 'subdealer','id' => $data->id])}}">
                      <button class="btn btn-info mb1 bg-olive btn-xs"><i class="fa fa-edit"> </i> Edit</button>
                    </a>
                    <a href="{{ route('users.user.previewlogin', ['username' => $data->username]) }}" target="_blank">
                        <button class="btn btn-danger mb1 bg-olive btn-xs">
                            <i class="fa fa-shield"></i> Login
                        </button>
                    </a>
                    <div class="dropdown action-dropdown">
                      <button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                      <div class="dropdown-menu action-dropdown_menu">
                        <ul>
                          <li class="dropdown-item">
                            <a href="{{route('users.user.show',['status' => 'subdealer','id' => $data->id])}}" ><i class="la la-eye"></i> View</a>
                          </li>
                          <li class="dropdown-item">
                            <a href="{{route('users.user.edit',['status' => 'subdealer','id' => $data->id])}}"><i class="la la-edit"> </i> Edit</a>
                          </li>
                          <hr style="margin-top:0">
                          <li class="dropdown-item">
                            <?php if($verified == '' || $nicF == '' ||$nicB == ''){ ?>
                              <form action="{{route('users.user.nicVerify')}}" method="POST" style="margin-bottom: 2px;margin-top:3px;display:inline-block">
                                @csrf
                                <input type="hidden" name="username" id="username" value="{{$data->username}}">
                                <button type="submit"><i class="las la-exclamation-triangle"></i> CNIC<span style="color:red"> (Not Verified)</span></button>
                              </form>
                            <?php }else{?>
                              <button disabled> <i class="la la-check"></i> CNIC <span style="color:darkgreen">(Verified)</span> </button>
                            <?php } ?>
                          </li>
                          <li class="dropdown-item">
                            <?php if($mob != 1){ ?>
                              <form action="{{route('users.user.smsverify')}}" method="POST" style="margin-bottom: 0;margin-top:3px;display:inline-block">
                                @csrf
                                <input type="hidden" name="username" id="username" value="{{$data->username}}">
                                <button type="submit"><i class="las la-exclamation-triangle"></i> Mobile <span style="color:red"> (Not Verified)</span></button>
                              </form>
                            <?php }else{?>
                              <button disabled> <i class="la la-check"></i>  Mobile <span style="color:darkgreen">(Verified)</span> </button>
                            <?php } ?>
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
                          <li class="dropdown-item">
                            <button class="agent-account" data-id="{{$data->id}}" data-username="{{$data->username}}" data-status="{{$data->account_disabled}}">
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
    </section>
  </section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
  function showpass() {
    var x = document.getElementById("inputPasswod");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
    $('.toggleClass').toggleClass('fa-eye fa-eye-slash');
  }
  function showpass2() {
    var x = document.getElementById("retypePasswod");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
    $('.toggleClass2').toggleClass('fa-eye fa-eye-slash');
  }
</script>
<script>
  $(document).ready(function(){
    setTimeout(function(){
      $('.alert').fadeOut(); }, 3000);
  });
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
<script >
  function checkavailablesubdealer(subdealerid){
    var val=$('#subdealerid').val();
    $('#subusername').val(val);
    if(val.length > 0){
      $.ajax({
        type: "POST",
        url: "{{route('users.checkunique.ajax.post')}}",
        data:'subdealerid='+subdealerid,
        success: function(data){
// For Get Return Data
$('#subdealercheck').html(data);
}
});
    }
    else{
      $('#subdealercheck').html('');
    }
  }
  function checkSubUser(subusername) {
    var val=$('#subusername').val();
    if(val.length > 0){
      $.ajax({
        type: "POST",
        url: "{{route('users.checkunique.ajax.post')}}",
        data:'username='+subusername,
        success: function(data){
// For Get Return Data
$('#subcheck').html(data);
}
});
    }
    else{
      $('#subcheck').html('');
    }
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

  document.querySelectorAll('a[target="_blank"]').forEach(link => {
    link.addEventListener('click', () => {
        const uniqueSessionId = 'tab_' + Math.random().toString(36).substring(2);
        localStorage.setItem('sessionId', uniqueSessionId);
    });
});

</script>
@endsection
@include('users.dealer.TraderFreeze')
<!-- Code Finalize -->
