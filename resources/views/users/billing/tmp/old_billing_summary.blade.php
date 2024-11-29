@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
.th-color{
  color: white !important;
  /*font-size: 15px !important;*/
}
.header_view{
  margin: auto;
  height: 40px;
  padding: auto;
  text-align: center;

  font-family:Arial,Helvetica,sans-serif;
}
h2{
  color: #225094 !important;
}
.dataTables_filter{
  margin-left: 60%;
}
tr,th,td{
  text-align: center;
}
select{
  color: black;
}

.slider:before {
  position: absolute;
  content: "";
  height: 11px !important;
  width: 13px !important;
  left: 3px !important;
  /*bottom: 3px !important;*/
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

.active{
  background-color: #225094 !important;
}
.title h2 {
  font-size: 30px;
  line-height: 30px;
  margin: 3px 0 7px;
  font-weight: 700;
}
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">

  <!-- SIDEBAR - START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>

      <div class="">
        <div class="col-lg-12">

          <div class="header_view">
            <h2>Billing Summary</h2>

          </div>

          	<div class="col-lg-12" style="background-color: white">
          		<h2>Package Wise:</h2>
          		<div style="overflow-x: auto;">
														<table class="table table-bordered table-hover table-bordered" style="border:2px #225094 solid !important;">
															<thead style="background: #225094;color: white">
																<tr >

																	<th>S.No</th>
																	<th>Package Name</th>
																	<th>Rate</th>
																	<th>Number of users</th>
																	<th>Total Amount</th>
																</tr>
															</thead>
															<tbody>
																@php $sno = 1; 
                
                
              @endphp
              @foreach($monthlyBillingEntries as $entry)
              @php
              if($selectedUser->status == "reseller"){
                  $rate= $entry->rate;
                 }elseif($selectedUser->status == "dealer"){
                 $rate= $entry->dealerrate;
               }elseif($selectedUser->status == "subdealer"){
               $rate= $entry->subdealerrate;
               $total = $rate*100;
              

                
             }
                
             @endphp

              <tr>
                              <td>{{$sno++}}</td>
                            
                              <td>{{$entry->profileR->name}}</td>
                              <td>{{$rate}}</td>
                            <td></td>
                            <td>{{$total}}</td>

                             
                            </tr>
              @endforeach
                          </tbody>
                        </table>							</div>			
													</div>

          	</div>
      
              <!--  -->
              <div class="col-lg-12">
                <section class="box ">
                  <header class="panel_header">
                    <h2 class="title pull-left">Billing Report</h2>
                    <div class="actions panel_actions pull-right">
                      <a class="box_toggle fa fa-chevron-down"></a>


                    </div>
                  </header>
                  <div class="content-body">
                   <div class="row">
  <form action="{{route('users.billing.summary.generate')}}" method="POST">
                                @csrf
                              <div class="col-md-5">
                                <div class="form-group">
                                  <label  class="form-label">Date Range</label>
                                  <div class="controls" style="margin-top: 0px;">
                                    <input type="text" id="daterange-1" 
                                    name="date" 
                                    class="form-control daterange">
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-5">
                                <div class="form-group">
                                  <label  class="form-label">Dealers/Sub Dealers Name</label>

                                  <select class="form-control" id="username-select" name="username" required >
                                  <option>select dealer</option>
                                  @foreach($userCollection as $dealer)
                                  <option value="{{$dealer->username}}">{{$dealer->username}}</option>
                                  @endforeach
                                </select>

                                </div>
                              </div>
                              <div class="col-md-2">
                                <br>
                                <div class="form-group" style="margin-top: 5px;">
                                  <button class="btn btn-flat btn-info">Search</button>
                                </div>
                              </div>
                            </form>

                    <!-- Report Summary -->
          
          @if($isSearched)
                    <div class="col-md-12" ">
                     <div style="overflow-x: auto;">
                        <table class="table table-bordered table-responsive" style="border: 2px #225094 solid">
                          <thead>
                            <tr>

                              <th colspan="5" style="background: #225094;color: white; font-weight: bold;">Report Summary</th>


                            </tr>
                            <tr>
                              <th>Manager Name</th>
                              <th>Reseller Name</th>
                              <th>Dealer Name</th>
                              <th>Sub Dealer Name </th>
                              <th>Net Payable Amount (PKR)</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>{{$selectedUser->manager_id}}</td>
                              <td>{{$selectedUser->resellerid}}</td>
                              <td>{{$selectedUser->dealerid}}</td>
                              <td>{{$selectedUser->sub_dealer_id}}</td>
                              <td>{{$monthlyPayableAmount}}</td>
                            </tr>
                          </tbody>

                        </table>
                      </div>
                      </div>
                    </div>

                    <div class="col-s-12" style="overflow-x: auto;">
                      <div style="border: 2px #225094 solid;">
                        <div style="">
                          <h3 style="color:white;background:#225094;height: 40px; margin-top: 0px;  padding-top: 7px;font-size: 18px;font-weight: bold; text-align: center;"> Monthly Billing Report</h3>
                        </div>
                        <table id="example-1" class="table table-striped dt-responsive display">
                          <thead>
                            <tr>
                              <th>S.No </th>
                              <th>Customer ID </th>
                              <th>Package </th>
                              <th>Service Activation Date </th>
                              <th>Service Billing Start Date  </th>
                              <th>Service Billing End Date </th>
                              
                              <th>Sub Dealer ID </th>
                              <th>Package Amount (PKR)</th>
                            </tr>
                          </thead>

                          <tbody>
              @php $sno = 1; 
                
                
              @endphp
              @foreach($monthlyBillingEntries as $entry)
              @php
              if($selectedUser->status == "reseller"){
                  $rate= $entry->rate;
                 }elseif($selectedUser->status == "dealer"){
                 $rate= $entry->dealerrate;
               }elseif($selectedUser->status == "subdealer"){
               $rate= $entry->subdealerrate;
             }
                
             @endphp

              <tr>
                              <td>{{$sno++}}</td>
                              <td>{{$entry->username}}</td>
                              <td>{{$entry->profileR->name}}</td>
                              <td>{{$entry->user_info->creationdate}}</td>
                              <td>{{$entry->user_info->user_status_info->card_charge_on}}</td>
                              <td>{{$entry->user_info->user_status_info->card_expire_on}}</td>
                              <td>{{$entry->sub_dealer_id}}</td>

                              <td>{{$rate}}</td>
                            </tr>
              @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
              
          @endif
                  </div>
                </div>

              </section></div>

            </div>

          </div>

          <div class="chart-container " style="display: none;">
            <div class="" style="height:200px" id="platform_type_dates"></div>
            <div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
            <div class="" style="height:200px" id="user_type"></div>
            <div class="" style="height:200px" id="browser_type"></div>
            <div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
            <div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
          </div>
        </section>
      </section>
      <!-- END CONTENT -->
      <!---Model Dialog --->
    </div>
    <!---Model Dialog --->

    @endsection
  
  @section('ownjs')
  
   
<!-- Select User List -->



  @endsection
  
  
  
