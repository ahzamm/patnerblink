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
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<div class="">
				<div class="">
					<form action="{{route('users.data.remove')}}" method="POST">
						@csrf
						
						<div class="header_view">
							<h2>Data Remove</h2>
						</div>
						@if(session('success'))
						<div id="alert" class="alert alert-success alert-dismissible">
							{{session('success')}}
						</div>
						@endif
						<div class="">
							<section class="box">
								<div class="content-body">
									<div class="text-center" style="margin-bottom: 20px">
										<h3>Remove NIC or Mobile Numbers</h3>
									</div>
											
									<div class="row">
										<div class="col-md-3 col-xs-12">			
										</div>
										<div class="col-md-6 col-xs-12">
											<div class="form-group">
												<label>Username</label>
												<input type="text" name="username" class="form-control">
											</div>
										</div>
										<div class="col-md-3 col-xs-12"></div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div style="display:flex; align-items:center; justify-content:center;column-gap:20px">
													<span>
														<input type="checkbox" name="nic">
														<label  class="form-label">NIC</label>
													</span>
													<span>
														<input type="checkbox" name="mobile">
														<label  class="form-label">Mobile</label>
													</span>
													<span>
														<input type="checkbox" name="image">
														<label  class="form-label">Image</label>
													</span>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group pull-right">
												<button type="submit" class="btn btn-primary"  style="margin-top: 28px;">Remove</button>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
					</form>
					<!--  -->
					<section class="box" style="padding-top: 20px">
						<div class="content-body">
							<table id="example-1" class="table dt-responsive w-100">
								<thead>
									<tr>
										<th>Serial#</th>
										<th>Column 1</th>
										<th>Column 2</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>data</td>
										<td>data2</td>
									</tr>
								</tbody>
							</table>
							
						</div>
					</section>
				</div>

			</div>
		</section>
	</section>
</div>

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