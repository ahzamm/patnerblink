@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">

  <!-- SIDEBAR - START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row">

      <div class="">
        <div class="header_view">
            <h2>Cash Receipt Amount <span style="color: lightgray"><small>(History)</small></span></h2>
            </div>
      <!--  -->
      <div class="col-lg-12">
              <section class="box ">
                <header class="panel_header">
                  <h2 class="title pull-left"></h2>

                </header>
                <div class="content-body">   
                  <div class="row">
                    <div class="col-md-12">
                      <div style="overflow-x: auto;">
                        <table id="example-1" class="table ">
                          <thead>
                            <tr>
                              <th style="width:25px">Receipt No.</th>
                              <th>Username</th>
                              <th>Recieved Amount (Rs.)</th>
                              <th>Recieved By</th>
                              <th>Action Date & Time</th>
                              <th>IP Address</th>
                              
                            </tr>
                          </thead>

                          <tbody>
                            @php $sno = 100000; @endphp
                            @foreach($paymentTransactions as $data)
                            <tr>
                            <td>{{++$sno}}</td>
                            <td>{{$data->sender}}</td>
                            <td>{{$data->amount}}</td>
                            <td>{{$data->admin}}</td>
                            <td>{{$data->date}}</td>
                            <td>{{$data->action_ip}}</td>
                            
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>

                    </div>



                  </div>

                </div>
              </section></div>
      <!--  -->
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