@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
        <div class="header_view">
          <h2>Consumer (ID) Disabled <small style="color: lightgray">(But Online)</small>
          <span class="info-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
          </h2>
        </div>
        <section class="box">
        <div class="content-body">
            <form>
                <table id="example-1" class="table display dt-responsive w-100">
                <thead>
                    <tr>
                    <th style="width:25px">Serial#</th>
                    <th>Consumer (ID)</th>
                    <th>Internet Profile</th>
                    <th>Reseller (ID)</th>
                    <th>Contractor (ID)</th>
                    <th>Session Date & Time</th>
                    <th>Expiry Date</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $sno=1;
                    
                    foreach($allusers as $data){
                    $users=App\model\Users\UserInfo::leftJoin('user_status_info', function($join1) {
                    $join1->on('user_info.username', '=', 'user_status_info.username');
                    })->where('user_info.username','=',$data['username'])->first(['user_info.profile','user_info.dealerid','user_status_info.card_expire_on']);
                    @endphp
                    <tr>
                    <td>{{$sno++}}</td>
                    <td class="td__profileName">{{$data['username']}}</td>
                    <td>{{$users['profile']}}</td>
                    <td>{{$users['resellerid']}}</td>
                    <td>{{$users['dealerid']}}</td>
                    <td>{{$data['acctstarttime']}}</td>
                    <td>{{$users['card_expire_on']}}</td>
                    <td><a href="#" class="btn btn-danger brn-xs">Kick Consumer (ID)</a></td>
                    </tr>
                    @php
                    }
                    @endphp
                </tbody>
                </table>
            </form>
        </div>
        </section>
      </section>
    </section>
  </div>
  @endsection
