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
        <a target="_blank" href="{{route('admin.users.getuseragreements_view',['id' => $id])}}" class="btn btn-success" style="float: right;
    margin-top: 15px;">View PDF</a>
          <div class="header_view">
            <h2>Dealer agreement update form </h2>
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
          <form action="{{route('usersagreement.update',['id' =>$id])}}" method="POST">
            @csrf
          <div class="row">
            
             <div class="col-md-4" >
             <section class="box">
             <div class="form-group">
                  <h2 class="text-center">Company Detail</h2>
                </div>
			        <div class="form-group">
                <label for="company_date">Select Company</label>
                
                <select class="form-control form-select" name="company_logo" >
                  <option value="logonbroadband" @php if($company_logo=="logonbroadband") echo "selected";  @endphp  >Logon Broadband</option>
                  <option value="spark" @php if($company_logo=="spark") echo "selected";  @endphp>Spark</option>
                  <option value="blackoptic" @php if($company_logo=="blackoptic") echo "selected";  @endphp >Black Optic</option>
                </select>
                </div>
				
			          <div class="form-group">
                 <label for="company_date">Contract Date</label>
                 <input type="date" required value="{{$company_date}}"  class="form-control" name="company_date" id="company_date">
                </div>
			
			          <div class="form-group">
                 <label for="username">	Contractor name</label>
                 <input type="text" value="{{$company_name}}" required class="form-control" name="company_name" id="company_name">
                </div>
				
				        <div class="form-group">
                 <label for="phone_no">Contractor CNIC</label>
                 <input type="text" value="{{$cnic}}" required class="form-control" name="cnic" id="cnic">
                </div>
                
                <div class="form-group">
                 <label for="email">Contractor Address</label>
                 <input type="text" value="{{ $dealer_name }}"  required class="form-control" name="dealer_name" id="dealer_name">
                </div>
				
				
			        <div class="form-group">
                 <label for="email">Contractor Area</label>
                 <input type="text" value="{{ $dealer_area}}"  required class="form-control" name="dealer_area" id="dealer_area">
                </div>
				
</section>
              
            </div>
			
			<div class="col-md-4" >
			  <section class="box">
			          <div class="form-group">
                  <h2 class="text-center">On behalf of LOGON Broadband</h2>
                </div>
				
				      <div class="form-group">
                 <label for="behalf_name">Name</label>
                 <input type="text" required value="{{$behalf_name}}" class="form-control" name="behalf_name" id="behalf_name">
                </div>
                
                <div class="form-group">
                 <label for="behalf_designation">Designation</label>
                 <input type="text"  required value="{{$behalf_designation}}" class="form-control" name="behalf_designation" id="behalf_designation">
                </div>

</section>
            </div>
			
			<div class="col-md-4" >
			  <section class="box">
			    <div class="form-group">
                  <h2 class="text-center">On behalf of Contractor</h2>
                </div>
				
				    <div class="form-group">
                 <label for="phone_no">Name</label>
                 <input type="text" value="{{ $contractor_name}}" required class="form-control" name="contractor_name" id="contractor_name">
                </div>
                
                <div class="form-group">
                 <label for="contractor_cnic">CNIC</label>
                 <input type="text" value="{{$contractor_cnic}}" required class="form-control" name="contractor_cnic" id="contractor_cnic">
                </div>
				
				
			          <div class="form-group">
                 <label for="contractor_mobile">Mobile</label>
                 <input type="text" value="{{$contractor_mobile}}" required class="form-control" name="contractor_mobile" id="dealer_area">
                </div>
				
				
                  <div class="row">
                    <div class="col-md-12">
                     
                    </div>
                    <div class="col-md-12" style="padding-top: 15px">
                      <button type="submit" class="btn btn-success pull-right">Update</button>
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
@endsection
@section('ownjs')

@endsection