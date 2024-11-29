<div aria-hidden="true"  role="dialog" tabindex="-1" id="myModal-1" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8"> 
		<div class="modal-dialog" style="width: 100%">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #4878bf;">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
					<h4 class="modal-title" style="text-align: center; color: white"> Add Dealer</h4>
				</div>
				<div class="modal-body">
					<form action="{{route('admin.user.post',['status' => 'dealer'])}}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label  class="form-label">Manager-ID</label>
									<select class="form-control" name="manager_id" required>
										@foreach($managerIdList as $manager)
										<option value="{{$manager->manager_id}}">{{$manager->manager_id}}
										</option>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label  class="form-label">Reseller-ID</label>
									<select class="form-control" name="resellerid" required>
										@foreach($resellerIdList as $reseller)
										<option value="{{$reseller->resellerid}}">{{$reseller->resellerid}}
										</option>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label  class="form-label">Dealer ID</label><span style="float: right;" id="availabledealer"></span>
									<input type="text" name="dealerid" id="dealerid" class="form-control"  placeholder="Dealer-ID" required onkeyup="checkavailabledealer(this.value)">
								</div>
								<div class="form-group">
									<label  class="form-label">First Name</label>
									
										<input type="text" name="fname" class="form-control" required>
									
								</div>

								<div class="form-group">
									<label  class="form-label">Last Name</label>
									<input type="text" name="lname" class="form-control"  placeholder="lastname" required>
								</div>
							</div>
							<div class="col-md-4">

								<div class="form-group">
									<label  class="form-label">Mobile Number</label>
									
										<input type="text" name="mobile_number"  data-mask="9999 9999999" class="form-control"   required>
									
								</div>
								
								<div class="form-group">
									<label  class="form-label">Landline Number</label>
									
										<input type="text" name="land_number"  class="form-control" data-mask="(999)9999999" required>
									
								</div>
								<div class="form-group">
									<label  class="form-label">CNIC</label>
									
										<input type="text" name="nic" class="form-control"  data-mask="99999-9999999-9" required>
									
								</div>
								<div class="form-group">
									<label  class="form-label">Email</label>
									<input type="email" name="mail" class="form-control"  placeholder="info@lbi.net.pk" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Address</label>
									<input type="text" name="address" class="form-control"  placeholder="Address" required>
								</div>

							</div>


							<div class="col-md-4">

								
								<div class="form-group">
									<label  class="form-label">Dealer Area</label>
									<input type="text" name="area" class="form-control"  placeholder="Area " required>
								</div>
								<div class="form-group">
									<label  class="form-label">Username</label><span style="float: right;" id="availableuser"></span>
									<input type="text" name="username" id="username" class="form-control"  placeholder="username" required onkeyup="checkavailableuser(this.value)">
								</div>
								<div class="form-group">
									<label  class="form-label">Password</label>
									<input type="password" name="password" class="form-control"  placeholder="****" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Retype Password</label>
									<input type="password" name="password_confirmation" class="form-control"  placeholder="****" required>
								</div>
								<!-- <div class="form-group">
									<label  class="form-label">Server Type</label>
									<select class="form-control" name="nas_type" required>
										@foreach($nas_type as $data)
										<option >{{$data->type}}</option>
										@endforeach 
									</select>
								</div> -->
							</div>
							<!--  -->
							<div class="col-md-8">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Submit</button>
									<button type="" class="btn btn-primary" data-dismiss="modal">Cancel</button>

								</div>
							</div>
							<!--  -->
							
							<!--  -->
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--  -->
	</div>
</div>

@section('ownjs')
<script type="text/javascript">
	function checkavailabledealer(dealerid) {   
	var val=$('#dealerid').val();
	if(val.length > 0){
		$.ajax({
			type: "POST",
			url: "{{route('admin.check.available.post')}}",
			data:'dealerid='+dealerid,
			success: function(data){
// for get return data
$('#availabledealer').html(data);
}
});
	}
	else{

		$('#availabledealer').html('');


	} 
}

function checkavailableuser(username) {   
		var val=$('#username').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('admin.check.available.post')}}",
				data:'username='+username,
				success: function(data){
// for get return data
$('#availableuser').html(data);
}
});
		} 

		else{

			$('#availableuser').html('');


		}
	}
</script>
@endsection()