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
  .green_color{
    color: #30761b;
    font-weight:bold;
  }
  #username{
    background-color: #fff !important;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT START -->
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="">
        <div class="col-lg-12">
          <?php if(Auth::user()->status == 'dealer' ||  Auth::user()->status == 'subdealer'){?>
            <a href="{{('#add_user')}}" data-toggle="modal">  <button class="btn btn-primary mb1 btn-md"><i class="fa fa-users"></i> Add Consumer </button></a>
          <?php } ?>
          <div class="header_view">
            <h2>Consumers
              <span class="info-mark" onmouseenter="popup_function(this, 'consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          @include('users.layouts.session')
          <table class="table table-bordered data-table dt-responsive" id="resultTable" style="width:100%">
            <thead>
              <tr>
                <th>Serial#</th>
                <th>Consumer (ID)</th>
                <th>Consumer Name</th>
                <th class="desktop">Contractor & Trader</th>
                <th class="desktop">Address </th>
                <th>Internet Profile</th>
                <th>Expiry Date</th>
                <th style="width: 6%">Invoice</th>
                <th class="desktop" style="width: 13%">Actions</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </section>
  <!-- CONTENT END -->
</div>
@endsection
@section('ownjs')
@include('users.layouts.bind_mac')
<script>
</script>
<script>
  $(document).ready(function(){
    setTimeout(function(){
      $('.alert').fadeOut(); }, 3000);
  });
</script>
<script type="text/javascript">
  function charge_alert(data){
    $('#chargeConfirm').val(data);
    $('#confromMsg').modal('show');
  }
  function chargeit(data) {
    var value = data.split("^");
    var username = value[0];
    var profile = value[1];
    $.ajax({
      type: "POST",
      url: "{{route('users.charge.single.post')}}",
      data:'username='+username+'&profileGroupname='+profile,
      success: function(data){
// For Get Return Data
location.reload();
}
});
  }
</script>
<script type="text/javascript">
  $(function () {
    var table = $('.data-table').DataTable({
      processing: true,
      serverSide: true,
      "lengthMenu": [[10, 25, 50, 100,-1], [10, 25, 50, 100,'All']],
      ajax: "{{ route('users.viewCustomerServerSideUser') }}",
      columns: [
      {data: 'DT_RowIndex', name: 'DT_RowIndex'},
      {data: 'usernames', name: 'usernames', class: 'td__profileName'},
      {data: 'fullname', name: 'fullname'},
      {data: 'subdealerid', name: 'subdealerid'},
      {data: 'address', name: 'address'},
      {data: 'name', name: 'name', class: 'green_color'},
      {data: 'card_expire_on_col', name: 'card_expire_on_col'},
// Billing Working
{data: 'invoice', name: 'invoice'},
{data: 'action', name: 'action'},
],
});
  });
</script>
<script>
  $(document).ready(function () {
    $("body").tooltip({   
      selector: "[data-toggle='tooltip']",
      container: "body"
    })
//Popover, Activated by Clicking
.popover({
  selector: "[data-toggle='popover']",
  container: "body",
  html: true
});
});
</script>
@include('users.never_expire.neverExpireModal')
@endsection
<!-- Code Finalize -->