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
      <a href="{{route('admin.create.rad')}}" class="btn btn-default"><i class="fas fa-pen-alt"></i> Create Attribute</a>
      <div class="header_view">
        <h2>Radius (Rad-Group-Reply attributes)
          <span class="info-mark" onmouseenter="popup_function(this, 'radius_radgroupreply_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box ">
        <div class="content-body">
          <table  class="table table-bordered data-table dt-responsive display w-100" id="datatables">
            <thead>
              <tr>
                <th style="background-color: #0d4dab !important">Group Name </th>
                <th>Attribute</th>
                <th>OP</th>
                <th>Value</th>
                <th>Action</th>
              </tr> 
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </section>
    </section>
  </section>
</div>
@include('admin.RadGroupReply.update_pkg')
@endsection
@section('ownjs')
<script type="text/javascript" src="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css"></script>
<script type="text/javascript">
  $(function () {
    var table = $('.data-table').DataTable({
      processing: true,
      serverSide: true,
      ajax:{
        url: "{{route('admin.data.rad')}}",
      },
      columns: [
      { data: 'groupname' ,orderable : false, class:'td__profileName'},
      { data: 'attribute' ,orderable : false },
      { data: 'op' ,orderable : false},
      { data: 'value', orderable : false},
      {data: 'action', name: 'action',orderable:false,serachable:false,Class:'text-center'},
      ]
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).on('click','.attrEdit',function(e){
      var get_id = $(this).attr('data-id');
      $('#update-pkg-model').modal('show');
      e.preventDefault();
      $.ajax({
        url: "{{route('admin.edit.rad')}}",
        type:'POST',
        data: {'get_id' : get_id},
        success: function(data) {
          var id = data['id'];
          $(".modal-body #update-id").val(id);
          var op = data['op'];
          $(".modal-body #pkg-op").val(op);
          var attribute = data['attribute'];
          $(".modal-body #pkg-attribute").val(attribute);
          var groupname = data['groupname'];
          $(".modal-body #pkg-groupname").val(groupname);
          var value = data['value'];
          $(".modal-body #pkg-value").val(value);
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $(".pkg-rad-update").click(function(e){
      e.preventDefault();
      $.ajax({
        url: "{{route('admin.update.rad')}}",
        type:'POST',
        data: $( '#update-pkg-data' ).serialize(),
        success: function(data) {
          if($.isEmptyObject(data.error)){
            $(".print-error-msg").css('display','none');
            $(".success-msg").css('display','block');
            $('.success-msg').html(data.success);
            location.reload(3000);
          }else{
            printErrorMsg(data.error);
          }
        }
      });
    }); 
    function printErrorMsg (msg) {
      $(".print-error-msg").find("ul").html('');
      $(".success-msg").css('display','none');
      $(".print-error-msg").css('display','block');
      $.each( msg, function( key, value ) {
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
      });
    }
  });
</script>
@endsection
<!-- Code Finalize -->