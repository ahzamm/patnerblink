<link href="{{asset('css/wizard.css')}}" rel="stylesheet" type="text/css"/>

<div aria-hidden="true"  role="dialog" tabindex="-1" id="myModal-2" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8">
		<div class="modal-dialog" style="width: 100%">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #4878bf;">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
					<h4 class="modal-title" style="text-align: center;color: white"> Add Reseller</h4>
				</div>
				<div class="modal-body">
					<form action="{{route('users.user.post',['status' => 'reseller'])}}" method="POST" id="wizard_form" class="wizard">
						@csrf
						<h4 style="margin: 0"></h4>
                		<section>
							<div class="row">
								<div class="col-md-4">
									<!-- <div class="form-group"> -->
										<!-- <label  class="form-label">Manager ID</label> -->
										
										<input type="hidden" readonly value="{{Auth::user()->manager_id}}" required name="manager_id" class="form-control">
										
										
									<!-- </div> -->
									<div class="form-group" >
										<label class="form-label">Reseller ID</label><span style="float: right;" id="reselleroutput"></span>
										<input type="text" name="resellerid" id="resellerid" class="form-control" placeholder="Unique Reseller Id" onkeyup="checkavailablereseller(this.value)">
									</div>
									<div class="form-group">
										<label for="fname" class="form-label">First Name</label>
										<input type="text" name="fname" class="form-control" id="fname" placeholder="First Name" >
									</div>
									<div class="form-group">
										<label  class="form-label">Last Name</label>
										<input type="text" name="lname" class="form-control"  placeholder="Last Name" >
									</div>
									<div class="form-group">
										<label  class="form-label">Business Address</label>
										<input type="text" name="address" class="form-control"  placeholder="Business Address" >
									</div>
									<div class="form-group">
										<label class="form-label">Select City</label>
										<select name="city" id="city" class="form-control" require>
											<option value="">Select City</option>
											<option value="karachi">Karachi</option>
											<option value="sukkur">Sukkur</option>
										</select>
									</div>

								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label  class="form-label">Mobile Number</label>
										<input name="mobile_number" class="form-control" id="mobile" data-mask="9999-9999999">
									</div>
									<div class="form-group">
										<label  class="form-label">Landline Number</label>
										<input type="text" name="land_number" class="form-control"  data-mask="(999)99999999" >
									</div>
									<div class="form-group">
										<label  class="form-label">CNIC Number</label>
										<input type="text" name="nic" class="form-control" data-mask="99999-9999999-9" >
									</div>
									<div class="form-group">
										<label for="email" class="form-label">Email Address</label>
										<input type="email" name="mail" id="email" class="form-control"  placeholder="info.logon.com.pk" >
									</div>
									<!-- <div class="form-group">
									<label for="email" class="form-label">Email Address</label>
										<div class="input-group mb-3" style="display: flex">
											<input type="text" class="form-control" placeholder="Email Address" aria-label="Email Address" aria-describedby="email-address" style="height: auto">
											<div class="input-group-append" style="display: flex">
												<span class="input-group-text" id="email-address" style="display: flex;
    -ms-flex-align: center;
    align-items: center;
    padding: 0.375rem 0.75rem;
    margin-bottom: 0;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    text-align: center;
    white-space: nowrap;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;">@logon.com.pk</span>
											</div>
										</div>
									</div> -->
									<div class="form-group">
										<label  class="form-label">Reseller Area</label>
										<input type="text" name="area" class="form-control"  placeholder="Business Area " >
									</div>
									
								</div>
								<div class="col-md-4">
									
									<div class="form-group">
										<label for="resusername"  class="form-label">Username</label>
										<span style="float: right;" id="availableresuser"></span>
										<input type="text" name="username" id="resusername" class="form-control"  placeholder="Username"  onkeyup="checkavailableuser(this.value)">
									</div>
									<div class="form-group">
										<label  class="form-label">Password</label>
										<input type="password" name="password" class="form-control"  placeholder="Password must be 8 character">
									</div>
									<div class="form-group">
										<label for="retypepassword" class="form-label">Retype Password</label>
										<input type="password" name="password_confirmation" id="retypepassword" class="form-control" placeholder="Confirm Password">
									</div>
									<!-- <div class="form-group">
										<label class="form-label">Select City</label>
										<select name="city" id="city" class="form-control" require>
											<option value="">Select City</option>
											<option value="karachi">Karachi</option>
											<option value="sukkur">Sukkur</option>
										</select>
									</div> -->
									<!-- <div class="form-group">
										<label class="form-label">Main Heading</label>
										<input type="text" name="mheading" class="form-control"
											required>
									</div> -->
									<div style="display:none;" class="form-group">
										<h5>Select Theme Color</h5>
										<input type="color" class="color1" name="color1" value="#232e3d">
										<input type="color" class="color2" name="color2" value="#7b1fa2">
									</div>


								</div>
							</div>
						</section>
						<h4 style="margin: 0"></h4>
						<section>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="form-label">Domain Name (http://abc.pk)</label>
										<input type="text" name="domain" class="form-control">
									</div>
									<div class="form-group">
										<label class="form-label">Package Name</label>
										<input type="text" name="packageName" class="form-control">
									</div>
									<div class="form-group">
										<label class="form-label">Powerd By</label>
										<input type="text" name="powerby" class="form-control">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="form-label">Company Slogan</label>
										<input type="text" name="slogan" class="form-control">
									</div>
									<div class="form-group">
										<label class="form-label">Select Company Logo</label>
										<input type="file" name="logo" class="form-control"
											>
									</div>
									
									<div class="form-group">
										<label class="form-label">Choose Background Image</label>
										<input type="file" name="bgImage" class="form-control"
											>
									</div>
								</div>
								<div class="col-md-4">
											
									<div class="form-group">
										@php
											/*dynamic theme load start */
									
											$theme_loading = DB::table('partner_themes')
											->get();
										@endphp
										<label class="form-label">Select Theme</label>
										<select name="theme_color" id="theme_color" class="form-control" >
											<option value="">Select Theme</option>
											@php 
												foreach($theme_loading as $v){
											@endphp
												<option value="@php echo $v->color @endphp">
													@php echo $v->color @endphp
												</option>
											@php 
												}
											@endphp
										</select>
									</div>

									<div class="form-group">
										<label class="form-label">Login Form Alignment</label>
										<select name="login_alignment" id="login_alignment" class="form-control" >
											<option value="">Select Alignment</option>
											<option value="center">Center</option>
											<option value="left">left</option>
											<option value="right">Right</option>
										</select>
									</div>
									<div class="form-group">
										<label class="form-label">Main Heading</label>
										<input type="text" name="mheading" class="form-control"
											>
									</div>
								</div>
							</div>
						</section>
							<!--  -->
							<!-- <div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Submit</button>
										<button type="" class="btn btn-primary" data-dismiss="modal">Cancel</button>
									</div>
								</div>
							</div> -->
							<!--  -->
							<!--  -->
						<!-- </div> -->
						<input type="submit" value="submit" class="submit_form">

					</form>
				</div>
			</div>
		</div>
		<!--  -->
	</div>
	</div>
</div>
<!--  -->
<!--  -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script type="text/javascript">

	
	function FromValidate() {
		$("#wizard_form").validate({
                rules: {
                    fname:{
                        required: true
                    },
                    lname: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    mobile_number: {
                        required: true
                    },
                    land_number: {
                        required: true
                    },
                    nic: {
                        required: true
                    },
                    mail: {
                        required: true
                    },
                    area: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    password_confirmation: {
                        required: true
                    },
					resellerid: {
						required: true
					},
					domain: {
						required: true
					},
					packageName: {
						required: true
					},
					powerby: {
						required: true
					},
					slogan: {
						required: true
					},
					logo: {
						required: true
					},
					bgImage: {
						required: true
					},
					theme_color: {
						required: true
					},
					login_alignment: {
						required: true
					},
					mheading: {
						required: true
					}
                },
                messages:{
                    fname: "please enter a First Name",
                    lname: "please enter a Last Name",
                    address: "please enter Business Address",
                    mobile_number: "please enter Mobile Number",
                    land_number: "please enter Landline Number",
                    nic: "please enter CNIC Number",
                    mail: "please enter Email Address",
					username: "Please enter username",
					password: "Please enter Password",
					password_confirmation: "Please retype password",
					resellerid: "Please enter Reseller ID",
					domain: "Please enter Domain",
					packageName: "Please enter Package Name",
					powerby: "Please enter Power By",
					slogan: "Please enter Slogan",
					logo: "Please select logo",
					bgImage: "Please select Background Image",
					theme_color: "Please select Theme Color",
					login_alignment: "Please select Login Alignment",
					mheading: "Please select Main Heading"
                }
            });
	}
	// $(document).ready(function(){
		$('#wizard_form').on('submit', function(e){
			e.preventDefault();
			FromValidate();

			if($(this).valid()) {
				console.log('form is valid')
			} else {
				console.log('form is not valid')
			}
			// var result = FromValidate();
			// console.log(result);

		});
		
	// })

	function checkavailablereseller(id) {
		var val=$('#resellerid').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('users.checkunique.ajax.post')}}",
				data:'resellerid='+id,
				success: function(data){
// for get return data
$('#reselleroutput').html(data);
}
});
		}
		else{
			$('#reselleroutput').html('');
		}
	}
	//
	//
	function checkavailableuser(resusername) {
		var val=$('#resusername').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('users.checkunique.ajax.post')}}",
				data:'username='+resusername,
				success: function(data){
// for get return data
$('#availableresuser').html(data);
}
});
		}
		else{
			$('#availableresuser').html('');
		}
	}
</script>