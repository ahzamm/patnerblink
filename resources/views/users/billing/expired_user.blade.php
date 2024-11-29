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
  .fw-bold{
    font-weight: bold;
  }
</style>
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="">
        <div class="header_view">
          <h2>Expired Consumers
            <span class="info-mark" onmouseenter="popup_function(this, 'expired_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
          </h2>
          @include('users.layouts.session')
        </div>
        <table class="table table-bordered data-table dt-responsive display" style="width: 100%">
          <thead>
            <tr>
              <th>Serial#</th>
              <th>Consumer (ID)</th>
              <th>Consumer Name</th>
              <th class="desktop">Address </th>
              <th>Mobile Number</th>
              <th class="desktop">Internet Profile</th>
              <th>Expiry Date</th>
              <th class="desktop">Trader (ID)</th>
              <th style="width: 17%">Actions</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </section>
  </section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
</script>
<script>
  $(document).ready(function(){
    setTimeout(function(){
      $('.alert').fadeOut(); }, 3000);
  });
</script>
<script type="text/javascript">
  $(function () {
    var table = $('.data-table').DataTable({
      processing: true,
      serverSide: true,
      "lengthMenu": [[10, 25, 50, 100,-1], [10, 25, 50, 100,'All']],
      ajax: "{{ route('users.expireServerSideUser') }}",
      columns: [
      {data: 'DT_RowIndex', name: 'DT_RowIndex'},
      {data: 'username', name: 'username', class:'td__profileName'},
      {data: 'fullname', name: 'fullname'},
      {data: 'address', name: 'address'},
      {data: 'mobilephone', name: 'mobilephone'},
      {data: 'name', name: 'name', class:'green_color'},
      {data: 'expireDate', name: 'expireDate', class:'fw-bold'},
      {data: 'subdealerid', name: 'subdealerid'},
      {data: 'action', name: 'action'},
      ]
    });
  });
</script>
@include('users.never_expire.neverExpireModal')
@endsection
<!-- Code Finalize -->