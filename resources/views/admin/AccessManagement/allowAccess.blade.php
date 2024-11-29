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
@section('owncss')
<style type="text/css">
  .dataTables_filter{
    margin-left: 60%;
  }
  .slider:before {
    position: absolute;
    content: "";
    height: 11px !important;
    width: 13px !important;
    left: 3px !important;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }
  #mob select,
  #app select,
  #res select,
  #nic select,
  #username select,
  #sno select,
  #ip select{
    display: none;
  }
  #snackbar {
    visibility: hidden;
    min-width: 250px;
    margin-left: -125px;
    background-color: #3CB371;
    color: #fff;
    text-align: center;
    border-radius: 2px;
    box-shadow: 0 15px 10px #777;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 85%;
    top: 70px;
    font-size: 17px;
  }
  #snackbar.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
  }
  @-webkit-keyframes fadein {
    from {top: 0; opacity: 0;} 
    to {top: 70px; opacity: 1;}
  }
  @keyframes fadein {
    from {top: 0; opacity: 0;}
    to {top: 70px; opacity: 1;}
  }
  @-webkit-keyframes fadeout {
    from {top: 70px; opacity: 1;} 
    to {top: 0; opacity: 0;}
  }
  @keyframes fadeout {
    from {top: 70px; opacity: 1;}
    to {top: 0; opacity: 0;}
  }
</style>
@endsection
@section('content')
<div id="snackbar">Access Setting Successfully Changed</div>
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="">
        <div class="col-lg-12">
          <div class="header_view">
            <h2> Access Management<small></small></h2>
          </div>
          <div>
            <section class="box ">
              <div class="content-body">
                <div class="row">
                  <hr/> 
                  <div  class="page-sidebar-wrapper col-md-12" id="main-menu-wrapper">
                    <ul class='wraplist'>
                      @foreach($allData as $data)
                      <li>
                        <a href="javascript:;">
                          <i class="fa fa-user"></i>
                          <span class="title h4">{{$data->username}} ({{$data->status}})</span>
                          <span class="arrow "></span>
                        </a>
                        <ul class="sub-menu">
                          <div style="height:300px; margin-left: 20px; overflow-y:scroll">
                            <table class="table table-striped  display" style="height:30%">
                              <thead>
                                <tr>
                                  <th>Serial#</th>
                                  <th>Menu</th>
                                  <th>Module</th>
                                  <th>Action On & Off</th>
                                </tr>
                              </thead>
                              <tbody>
                                @php
                                $num=1;
                                @endphp
                                @foreach($access as $datas)
                                <tr>
                                  <td>{{$num++}}</td>
                                  <td>{{$datas->parentModule}}</td>
                                  <td>{{$datas->childModule}}</td>
                                  <td>
                                    @php
                                    $check ='';
                                    $loadData = App\model\admin\Access::where(['childModule' => $datas->childModule])->first();
                                    if(!empty($loadData)){
                                    $check = $loadData['user'.$data->id];
                                  }
                                  $isCheck = $check;     
                                  @endphp
                                  @if($isCheck == 0)
                                  <label class="switch" style="width: 46px;height: 19px;">
                                    <input type="checkbox" name="chk" onchange="changeAccess(this, '{{$data->id}}','{{$datas->childModule}}');myFunction()">
                                    <span class="slider square" ></span>
                                  </label>
                                  @elseif($isCheck == 1)
                                  <label class="switch" style="width: 46px;height: 19px;">
                                    <input type="checkbox" checked  name="chk" onchange="changeAccess(this, '{{$data->id}}','{{$datas->childModule}}');myFunction()">
                                    <span class="slider square" ></span>
                                  </label>
                                  @else
                                  <label class="switch" style="width: 46px;height: 19px;">
                                    <input type="checkbox"   name="chk" onchange="changeAccess(this, '{{$data->id}}','{{$datas->childModule}}');myFunction()">
                                    <span class="slider square" ></span>
                                  </label>
                                  @endif
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </ul>
                    </li>
                    @endforeach
                  </ul>
                </div>
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
      <!-- CONTENT START -->
    </div>
    <!---Model Dialog --->
    {{-- @include('users.reseller.model_dealer')--}}
    @endsection
    @section('ownjs')
    <script type="text/javascript">
      $(document).ready(function() {
        var table = $('#example1').DataTable();
        $("#example1 thead td").each( function ( i ) {
          var select = $('<select class="form-control"><option value="">Show All</option></select>')
          .appendTo( $(this).empty() )
          .on( 'change', function () {
            table.column( i )
            .search( $(this).val() )
            .draw();
          } );
          table.column( i ).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
          } );
        } );
      } );
    </script>
    <script>
      function changeAccess(checkBox,id,childModule)
      {
        let isCheck = checkBox.checked;
        $.ajax({
          url:"{{route('admin.AccessManagement.userAccess')}}",
          method:"POST",
          data:{isChecked:isCheck, id:id,child:childModule},
          success:function(data){
          }
        });
      }
    </script>
    <script>
      function myFunction() {
        var x = document.getElementById("snackbar");
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
      }
    </script>
    @endsection
<!-- Code Finalize -->