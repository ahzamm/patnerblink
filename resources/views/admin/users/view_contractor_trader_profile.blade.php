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
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <a href="{{('#add_contractor_trader_profile_modal')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fas fa-hdd"></i> Add New Internet Profile </button></a>
      <div class="header_view">
        <h2>Contractor & Trader Internet Profile Management
          <span class="info-mark" onmouseenter="popup_function(this, 'nas_management_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <div class="" id="tablediv">
        <div id="returnMsg"></div>
        <table id="example-1" class="table table-striped dt-responsive display w-100">
          <thead>
            <tr>
              <th>Serial#</th>
              <th>BRAS/NAS Short Name</th>
              <th>Contractor Internet Profile</th>
              <th>Trader Internet Profile</th>
              <th>Action </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($list as $key => $value){?>
              <tr>
                <td><?= $key+1;?></td>
                <td><?= $value->nas_shortname;?></td>
                <td><?= $value->contractor_profile?></td>
                <td><?= $value->trader_profile;?></td>
                <td>
                  <button class="btn btn-danger btn-sm" value="{{$value->id}}" onclick="deleteit(this.value)">
                    <i class="fa fa-trash"></i></button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </section>
    </section>
  </div>
  <!-- Delete Modal Start -->
  <div class="modal fade" id="deleteModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h4>Do you realy want to delete this?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" id="deletbtn" onclick="deletethis(this.value);" class="btn btn-danger">Yes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete Modal End -->
  @include('admin.users.add_contractor_trader_profile')
  @endsection
  @section('ownjs')
  <script type="text/javascript">
    function deleteit(id){
      $('#deletbtn').val(id);
      $('#deleteModel').modal('show');
    }
    function deletethis(val) {
      $.ajax({
        type: "POST",
        url: "{{route('admin.contractor_trader_profile.delete')}}",
        data:'id='+val,
        success: function(data){
          $('#deleteModel').modal('hide');
          $('#tablediv').load(" #tablediv");
        }
      });
    }
  </script>
  <script type="text/javascript">
    $("#addNewContTradProfile").submit(function() { 
      $.ajax({ 
        type: "POST",
        url: "{{route('admin.contractor_trader_profile.store')}}",
        data:$("#addNewContTradProfile").serialize(),
        success: function (data) {
          $('#add_contractor_trader_profile_modal').modal('hide');
          $('#returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
          setTimeout(
            location.reload()
            , 3000);
        },
        error: function(jqXHR, text, error){
          $('#add_contractor_trader_profile_modal').modal('hide');
          $('html, body').scrollTop(0);
          $('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
        },
        complete:function(){
        },
      });
      return false;
    })
  </script>
  @endsection  
<!-- Code Finalize -->