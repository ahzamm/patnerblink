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
* Created: June, 2023
* Last Updated: 08th June, 2023
* Author: Talha Fahim & Hasnain Raza <info@squadcloud.co>
*-->
<!-- Code Onset -->
@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <a href="{{('#servicesModal')}}" data-toggle="modal" class="pr-3 mt-3">
        <button class="btn btn-default" style="border: 1px solid black">
          <i class="fa-solid fa-server"></i> Add Host Servers</button>
        </a>
        <div class="header_view">
          <h2>Servers Management
            <span class="info-mark" onmouseenter="popup_function(this, 'servers_management_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
          </h2>
        </div>
        <section class="box">
          @if(session('error'))
          <div class="alert alert-error alert-dismissible show">
            {{session('error')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          @endif
          @if(session('success'))
          <div class="alert alert-success alert-dismissible show">
            {{session('success')}}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          @endif
          <div class="content-body" style="padding-top: 20px">
            <div class="table-responsive">
              <table id="example-11" class="table dt-responsive w-100 display">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Server Host Name</th>
                    <th>Status</th>
                    <th>Latency</th>
                    <th>Domain Name</th>
                    <th>Server (IP Address)</th>
                    <th>Manage Services</th>
                    <th>CPU Usage (%)</th>
                    <th>Memory Usage (%)</th>
                    <th>Actions </th>
                  </tr>
                </thead>
                <tbody id="servertbody">
                  <tr><td colspan="10">Patience, pages are in the process of loading...</td></tr>
                </tbody>
              </table>
            </div>    
          </div>
        </section>
      </section>
    </section>
  </div>
  <!-- Add Services Modal Start-->
  <div class="modal fade" id="servicesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-lg" >
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel" style="text-align: center;color: white">Add Managed Server</h4>
        </div>
        <div class="modal-body text-center">
          <div class="" id="tblData">
            <div class="alert alert-danger print-error-msg" style="display:none">
              <ul></ul>
            </div>
            <div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
              <ul></ul>
            </div>
            <div class="">
              <form id="add-service" method="POST" action="{{route('admin.services.store')}}">
                @csrf
                <div class="row register-form">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Server Host Name <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_hostname_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="server_name" class="form-control" id="" placeholder="Example: Radus Server" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Domain Name <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_domain_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="domain_name" class="form-control" id="" placeholder="Example: xyz.com" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Host (IP Address) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_ipaddress_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="ip_address" class="form-control" id="" placeholder="Example: 192.168.0.10" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">API (URL) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_api_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="api_url" class="form-control" id="" placeholder="Example: https://xyz.com/index.php" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Host (Username) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_username_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="username" class="form-control" id="" placeholder="Example: root"  required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Host (Password) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_password_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="password" value="" name="passoword" class="form-control" id="" placeholder="Example: abc123"  required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Host (SSH Port) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_ssh_port_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="port" class="form-control" id="" placeholder="Example: 22" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Managed Services <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_manage_services_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select class="form-control" name="service">
                        <option>Select Managed Service</option>
                        <option>MySql</option>
                        <option>Ngnix</option>
                        <option>FreeRadius</option>
                        <option>PHP7.3</option>
                        <option>PHP7.4</option>
                        <option>PHP8.0</option>
                        <option>PHP8.1</option>
                        <option>PHP8.2</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Assign Start-Service (Command) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_service_command_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="start_cmd" class="form-control" id="" placeholder="Example: sudo systemctl start sshd.service" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Assign Stop-Service (Command) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_service_command_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="stop_cmd" class="form-control" id="" placeholder="Example: sudo systemctl stop sshd.service" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Assign Restart-Service (Command) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_service_command_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="restart_cmd" class="form-control" id="" placeholder="Example: sudo systemctl restart sshd.service" required>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="pull-right">
                      <input type="submit" class="btn btn-primary btn-submit"  value="Submit"/>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Add Services Modal End-->
  <!-- Delete Module Start -->
  <div class="modal fade" id="deleteModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h4>Do you realy want to delete this?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" id="deletbtn" class="btn btn-danger">Yes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete Module End -->
  @include('admin.services.update');
  @endsection
  @section('ownjs')
  <script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>
  <script type="text/javascript">
// $("#repair-id").submit(function() {
  $(document).on('click','.serviceBtn',function(){
    var action = $(this).attr('data-action');
    var id = $(this).attr('data-id');
    if(confirm('Do you really want to '+action+' ?')){
      $.ajax({
        type: "POST",
        url: "{{route('admin.services.action')}}",
        data:{
          action : action, id : id,
        },
        success: function (data) {
          alert(data);
        },
        error: function(jqXHR, text, error){
        }
      });
      return false;
    }
  });
</script>
<script type="text/javascript">
  $(document).on('click','.editBtn',function(){
    var id = $(this).attr('data-id');
    $.ajax({
      dataType: 'json', 
      type: "POST",
      url: "{{route('admin.services.edit')}}",
      data:{
        id : id,
      },
      success: function (data) {
        $('#update-service #id').val(data.id);
        $('#update-service #server_name').val(data.server_name);
        $('#update-service #domain_name').val(data.domain_name);
        $('#update-service #ip_address').val(data.ip_address);
        $('#update-service #api_url').val(data.api_url);
        $('#update-service #service').val(data.service);
        $('#update-service #start_cmd').val(data.start_cmd);
        $('#update-service #stop_cmd').val(data.stop_cmd);
        $('#update-service #restart_cmd').val(data.restart_cmd);
        $('#update-service #username').val(data.username);
        $('#update-service #password').val(data.password);
        $('#update-service #port').val(data.port);
      },
      error: function(jqXHR, text, error){
      },
      complete:function(){
        $('#servicesUpdateModal').modal('show');
      },
    });
    return false;
  })
</script>
<script type="text/javascript">
  $(document).on('click','.deleteBtn',function(){
    var id = $(this).attr('data-id');
    if(confirm('Do You Really Want To Delete This Server ?')){
      $.ajax({ 
        type: "POST",
        url: "{{route('admin.services.delete')}}",
        data:{
          id : id,
        },
        success: function (data) {
        },
        error: function(jqXHR, text, error){
        },
        complete:function(){
          location.reload();
        },
      });
      return false;
    }
  })
</script>
<script type="text/javascript">
  $(document).ready(function(){
// $(document).on('click','.deleteBtn',function(){    
  $.ajax({ 
    type: "POST",
    url: "{{route('admin.service_list')}}",
    success: function (data) {
      $('#servertbody').html(data);
    },
    error: function(jqXHR, text, error){
    },
    complete:function(){
      $('#example-11').dataTable();
    },
  });
  return false;
})
</script>
@endsection
<!-- Code Finalize -->