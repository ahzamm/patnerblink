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
  .ttable{
    overflow: hidden;
    overflow-y: scroll;
    max-height: 350px;
    width: 100%;
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
            <h2>Manage MRTG Graph / DHCP Server </h2>
          </div>
          <section class="box" style="padding: 15px;">
            <div>
              @if(session('error'))
            <div class="alert alert-error alert-dismissible">
              {{session('error')}}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
              {{session('success')}}
            </div>
            @endif
            </div>
          <form action="{{route('admin.postdhcpAli')}}" method="POST">
            @csrf
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <label  class="form-label">Contractor </label>
                  <select name="dealers" id="dealers" onchange="showsubdealer(this);" required class="form-control">
                    <option value="">Select Contractor</option>
                    @foreach ($dealer as $item)
                  <option value="{{$item->dealerid}}">{{$item->username}}</option>
                    @endforeach
                  </select>
                  </div>
                  <div class="form-group">
                    <label  class="form-label">Trader </label>
                    <select name="subdealers" id="subdealers" class="form-control">
                     
                    </select>
                    </div>
                <div class="form-group">
                  <label  class="form-label">MRTG Graph </label>
                  <input type="number" name="graph" value="" class="form-control" >
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                     <label for="" class="text-center">Select DHCP server</label>
                     <select name="dhcp_serverip" id="" class="form-control">
                      <option value="">Select Server</option>
                   <option value="0 none">None</option>
                   @foreach ($dhcp_server as $item)
                     <option value="{{$item['id'] }} {{$item['name']}}">{{$item['name']}}</option>
                     @endforeach
                   </select>
                    </div>
                    <div class="col-md-12" style="padding-top: 15px">
                      <button type="submit" class="btn btn-primary pull-right">Submit</button>
                    </div>
                 </div>
            </div>
             <div class="col-md-6">
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">MRTG Graph</a></li>
                <li><a data-toggle="tab" id="DDU" href="#dhcp">DHCP Server</a></li>
                <div class="tab-content">
                  <div id="home" class="tab-pane fade in active" >
                      <div class="ttable" >
                        <table class="table table-bordered table-hover">
                          <tr style="background: gray; color: white">
                            <th>Username</th>
                            <th>Graph ID</th>
                          </tr>
                          @php
                            $totalPkg = 0;
                          @endphp
                          @foreach ($cacti as $key => $cacti)
                          <tr>
                            <th>{{$cacti->user_id}}</th>
                            <td>{{$cacti->graph_no}}</td>
                          </tr>
                          @endforeach
                        </table>
                        </div>
                  </div>
                  <div id="dhcp" class="tab-pane">
                      <div class="table-responsive ttable">
                        <table class="table table-bordered table-hover ">
                          <tr style="background: gray; color: white">
                            <th>Username</th>
                            <th>Server Name</th>
                          </tr>
                         @foreach ($dhcp_table as $item)
                         @php
                             $serverName = App\model\Users\Dhcp_server::where('id',$item->server_id)->first();
                         @endphp
                          <tr>
                            <th>{{$item->dealerid}}</th>
                            <td>{{$serverName['name']}}</td>
                          </tr>
                          @endforeach
                        </table>
                        </div>
                  </div>
                </div>
              </ul>
             </div>
          </div>
        
          </form>
        </section>
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
@endsection
@section('ownjs')
<script>
   $(document).ready(function(){
setTimeout(function(){ 
  $('.alert').fadeOut(); }, 4000);
});
    function showsubdealer($username){
        $.ajax({
        type : 'get',
        url : "{{route('admin.loadSubDealers')}}",
        data:'username='+$username.value,
        dataType:"json",
        success:function(res){
      $("#subdealers").empty();
      $("#subdealers").append('<option value="">Select Subdealer</option>');
       $.each(res,function(key,value){
        $("#subdealers").append('<option value="'+value.username+'">'+value.username+'</option>');
       });
      }
        });
      }
</script>
@endsection