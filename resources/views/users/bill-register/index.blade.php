@extends('admin.layouts.app')
@section('title') Dashboard @endsection
<style type="text/css">
    .row{margin-left: 0px!important;margin-right: 0px!important}
</style>
@section('content')
<div class="page-container row-fluid container-fluid pt-2">
	<!-- SIDEBAR - START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row">
         <div class="clear-fix"></div>
         <div class="header_view">
            <h2>Bill Register
                <span class="info-mark" onmouseenter="popup_function(this, 'bulk_consumer_create_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
        </div>
        <div id="returnMsg"></div>
        <div class="row justify-content-center">
          <div class="card bg-white">
              <!-- <div class="card-header">Upload File</div> -->
              <div class="card-body" style="padding-top: 20px">

                 @if ($message = Session::get('success'))
                 <div class="alert alert-success alert-block">
                   <button type="button" class="close" data-dismiss="alert"><span class="fa fa-close"></span></button>
                   <strong>{{ $message }}</strong>
               </div>
               @endif

               @if ($message = Session::get('error'))
               <div class="alert alert-danger alert-block">
                   <button type="button" class="close" data-dismiss="alert"><span class="fa fa-close"></span></button>
                   <strong>{{ $message }}</strong>
               </div>
               @endif

               @if (count($errors) > 0)
               <div class="alert alert-danger">
                   <strong>Whoops!</strong> There were some problems with your input.<br>
                   <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="billRegisterFilter" method="get" action="{{route('user.bill-register.download_sheets')}}" >

              <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group position-relative">
                        <label>Select Reseller <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'select_reseller_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <select id="reseller-dropdown" class="js-select2" name="reseller_data">
                          <option value="">-- Select reseller --</option>
                          @foreach($selectedReseller  as $reseller)
                          <option value="{{$reseller->username}}">{{$reseller->username}}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="form-group position-relative">
                 <label>Select Contractor <span style="color: red">*</span></label>
                 <span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                 <select id="dealer-dropdown" class="js-select2" name="dealer_data">
                    <option value="">--- First Select Contractor ---</option>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
                <div class="form-group position-relative">
                 <label>Select Year <span style="color: red">*</span></label>
                 <span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                 <select class="js-select2" name="year" id="filterYear" required>
                    <option value="">select year</option>
                    <option>2021</option>
                    <option>2022</option>
                    <option>2023</option>
                    <option>2024</option>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
                <div class="form-group position-relative">
                 <label>Select Month <span style="color: red">*</span></label>
                 <span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                 <select class="js-select2" name="month" id="filterMonth" required>
                    <option value="">select month</option>
                    <option value="01">Jan</option>
                    <option value="02">Feb</option>
                    <option value="03">Mar</option>
                    <option value="04">Apr</option>
                    <option value="05">May</option>
                    <option value="06">Jun</option>
                    <option value="07">Jul</option>
                    <option value="08">Aug</option>
                    <option value="09">Sep</option>
                    <option value="10">Oct</option>
                    <option value="11">Nov</option>
                    <option value="12">Dec</option>
                </select>
            </div>
        </div>

       <!--  <div class="col-lg-3 col-md-6">
          <div class="form-group position-relative">
           <label>Select Trader</label>
           <span class="helping-mark" onmouseenter="popup_function(this, 'select_trader_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
           <select id="trader-dropdown" class="js-select2" name="trader_data" >
            <option value="">--- First Select Contractor ---</option>    
        </select>
    </div>
</div> -->

<!-- <div class="form-group col-lg-3 col-md-6">
  <div class="form-group position-relative">
    <label>Select File <span style="color: red">*</span></label>
    <span class="helping-mark" onmouseenter="popup_function(this, 'select_file_upload_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
    <input type="file" class="form-control" name="file" id="file" required>

</div>
</div> -->
<div class="form-group col-md-6">
    <br>
    <button type="button" class="btn btn-primary" id="btn-submit">Fetch Data</button>
    <button type="submit" class="btn btn-info">Download Sheet</button>
    <!-- <button type="button"class="btn btn-default ml-4"><a href="{{asset('sample.csv')}}"><span class="fa fa-download"></span> Download Sample (.csv) File</a></button> -->

