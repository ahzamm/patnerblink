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
    <section class="wrapper main-wrapper row">
      <a href="{{('#myModal')}}" data-toggle="modal" class="pr-3 mt-3">
        <button class="btn btn-default" style="border: 1px solid black">
          <i class="fas fa-globe-asia"></i> Add City</button>
        </a>
        <div class="header_view">
          <h2>Pakistan City List
            <span class="info-mark" onmouseenter="popup_function(this, 'city_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
          </h2>
        </div>
        <section class="box ">
          <div class="content-body">
            <div class="table-responsive">
              <table id="example-1" class="table dt-responsive w-100 display">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>City Name</th>
                    <th>Action </th>
                  </tr>
                </thead>
                <tbody>
                  @php $sno = 0; @endphp
                  @foreach($city_data as $city_data)
                  <tr>
                    <td style="width: 100px">{{++$sno}}</td>
                    <td class="td__profileName">{{ $city_data->city_name }}</td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm" value="{{$city_data->id}}" onclick="deleteit(this.value)">
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
    @include('admin.City.add_city')
    @endsection
<!-- Code Finalize -->