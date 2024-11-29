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
          <a href="{{('#myModal-1')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fa fa-users"></i> Add Dealer </button></a>
          <div class="header_view">
            <h2>View Dealer</h2>
          </div>


          <div class="table-responsive">
            <table id="example-1" class="table table-striped  display">
              <thead class="text-primary" style="background:#225094c7;">
                <tr>
                    <th class="th-color">Sno</th>
                  <th class="th-color">Username</th>
                  <th class="th-color">Full-Name</th>
                  <th class="th-color">Reseller-ID </th>
                  <th class="th-color">Dealer-ID </th>
                  <th class="th-color">SubDealer</th>
                  <th class="th-color">Users</th>
                  
                  <th class="th-color">Actions</th>
                </tr>
              </thead>
                @php
            $count=1;
             @endphp
              <tbody>
               @foreach($dealerCollection as $data)
               <tr>
                <td>{{$count++}}</td> 
                <td>{{$data->username}}</td>
                <td>{{$data->firstname}}</td>
                <td>{{$data->resellerid}}</td>
                
                <td>{{$data->dealerid}}</td>
                <td>{{App\model\Users\UserInfo::where(['status' => 'subdealer','resellerid' => $data->resellerid,'dealerid' => $data->dealerid])->count()}}</td>
                
                <td>{{App\model\Users\UserInfo::where(['status' => 'user','resellerid' => $data->resellerid,'dealerid' => $data->dealerid])->count()}}</td>
                <td>
                  <center><a href="{{route('admin.user.show',['status' => 'dealer','id' => $data->id])}}" >
                    <button class="btn btn-info btn-xs">
                      <i class="fa fa-user"> </i> View </button></a>
                      <a href="{{route('admin.user.edit',['status' => 'dealer','id' => $data->id])}}"><button class="btn btn-primary mb1 bg-olive btn-xs">
                        <i class="fa fa-edit"> </i> Edit
                      </button></a> </center>
                    </td>
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
    <!---Model Dialog --->


  </div>
  <!---Model Dialog --->
  @include('admin.users.model',['managerIdList' => $managerIdList,'resellerIdList' => $resellerIdList])
  @endsection
  @section('ownjs')
  
  <script type="text/javascript">

    $(document).ready(function() {
      var table = $('#mytable').DataTable();


    } );

    $(document).ready(function(){


      setTimeout(function(){ 

        $('.alert').fadeOut(); }, 3000);
    });

  </script>
  @endsection

