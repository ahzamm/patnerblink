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
          <a href="{{('#add_user')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fa fa-users"></i> Add Customers </button></a>
          <div class="header_view">
            <h2>View Customers</h2>
          </div>
        
       
          <div class="table-responsive">
             @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  {{session('success')}}
              </div>
            @endif
            @if(count($errors) > 0)
              <div class=" alert alert-danger">
                <ul>
                  @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <table id="example-1" class="table table-striped dt-responsive display">
                <thead class="text-primary" style="background:#225094c7;">
                <tr>
                <th class="th-color">Username</th>
                <th class="th-color">Full-Name</th>
                <th class="th-color">Reseller-ID</th>
                <th class="th-color">Dealer-ID</th>
                <th class="th-color">Sub-Dealer-ID</th>
                <th class="th-color">Actions</th>
              </tr>
              </thead>
              
               <tbody>
                @foreach($usersCollection as $data)
                <tr>
                  <td>{{$data->username}}</td>
                  <td>{{$data->firstname}}</td>
                  <td>{{$data->resellerid}}</td>
                  <td>{{$data->dealerid}}</td>
                  <td>{{$data->sub_dealer_id}}</td>
              
                  <td>
                    <center><a href="{{route('users.user.show',['status' => 'user','id' => $data->id])}}" >
                      <button class="btn btn-info btn-xs">
                      <i class="fa fa-user"> </i> View</button></a>
                      <a href="{{route('users.user.edit',['status' => 'user','id' => $data->id])}}"><button class="btn btn-primary mb1 bg-olive btn-xs">
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

  </div>
  @endsection
  @section('ownjs')
 <script>
   $(document).ready(function(){


setTimeout(function(){ 

  $('.alert').fadeOut(); }, 3000);
   });

 </script>
 @endsection