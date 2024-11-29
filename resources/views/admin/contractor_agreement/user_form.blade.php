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
  span#cke_32, #cke_46 ,#cke_21,#cke_19{
    display: none;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <a href="{{route('admin.users.getuseragreements')}}" class="btn btn-default" style="border: 1px solid black"><i class="fa fa-handshake"></i> Agreement List</a>
      <div class="header_view">
        <h2>Contractor agreement form
          <span class="info-mark" onmouseenter="popup_function(this, 'contractor_agreement_form_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section style="padding: 15px;">
        <div>
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
        </div>
        <form target="_blank" action="{{route('admin.postuserform')}}" method="POST">
          @csrf
          <div class="row">
            <section class="box" style="overflow: hidden;">
              <div class="form-group">
                <h3 class="text-center">Contractor Detail</h3>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="company_date">Select Brand Name <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'select_company_brand_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select  class="form-control form-select company" name="company_name" id="company" onchange="select_company(this)">
                    <option value="" selected>--- Select Brand Name --- </span></option>
                    @foreach($get_brands as $brand)
                    <option value="{{$brand->reseller_id}}" data-id="{{asset('logo').'/'.$brand->image}}">{{$brand->brand_name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="company_date">Select Contractor <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select class="js-select2 form-select" name="dealer_name" required id="dealer-dropdown">
                    <option value="">--- First Select Company Brand ---</option>  
                  </select>
                </div>
              </div>
              <div class="col-md-4 form-group">
                <p class="text-center"><img src="" class="img" id="image" name="company_logo" style="width: 180px"></p>
              </div>
            </section>
            <section class="box" style="overflow: hidden;">
              <div class="form-group">
                <h3 class="text-center">On Behalf of LOGON BROADBAND (Pvt.) Limited</h3>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group position-relative">
                  <label for="behalf_name">Person Full Name <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'contractor_agreement_behalf_of_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" required class="form-control" name="behalf_name" id="behalf_name" placeholder="Example : Azmat Baig">
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <div class="form-group position-relative">
                  <label for="behalf_designation">Designation <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'contractor_agreement_behalf_of_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text"  required class="form-control" name="behalf_designation" id="behalf_designation" placeholder="Example : Corporate Sales Manager">
                </div>
              </div>
            </section>
            <section class="box" style="overflow: hidden; padding:20px">
              <div class="form-group">
                <label><strong>Description :</strong></label>
                <textarea class="ckeditor form-control" name="content_name" rows="10">{{$data_agreement->content_name}}</textarea>
              </div>
              <div class="col-md-12" style="padding-top: 15px">
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
              </div>
            </section>
          </div>
        </form>
      </section>
    </section>
  </section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
  function select_company(select) {
    var company = (select.options[select.selectedIndex].value);
    var com = $('#company option:selected').data('id');
    console.log(com);
    $("#image").attr("src", com);
  }
  $('#company').on('change', function () {
    var reseller_id = this.value;
    $("#dealer-dropdown").html('');
    $.ajax({
      url: "{{route('admin.dealer')}}",
      type: "POST",
      data: {
        reseller_id: reseller_id,
        _token: '{{csrf_token()}}'
      },
      dataType: 'json',
      success: function (result) {
        $('#dealer-dropdown').html('<option value="">--- Select Contractor ---</option>');
        $.each(result.dealer, function (key, value) {
          $("#dealer-dropdown").append('<option value="' + value
            .dealerid + '">' + value.dealerid + '</option>');
        });
        $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
      }
    });
  });
</script>
@endsection
<!-- Code Finalize -->