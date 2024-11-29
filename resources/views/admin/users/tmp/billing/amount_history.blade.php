@extends('admin.layouts.app')
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
            <h2>Current Balance Amount <span style="color: lightgray"><small>(Report)</small></span></h2>
          
          </div>


          <div class="col-lg-12">
            <section class="box ">
              <header class="panel_header">

                <div class="actions panel_actions pull-right">
                  <a class="box_toggle fa fa-chevron-down"></a>


                </div>
              </header>
              <div class="content-body collapsed " >   
                <div class="row">

                  <div style="overflow-x: auto;">
                    <table id="example-1" class="table table-responsive table-bordered">
                      <thead>
                        <tr>
                        <th>Serial#</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Balance Amount (Rs.)</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                        @foreach($userCollection as $data)
                       
                        <tr>
                          
                          <td>1</td>
                          <td>{{$data->username}}</td>
                          <td>{{$data->status}}</td>
                         
                          <td>{{$data->amount}}</td>
                        </tr>
                     @endforeach
                      </tbody>
                    </table>

                    </div>


              


                  </div>
                </div>
              </section>
            </div>

 
              <div class="col-lg-12"></div>
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
	<script></script>
<!-- Select User List -->
<script></script>

	@endsection
	
	
	
