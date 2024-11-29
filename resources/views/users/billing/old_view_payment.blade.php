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
 
 
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">

  <!-- SIDEBAR - START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>

      <div class="">
        <div class="col-lg-12">
         <!--  <a href="{{('#manage_payment_model')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fa fa-ellipsis-v"></i> Manage Payment </button></a>
         -->

         <div class="header_view">
          <h2>View Payment</h2>
        </div>


        <div class="table-responsive">
          <table id="example-1" class="table table-striped dt-responsive display">
            <thead class="text-primary" style="background:#225094c7;">
              <tr>
                <th class="th-color">#</th>
                <th class="th-color">Username</th>
                <th class="th-color">Amount Recieve</th>
                <th class="th-color">Discount</th>
                <th class="th-color">Remain Balance(CR)</th>
                <th class="th-color">Remain Balance(DR)</th>
                <th class="th-color">Transaction Date</th>
                <th class="th-color">Payment method</th>

              </tr>
            </thead>
            <tbody>
				@php $sno = 0; @endphp
              @foreach($paymentTransactions as $data)
              <tr>
                <td>{{++$sno}}</td>
                <td>{{$data->sender}}</td>
                <td>{{$data->amount}}</td>
                <td>{{$data->discount}}</td>
                <td>{{$data->current_credit}}</td>
                <td>{{$data->current_debit}}</td>
                <td>{{$data->date}}</td>
                <td>{{($data->is_cash) ? 'CASH' : 'CHEQUE'}}</td>


              </tr>
              @endforeach
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
<script type="text/javascript">



</script>