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

.slider:before {
  position: absolute;
  content: "";
  height: 11px !important;
  width: 13px !important;
  left: 3px !important;
  /*bottom: 3px !important;*/
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

.active{
  background-color: #225094 !important;
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
		<form action="{{route('admin.billing.post',['status' => 'recieve'])}}" method="POST">
			@csrf
          <div class="header_view">
            <h2>Recieve Amount</h2>
          </div>


          <div class="col-lg-12">
            <section class="box ">
              <header class="panel_header">

              </header>
              <div class="content-body">   
                <div class="row">
                  <div class="col-xs-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
						<label >Add To Acc:</label>
						<div class="form-group">
							<div class="btn-group btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-secondary active">
									<input type="radio" name="options" value="manager" id="option1" onchange="loadUserList(this)" autocomplete="off" checked="true" > Manager
								</label>
								<label class="btn btn-secondary">
									<input type="radio" name="options" value="reseller" id="option2" onchange="loadUserList(this)" autocomplete="off"> Reseller
								</label>
								<label class="btn btn-secondary">
									<input type="radio" name="options" value="dealer" id="option3" onchange="loadUserList(this)" autocomplete="off"> Dealer
								</label>
							</div>

						</div>

						<div class="form-group">
							<select name="username" id="username-select" class="form-control" required >
								<option>select manager</option>
								@foreach($managerCollection as $manager)
								<option value="{{$manager->username}}">{{$manager->username}}</option>
								@endforeach
							</select>
						</div>
                      <div class="form-group">
                        <label  class="form-label">Amount</label>
                        <input type="Number" class="form-control"  placeholder="0" required name="amount">
                      </div> 
                      <div class="form-group">
                        <label  class="form-label">Discount</label>
                        <input type="Number" class="form-control"  placeholder="0" required name="discount">
                      </div>
                      <div class="form-group">
                        <label  class="form-label">Paid By</label>
                        <input type="text" class="form-control"  placeholder="username" required name="paidBy">
                      </div>

                      <div class="form-group">
                        <label class="form-label">Comment</label>
                       
                       <textarea class="form-control" name="comment"  placeholder="Comment here !!"></textarea>
                      </div>

                       <label >Payment By:</label>
                      <div class="form-group">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                          <label class="btn btn-secondary" onclick="chequeDetail()">
                            <input type="radio" name="paymentType" value="cheque" id="option2" autocomplete="off"> Cheque 
                          </label>
                          <label class="btn btn-secondary active" onclick="slideup()">
                            <input type="radio" name="paymentType" value="chash" id="option3" autocomplete="off" checked="true"> Cash
                          </label>
                        </div>
                      </div>
                      <div style="display: none;" id="showChequeDetails">
                       <div class="form-group">
                        <label  class="form-label">Bank Name</label>
                        <input type="text" class="form-control"  name="bankname" placeholder="Enter Bank name " >
                      </div>

                      <div class="form-group">
                        <label  class="form-label">Cheque No</label>
                        <input type="text" class="form-control"  name="checkNo" placeholder="Enter Cheque No here" >
                      </div>
                      </div>

                    </div>

                  </div>
                  <div class="col-md-3"></div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary">Recieve Amount</button>
                    </div>
                  </div>
                  <!--  -->
                  
                </div>
              </div>
            </section></div>

			</form>
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
	<script >
   function chequeDetail(){
   $('#showChequeDetails').slideDown(); 
  
  }

  function slideup()
  {
 $('#showChequeDetails').slideUp(); 
  

  }
  </script>

	<script>

   


		function loadUserList(option){
			let userStatus = option.value;
			
			// ajax call: jquery
			console.log("URL: " + "{{route('admin.user.status.usernameList')}}?status="+userStatus);
			$.get(
				"{{route('admin.user.status.usernameList')}}?status="+userStatus,
				function(data){
					console.log(data);
					let content = "<option>select "+userStatus+"</option>";
					$.each(data,function(i,obj){
						content += "<option value='"+obj.username+"'>"+obj.username+"</option>"
					});
					$("#username-select").empty().append(content);
				});
		}		
	</script>
	@endsection