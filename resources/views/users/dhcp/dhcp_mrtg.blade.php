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
@section('owncss')
<style type="text/css">
  .ttable{
    overflow: hidden;
    overflow-y: scroll;
    max-height: 350px;
    width: 100%;
  }
  #mrtg__graph,
  #dhcp__server{
    display:none
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Manage MRTG Graph / DHCP Server
          <span class="info-mark" onmouseenter="popup_function(this, 'mrtg_dhcp_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box" style="padding: 15px;">
        @if(session('error'))
        <div class="alert alert-error alert-dismissible">
          {{session('error')}}
        </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
          {{session('success')}}
        </div>
        @endif
        <form action="{{route('users.postdhcpAli')}}" method="POST">
          @csrf
          <div class="row">
            <div class="col-xs-12">
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label  class="form-label">Contractor <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'dhcp_mrtg_select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select name="dealers" id="dealers" required class="form-control">
                    <option value="">--- Select Contractor ---</option>
                    @foreach ($dealer as $item)
                    <option value="{{$item->dealerid}}">{{$item->dealerid}} | Contractor of : {{$item->resellerid}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label  class="form-label">Trader <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'dhcp_mrtg_select_trader_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select name="subdealers" id="subdealers" class="form-control">
                    <option value="">--- First Select Contractor ---</option>  
                  </select>
                </div>
              </div>
              <div class="col-lg-4">
                <label  class="form-label">What do you want to add ? <span style="color: red">*</span></label></br>
                <div class="btn-group" role="group" aria-label="Basic example" style="margin-bottom:12px">
                  <button type="button" id="mrtg__btn" onclick="toggleFunction('mrtg')" class="btn btn-secondary" style="margin-right:3px">MRTG Port (ID)</button>
                  <button type="button" id="dhcp__btn" onclick="toggleFunction('dhcp')" class="btn btn-secondary">DHCP Server</button>
                </div>
              </div>
              <div class="col-md-6" id="mrtg__graph">
                <div class="form-group position-relative">
                  <label  class="form-label">Assign MRTG Port (ID) <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'mrtg_id_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="number" name="graph" value="" class="form-control" placeholder="Example : 555" >
                </div>
              </div>
              <div class="col-md-6" id="dhcp__server">
                <label for="" class="form-label">Assign (DHCP) server <span style="color: red">*</span></label>
                <span class="helping-mark" onmouseenter="popup_function(this, 'dhcp_server_select_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                <select name="dhcp_serverip" id="" class="form-control">
                  <option value="">--- Select (DHCP) Server ---</option>
                  <option value="0 none">None</option>
                  @foreach ($dhcp_server as $item)
                  <option value="{{$item['id'] }} {{$item['name']}}">{{$item['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-12" style="padding-top: 15px">
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
              </div>
            </div>
          </div>
        </form>
      </section>
      <div class="row">
        <div class="col-xs-12">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Assigned MRTG Graph Port (IDs)</a></li>
            <li><a data-toggle="tab" id="DDU" href="#dhcp">Assigned (DHCP) Servers</a></li>
          </ul>
          <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
              <div class="" >
                <table id="dt_table-1" class="table table-bordered table-hover dt-responsive w-100 display">
                  <thead>
                    <tr>
                      <th>Contractor (ID)</th>
                      <th>Graph Port (ID)</th>
                      <th>Created Date & Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $totalPkg = 0;
                    @endphp
                    @foreach ($cacti as $key => $cacti)
                    <tr>
                      <td class="td__profileName">{{$cacti->user_id}}</td>
                      <td>{{$cacti->graph_no}}</td>
                      <td>{{date('M d,Y H:i:s',strtotime($cacti->created_on))}}</td>
                      <td>
                        <button class="btn btn-primary btn-xs show-graph-modal mb-5" data-id="{{$cacti->id}}" data-cat="cacti" data-toggle="modal">
                          <i class="fa fa-eye"></i> View Graph</button>
                          <a class="btn btn-danger btn-xs deleteit mb-5" data-id="{{$cacti->id}}" data-cat="cacti"><i class="fa fa-trash"></i> Delete</a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="dhcp" class="tab-pane">
                  <div class="table-responsive">
                    <table id="example-2" class="table table-bordered table-hover ">
                      <thead>
                        <tr>
                          <th>Contractor (ID)</th>
                          <th>DHCP Server Name</th>
                          <th>Created Date & Time</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($dhcp_table as $item)
                        @php
                        $serverName = App\model\Users\Dhcp_server::where('id',$item->server_id)->first();
                        @endphp
                        <tr>
                          <td class="td__profileName">{{$item->dealerid}}</td>
                          <td>{{$serverName['name']}}</td>
                          <td>{{date('M d,Y H:i:s',strtotime($item->created_on))}}</td>
                          <td><a class="btn btn-danger btn-xs deleteit" data-id="{{$item->id}}" data-cat="dhcp"><i class="fa fa-trash"></i> Delete</a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </section>
    </div>
    <div class="modal" id="graph_modal">
      <div class="modal-dialog modal-width">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-white" style="color: #fff"><span id="username" style="color:#fff;font-weight:bold;"></span></h4>
            <input type="hidden" id="unique_id">
            <button class="btn single-refresh" id="refresh_button" title="refresh"><i class="fa fa-refresh"></i> Refresh</button>
          </div>
          <!-- Modal Body -->
          <div class="modal-body image-graph-model" style="padding-bottom:0px">
            <img src="" style="width: 100%;margin-bottom:5px" id="single-graph" class="img img-responsive dynamic_graph_img-single">
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    @endsection
    @section('ownjs')
    <script>
      $(window).load(function() {
        console.log('Window ready');
        $('#dt_table-1').DataTable();
        $('#example-2').DataTable();
      })
      $(document).ready(function(){
        setTimeout(function(){ 
          $('.alert').fadeOut(); }, 4000);
      });
    </script>
    <script type="text/javascript">
      $('#dealers').on('change', function () {
        var dealer = this.value;
        $("#subdealers").html('');
        $.ajax({
          url: "{{ route('users.getSubDealer') }}",
          type: "POST",
          data: {
            dealer: dealer,
            _token: "{{ csrf_token() }}"
          },
          success: function(data) {
            $('#subdealers').html('');
            $('#subdealers').append('<option value="">--- Select Trader ---</option>');
            $.each(data, function(key, value) {
              $('#subdealers').append('<option value="' + value.username + '">' + value
                .username + '</option>');
            });
          }
        });
      });
    </script>
    <script>
      function toggleFunction(name) {
        $(this).css('background-color', '#0d4dab');
        if(name === 'dhcp'){
          $('#dhcp__server').css('display', 'block');
          $('#dhcp__btn').css('background-color', '#0d4dab');
          $('#mrtg__btn').css('background-color', '#9e9e9e');
          $('#mrtg__graph').css('display', 'none');
        } else {
          $('#dhcp__server').css('display', 'none');
          $('#mrtg__btn').css('background-color', '#0d4dab');
          $('#dhcp__btn').css('background-color', '#9e9e9e');
          $('#mrtg__graph').css('display', 'block');
        }
      }
    </script>
    <script type="text/javascript">
      $(document).on('click','.deleteit',function(){
        var id = $(this).attr('data-id');
        var cat = $(this).attr('data-cat');
//
if(confirm("Do you really want to delete this?")){
  $.ajax({
    method: 'POST',
    url: "{{route('users.delete.dhcpmrtg')}}",
    data: {
      id : id,
      cat : cat,
      _token: '{{csrf_token()}}',
    },
    success: function(data){
      alert(data);
      location.reload();
    },
    complete: function(){
    }   
  });
}
});
</script>
<script>
  $(document).ready(function() {
    $('.show-graph-modal').on('click', function() {
      var cacti_id = $(this).attr('data-id');
      $.ajax({
        type: 'POST',
        data: {
          cacti_id: cacti_id
        },
        url: "{{route('user.show_mrtg_graph')}}",
        success: function(result) {
          console.log(result);
          var username = $('#username').text(result.data_cacti.user_id);
          var cacti_id = $('#unique_id').text(result.data_cacti.id);
          $('#graph_modal').modal('show');
          console.log(result.url);
          if ($.isEmptyObject(result.error)) {
            $('.dynamic_graph_img-single').attr('src', result.url);
          } else {
            printErrorMsg(result.error);
          }
        }
      })
    });
  });
  $('#refresh_button').on('click', function(e) {
    var cacti_id = $('#unique_id').text();
    $.ajax({
      type: 'POST',
      data: {
        cacti_id: cacti_id
      },
      url: "{{route('user.show_mrtg_graph')}}",
      success: function(result) {
        console.log(result.url);
        if ($.isEmptyObject(result.error)) {
          $('.dynamic_graph_img-single').attr('src', result.url);
        } else {
          printErrorMsg(result.error);
        }
      }
    })
  });
</script>
@endsection
<!-- Code Finalize -->