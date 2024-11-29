@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">

.dataTables_filter{
  margin-left: 60%;
}
tr,th,td{
  text-align: center;
}

</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">

  <!-- SIDEBAR - START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>

      <div class="col-lg-12">
        <a href="{{('#add_support')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fa fa-user"></i> Add Helpdesk Agent </button></a>
        <div class="header_view">
            <h2>Helpdesk Agents</h2>
          </div>
      <!--  -->

            @if(session('success'))

              <div class="alert alert-success alert-dismissible">
                  {{session('success')}}
              </div>
            @endif

      <div class="col-lg-12">
              <section class="box ">
                <header class="panel_header">
                  <h2 class="title pull-left"></h2>

                </header>
                <div class="content-body">   
                  <div class="row">
                    <div class="col-md-12">
                      <form>
                      <div style="overflow-x: auto;">
                        <table class="table ">
                          <thead>
                            <tr>
                              <th style="width:25px">Serial#</th>
                              <th>Username</th>
                              <th>Full Name</th>
                              <th>Email Address</th>
                              <th>Mobile Number</th>
                              <th>CNIC Number</th>
                            
                             
                            
                              
                            </tr>
                          </thead>

                          <tbody>
                            @php 
                            $count=1;
                            @endphp
                            @foreach($support as $data)
                            <tr>
                              <td>{{$count++}}</td>
                              <td>{{$data->username}}</td>
                              <td>{{$data->firstname}}</td>
                              <td>{{$data->email}}</td>
                              <td>{{$data->mobilephone}}</td>
                              <td>{{$data->nic}}</td>
                             
                            
                              
                            </tr>
                            @endforeach

                          </tbody>
                        </table>
                      </div>
                    </form>

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


@include('admin.users.model_add_support')


@endsection
@section('ownjs')
 <script>
   $(document).ready(function(){


setTimeout(function(){ 

  $('.alert').fadeOut(); }, 3000);
   });

 </script>
 @endsection