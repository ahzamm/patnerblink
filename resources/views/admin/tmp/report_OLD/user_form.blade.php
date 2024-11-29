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
.border-right{
  border-right: 1px solid #b4b4b4;
  width: 0px;
    background: #000;
    display: inline-block;
    /* position: relative; */
    height: 500px;

}
section.box{
  padding: 15px;
}
.col-md-4{
  padding: 0 5px;
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
        <a href="{{route('admin.users.getuseragreements')}}" class="btn btn-success" style="float: right;
    margin-top: 15px;">Agreement List</a>

          <div class="header_view">
            <h2>Contractor agreement form </h2>
          </div>
          <section class="" style="padding: 15px;">
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
          <form target="_blank" action="{{route('admin.postuserform')}}" method="POST">
            @csrf
          <div class="row">
            <div class="col-md-4" >
			
            <section class="box" >
            <div class="form-group">
                  <h3 class="text-center">Contractor Detail</h3>
                </div>
                <div class="form-group">
                <label for="company_date">Select company</label>
                
                <select class="form-control form-select" name="company_logo" >
                  <option value="logonbroadband"  selected>Logon Broadband</option>
                  <option value="spark">Spark</option>
                  <option value="blackoptic">Black Optic</option>
                </select>
                </div>
				
			        <div class="form-group">
                 <label for="company_date">Contract Date</label>
                 <input type="date" required class="form-control" name="company_date" id="company_date">
                </div>
			
			        <div class="form-group">
                 <label for="username">	Contractor name</label>
                 <input type="text" required class="form-control" name="company_name" id="company_name">
                </div>
				
				      <div class="form-group">
                 <label for="phone_no">Contractor CNIC</label>
                 <input type="text" required class="form-control" name="cnic" id="cnic">
                </div>
                
                <div class="form-group">
                 <label for="email">Contractor Address</label>
                 <input type="text"  required class="form-control" name="dealer_name" id="dealer_name">
                </div>
				
				
			        <div class="form-group">
                 <label for="email">Contractor Area</label>
                 <input type="text"  required class="form-control" name="dealer_area" id="dealer_area">
                </div>
				
				
              
</section>
</div>
            <!-- <div class="border-right"></div> -->
			<div class="col-md-4" >
			  <section class="box" >
			    <div class="form-group">
                  <h3 class="text-center">On behalf of LOGON Broadband Pvt Ltd</h3>
                </div>
				
				  <div class="form-group">
                 <label for="behalf_name">Name</label>
                 <input type="text" required class="form-control" name="behalf_name" id="behalf_name">
                </div>
                
                <div class="form-group">
                 <label for="behalf_designation">Designation</label>
                 <input type="text"  required class="form-control" name="behalf_designation" id="behalf_designation">
                </div>

                 
</section>
</div>
            <!-- <div class="border-right"></div> -->
			
            <div class="col-md-4" >
            <section class="box">
			          <div class="form-group">
                  <h3 class="text-center">On behalf of Contractor</h3>
                </div>
				
				        <div class="form-group">
                 <label for="phone_no">Name</label>
                 <input type="text" required class="form-control" name="contractor_name" id="contractor_name">
                </div>
                
                <div class="form-group">
                 <label for="contractor_cnic">CNIC</label>
                 <input type="text"  required class="form-control" name="contractor_cnic" id="contractor_cnic">
                </div>
				
				
			          <div class="form-group">
                 <label for="contractor_mobile">Mobile</label>
                 <input type="text" required class="form-control" name="contractor_mobile" id="contractor_mobile">
                </div>
				
				
                  <div class="row">
                    <div class="col-md-12">
                     
                    </div>
                    <div class="col-md-12" style="padding-top: 15px">
                      <button type="submit" class="btn btn-success pull-right">Submit</button>
                    </div>
                  </div>
</section>
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
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script> -->


@endsection
@section('ownjs')
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script>
  $(document).ready( function() { 
    $("#contractor_cnic").inputmask({"mask": "99999-9999999-9"});   
    $("#cnic").inputmask({"mask": "99999-9999999-9"});   
    $("#contractor_mobile").inputmask({"mask": "9999-9999999"});   
});
</script>
@endsection
