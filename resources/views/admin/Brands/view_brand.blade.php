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
      <a href="{{('#myModal')}}" data-toggle="modal" class="pr-3 mt-3">
        <button class="btn btn-default" style="border: 1px solid black">
          <i class="fa-solid fa-registered"></i> Add Brand</button>
        </a>
        <div class="header_view">
          <h2>Company Brands Logos
            <span class="info-mark" onmouseenter="popup_function(this, 'brand_logo_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
          </h2>
        </div>
        <section class="box">
          <div class="content-body">
            <div class="table-responsive">
              <table id="example-1" class="table dt-responsive w-100 display" id="brands">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Brand & Product Name</th>
                    <th width="20%" height="10%">Brand & Product (Logos)</th>
                    <th>Action </th>
                  </tr>
                </thead>
                <tbody>
                  @php $sno = 0; @endphp
                  @foreach($brands_data as $brands)
                  <tr>
                    <td>{{++$sno}}</td>
                    <td class="td__profileName">{{ $brands->brand_name }}</td>
                    <td><img src="{{asset('logo').'/'.$brands->image}}" class="img img-thumbnail" style="width: 100px"></td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm" value="{{$brands->id}}" onclick="deleteit(this.value)">
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
    <!-- Delete Modal Ends -->
    @endsection
    @include('admin.Brands.add_brand')
<!-- Code Finalize -->