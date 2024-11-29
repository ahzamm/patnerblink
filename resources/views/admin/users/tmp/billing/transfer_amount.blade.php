@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
	
	#loadingnew{
		display: none;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row">
			
			<div class="">
				<div class="col-lg-12">
					
					<form>
						@csrf
						
						
						<div class="header_view">
							<h2>Transfer Amount</h2>
						</div>

						@if(session('success'))
						<div id="alert" class="alert alert-success alert-dismissible">
							{{session('success')}}
						</div>
						@endif
						<div class="col-lg-12">
							<section class="box">
								<header class="panel_header">
									<!-- <div class="actions panel_actions pull-right">
												<a class="box_toggle fa fa-chevron-down"></a>
												<a class="box_close fa fa-times"></a>
									</div> -->
								</header>
								<div class="content-body " style="display: block;" >
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<label >Add To Acc:</label>
											<div class="form-group">
												<div class="btn-group btn-group-toggle" data-toggle="buttons">
													<!-- <label class="btn btn-secondary active">
														<input type="radio" name="options" value="manager" id="option1" onchange="loadUserList(this)" autocomplete="off" checked="true" > Manager
													</label> -->
													<label class="btn btn-primary active">
														<input type="radio" name="options" value="reseller" id="option2" onchange="loadUserList(this)" autocomplete="off"> Reseller
													</label>
													<!-- <label class="btn btn-secondary">
														<input type="radio" name="options" value="dealer" id="option3" onchange="loadUserList(this)" autocomplete="off"> Dealer
													</label> -->
												</div>
											</div>
											
											<div class="col-md-4 col-xs-12">
												
												<div class="form-group">
													<label  class="form-label">Select ID</label>
													<select name="username" id="username_select" class="form-control" required>
														<option value="">Select Reseller</option>
														@foreach($managerCollection as $manager)
														<option value="{{$manager->username}}">{{$manager->username}}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-4 col-xs-12">
												<div class="form-group">
													<label  class="form-label">Tranfer Amount</label>
													<input type="Number" class="form-control" id="amounts" name="amount" placeholder="Amount" min="0"required>
												</div>
											</div>
											<div class="col-md-4 col-xs-12">
												<div class="form-group">
													<button type="button" class="btn btn-primary" onclick="showConfrom(username_select.value,amounts.value)" style="margin-top: 28px;">Transfer Amount</button>

												</div>
											</div>
											
										</div>
										
										
									</div>
								</div>
							</section>
						</div>
					</form>
				</div>
				
			</div>
		</section>
	</section>
</div>
<!--  modal start -->
<form action="{{route('admin.billing.post',['status' => 'transfer'])}}" method="POST" id="myform">
	@csrf
	<div class="modal fade" id="confromMsg" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-body">
					<input type="hidden" name="username" id="showUsername" required="">
					<!-- <h2 id="showUsername"></h2> -->
					<input type="hidden" name="amount" id="showAmount" required="">
					<!-- <h3 id="showAmount"></h3> -->
					<center>
					<h4> Are you sure want to transfer amount , that is </h4>
					
					<h2 id="Amount"></h2>
					<h4 id="amounInWords"></h4>
					</center>
				</div>
				<div class="modal-footer">
					<button type="submit" id="transferBtn" class="btn btn-danger">Yes</button>
					<img src="{{asset('img/loading.gif')}}" id="loadingnew" width="5%" >
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--  modal ends -->
<div class="chart-container " style="display: none;">
	<div class="" style="height:200px" id="platform_type_dates"></div>
	<div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
	<div class="" style="height:200px" id="user_type"></div>
	<div class="" style="height:200px" id="browser_type"></div>
	<div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
	<div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
</div>
<!---Model Dialog --->
@endsection
@section('ownjs')
<script>
	$(document).ready(function(){
		$("#myform").submit(function(){
			$('#transferBtn').hide();
			$('#loadingnew').show();
		});
	});
</script>
<script type="text/javascript">
	
	function showConfrom(username,amounts)
	{
		if (username!="" && amounts!="") {
			$('#showUsername').val(username);
			$('#showAmount').val(amounts);
			var amount= "Rs. "+formatMoney(amounts)+"/-";
			var inwords=inWords(amounts);
			
			$('#Amount').html(amount);
			$('#amounInWords').html(inwords.toUpperCase());
			$('#confromMsg').modal('show');
		}
		else{
			alert('Select the Username and Amount');
		}
	}
	// 
	// 
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
	function loadUserList(option){
		let userStatus = option.value;
		
		// ajax call: jquery
		console.log("URL: " + "{{route('admin.user.status.usernameList')}}?status="+userStatus);
		$.get(
			"{{route('admin.user.status.usernameList')}}?status="+userStatus,
			function(data){
				console.log(data);
				let content = "<option value=''>select "+userStatus+"</option>";
				$.each(data,function(i,obj){
					content += "<option value='"+obj.username+"'>"+obj.username+"</option>"
				});
				$("#username_select").empty().append(content);
			});
	}
</script>

<script>
	$(document).ready(function(){
		setTimeout(function(){
			$('.alert').fadeOut(); }, 3000);
	});
//
</script>
@endsection