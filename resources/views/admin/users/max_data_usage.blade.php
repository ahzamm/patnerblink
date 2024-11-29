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
        <div class="header_view">
            <h2>Max Data Usage</h2>
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
                              <th style="width:25px">Sno</th>
                              <th>USER_ID</th>
                              <th>DEALER_ID</th>
                              <th>SUB-DEALER</th>
                              <th>PROFILE</th>
                              <th>NO OF LOGIN</th>
                              <th>LAST DC</th>
                              <th>LAST LOGIN</th>
                              <th>UPLOAD</th>
                              <th>DOWNLOAD</th>
                              <th>AVERAGE USAGE Up/Down</th>
                              
                            </tr>
                          </thead>

                          <tbody>
                            <tr>
                              
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>


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