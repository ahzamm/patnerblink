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
      <div class="alert alert-danger print-error-msg" style="display:none" >
        <ul></ul>
      </div>
      <div class="alert alert-success success-msg" style="display:none" >
        <ul></ul>
      </div>
      <a href="{{('#banks-modal')}}" data-toggle="modal" class="pr-3 mt-3">
        <button class="btn btn-default" style="border: 1px solid black">
          <i class="fa-solid fa-building-columns"></i> Add Bank</button>
        </a>
        <div class="header_view">
          <h2>Bank Logos
            <span class="info-mark" onmouseenter="popup_function(this, 'bank_logos_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
          </h2>
        </div>
        <section class="box ">
          <div class="content-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover display" id="bank-list">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Bank Name</th>
                    <th width="20%" height="10%">Bank (Logos)</th>
                    <th>Active/Deactive</th>
                    <th>Action </th>
                  </tr>
                </thead>
                <tbody>
                  @php $sno = 0; @endphp
                  @foreach($banks_image_data as $bank)
                  <tr>
                    <td>{{++$sno}}</td>
                    <td class="td__profileName">{{$bank->bank_name }}</td>
                    <td><img src="{{asset('images/bank-images').'/'.$bank->image}}" class="img img-thumbnail" style="width: 100px"></td>
                    <td><input type="checkbox" name="status" style="width:30px;height:30px;" id="status-image" data-id="{{$bank->id}}" @if($bank->status == 1) checked @endif></td>
                    <td>
                      <button type="button" class="btn btn-danger" value="{{$bank->id}}" onclick="deleteit(this.value)">
                        <i class="fa fa-trash"></i>  Delete</button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </section>
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
    @endsection
    @include('admin.bank-image.add')
    @section('ownjs')
    ​
    <script>
      $(document).ready(function() {
        $('#bank-list').DataTable();
        $('#my-form').submit(function(event) {
          event.preventDefault();
          var formData = new FormData($(this)[0]);
          $.ajax({
            url: "{{route('admin.banks.store')}}",              
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
// alert(data);
if($.isEmptyObject(data.error)){
  $(".print-error-msg").css('display','none');
  $(".success-msg").css('display','block');
  $('.success-msg').html(data.success);
  $('#banks-modal').modal('hide');
  location.reload();
}else{
  printErrorMsg(data.error);
  $('#banks-modal').modal('hide');
}
}
});
        }); function printErrorMsg (msg) {
          $(".print-error-msg").find("ul").html('');
          $(".success-msg").css('display','none');
          $(".print-error-msg").css('display','block');
          $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
          });
        }
      }
      );
    </script>
    <script type="text/javascript">
      function deleteit(id){
        $('#deletbtn').val(id);
        $('#deleteModel').modal('show');
      }
      function deletethis(val) {
        $.ajax({
          type: "POST",
          url: "{{route('admin.banks.destroy')}}",
          data:{id:val,"_token": "{{ csrf_token() }}"},
          success: function(data){
            $('#deleteModel').modal('hide');
            $('#tablediv').load(" #tablediv");
            if($.isEmptyObject(data.error)){
              $(".print-error-msg").css('display','none');
              $(".success-msg").css('display','block');
              $('.success-msg').html(data.success);
              location.reload();
            }else{
              printErrorMsg(data.error);
            }
          }
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
    <script>
      $(document).ready(function() {
        $(document).on('change','#status-image',function(){
          var status = 0;
          if ($(this).is(':checked')) {
            var status = 1;
          }else{
            var status = 0;   
          }
          var id = $(this).attr('data-id');
          $.ajax({
            type: 'POST',
            url: "{{route('admin.banks.update')}}",
            data:{
              status : status, id : id,
            },
            success: function(data) {
// alert(data);
if($.isEmptyObject(data.error)){
  $(".print-error-msg").css('display','none');
  $(".success-msg").css('display','block');
  $('.success-msg').html(data.success);
//  location.reload(3000);
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