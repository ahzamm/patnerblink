@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">

   .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1; /* Sit on top */
      padding-top: 100px; /* Location of the box */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
   }
   /* Modal Content */
   .modal-content {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
   }
   /* The Close Button */
   .close {
      color: #aaaaaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
   }
   .close:hover,
   .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
   }
   
</style>
@endsection
@section('content')

<div class="page-container row-fluid container-fluid">
   <section id="main-content">
      <section class="wrapper main-wrapper">
         <div class="header_view">
            <h2>Partner Profit Margin Report
               <span class="info-mark" onmouseenter="popup_function(this, 'billing_summary');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
         </div>

         <?php
         $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
         $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
         $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
         $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
         $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
            //
         if(empty($resellerid)){
            $panelof = 'manager';
         }else if(empty($dealerid)){
            $panelof = 'reseller';
         }else if(empty($sub_dealer_id)){
            $panelof = 'dealer';
         }else{
            $panelof = 'subdealer'; 
         }
         ?>


         <section class="box">
            <header class="panel_header">
               <div class="actions panel_actions pull-right">
                  <a class="box_toggle fa fa-chevron-down"></a>
               </div>
            </header>
            <div class="content-body">
               <form action="{{route('user.users.ppm_action')}}" target="_blank" method="POST">
                  @csrf
                  <div class="row">
                     <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                           <label  class="form-label">Select Billing Cycle (MM/DD/YY) <span style="color: red">*</span></label>
                           <div class="controls" style="margin-top: 0px;">
                              <select class="form-control" name="date" required="" oninvalid="this.setCustomValidity('Select Billing Cycle')" onchange="this.setCustomValidity('')">
                                 <option value="">Select (MM/DD/YY)</option>
                                 @php
                                 $from=date('2023-08-24');
                                 $startTime = strtotime($from);
                                 $now = time();
                                 $datediff =($now-$startTime);
                                 $range=floor(($datediff / (60 * 60 * 24))+1);

                                 for($i= $range;$i > 0;$i--){
                                    $date = date('Y-m-d',strtotime($from ."+".$i." days"));
                                    $newdate=explode("-",$date);
                                    if($newdate[2] == 10 || $newdate[2] == 25){
                                       @endphp
                                       <option value="{{$date}}">{{date('M d,Y' ,strtotime($date))}}</option>
                                       @php
                                    }

                                 }
                                 @endphp
                              </select>
                           </div>
                        </div>
                     </div>
                      

                    
                  </div>

                 
                  


                  <div class="row">
                     <?php
                     if($panelof == 'manager'){
                        $selectedReseller = App\model\Users\UserInfo::where('status','reseller')->where('manager_id',Auth::user()->manager_id)->get(); ?>

                        <div class="form-group col-md-4">
                           <label style="font-weight: normal">Select Reseller <span style="color: red">*</span></label>
                           <select id="reseller-dropdown" class="js-select2" name="reseller_data">
                              <option value="">-- Select reseller --</option>
                              @foreach($selectedReseller  as $reseller)
                              <option value="{{$reseller->username}}">{{$reseller->username}}</option>
                              @endforeach
                           </select>
                        </div>

                     <?php } if(($panelof == 'manager') || ($panelof == 'reseller')){  ?>
                        <div class="form-group col-md-4">
                           <label style="font-weight: normal">Select Contractor <span style="color: red">*</span></label>
                           <select id="dealer-dropdown" class="js-select2" name="dealer_data">
                              <option value="">-- Select contractor --</option> 
                              <?php
                              if(Auth::user()->status == 'reseller' || Auth::user()->status == 'inhouse'){
                                 $selectedDealer = App\model\Users\UserInfo::where('status','dealer')->where('resellerid',Auth::user()->resellerid)->get(); 
                                 foreach ($selectedDealer as $dealer) { ?>
                                    <option value="{{$dealer->username}}">{{$dealer->username}}</option>
                                    <?php   
                                 } 
                              }
                              ?>
                           </select>
                        </div>

                     <?php } if(($panelof == 'manager') || ($panelof == 'reseller') || ($panelof == 'dealer') ){ ?>
                        <div class="form-group col-md-4">
                           <label style="font-weight: normal">Select Trader <span style="color: red">*</span></label>
                           <select id="trader-dropdown" class="js-select2" name="trader_data">
                              <option value="">-- Select trader --</option>
                              <?php
                              if(Auth::user()->status == 'dealer'){
                                 $selectedDealer = App\model\Users\UserInfo::where('status','subdealer')->where('dealerid',Auth::user()->dealerid)->get(); 
                                 foreach ($selectedDealer as $subdealer) { ?>
                                    <option value="{{$subdealer->username}}">{{$subdealer->username}}</option>
                                 <?php  } 
                              } ?>
                           </select>
                        </div>
                     <?php } ?>
                     <div class="col-xs-12">
                        <div class="form-group pull-right" style="margin-top: 5px;">
                           <button class="btn btn-flat btn-primary" id="btn_generate">Generate</button>
                           <!-- <button class="btn btn-flat btn-success" value="download" name="download"><i class="fa fa-download"></i> Download</button> -->
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </section>
      </section>
   </section>
