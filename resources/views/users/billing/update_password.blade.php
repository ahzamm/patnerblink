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
  .st{
    z-index: 0;
    margin-top: -53px;
    margin-right: 42px;
    width: 100px;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="header_view">
        <h2>Change Consumer Password
          <span class="info-mark" onmouseenter="popup_function(this, 'change_consumer_password');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
        @include('users.layouts.session')
      </div>
      <section class="box" style="max-width:600px;margin:auto">
        <header class="panel_header">
          <div class="actions panel_actions pull-right">
            <a class="box_toggle fa fa-chevron-down"></a>
          </div>
        </header>
        <div class="content-body" >   
          <form method="POST" action="{{route('users.billing.update_password')}}" autocomplete="off">
            @csrf
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="browser">Consumer (ID)</label>
                  <input list="browsers" class="form-control" id="username-select" name="username" required placeholder="Example: abc123">
                  <datalist id="browsers">
                    <option value="">select Username</option>
                    @foreach($userCollection as $dealer)
                    @if($dealer->status == 'subdealer')
                    <option value="{{$dealer->username}}">{{$dealer->username}} ({{$dealer->status}})</option>
                    @else
                    <option value="{{$dealer->username}}">{{$dealer->username}} ({{$dealer->status}})</option>
                    @endif
                    @endforeach
                  </datalist>
                </div>
                <div class="form-group" style="position:relative">
                  <label  class="form-label">Password <span style="color: red">*</span></label>
                  <input type="Password" name="password" class="form-control" id="inputPassword" placeholder="Must be 8 characters long" required> <i class="fa fa-eye-slash toggleClass" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass();" > </i>
                </div>
                <div class="form-group" style="position:relative">
                  <label  class="form-label">Retype Password <span style="color: red">*</span></label>
                  <input type="Password" name="password_confirmation" class="form-control" id="retype_password" placeholder="Must be 8 characters long" required> <i class="fa fa-eye-slash toggleClass2" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass2();" > </i>
                </div>
              </div>
              <div class="col-md-12 text-right">
                <div class="form-group ">
                  <button type="submit" class="btn btn-primary">Update</button>
                  <button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </form>
          {{-- <button  class="btn btn-primary pull-right st" data-toggle="modal" data-target=".bd-example-modal-lg" onclick="reset();">Reset</button> --}}
          <div  style="display: none;" class="modal fade" id="bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" style="text-align: center; color: green" id="exampleModalLabel">Password Reset Successfully</h3>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <h4 style="text-align: center;">Your password has been successfully reset 
                      which is only applicable on user panel..</h4>
                    </div>       
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </section>
      </section>
    </section>
  </div>
  @endsection
  @section('ownjs')
  <script>
    function showpass() {
      var x = document.getElementById("inputPassword");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
      $('.toggleClass').toggleClass('fa-eye fa-eye-slash');
    }
    function showpass2() {
      var x = document.getElementById("retype_password");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
      $('.toggleClass2').toggleClass('fa-eye fa-eye-slash');
    }
  </script>
  <script type="text/javascript">
    function reset() {
      var username = document.getElementById('username-select').value;
      if(username == ''){
        alert('Please Select User First');
      }else{
        $.ajax({
          type : "POST",
          url : "{{route('users.billing.reset')}}",
          data : {username:username},
          success : function(data){
            $("#username-select").val('');
            $('#bd-example-modal-lg').modal('show');
          },
          error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
$('#demo').html(error);
}
});
      }
    }
  </script>
  <script></script>
  @endsection
<!-- Code Finalize -->