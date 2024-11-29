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
  #loadingnew{
    display: none;
  }
  #chargeBtn:hover{
    background-color: green;
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
            <h2>Recharge Account
            <span class="info-mark" onmouseenter="popup_function(this, 'singal_recharge_consumer');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>

          <section class="box">
            <!-- <header class="panel_header">
             <h2 class="title">Recharge Accounts </h2>
             
           </header> -->
           <div class="content-body">
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

            <div class="row">

             <!-- <div class="col-md-12"> -->
              <form action="{{route('users.single.charge')}}" method="POST">
                @csrf
                <input type="hidden" value="{{$username}}" name="username"/>
                <input type="hidden" value="{{$name}}" name="profileGroupname"/>
                    <!-- <div class="col-md-6">

                        <div class="form-group">
                                <label  class="form-label">Consumer (ID)</label>
                                <input type="text" value="{{$username}}" name="username" class="form-control"  placeholder=""  readonly/>
                        </div>

                    
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label  class="form-label">Current Internet Profile</label>
                            <input type="text" value="{{$name}}" name="profileGroupname" class="form-control"  placeholder=""  readonly/>
                        </div>

                      </div> -->







                      <!-- </div> -->

                      <div class="col-md-12">

                        <div class="modal-body">
                          <p style="font-size: 18px"><span>{{$data['chargeBy']}}</span> you have <span><strong>{{number_format($data['walletAmount'],2)}} (PKR)</strong></span> in your wallet. </p>
                          <h2><span style="font-size: 16px">Consumer ID:</span> {{$username}}</h2>


                          <?php if($data['info']->company_rate == 'no'){ ?>

                            <div class="table-responsive" style="border: 1px solid #0d4dab">
                             <table class="table tax_table">
                              <thead>
                               <tr>
                                 <th>Current Internet Profile</th>
                                 <th>Internet Profile Rate  (PKR)</th>
                                 <th>Sindh Sales Tax  (PKR)</th>
                                 <th>Advance Income Tax  (PKR)</th>
                                 <?php if($data['tax'] > 0 ){ ?>
                                  <th>Contractor Commission Tax (PKR)</th>
                                <?php } if($data['staticIPAmnt'] > 0 ){ ?>
                                  <th>Static IP Rate (PKR)</th>
                                <?php } ?>
                                <th>Grand Total (PKR)</th>
                              </tr>
                            </thead>
                            <tbody>
                             <tr>
                              <td>{{$name}}</td>
                              <td>{{number_format($data['profileRate'],2)}}</td>
                              <td>{{number_format($data['sst'],2)}}</td>
                              <td>{{number_format($data['adv'],2)}}</td>
                              <?php if($data['tax'] > 0 ){ ?>
                                <td>{{number_format($data['tax'],2)}} <small style="color:green;">({{$data['taxStatus']}})</small></td>
                              <?php } if($data['staticIPAmnt'] > 0 ){ ?>
                                <td>{{number_format($data['staticIPAmnt'],2)}}</td>
                              <?php } ?>
                              <td><strong style="font-size: 16px; color: darkgreen">{{number_format($data['total'],2)}}</strong></td>
                            </tr>
                          </tbody>
                        </table>
                      </div> 

                    <?php } ?>




                    <p style="font-size: 20px; margin-top: 20px;">The recharge amount is <span style="color: darkgreen"><strong>{{number_format($data['wallet_deduction'],2)}} (PKR)</strong></span>. After recharge the amount in your wallet will be <span><strong>{{number_format($data['wallet_after'],2)}} (PKR)</strong></span></p>
                    <hr style="margin-top: 20px">
                    <p style="font-size: 18px; color: #d16565;text-align:center">Please provide the correct information regarding (Consumer) CNIC for taxation purpose. However, Company is not responsible for any false information.</p>
                    <p style="text-align: center; color: #d16565;font-size: 16px">براہ کرم ان صارف کا ٹیکس ادا کرنے کے لیے اپنے شناختی کارڈ کی صحیح معلومات دیں- غلط معلومات کی صورت میں کمپنی زمہ دار نہیں ہو گی
                    براہ کرم صارفین تک کمپنی کی انوائس کی رسائی کو یقینی بنائیں- شکریہ</p>

                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group pull-right">

                    <button type="submit" class="btn btn-primary"  id="chargeBtn">Recharge Now</button>
                    <img src="{{asset('img/loading.gif')}}" id="loadingnew" width="15%" >
                  </div>
                </div>
              </form>
            </div>
            
          </div>
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

</div>
@endsection
@section('ownjs')

<script type="">


 $('#chargeBtn').click(function(){
  $('#chargeBtn').hide();
  $('#loadingnew').show();
});


</script>

<script>



 $(document).ready(function(){


  setTimeout(function(){ 

    $('.alert').fadeOut(); }, 3000);
});

</script>

<script type="text/javascript">

	function onProfileChange(profileGroupName){
		profileGroupName = profileGroupName.value
		console.log("profileGroupName: " + profileGroupName);
		// ajax call: jquery
   $.post(
    "{{route('users.ajax.charge.profileGroupWiseUsers')}}",
    {
     "profileGroupName" : ""+profileGroupName+""
   },
   function(data){
     console.log(data);
     let content = "<option>Select Username</option>";
     $.each(data,function(i,user){
      if(user.user_status_info_expired){
        content += "<option value="+user.username+">"+user.username+"</option>";	
      }
    });
     $("#select-username").empty().append(content);
   });
 }


 $(document).ready(function() {
  var table = $('#mytable').DataTable();
} );

</script>
@endsection
