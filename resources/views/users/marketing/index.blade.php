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
@section('content')
<style>
  .slider:before{
    height: 19px;
    left: 2px;
    bottom: 2px;
  }
  input:checked + .slider:before{
    transform: translateX(16px);
  }
  .video-display-none , .image-display-none{
    display: none;
  }
</style>
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="">
        <a href="{{route('users.marketing.add')}}" data-toggle="modal">  
          <button class="btn btn-default" style="border: 1px solid black"><i class="fas fa-ad"></i> Add Commercials </button>
        </a>
        <div class="" >
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          @if ($message = Session::get('success'))
          <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>  
            <strong>{{ $message }}</strong>
          </div>
          @endif
          @if ($message = Session::get('error'))
          <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>  
            <strong>{{ $message }}</strong>
          </div>
          @endif
          <div class="alert alert-danger print-error-msg" data-dismiss="alert" style="display:none" >
            <ul></ul>
          </div>
          <div class="alert alert-success success-msg" data-dismiss="alert" style="display:none" >
            <ul></ul>
          </div>
          <div class="header_view">
            <h2>Advertisement & Marketing
              <span class="info-mark" onmouseenter="popup_function(this, 'advertisement_marketing_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
        </div>
        <div class="table-responsive">
          <table id="example-1" class="table table-striped dt-responsive display">
            <thead>
              <tr>
                <th>Serial#</th>  
                <th>Reseller (ID)</th>
                <th>Marketing Category</th>
                <th>Post Date & Time</th>
                <th>Status </th>            
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($get_marketing_data as $data)
              <tr>
                <td>{{$data->id}}</td>
                <td>{{$data->reseller_id}}</td>
                <td>{{$data->category}}</td>
                <td>{{date('M d,Y',strtotime($data->created_at))}}</td>
                <td>
                  <label class="switch" style="width: 46px;height: 23px;">
                    <input type="checkbox" name="status" id="status-post" data-id="{{$data->id}}" @if($data->status == 1) checked @endif>
                    <span class="slider square"></span>
                  </label>
                </td>
                <td>
                  <button  class="btn btn-primary btn-xs showRecord" data-id="{{ $data->id }}" data-toggle="modal">
                    <i class="fa fa-eye"> </i> View</button>
                    <button class="btn btn-danger btn-xs" value="{{$data->id}}" onclick="deleteit(this.value)">
                      <i class="fa fa-trash"></i> Delete</button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </section>
    </div>
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
    <div aria-hidden="true"  role="dialog" tabindex="-1" id="ad_modal" class="modal fade" style="display: none;">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
            <h4 class="modal-title" style="text-align: center; color: white"> Marketing Post</h4>
          </div>
          <div class="modal-body" id="video">
            <video id="videoclip" controls="controls" title="Video title">
              <source id="mp4video" src="" type="video/mp4"  />
            </video>
          </div>
          <div class="modal-body" id="image">
            <img src="" alt="" id="image-display" style="width:100%"/>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
  @section('ownjs')
  <script>
    $(document).ready(function(){
      setTimeout(function(){
        $('.alert').fadeOut(); }, 3000);
    });
  </script>
  <script type="text/javascript">
    $(document).ready( function () {
      $('#myTable').DataTable();
    } );
    function deleteit(id){
      $('#deletbtn').val(id);
      $('#deleteModel').modal('show');
    }
    function deletethis(val) {
      var token = $("meta[name='csrf-token']").attr("content");
      $.ajax({
        type: 'DELETE',
        url: '/users/marketing/' + val,
        data:'id='+val, "_token": token,
        success: function(data){
          $('#deleteModel').modal('hide');
          $('#tablediv').load(" #tablediv");
          if($.isEmptyObject(data.error)){
            $(".print-error-msg").css('display','none');
            $(".success-msg").css('display','block');
            $('.success-msg').html(data.success);
            location.reload(3000);
          }else{
            printErrorMsg(data.error);
          }
        },
        error: function (jqXHR, exception) {
          console.log(jqXHR.status);
        },
      });
    }
    function printErrorMsg (msg) {
      $(".print-error-msg").find("ul").html('');
      $(".success-msg").css('display','none');
      $(".print-error-msg").css('display','block');
      $.each( msg, function( key, value ) {
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
      });
    }
  </script>
  <script type="text/javascript">
    $(".showRecord").click(function(e){
      var id = $(this).data("id");
      $.ajax(
      {
        url: "{{route('users.marketing.show')}}",
        type: 'GET',
        data: {
          "id": id,
        },
        success: function (res){
          $('#ad_modal').modal('show');
          $('#reseller_id').text(res.reseller_id);
          var name = '<?php echo asset(''); ?>'   +'marketing'+'/'  +res.reseller_id+'/'+res.category.replace(/ /g,'')+'/'+res.file_name;
          if(res.category == "Promotions Video")
          {
            var videocontainer = document.getElementById('videoclip');
            var videosource = document.getElementById('mp4video');
            $("#video").removeClass("video-display-none");
            $("#image").addClass("image-display-none");
            videocontainer.pause();
            videosource.setAttribute('src', name);
            videocontainer.load();
            videocontainer.play();
          }
          else{
            var name = '<?php echo asset(''); ?>'   +'marketing'+'/'  +res.reseller_id+'/'+res.category.replace(/ /g,'')+'/'+res.file_name;
            $("#image-display").attr("src", name);
            $("#video").addClass("video-display-none");
            $("#image").removeClass("image-display-none");
          }
          $('#category').text(res.category);
          $('#status').text(res.status);
        }
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('change','#status-post',function(){
        var status = 0;
        if ($(this).is(':checked')) {
          var status = 1;
        }else{
          var status = 0;    
        }
        var id = $(this).attr('data-id');
        $.ajax({
          type: 'POST',
          url: "{{route('users.marketing.update')}}",
          data:{
            status : status, id : id,
          },
          success: function(data) {
// alert(data);
if($.isEmptyObject(data.error)){
// alert(data.success);
$(".print-error-msg").css('display','none');
$(".success-msg").css('display','block');
$('.success-msg').html(data.success);
// location.reload(3000);
}else{
  printErrorMsg(data.error);
}
}
})
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