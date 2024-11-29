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
@include('admin.users.add_profile')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <a href="{{('#add_profile_model')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fa fa-tachometer-alt"></i> Add Internet Package </button></a>
      <div class="header_view">
        <h2>Internet Packages
          <span class="info-mark" onmouseenter="popup_function(this, 'internet_packages_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <div id="tablediv">
        <table id="myTable" class="table table-striped dt-responsive display w-100">
          <thead>
            <tr>
              <th>Serial#</th>
              <th>Internet Packages Speed (Mb)</th>
              <th>Internet Packages Name</th>
              <th>Data Limit (Cage)</th>
              <th>Internet Packages Color</th>
              <th>Internet Profiles Type</th>
              <th>CDN Servers </th>
              <th>Action </th>
            </tr>
          </thead>
          <tbody>
            @php $sno = 0; @endphp
            @foreach($profileList as $data)
            <tr>
              <td>{{++$sno}}</td>
              <td>{{$data->groupname}}</td>
              <td>{{$data->name}}</td>
              <td>{{$data->quota_limit / 1024 / 1024}} GB</td>
              <td><div style="width: 60px; height:20px; background-color:{{$data->color}};margin: auto;border-radius: 3px"></div></td>
              <td>{{$data->profile_type}}</td>
              <td><div style="display: flex; align-items: center; justify-content: space-around"><div><p style="margin-bottom: 0; color: #3265dd"><i class="fa fa-facebook"></i></p><strong>{{$data->soc_facebook}}</strong></div> <div><p style="margin-bottom: 0; color: #a52a2a"><i class="fa fa-youtube"></i></p><strong>{{$data->soc_youtube}}</strong></div> <div><p style="margin-bottom: 0"><img src="https://manager.logon.com.pk/images/netflix.png" style="width: 15px;filter: hue-rotate(126deg)"></p><strong>{{$data->soc_netflix}}</strong></div></div></td>
              <td>
                <button class="btn btn-danger btn-sm" value="{{$data->id}}" onclick="deleteit(this.value)">
                  <i class="fa fa-trash"></i></button>
                  <button class="btn btn-sm btn-info update-btn" data-id="{{$data->id}}">
                    <i class="fa fa-pencil"></i>
                  </button>
                </td>
              </tr>
              @endforeach
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
  @include('admin.users.add_profile')
  @include('admin.users.update_profile')
  @endsection
  @section('ownjs')
  <script type="text/javascript">
    $(document).ready( function () {
      $('#myTable').DataTable();
    } );
    function deleteit(id){
      $('#deletbtn').val(id);
      $('#deleteModel').modal('show');
    }
    function deletethis(val) {
      $.ajax({
        type: "POST",
        url: "{{route('admin.profile.ajax.post')}}",
        data:'id='+val,
        success: function(data){
          $('#deleteModel').modal('hide');
          $('#tablediv').load(" #tablediv");
        }
      });
    }
  </script>
  <script type="text/javascript">
    $(document).ready(function() {  
     $(document).on("click",".update-btn", function(e) {
      var get_id = $(this).attr('data-id');
      e.preventDefault();
      $.ajax({
        url: "{{route('admin.profile.edit')}}",
        type:'POST',
        data: {'get_id' : get_id},
        success: function(data) {
         var id = data['id'];
         $(".modal-body #update-id").val(id);
         var adv_tax = data['adv_tax'];
         $(".modal-body #update-adv_tax").val(adv_tax);
         var adv_taxB = data['adv_taxB'];
         $(".modal-body #update-adv_taxB").val(adv_taxB);
         var adv_taxC = data['adv_taxC'];
         $(".modal-body #update-adv_taxC").val(adv_taxC);
         var charges = data['charges'];
         $(".modal-body #update-charges").val(charges);
         var chargesB = data['chargesB'];
         $(".modal-body #update-chargesB").val(chargesB);
         var chargesC = data['chargesC'];
         $(".modal-body #update-chargesC").val(chargesC);
         var code = data['code'];
         $(".modal-body #update-code").val(code);
         var color = data['color'];
         $(".modal-body #update-color").val(color);
         var final_rates = data['final_rates'];
         $(".modal-body #update-final_rates").val(final_rates);
         var final_ratesB = data['final_ratesB'];
         $(".modal-body #update-final_ratesB").val(final_ratesB);
         var final_ratesC = data['final_ratesC'];
         $(".modal-body #update-final_ratesC").val(final_ratesC);
         var groupname = data['groupname'];
         $(".modal-body #update-groupname").val(groupname);
         var name = data['name'];
         $(".modal-body #update-name").val(name);
         var quota_limit = data['quota_limit'];
         $(".modal-body #update-quota_limit").val(quota_limit);
         var sst = data['sst'];
         $(".modal-body #update-sst").val(sst);
         var sstB = data['sstB'];
         $(".modal-body #update-sstB").val(sstB);
         var sstC = data['sstC'];
         $(".modal-body #update-sstC").val(sstC);
       // Update New Fileds Data
       var bw_show = data['bw_show'] / 1024;
       $(".modal-body #update-bw_show").val(bw_show);
       var soc_facebook = data['soc_facebook'];
       $(".modal-body #update-soc_facebook").val(soc_facebook);
       var soc_youtube = data['soc_youtube'];
       $(".modal-body #update-soc_youtube").val(soc_youtube);
       var soc_internet = data['soc_internet'];
       $(".modal-body #update-soc_internet").val(soc_internet);
       var soc_netflix = data['soc_netflix'];
       $(".modal-body #update-soc_netflix").val(soc_netflix);
       var base_price = data['base_price'];
       $(".modal-body #update-base_price").val(base_price);
       var profile_type = data['profile_type'];
       var show_data =  $(".modal-body #show-data").val(profile_type);
       var check = $('#update-profile-type option[value='+profile_type+']').attr('selected', true);
       $('#update_profile_model').modal('show');
     } // End Success
   });
    });
    }); // End Jquery Calling
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#store-update-btn").click(function(e){
        e.preventDefault();
        $.ajax({
          url: "{{route('admin.profile.update')}}",
          type:'POST',
          data: $( '#pkg-form-update' ).serialize(),
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
    $('#update-groupname').attr('readonly', true);
    $('#update-name').attr('readonly', true);
  </script>
  <script>
    $('#profile-type').on('change', function(){
      var val = $(this).val();
      if(val === 'cdn'){
        $('.social').prop('disabled', false);
      }
      else{
        $('.social').prop('disabled', true);
      }
    });
    $('#update_profile_model').on('shown.bs.modal', function (e) {
      var val = $('#update-profile-type').val();
      if(val === 'cdn'){
        $('.social-update').prop('disabled', false);
      }
      else{
        $('.social-update').prop('disabled', true);
      }
    })
    $('#update_profile_model').on('hidden.bs.modal', function (e) {
      $("#update-profile-type option").attr("selected", false)
    })
    $('#update-profile-type').on('change', function(){
      var val = $(this).val();
      if(val === 'cdn'){
        $('.social-update').prop('disabled', false);
      }
      else{
        $('.social-update').prop('disabled', true);
      }
    });
  </script>
  @endsection
  <!-- Code Finalize -->