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
      <a href="{{('#add_nas_model')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fas fa-hdd"></i> Add Nas </button></a>
      <div class="header_view">
        <h2>NAS (BRAS) Management
          <span class="info-mark" onmouseenter="popup_function(this, 'nas_management_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <div class="" id="tablediv">
        <table id="example-1" class="table table-striped dt-responsive display w-100">
          <thead>
            <tr>
              <th>Serial#</th>
              <th>Nas IP Address</th>
              <th>Short Name</th>
              <th>Nas Brand</th>
              <th>Carrier</th>
              <th>Nick Name</th>
              <th>Nas Port</th>
              <th>Add by IP Address</th>
              <th>Action </th>
            </tr>
          </thead>
          <tbody>
            @php $sno = 0; @endphp
            @foreach($naslist as $data)
            <tr>
              <td>{{++$sno}}</td>
              <td>{{ $data->nasname }}</td>
              <td>{{ $data->shortname }}</td>
              <td>{{$data->type}}</td>
              <td>{{$data->carrier}}</td>
              <td>{{$data->abbr}}</td>
              <td>{{$data->ports}}</td>
              <td>{{$data->addedbyip}}</td>
              <td>
                <a href="{{route('admin.router.edit',['id' => $data->id])}}">  
                  <button class="btn btn-info btn-sm"> <i class="fa fa-edit"> </i> Edit</button>
                </a>
                <button class="btn btn-danger btn-sm" value="{{$data->id}}" onclick="deleteit(this.value)">
                  <i class="fa fa-trash"></i></button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
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
  @include('admin.users.add_nas')
  @endsection
  @section('ownjs')
  <script type="text/javascript">
    function deleteit(id){
      $('#deletbtn').val(id);
      $('#deleteModel').modal('show');
    }
    function deletethis(val) {
      $.ajax({
        type: "POST",
        url: "{{route('admin.router.ajax.post')}}",
        data:'id='+val,
        success: function(data){
          $('#deleteModel').modal('hide');
          $('#tablediv').load(" #tablediv");
        }
      });
    }
  </script>
  @endsection
<!-- Code Finalize -->