</div>
<!---Model Dialog --->
@endsection
@section('ownjs')

<script>
   $(document).ready(function() {
      $('#example1').DataTable( {
       "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
       "footerCallback": function ( row, data, start, end, display ) {
         var api = this.api(), data;
         var totalCol = $('#totalHeading').attr('colspan');
              // Remove the formatting to get integer data for summation
         var intVal = function ( i ) {
            return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1 :
            typeof i === 'number' ?
            i : 0;
         };

              // Total over all pages
         total = api
         .column( totalCol )
         .data()
         .reduce( function (a, b) {
            return intVal(a) + intVal(b);
         }, 0 );

              // Total over this page
         pageTotal = api
         .column( totalCol, { page: 'current'} )
         .data()
         .reduce( function (a, b) {
            return intVal(a) + intVal(b);
         }, 0 );

         pageTotal = pageTotal.toFixed(2);
              // Update footer
         $( api.column( totalCol ).footer() ).html(pageTotal);

      }

   } );
   } );
</script>
<script type="text/javascript">
   function formatMoney(n, c, d, t) {
     var c = isNaN(c = Math.abs(c)) ? 2 : c,
     d = d == undefined ? "." : d,
     t = t == undefined ? "," : t,
     s = n < 0 ? "-" : "",
     i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
     j = (j = i.length) > 3 ? j % 3 : 0;
     return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
  }
</script>
<script>
   $(function() {
     $('input[name="datetimes"]').daterangepicker({
       timePicker: true,
       startDate: moment().startOf('hour'),
       endDate: moment().startOf('hour'),
       locale: {
         format: 'M/DD/YYYY HH:mm'
      }
   });

  });
</script>

<script>
  $(document).ready(function () {

   $('#reseller-dropdown').on('change', function () {
    var reseller_id = this.value;

    $("#dealer-dropdown").html('');
    $.ajax({
     url: "{{route('get.dealer')}}",
     type: "POST",
     data: {
      reseller_id: reseller_id,
      _token: '{{csrf_token()}}'
   },
   dataType: 'json',
   success: function (result) {
      $('#dealer-dropdown').html('<option value="">-- Select contractor --</option>');
      $.each(result.dealer, function (key, value) {
       $("#dealer-dropdown").append('<option value="' + value
        .username + '">' + value.username + '</option>');
    });
      $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
   }
});
 });

             /*------------------------------------------
            --------------------------------------------
            Trader Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
   $('#dealer-dropdown').on('change', function () {

    var dealer_id = this.value;

    $("#trader-dropdown").html('');
    $.ajax({
     url: "{{route('get.trader')}}",
     type: "POST",
     data: {
      dealer_id: dealer_id,
      _token: '{{csrf_token()}}'
   },
   dataType: 'json',
   success: function (result) {
      $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
      $.each(result.subdealer, function (key, value) {
       $("#trader-dropdown").append('<option value="' + value
        .username + '">' + value.username + '</option>');
    });
   }
});
 });

});
</script>

@endsection