</div>

</div>
</form>
<!-- <div style="padding: 2px 5px 5px 20px">
 <b>Instructions:</b>
 <ul style="padding-inline-start: 20px">
    <li>File must be <b>.csv</b> format.</li>
    <li>File must contain only <b>7</b> columns.</li>
    <li>File must contain heading of column.</li>
</ul>
</div> -->

</div>
</div>
</div>


<div class="row justify-content-center" style="margin-top:20px;">
  <div class="card bg-white">



    <div class="content-body" >
        <!-- <h3>Logs</h3> -->
        <form id="billRegisterUpdateForm">

            <input type="hidden" name="month" id="month">
            <input type="hidden" name="year" id="year">

        <table id="billRegisterFiltertable" class="table dt-responsive display w-100">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Status</td>
                    <td>Last Month User Count</td>
                    <td>Current Month User Count</td>
                    <td>Assign New User Count</td>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>


        </form>
        <button type="button" class="btn btn-primary" id="udpateBtn">Update</button>
    </div>


</div>
</div>



</section>
</section>
<!-- END CONTENT -->
<!-- processing -->

                
                <div class="modal fade bs-example-modal-center custom-center-modal" id="processLayer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-dialog modal-md">
                        <div class="modal-content" style="background-color:#02020200 !important;border-color:#02020200 !important; ">
                            <div class="modal-body">

                                <center><h1 style="color:white;">Processing....</h1>
                                    <p style="color:white;">please wait.</p>
                                </center>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->      




</div>
@endsection
@section('ownjs')

<script>
    $(document).ready(function () {

        $('#reseller-dropdown').on('change', function () {
            var reseller_id = this.value;
            if(reseller_id == ''){
              $('#btn_generate').prop('disabled', true)
          }else{
              $('#btn_generate').prop('disabled', false)
          }
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

    });
</script>


<script type="text/javascript">
            $(document).ready(function(){  
    // $("#billRegisterFilter").submit(function() { 
                $(document).on('click','#btn-submit',function(){

        $('#processLayer').modal('show');
      //
                $.ajax({ 
                    type: "POST",
                    url: "{{route('user.bill-register.get-data')}}",
                    data:$("#billRegisterFilter").serialize(),
                    success: function (data) {
          //
                        $('#billRegisterFiltertable tbody').html(data);
                        //
                        $('#year').val($('#filterYear').val());
                        $('#month').val($('#filterMonth').val());
          //
                    },
                    error: function(jqXHR, text, error){
                        $('html, body').scrollTop(0);
                            $('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
                            $('#processLayer').modal('hide');
                    },
                    complete:function(){
                        // $('#billRegisterFiltertable').dataTable();
                        $('#processLayer').modal('hide');
                    },
                });
                return false;
            });
            });
        </script>

        <script type="text/javascript">
    // $(document).ready(function(){
            // $("#bulkRechargeConfirmationForm").submit(function() {
            $(document).on('click','#udpateBtn',function(){
    // 
                if(confirm("Do your really want to update?")){
                    
                    $('#processLayer').modal('show');
      //
                    $.ajax({ 
                        type: "POST",
                        url: "{{route('user.bill-register.update-data')}}",
                        data:$("#billRegisterUpdateForm").serialize(),
                        success: function (data) {
                            //
                            $('html, body').scrollTop(0);
                            $('#processLayer').modal('hide');

                            $('#returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
                            
                            // setTimeout(function() { 
                            //     location.reload();
                            // }, 2000);

                             //
                        },
                        error: function(jqXHR, text, error){
                                // alert(jqXHR.responseJSON.message);
                            $('html, body').scrollTop(0);
                            $('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
                            $('#processLayer').modal('hide');
                        },
                        complete:function(){

                        },
                    });
                    
                }
                return false;
            })
        </script>


@endsection
