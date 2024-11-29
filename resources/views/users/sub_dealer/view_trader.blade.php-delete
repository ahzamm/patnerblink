@extends('users.layouts.app')
@section('title') Dashboard @endsection
@include('users.dealer.subDealerFreeze')
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
          <a href="{{('#myModal-5')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fa fa-users"></i> Add Trader </button></a>
         
          <div class="header_view">
            <h2>View Trader</h2>
          </div>


          <div class="table-responsive">
             @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                  {{session('success')}}
              </div>
            @endif
            @if(count($errors) > 0)
              <div class="alert alert-danger">
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
                  <th class="th-color">Sno</th>
                  <th class="th-color">Username</th>
                  <th class="th-color">Full-Name</th>
                  
                  <th class="th-color">Dealer-ID</th>
                  <th class="th-color">Sub-Dealer-ID</th>
                  <th class="th-color">Trader-ID</th>
                  <th class="th-color">T-Users</th>
                  <th class="th-color">Actions</th>
                </tr>
              </thead>
              @php
              $count=1;
              @endphp
             <tbody>
                @foreach($traderCollection as $data)

                <?php
 
                $mob = '';
                $cnic = '';
             
                    $isverify = App\model\Users\UserVerification::where('username',$data->username)->select('mobile_status','cnic')->get();
                    foreach ($isverify as $value) {
                      $mob = $value['mobile_status'];
                      $cnic = $value['cnic'];
                     
                    }
              ?>
                <tr>
                  <td>{{$count++}}</td>
                  <td>{{$data->username}}<br>
                <?php if($cnic == ''){ ?>
                  <a href="{{route('users.user.verifyUser',['username' => $data->username])}}"><span style="color:red; font-size:16px; text-decoration: none;" ><i class="fa fa-close"></i>Cnic Verify</span></a><br> 
                  <?php }else{?>
                   <button class="btn btn-link mb1 bg-olive btn-xs" style="color:green; font-size:16px; text-decoration: none;"> <i class="fa fa-check"> <br></i> Cnic Verified </button><br>
                   <?php } ?>
                   <?php if($mob != 1){ ?>
                     <a href="{{route('users.user.smsverify',['username' => $data->username])}}"><span style="color:red; font-size:16px; text-decoration: none;" ><i class="fa fa-close"></i> Mobile Verify</span></a> 
                     <?php }else{?>
                      <button class="btn btn-link mb1 bg-olive btn-xs" style="color:green; font-size:16px; text-decoration: none;"> <i class="fa fa-check"> <br></i>  Mobile Verified </button>
                      <?php } ?>

                  </td>
                  <td>{{$data->firstname}} {{$data->lastname}}</td>
                  
                  <td>{{$data->dealerid}}</td>
                  <td>{{$data->sub_dealer_id}}</td>
                  <td>{{$data->trader_id}}</td>
                  <td>{{ DB::table('user_info')
               ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
               ->where('user_status_info.card_expire_on', '>=', today())
               ->where(['status' => 'user','resellerid' => $data->resellerid,'dealerid' => $data->dealerid,'trader_id' => $data->trader_id])->count() }}</td>
                  <td>
                    <center><a href="{{route('users.user.show',['status' => 'trader','id' => $data->id])}}" >
                        <button class="btn btn-info btn-xs">
                      <i class="fa fa-user"> </i> View</button></a>
                      <a href="{{route('users.user.edit',['status' => 'trader','id' => $data->id])}}"><button class="btn btn-primary mb1 bg-olive btn-xs">
                        <i class="fa fa-edit"> </i> Edit
                      </button></a> <br>@php
                         $freezeCheck = App\model\Users\FreezeAccount::where('username',$data->username)->select('freeze')->first();
                    @endphp
                   @if($freezeCheck['freeze'] == 'yes')
                   <button class="btn btn-danger mb1 bg-olive btn-xs" onclick="showFreezeModel({{$data->id}});">
                      <i class="fa fa-check"> </i> Active Account 
                    </button>
                    @elseif($freezeCheck['freeze'] == 'yes')
                    <button class="btn btn-success mb1 bg-olive btn-xs" onclick="showFreezeModel({{$data->id}});">
                      <i class="fa fa-check"> </i> Freeze Account 
                    </button>
                    @else
                    <button class="btn btn-success mb1 bg-olive btn-xs" onclick="showFreezeModel({{$data->id}});">
                      <i class="fa fa-check"> </i> Freeze Account 
                    </button>
                    @endif 
                </center>
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
       

  
      @endsection
    @section('ownjs')
 <script>
   $(document).ready(function(){


setTimeout(function(){ 

  $('.alert').fadeOut(); }, 3000);
   });

 </script>
 <script>
    function showFreezeModel($id){
        
      $.ajax({
      type : 'get',
      url : "{{route('users.freeze.subdealershow')}}",
      data:'id='+$id,
      success:function(res){
        if(res == 'parentFreezed'){
          alert("You can't unFreeze your subdealer");
        }else if(res.freeze == 'yes'){
        $('#subIdActive').text(res.username);
        $('#subIdTextActive').text(res.subdealerid);
        $('#freezeDate').text(res.freezeDate);
        $("#usernameActive").val(res.username);
          $('#activeModel').show();
          $('#freezeModal').hide();
          $('#subdealerfreeze').modal('show');
       }else if(res.freeze == 'no'){

        $('#subId').text(res.username);
        $('#subIdText').text(res.subdealerid);

        $("#username").val(res.username);
        $('#activeModel').hide();
          $('#freezeModal').show();
        $('#subdealerfreeze').modal('show');
       }else{
        $('#subId').text(res.username);
        $('#subIdText').text(res.sub_dealer_id);

        $("#username").val(res.username);
        $('#activeModel').hide();
          $('#freezeModal').show();
        $('#subdealerfreeze').modal('show');
      }
      }
      });
    }
    </script>
 @endsection