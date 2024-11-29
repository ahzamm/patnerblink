@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <!-- SIDEBAR - START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row">
      <div class="col-lg-12">
        <div class="header_view">
          <h2>Disabled (But Online Consumers)</h2>
        </div>
        <!--  -->
        <div class="col-lg-12">
          <section class="box ">
            <!-- <header class="panel_header">
              <h2 class="title pull-left"></h2>
            </header> -->
            <div class="content-body">
              <div class="row">
                <div class="col-md-12">
                  <form>
                    <div style="overflow-x: auto;">
                      <table id="example-1" class="table display">
                        <thead>
                          <tr>
                            <th style="width:25px">Serial#</th>
                            <th>Username</th>
                            <th>Internet Profile</th>
                            <th>Contractor</th>
                            <th>Session Date & Time</th>
                            <th>Expiry Date</th>
                            
                            
                            
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
                            <td>{{$users['dealerid']}}</td>
                            <td>{{$data['acctstarttime']}}</td>
                            <td>{{$users['card_expire_on']}}</td>
                            
                          </tr>
                          @php
                          }
                          @endphp
                        </tbody>
                      </table>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </section></div>
          <!--  -->
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
    <!-- END CONTENT -->
  </div>
  @include('admin.users.model_add_support')
  @endsection
  @section('ownjs')
  <script>
  $(document).ready(function(){
  setTimeout(function(){
  $('.alert').fadeOut(); }, 3000);
  });
  </script>
  @endsection