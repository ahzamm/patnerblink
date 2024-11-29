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
         <div class="header_view">
            <h2>MANAGE CGN IPS
               <span class="info-mark" onmouseenter="popup_function(this, 'manage_cgn_ips_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
         </div>
         <section class="box ">
            <div class="content-body">
               <h4>ADD CGN IPS</h4>
               <form id="cgnForm">
                  @csrf
                  <div class="row">
                     <div class="col-lg-4 col-md-6">
                        <div class="form-group position-relative">
                           <label  class="form-label">Asgin IP Address (Just 2 Octate) <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'cgn_ip_2octate_add_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" class="form-control" name="ip" placeholder="Example: 192.168" required pattern="[0-9.]*">
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="form-group position-relative">
                           <label  class="form-label">Asgin Only (3) Octate "Start" <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'cgn_ip_3octate_start_add_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="number" name="start" class="form-control" placeholder="Example: 0" required>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="form-group position-relative">
                           <label  class="form-label">Asgin Only (3) Octate "End" <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'cgn_ip_3octate_end_add_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="number" name="end" class="form-control" placeholder="Example: 1" required>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="form-group position-relative">
                           <label  class="form-label">Asgin Main Group <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'cgn_ip_main_group_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="number" name="main" class="form-control" placeholder="Example: 1" required>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="form-group position-relative">
                           <label  class="form-label">Asgin Sub Group <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'cgn_ip_sub_group_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" name="sub" class="form-control" placeholder="Example: A" required>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="form-group position-relative">
                           <label  class="form-label">Select NAS <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'cgn_ip_select_nas_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <select name="nasShortName" class="form-control" required>
                              <option>Select NAS</option>
                              <?php foreach($nas as $value){?>
                                 <option value="<?= $value->shortname;?>"><?= $value->shortname;?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-xs-12">
                        <div class="form-group">
                           <div class="form-group pull-right">
                              <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
               <span id="output22"></span>
            </div>
         </section>
         <section class="box ">
            <div class="content-body">
               <h4>CGN IPS LIST</h4>
               <div class="table-responsive">
                  <table class="table table-bordered data-table" id="dataTable">
                     <thead>
                        <tr>
                           <th>IP Address</th>
                           <th>Main Group</th>
                           <th>Sub Group</th>
                           <th>Status</th>
                           <th>Nas</th>
                           <th>City</th>
                        </tr>
                        <tr>
                           <td>
                              <input type="search" id="ip" class="form-control" placeholder="Type your ip here..">
                           </td>
                           <td>
                              <input type="search" id="main-group" class="form-control" placeholder="Type main-group here..">
                           </td>
                           <td>
                              <input type="search" id="sub-group" class="form-control" placeholder="Type sub-group here..">
                           </td>
                           <td>
                              <input type="search" id="status" class="form-control" placeholder="Type Status here..">
                           </td>
                           <td>   
                              <input type="search" id="nas" class="form-control" placeholder="Type Nas here..">
                           </td>
                           <td>
                              <input type="search" id="city" class="form-control" placeholder="Type City here..">
                           </td>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
               </div>
            </div>
         </section>
      </section>
   </section>
</div>
<!-- Processing -->
<div class="modal fade bs-example-modal-center custom-center-modal" id="processLayer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-backdrop="static">
   <div class="modal-dialog modal-dialog-centered modal-dialog modal-md">
      <div class="modal-content" style="background-color:#02020200 !important;border-color:#02020200 !important; ">
         <div class="modal-body">
            <center><h1 style="color:white;">Processing....</h1>
               <p style="color:white;">please wait.</p>
            </center>
         </div>
      </div>
   </div>
</div>
@endsection
@section('ownjs')
<script>
   $('#cgnForm').submit(function(e){
      $('#processLayer').modal('show');
      $.ajax({
         url: "{{route('admin.postcgn')}}",
         type: "POST",
         data: $( '#cgnForm' ).serialize(),
         success: function(result){
            $('#output22').html(result);
            $("#cgnForm").trigger("reset");
            setTimeout(function(){ $('#output22').html("");$('#processLayer').modal('hide'); }, 2000);
         }
      });
      e.preventDefault();
   });
</script>
<script type="text/javascript">
   $(function () {
      var table = $('#dataTable').DataTable({
         processing: true,
         serverSide: true,
         ajax:{
            url: "{{route('admin.cgnView.data')}}",
            data: function (d) {
               console.log(d);
               d.status = $('#status').val(),
               d.nas = $('#nas').val(),
               d.ip = $('#ip').val(),
               d.main_group = $('#main-group').val(),
               d.sub_group = $('#sub-group').val(),
               d.city = $('#city').val(),
               d.search = $('input[type="search"]').val()
            },
         },
         columns: [
         { data: 'ip', orderable : false },
         { data: 'main_group', orderable : false },
         { data: 'sub_group', orderable : false },
         { data: 'status', orderable : false},
         { data: 'nas', orderable : false },
         { data: 'city', orderable : false},
         ]
      });
      $('.data-table').change(function(){
         table.draw();
      });
   });
</script>
@endsection
<!-- Code Finalize -->