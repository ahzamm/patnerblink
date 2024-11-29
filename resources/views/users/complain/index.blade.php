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
  .rate {
    height: 40px;
    padding: 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: row-reverse;
  }
  .rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
  }
  .rate:not(:checked) > label {
    width:1em;
    height: 1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:40px;
    color:#ccc;
  }
  .rate:not(:checked) > label:before {
    content: '★ ';
  }
  .rate > input:checked ~ label {
    color: #ffc700;    
  }
  .rate:not(:checked) > label:hover,
  .rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
  }
  .rate > input:checked + label:hover,
  .rate > input:checked + label:hover ~ label,
  .rate > input:checked ~ label:hover,
  .rate > input:checked ~ label:hover ~ label,
  .rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
  }
</style>
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Complaint Feedback</small>
          <span class="info-mark" onmouseenter="popup_function(this, 'complaint_feedback');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <div>
        <div class="row">
          <div class="col-md-6">
            <div class="content-body" style="padding: 10px 20px">
              <a data-toggle="collapse" data-target="#more-cards" aria-expanded="true" style="cursor: pointer; display: block;color: #000; text-decoration: none;"><i class="fa fa-filter"></i> Filters</a>
              <div class="card-body">
                <div id="more-cards" class="collapse" style="padding: 20px 10px;">
                  <form action="" method="get">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">From Date</label>
                          <input type="date" class="form-control" name="fromDate">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">To Date</label>
                          <input type="date" class="form-control" name="toDate">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Receive Via</label>
                          <select class="form-control" name="via">
                            <option value="">All</option>
                            <option value="On Call">On Call</option>
                            <option value="Whatsapp">Whatsapp</option>
                            <option value="Email">Email</option>
                            <option value="SMS">SMS</option>
                            <option value="Portal">Portal</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label">Complaint Status</label>
                          <select class="form-control" name="status">
                            <option value="">All</option>
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                            <option value="Rejected">Rejected</option>
                          </select>            
                        </div>
                      </div>
                      <div class="col-12">
                        <button type="submit" class="btn btn-primary pull-right">Search</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 pl-lg-0">
            <div class="content-body" style="padding: 10px 20px">
              <div id="returnMsg"></div>
              <a data-toggle="collapse" data-target="#registerComplain" aria-expanded="true" style="cursor: pointer; display: block;color: #000; text-decoration: none;"><i class="fa fa-edit"></i> Register Complaint</a>
              <div class="card-body">
                <div id="registerComplain" class="collapse" style="padding: 20px 10px;">
                  <form id="registerComplainForm">
                    @csrf
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="form-label">Complaint Nature</label>
                          <select class="form-control js-select2" name="complaint_nature_id" required style="padding: 0">
                            <option value="">select</option>
                            <?php foreach($complainNatureAPI_Response as $value){?>
                              <option value="<?= $value['id'];?>"><?= $value['name'];?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="form-label">Description <span class="text-danger" style="font-size: 13px">(Max length 150 character)</span></label>
                          <textarea class="form-control w-100" name="description" rows="1" required ></textarea>            
                        </div>
                      </div>
                      <div class="col-12">
                        <input type="submit" class="btn btn-primary pull-right" value="Register">
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <section class="box">
          <div class="content-body" style="padding-top:20px">
            <table id="example-1" class="table display dt-responsive w-100 display">
              <thead>
                <tr>
                  <th style="width:25px">Serial#</th>
                  <th>Complainer</th>
                  <th>Ticket #</th>
                  <th>Receive Via</th>
                  <th>Nature</th>
                  <th>Priority</th>
                  <th>Compaint Status</th>
                  <th>Registered By</th>
                  <th>Resolved By</th>
                  <th>Feedback</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $sessid = Auth::user()->id;
                if($data != 'No Data Found'){
                  foreach($data as $key => $value){ ?>
                    <tr>
                      <td><?= $key+1;?></td>
                      <td><?php 
                      $complainerInfo = DB::table('user_info')->where('id',$value['user_info_id'])->select('username','status')->first();
                      $userStatus = ($complainerInfo->status == 'dealer') ? 'contractor' : (($complainerInfo->status == 'subdealer') ? 'trader' : (($complainerInfo->status == 'user') ? 'consumer' : $complainerInfo->status )  );
                      echo $complainerInfo->username.'<br><span class="badge badge-success">' . $userStatus . '</span>';?></td>
                      <td><b><?= 'TKTID'.str_pad($value['id'], 7, '0', STR_PAD_LEFT)?></b></td>
                      <td><?= $value['recieved_on'];?></td>
                      <td><?= $value['complaint_nature'];?></td>
                      <td><?php
                      if($value['priority'] == 'high'){
                        $priority = '<div class="badge p-1 text-white badge-danger">High</div>';
                      }
                      if($value['priority'] == 'low'){
                        $priority = '<div class="badge p-1 text-white" style="background-color: #4260fd">Low</div>';
                      }
                      if($value['priority'] == 'medium'){
                        $priority = '<div class="badge p-1 text-white" style="background-color: #ff5907">Medium</div>';
                      }
                      echo $priority;
                      ?></td>
                      <td style="color:black;"><?php 
                      if($value['status'] == "Pending" || $value['status'] == "pending"){
                        $complaintStatus = '<div class="badge p-1 text-white badge-warning"><span class="blink" style="color: #000">Open</span></div>';
                      }
                      if($value['status'] == "Resolved" || $value['status']=="resolved"){
                        $complaintStatus = '<div class="badge p-1  badge-success">Closed</div>';
                      }
                      if($value['status'] == "Rejected" || $value['status']=="rejected"){
                        $complaintStatus = '<div class="badge p-1  badge-danger">Rejected</div>';
                      }
                      echo $complaintStatus;
                      ?></td>
                      <td><?= $value['registered_by'].'<br>'.date('M d, Y H:i:s',strtotime($value['created_at']));?></td>
                      <td><?php 
                      if(!empty($value['resolved_date'])){
                        echo $value['resolved_by'].'<br>'.date('M d, Y H:i:s',strtotime($value['resolved_date']));
                      }else{ echo 'Under Process...';}?></td>
                      <td>
                        <?php if($value['complaint_id']){ ?>
                          <div data-toggle="tooltip" data-title="{{$value['feedback']}}" style="color: #ffc700">
                            <?php for($i=0;$i<$value['rating'];$i++ ){?>
                              <i class="fa fa-star"></i>
                            <?php }?>
                          </div>
                        <?php }else{?>
                          <?php if($value['status'] == 'Resolved' && $value['user_info_id'] == $sessid ){?>
                            <a href="#feedbackModal" data-toggle="modal" class="btn btn-primary btn-sm feedbackbtn" data-id="{{$value['id']}}" >Feedback</a>
                          <?php }else{?>
                            <span class="info-mark" data-toggle="tooltip" data-title="<?= $complainerInfo->username;?> have rights to give feedback"><i class="las la-info-circle"></i></span>
                          <?php }?>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php } } ?>
                </tbody>
              </table>
            </div>
          </section>
        </section>
      </section>
    </div>
    <!-- Feedback Modal -->
    <div class="modal" id="feedbackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-dialog-scrollable modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" style="color: white">Feedback</h4>
          </div>
          <div class="modal-body">
            <p style="margin-bottom:20px">Please rate us our complain service</p>
            <form id="complainFeedBack">
              <input type="hidden" name="id" id="complainid">
              <div class="rate">
                <input type="radio" id="star5" name="rate" value="5"  />
                <label for="star5" title="5 Start">5 stars</label>
                <input type="radio" id="star4" name="rate" value="4" />
                <label for="star4" title="4 Start">4 stars</label>
                <input type="radio" id="star3" name="rate" value="3" />
                <label for="star3" title="3 Start">3 stars</label>
                <input type="radio" id="star2" name="rate" value="2" />
                <label for="star2" title="2 Start">2 stars</label>
                <input type="radio" id="star1" name="rate" value="1" />
                <label for="star1" title="1 Start">1 star</label>
              </div>
              <textarea name="feedback" id="feedback" rows="5" class="form-control" maxlength="100" placeholder="Write your feedback here" required style="resize:vertical"></textarea>
              <div class="d-flex justify-content-end" style="display: flex; justify-content: flex-end;margin-top: 15px">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Instruction Modal -->
    <div class="modal" id="instructionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-dialog-scrollable modal-md">
        <div class="modal-content" style="background-color: rgb(203 200 200 / 62%); backdrop-filter: blur(10px)">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" style="color: white">Announcement</h4>
          </div>
          <div class="modal-body">
            <p class="english-instruction"  style="font-size: 16px">
              We have introduced the complaint feedback function on the <?= strtoupper(Auth()->user()->resellerid);?> billing portal. The purpose of this function is to address your complaints promptly and effectively. We kindly request you to share your valuable feedback with us so that we can improve our services further and provide you with a better experience.</p>
              <hr style="background-color: #898989">
              <p class="urdu-instruction" style="text-align:right;font-size: 17px">
              ہم نے بلنگ پورٹل پر شکایتوں کی فیڈبیک فعال کر دی ہے۔ اس فعل کا مقصد آپ کی شکایات کو فوری اور مؤثر طریقے سے حل کرنا ہے۔ ہم آپ سے درخواست کرتے ہیں کہ براہ کرم اپنی قیمتی رائے ہمارے ساتھ شیئر کریں تاکہ ہم اپنی خدمات کو بہتر بنا سکیں اور آپ کو بہتر تجربہ فراہم کر سکیں۔      </p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade bs-example-modal-center custom-center-modal" id="processLayer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog modal-md">
          <div class="modal-content" style="background-color:#02020200 !important;border-color:#02020200 !important; ">
            <div class="modal-body">
              <center><h1 style="color:white;">Processing....</h1>
                <p style="color:white;">please wait.</p>
              </center>
            </div>
          </div>
        </div>
      </div>
      @endsection
      @section('ownjs')
      <script>
        $(document).ready(function() {
          $('#instructionModal').modal('show');
          $(document).on('click', '.kickBtn', function(e) {
            $(this).prop('disabled', true);
            $(this).html('processing..');
            e.preventDefault();
            var username = $(this).attr('data-id');
// alert(username);
$.ajax({
  type: "POST",
  url: "{{route('users.kickit')}}",
  data:'username='+username,
  success: function (data) {
    alert('Successfully Kicked');
    location.reload();
  },
  error: function(jqXHR, text, error){
    $("#outputt").html('<p style="color:red">Inavlid username or no record found</p>');
  }
});
return false;
});
        });
      </script>
      <script type="text/javascript">
        $(document).on('click','.feedbackbtn',function(){
          var id = $(this).attr('data-id');
          $('#complainid').val(id);
        });
        $("#complainFeedBack").submit(function() {
          $.ajax({ 
            type: "POST",
            url: "{{route('users.complain.give_feedback')}}",
            data:$("#complainFeedBack").serialize(),
            success: function (data) {
              $('#feedbackModal').modal('hide');
              alert('Thank you for your feedback.');
              location.reload();
            },
            error: function(jqXHR, text, error){
              alert(jqXHR.responseJSON.message);      
            },
            complete:function(){
            },
          });
          return false;
        })
      </script>
      <script type="text/javascript">
        $("#registerComplainForm").submit(function() {
          if(confirm("Do your really want register this complain?")){
            $('#processLayer').modal('show');
            $.ajax({ 
              type: "POST",
              url: "{{route('users.complain.generate_complaint')}}",
              data:$("#registerComplainForm").serialize(),
              success: function (data) {
                $('#processLayer').modal('hide');
                $('#returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
                $('#registerComplainForm').trigger('reset');
//
},
error: function(jqXHR, text, error){
// alert(jqXHR.responseJSON.message);
$('html, body').scrollTop(0);
$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
$('#processLayer').modal('hide');
},
complete:function(){
},
});
          }
          return false;
        })
      </script>
      @endsection
<!-- Code Finalize -->