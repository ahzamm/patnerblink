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
        <h2>Contractors Agreements (SLA)
          <span class="info-mark" onmouseenter="popup_function(this, 'contractor_agreement_preview_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <table id="agreement" class="table table-striped dt-responsive display w-100">
        <thead>
          <tr>
            <th>Reseller</th>
            <th>Contractor</th>
            <th>CNIC Number</th>
            <th>Mobile#</th>
            <th>Assigned Area</th>
            <th>Contract Updated (Date & Time)</th>
            <th>Contract Created (Date & Time)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($useragreements as $data)
          <tr>
            <td>{{$data->company_name}}</td>
            <td class="td__profileName">{{$data->dealer_name}}</td>
            <td>{{$data->cnic}}</td>
            <td>{{$data->contractor_mobile}}</td>
            <td>{{$data->dealer_area}}</td>
            <td>{{date('M d,Y H:i:s',strtotime($data->updated_at))}}</td>
            <td>{{date('M d,Y H:i:s',strtotime($data->created_at))}}</td>
            <td>
              <center>
                <a href="{{route('usersagreement.edit',['id' => $data->id])}}">
                  <button class="btn btn-info mb1 bg-olive btn-xs" style="margin-bottom:4px">
                    <i class="fa fa-edit"> </i> Edit
                  </button>
                </a>
                <a href="{{route('usersagreement.delete',['id' => $data->id])}}" class="btn btn-danger mb1 bg-danger btn-xs" style="margin-bottom:4px">
                  <i class="fa fa-trash"> </i> Delete
                </a>
                <a target="_blank" href="{{route('admin.users.getuseragreements_view',['id' => $data->id])}}" >
                  <button class="btn btn-primary btn-xs" style="margin-bottom:4px">
                    <i class="fa fa-file-pdf"> </i> PDF Preview</button></a>
                  </center>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </section>
      </section>
    </div>
    <!-- Delete Module Start -->
    <div class="modal fade" id="deleteModel" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h4>Do You Realy Want To Delete This Contractor & Trader Agreement ?</h4>
          </div>
          <div class="modal-footer">
            <button type="button" id="deletbtn" class="btn btn-danger">Yes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete Module End -->
    @endsection
    @section('ownjs')
    <script>
      $('#agreement').DataTable();
    </script>
    @endsection
<!-- Code Finalize -->