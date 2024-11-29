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
  #ip select{
    display: none;
  }
  #sno select{
    display: none;
  }
  #username select{
    display: none;
  }
  #nic select{
    display: none;
  } 
  #res select{
    display: none;
  } 
  #app select{
    display: none;
    }#mob select{
      display: none;
    }
  </style>
  @endsection
  @section('content')
  <div class="page-container row-fluid container-fluid">
    <section id="main-content">
      <section class="wrapper main-wrapper">
        <a href="#add_user" data-toggle="modal" >
          <button class="btn btn-default"><i class="fa fa-people-arrows"></i> Add Management Account</button>
        </a>
        <div class="header_view">
          <h2>Management Members
            <span class="info-mark" onmouseenter="popup_function(this, 'management_member_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
          </h2>
        </div>
        <div class="" id="tablediv">
          <section class="box ">     
            <header class="panel_header">
              <center><h3><p class="profile-title">Active Admins</p></h3> </center>
            </header>
            <!-- Enable Admin Table Start -->
            <div class="content-body">          
              <table id="example-1" class="table table-striped dt-responsive display w-100">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Status</th>
                    <th>Email Address</th>
                    <th>Mobile Number</th>
                    <th>Actions</th>
                  </tr>              
                </thead>
                <tbody>
                  @php
                  $num=1;
                  @endphp
                  @foreach($allData as $data)
                  <tr>
                    <td>{{$num++}}</td>
                    <td class="td__profileName">{{$data->username}}</td>
                    <td>{{$data->firstname.' '.$data->lastname}}</td>
                    <td>{{$data->status}}</td>
                    <td>{{$data->email}}</td>
                    <td>{{$data->mobilephone}}</td>
                    <td>
                      <center>
                        <a href="{{url('admin/view/'.$data->id)}}" class="btn btn-primary btn-xs" style="margin-bottom:4px"><i class="fa fa-eye"> </i> View
                        </a> 
                        <button onclick="showEditModal({{$data->id}})" data-toggle="modal" class="btn btn-info mb1 bg-olive btn-xs" style="margin-bottom:4px">
                          <i class="fa fa-edit"> </i> Edit
                        </button>                    
                        <button class="btn btn-danger btn-xs" style="margin-bottom:4px" value="{{$data->id}}" onclick="deleteit(this.value)" >
                          <i class="fa fa-trash"> </i> Delete</button>                                                                    
                          @if($data->enable == "1")                           
                          <button class="btn btn-danger btn-xs" style="margin-bottom:4px;background-color: #ed7b73" value="{{$data->id}}" onclick="disableit(this.value)">
                            <i class="fa fa-ban"> </i> Disable</button>                          
                            @else
                            <button class="btn btn-success btn-xs" style="margin-bottom:4px;" value="{{$data->id}}" onclick="enableit(this.value)">
                              <i class="fa fa-plus"> </i> Enable</button>                          
                              @endif                          
                              <a href="{{url('admin/admin-role/'.$data->id)}}">
                                <button class="btn btn-secondary btn-xs" style="margin-bottom:4px;" >
                                  <i class="fa fa-lock"> </i></button>
                                </a>
                              </center>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>             
                    </div>         
                  </section>
                  <!-- Enable Admin Table End-->
                </div>
              </section>
            </section>
          </div>
          <!-- Delete Modal Start -->
          <div class="modal fade" id="deleteModel" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-body">
                  <h4>Do you realy want to delete this? </h4>
                </div>
                <div class="modal-footer">
                  <button type="button" id="deletbtn" onclick="deletethis(this.value);" class="btn btn-danger">Yes</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Delete Modal End -->
          <!-- Disable Modal Start -->
          <div class="modal fade" id="disableModel" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-body">
                  <h4>Do you realy want to disable this? </h4>
                </div>
                <div class="modal-footer">
                  <button type="button" id="disablebtn" onclick="disablethis(this.value);" class="btn btn-danger">Yes</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Disable Modal End -->
          <!-- Enable Modal Start -->
          <div class="modal fade" id="enableModel" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-body">
                  <h4>Do you realy want to enable this? </h4>
                </div>
                <div class="modal-footer">
                  <button type="button" id="enablebtn" onclick="enablethis(this.value);" class="btn btn-success">Yes</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Enable Modal End -->
          {{-- @include('users.reseller.model_dealer')--}}
          @endsection
          @section('ownjs')
          <script type="text/javascript">
            function deleteit(id){
              $('#deletbtn').val(id);
              $('#deleteModel').modal('show');
            }
            function disableit(id){
              $('#disablebtn').val(id);
              $('#disableModel').modal('show');
            }
            function enableit(id){
              $('#enablebtn').val(id);
              $('#enableModel').modal('show');
            }
            function deletethis(val) {
              $.ajax({
                type: "POST",
                url: "{{route('admin.AccessManagement.delete')}}",
                data:'id='+val,
                success: function(data){    
                  $('#deleteModel').modal('hide');
                  $('#tablediv').load(" #tablediv");
                }
              });
            }
            function disablethis(val) {  
              $.ajax({
                type: "POST",
                url: "{{route('admin.AccessManagement.disable')}}",
                data:'id='+val,
                success: function(data){    
                  $('#disableModel').modal('hide');
                  $('#tablediv').load(" #tablediv");
                }
              });
            }
            function enablethis(val) {          
              $.ajax({
                type: "POST",
                url: "{{route('admin.AccessManagement.enable')}}",
                data:'id='+val,  
                success: function(data){        
                  $('#enableModel').modal('hide');
                  $('#tablediv').load(" #tablediv");
                }
              });
            }
          </script>
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
            $(document).ready(function() {
              var table = $('#tbl_enable').DataTable();
              $("#tbl_enable thead td").each( function ( i ) {
                var select = $('<select class="form-control"><option value="">Show All</option></select>')
                .appendTo( $(this).empty() )
                .on( 'change', function () {
                  table.column( i )
                  .search( $(this).val())
                  .draw();
                } );
                table.column( i ).data().unique().sort().each( function ( d, j ) {
                  select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
              } );
            } );
          </script>
          <script>
            function showEditModal($id){
              $.ajax({
                type : 'get',
                url : "{{route('admin.AccessManagement.edit')}}",
                data:'id='+$id,
                success:function(res){
                  $('#editModal').html(res);
                  $('#edit_user').modal('show');
                }
              });
            }
          </script>
          @endsection
<!-- Code Finalize -->