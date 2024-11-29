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
@section('owncss')
<style type="text/css">
  .ttable{
    overflow: hidden;
    overflow-y: scroll;
    max-height: 350px;
    width: 100%;
  }
  .percent_symbol{
    font-weight:bold;
    padding: 0 20px;
    position: absolute;
    right: 15px;
    top: 33px;
  }
  /* Checkbox */
  .containers {
    display: inline-block;
    position: relative;
    padding-left: 25px;
    margin-bottom: 12px;
    margin-right: 10px;
    margin-top: 10px;
    cursor: pointer;
    font-weight: 500;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }
  .containers input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
  }
  .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #eee;
  }
  .containers:hover input ~ .checkmark {
    background-color: #ccc;
  }
  .containers input:checked ~ .checkmark {
    background-color: #0d4dab;
  }
  .checkmark:after {
    content: "";
    position: absolute;
    display: none;
  }
  .containers input:checked ~ .checkmark:after {
    display: block;
  }
  .containers .checkmark:after {
    left: 8px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Taxation Management
          <span class="info-mark" onmouseenter="popup_function(this, 'taxation_management_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box" style="padding: 15px;margin:auto;">
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
        <form  id="taxation-data">
          @csrf
          <div class="row">
            <h3 style="padding-left: 15px">Contractor Commission <span style="font-size: 14px; color: #0d4dab">(Taxation Ratio)</span></h3>
            <div class="col-lg-4 col-md-6">
              @foreach($show_tax as $tax_data)
              <input type="hidden" value="{{$tax_data->serial}}" name="tax_id"/>
              <div class="form-group position-relative" style="position: relative">
                <label  class="form-label" style="font-weight: bold; padding-right: 20px;">Contractor (Filer) Taxation Rate (%) <span style="color: red">*</span> </label>
                <span class="helping-mark" onmouseenter="popup_function(this, 'contractor_filer_tax_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                <input type="number" name="filer_tax" value="{{$tax_data->filer_tax * 100}}" class="form-control" style="width: 100%;display: inline-block" > <span class="percent_symbol">%</span>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="form-group position-relative" style="position: relative">
                <label  class="form-label" style="font-weight: bold; padding-right: 20px;">Contractor (Non Filer) Taxation Rate (%) <span style="color: red">*</span> </label>
                <span class="helping-mark" onmouseenter="popup_function(this, 'contractor_non_filer_tax_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                <input type="number" name="non_file_tax" value="{{$tax_data->non_file_tax * 100}}" class="form-control" style="width: 100%;display: inline-block" > <span class="percent_symbol">%</span>
              </div>
            </div>
          </div>
          <div class="row">
            <h3 style="padding-left: 15px">PTA License Fees <span style="font-size: 14px; color: #0d4dab">(Ratio in Consumer Invoice )</span></h3>
            <div class="col-lg-4 col-md-6">
              <div class="form-group position-relative" style="position: relative">
                <label  class="form-label" style="font-weight: bold; padding-right: 20px;">Fix Local Loop (FLL) <span style="color: red">*</span> </label>
                <span class="helping-mark" onmouseenter="popup_function(this, 'pta_fll_fees_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                <input type="number" name="fll_tax" value="{{$tax_data->fll_tax * 100}}" class="form-control" style="width: 100%;display: inline-block" > <span class="percent_symbol">%</span>
                <div>
                  <label class="containers">
                    <input type="hidden" name="fll_sst" value="no"> 
                    <input type="checkbox" name="fll_sst" value="yes" <?= ($tax_data->fll_sst == 'yes') ? 'checked' : '';?> > 
                    <span class="checkmark"></span> SST
                  </label>
                  <label class="containers">
                    <input type="hidden" name="fll_adv" value="no"> 
                    <input type="checkbox" name="fll_adv" value="yes" <?= ($tax_data->fll_adv == 'yes') ? 'checked' : '';?> > 
                    <span class="checkmark"></span> Advance Tax
                  </label>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="form-group position-relative" style="position: relative">
                <label  class="form-label" style="font-weight: bold; padding-right: 20px;">Internet Data (CVAS) <span style="color: red">*</span> </label>
                <span class="helping-mark" onmouseenter="popup_function(this, 'pta_cvas_fees_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                <input type="number" name="cvas_tax" value="{{$tax_data->cvas_tax * 100}}" class="form-control" style="width: 100%;display: inline-block" > <span class="percent_symbol">%</span>
                <div>
                  <label class="containers">
                    <input type="hidden" name="cvas_sst" value="no"> 
                    <input type="checkbox" name="cvas_sst" value="yes" <?= ($tax_data->cvas_sst == 'yes') ? 'checked' : '';?> > 
                    <span class="checkmark"></span> SST
                  </label>
                  <label class="containers">
                    <input type="hidden" name="cvas_adv" value="no"> 
                    <input type="checkbox" name="cvas_adv" value="yes" <?= ($tax_data->cvas_adv == 'yes') ? 'checked' : '';?> > 
                    <span class="checkmark"></span> Advance Tax
                  </label>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="form-group position-relative" style="position: relative">
                <label  class="form-label" style="font-weight: bold; padding-right: 20px;">Telecom Infrastructure (TIP) <span style="color: red">*</span> </label>
                <span class="helping-mark" onmouseenter="popup_function(this, 'pta_tip_fees_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                <input type="number" name="tip_tax" value="{{$tax_data->tip_tax * 100}}" class="form-control" style="width: 100%;display: inline-block" > <span class="percent_symbol">%</span>
                <div>
                  <label class="containers">
                    <input type="hidden" name="tip_sst" value="no"> 
                    <input type="checkbox" name="tip_sst" value="yes" <?= ($tax_data->tip_sst == 'yes') ? 'checked' : '';?> > 
                    <span class="checkmark"></span> SST
                  </label>
                  <label class="containers">
                    <input type="hidden" name="tip_adv" value="no"> 
                    <input type="checkbox" name="tip_adv" value="yes" <?= ($tax_data->tip_adv == 'yes') ? 'checked' : '';?> > 
                    <span class="checkmark"></span> Advance Tax
                  </label>
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group pull-right">
                <button class="btn btn-primary btn-submit">Update</button>
              </div>
            </div>
            @endforeach
          </div>
        </form>
      </section>
      <section class="box" style="padding: 15px;margin-top:10px;">
        <h3 style="padding-left: 15px">Provincial Taxation <span style="font-size: 14px; color: #0d4dab"></span>
          <span class="info-mark" onmouseenter="popup_function(this, 'provincial_taxation_rates_admin_side');" onmouseleave="popover_dismiss();"><i class="fa-solid fa-circle-question"></i></span>
        </h3>
        <form id="ProvincialTaxationForm">
          <div class="table-responsive">
            <table class="table dt-responsive w-100 display"" id="example-1">
              <thead>
                <tr>
                  <td>State</td>
                  <td>Provincial Sales Tax (%)</td>
                  <td>Advance Income Tax (%)</td>
                </tr>
              </thead>
              <tbody>
                <?php foreach($provincial_tax as $taxValue){?>
                  <tr>
                    <td class="td__profileName">
                      <input type="hidden" name="id[]" value="{{$taxValue->id}}">
                      <input type="hidden" name="name[]" value="{{$taxValue->state}}">
                      <?= $taxValue->state;?>
                    </td>
                    <td>
                      <input type="number" step="0.01" class="form-control" name="sst[]" value="{{$taxValue->ss_tax*100}}">
                    </td>
                    <td>
                      <input type="number" step="0.01" class="form-control" name="adv[]" value="{{$taxValue->adv_tax*100}}">
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group pull-right mt-3"  style="margin-top: 10px">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </div>
        </form>
      </section>
    </section>
  </section>
</div>
@endsection
@section('ownjs')
<script>
  function newRow() {
    $html = '<tr><td><input type="text" placeholder="Enter Text" class="form-control"></td><td><input type="text" placeholder="Enter Text" class="form-control"></td><td><input type="text" placeholder="Enter Text" class="form-control"></td><td><input type="button" class="btn btn-danger remove-tr" value="Remove Row"></td></tr>';
    console.log($html);
    $("#tax__table").append($html);
  }
  $(document).on('click', '.remove-tr', function(){  
    $(this).parents('tr').remove();
  });  
</script>
<script>
  $(document).ready(function() {
    $(".btn-submit").click(function(e){
      var id = $('#tax_id').val();
//  alert(id);
e.preventDefault();
$.ajax({
  url: "{{route('admin.taxation.update')}}",
// url: '/taxation/update' + itemId,
type:'POST',
data: $('#taxation-data').serialize(),_token:'{{ csrf_token() }}',
success: function (data) {
  alert(data.success);
  location.reload(true);
},
error: function(jqXHR, text, error){
  console.log(error);
}
});
}); 
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#ProvincialTaxationForm").submit(function() {
      $.ajax({
        type: "POST",
        url: "{{route('admin.provincialtaxation.update')}}",
        data: $('#ProvincialTaxationForm').serialize(),_token:'{{ csrf_token() }}',
        success: function (data) {
          alert(data.success);
        },
        error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
console.log(error);
}
});
      return false;
    });
  });
</script>
@endsection
<!-- Code Finalize -->