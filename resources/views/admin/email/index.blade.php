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
        <h2>Emails
          <span class="info-mark" onmouseenter="popup_function(this, 'city_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box" style="margin-top: 0">
        <div class="content-body" style="padding-top: 20px">
          <div class="tab-content">
            <div id="email" class="tab-pane fade in active">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <h3>Email Addresses</h3>
                <a href="{{('#emailModal')}}" data-toggle="modal" class="pr-3 mt-3">
                  <button class="btn btn-default" style="border: 1px solid black">
                    <i class="fas fa-gear"></i> Email Setting</button>
                  </a>
                </div>
                <table id="example-1" class="table dt-responsive w-100 display">
                  <thead>
                    <tr>
                      <th>Serial#</th>
                      <th>Reseller</th>
                      <th>Email Title</th>
                      <th>Port</th>
                      <th>Email ID</th>
                      <th>SMTP Server</th>
                      <th>SMTP Encryption</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="width: 100px">1</td>
                      <td>Logon Broadband</td>
                      <td>csd helpdesk@logon.com.pk (Default)</td>
                      <td>21</td>
                      <td>helpdesk@logon.com.pk</td>
                      <td>logon.com.pk</td>
                      <td>TLS</td>
                    </tr>
                  </tbody>
                </table>
              </div>
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
          <button type="button" id="deletbtn" class="btn btn-danger">Yes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete Modal End -->
  @include('admin.email.add-email-modal')
  @include('admin.email.add-template-modal')
  @include('admin.email.add-helptopic-modal')
  @include('admin.email.email-setting-modal')
  @include('admin.email.ban-email-modal')
  @endsection
  @section('ownjs')
  <script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>
  @endsection
<!-- Code Finalize -->