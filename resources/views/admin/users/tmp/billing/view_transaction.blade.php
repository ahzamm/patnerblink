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
            <h2>View Transaction</h2>
          </div>
          
         
      <div class="table-responsive">
        <table id="example-1" class="table table-striped dt-responsive display">
          <thead class="text-primary" style="background:#225094c7;">
            <tr>
              <th class="th-color">#</th>
              <th class="th-color">Username</th>
              <th class="th-color">Transfer Amount</th>
              <th class="th-color">Last Balance</th>
              <th class="th-color">Total Balance</th>
              <th class="th-color">Transfer By</th>  
                
              <th class="th-color">Date</th>
              
              <th class="th-color">IP</th>
              
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>1Mb</td>
              <td>2121</td>
              <td>322322</td>
              <td>322322</td>
              <td>ali</td>
              <td>3/2/2018</td>
              <td>192.168.100.1</td>
              
              </tr>
             
                </tbody>
                
              </table>
            </div>
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

  </div>
  @endsection
  @section('ownjs')


  
  @endsection