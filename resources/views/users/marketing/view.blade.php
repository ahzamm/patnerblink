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
  .slider:before {
    height: 19px;
    left: 2px;
    bottom: 2px;
  }
  input:checked+.slider:before {
    transform: translateX(16px);
  }
  .video_overlay {
    position: relative;
  }
  .video_overlay:before {
    position: absolute;
    content: '';
    width: 100%;
    height: 100%;
    background: #000;
    opacity: .5;
  }
  .properties{
    width:100%;height: 100%;
  }
</style>
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="row">
        <div class="col-md-12">
          <div class="header_view">
            <h2>Advertisement & Marketing
              <span class="info-mark" onmouseenter="popup_function(this, 'advertisement_marketing_user_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div class="uprofile-content row">
            <div class="col-lg-12">
              <div style="margin-left:20px">
                <ul class="nav nav-tabs" style="display:flex;align-items:center;justify-content:center;flex-wrap:wrap">
                  <li class="active"><a data-toggle="tab" href="#post">Social Media Post</a></li>
                  <li><a data-toggle="tab" href="#promotion-videos">Promotion Videos</a></li>
                  <li><a data-toggle="tab" href="#brouchers">Brouchers</a></li>
                  <li><a data-toggle="tab" href="#billborads">Bill Board</a></li>
                  <li><a data-toggle="tab" href="#tips">Tips and Tricks</a></li>
                </ul>
              </div>
              <div class="tab-content" style="background-color:transparent">
                <div id="post" class="tab-pane fade in active">
                  @if(Count($show_data) < 1) 
                  @endif
                  <div class="row">
                    @foreach($show_data as $data)
                    <div class="col-lg-4 col-md-6">
                      <?php $new_str = str_replace(' ', '', $data['category']); ?>
                      <div class="">
                        <img src="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" alt="" class="img img-thumbnail">
                        <div style="display:flex;align-items:center;justify-content:space-between;column-gap:10px;margin-top:10px;"> 
                          <a href="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" class="btn btn-success" style="flex:1 1 auto" target="_blank"><i class="fa fa-eye"></i> View</a>
                          <a href="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" class="btn btn-info" style="flex:1 1 auto" download><i class="fa fa-download"></i> Download</a>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                <!-- Brouchers -->
                <div id="brouchers" class="tab-pane fade in">
                  @if(Count($show_data_brouchers) < 1) 
                  @endif
                  <div class="row">
                    @foreach($show_data_brouchers as $data)
                    <?php $new_str = str_replace(' ', '', $data['category']); ?>
                    <div class="col-lg-4 col-md-6">
                      <img src="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" alt="" class="img img-thumbnail">
                      <div style="display:flex;align-items:center;justify-content:space-between;column-gap:10px;margin-top:10px;">
                        <a href="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" class="btn btn-success" style="flex:1 1 auto" target="_blank"><i class="fa fa-eye"></i> View</a>
                        <a href="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" class="btn btn-info" style="flex:1 1 auto" download><i class="fa fa-download"></i> Download</a>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                <!-- Tips -->
                <div id="billborads" class="tab-pane fade in">
                  @if(Count($show_data_bill_board) < 1) 
                  @endif
                  <div class="row">
                    @foreach($show_data_bill_board as $data)
                    <?php $new_str = str_replace(' ', '', $data['category']); ?>
                    <div class="col-lg-4 col-md-6">
                      <img src="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" alt="" class="img img-thumbnail">
                      <div style="display:flex;align-items:center;justify-content:space-between;column-gap:10px;margin-top:10px;">
                        <a href="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" class="btn btn-success" style="flex:1 1 auto" target="_blank"><i class="fa fa-eye"></i> View</a>
                        <a href="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" class="btn btn-info" style="flex:1 1 auto" download><i class="fa fa-download"></i> Download</a>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                <div id="tips" class="tab-pane fade in">
                  <div class="row">
                    @if(Count($show_data_tip) < 1) 
                    @endif
                    <table id="example-1" class="table table-bordered dt-responsive display w-100">
                      <thead>
                        <tr>
                          <th style="width:5%">Serial#</th>
                          <th style="width:5%">Document</th>
                          <th>Description</th>
                          <th>Action</th>                    
                        </thead>
                        <tbody>
                          @php 
                          $count = 1;
                          @endphp
                          @foreach($show_data_tip as $data)
                          <?php $new_str = str_replace(' ', '', $data['category']); ?>
                          <tr>
                            <td>{{$count++}}</td>
                            <td> <img src="/img/pdf-logo.png" alt="" style="width:80px"></td>
                            <td>{{$data['short_description']}}</td>
                            <td> <a href="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" class="btn btn-primary btn-xs" style="flex:1 1 auto" target="_blank"><i class="fa fa-eye"></i> View</a>
                              <a href="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}" class="btn btn-info btn-xs" style="flex:1 1 auto" download><i class="fa fa-download"></i> Download</a></td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div id="promotion-videos" class="tab-pane fade in">
                      <div class="row">
                        @if(Count($show_data_video) < 1) 
                        @endif
                        @foreach($show_data_video as $data)
                        <?php $new_str = str_replace(' ', '', $data['category']); ?>
                        <div class="col-lg-4 col-md-6">
                          <div style="margin-top:10px;">
                            <video controls class="properties">
                              <source src="{{asset('marketing'.'/'.$data['reseller_id'].'/'.$new_str.'/'.$data['file_name'])}}">
                              </source>
                            </video>
                          </div>
                        </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </section>
      </div>
      @endsection
<!-- Code Finalize -->