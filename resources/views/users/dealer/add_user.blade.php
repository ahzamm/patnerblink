<div aria-hidden="true"  role="dialog" tabindex="-1" id="add_user" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center; color: white"> Consumer Form
					<span class="info-mark" onmouseenter="popup_function(this, 'consumer_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('users.user.post',['status' => 'user'])}}" method="POST" autocomplete="off">
					@csrf
					<div class="row">
						<div class="col-md-4">
							<div class="form-group" style="display: none;">
								<label  class="form-label">Manager (ID)</label>
								<input type="text" value="{{Auth::user()->manager_id}}" class="form-control" readonly required name="manager_id">
							</div>
							<div class="form-group" style="display: none;">
								<label  class="form-label">Reseller (ID)</label>
								<input type="text" value="{{Auth::user()->resellerid}}" class="form-control" readonly required name="resellerid">
							</div>
							<div class="form-group" style="display: none;">
								<label  class="form-label" >Contractor (ID)</label>
								<input type="text" value="{{Auth::user()->dealerid}}" class="form-control" readonly required name="dealerid">
							</div>
							<div class="form-group"  style="display: none;">
								<label  class="form-label">Trader (ID)</label>
								<input type="text" value="{{Auth::user()->sub_dealer_id}}" class="form-control" readonly  name="sub_dealer_id">
							</div>
							@php
							$dealerid = Auth::user()->dealerid;
							$checkdealer=App\model\Users\DealerProfileRate::where(['dealerid' => $dealerid])->first();
							$allowtrader = @$checkdealer['trader'];
							@endphp
							<div class="form-group" style="display: none;">
								<label  class="form-label">Trader (ID)</label>
								<input type="text" value="{{Auth::user()->trader_id}}" class="form-control" readonly  name="trader_id">
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Username <span style="color: red">*</span></label><span id="usercheck"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'pppoe_consumer_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="username" pattern="^[A-Za-z0-9-_]+$" id="subdealerusername" class="form-control"  placeholder="Username" required onkeyup="usercheckavailable(this.value)" autocomplete="off" auto-suggest="off">
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">First Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="fname" class="form-control"  placeholder="First Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Last Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="lname" class="form-control"  placeholder="Last Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Email Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="email" name="mail" class="form-control"  placeholder="info@gmail.com" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">CNIC Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="nic" class="form-control nic"  placeholder="00000-0000000-0" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Residential Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="address" class="form-control"  placeholder="Residential Address" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="mobile_number" class="form-control mobile" placeholder="0321-1234567" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Landline# | Mobile#2 <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="land_number" class="form-control" placeholder="021-12345678">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative" style="display: none">
								<label  class="form-label">Passport Number</label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'passport_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" class="form-control"  placeholder="AB1234567" data-mask="AA-9999999" readonly="">
							</div>
							<div class="form-group position-relative" style="display: none">
								<label  class="form-label">Mac Address</label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" class="form-control" readonly  placeholder="99-99-99-99-99-99" data-mask="99-99-99-99-99-99" required>
							</div>
							<div class="form-group" style="display: none">
								<label  class="form-label">User Static IP</label>
								<select class="form-control" required>
									<option selected>None</option>
									<option>192.168.100.1</option>
									<option>192.168.100.78</option>
								</select>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="Password" name="password" class="form-control"  placeholder="Must be 8 characters long" required>
							</div>
							<div class="form-group">
								<label  class="form-label">Retype Password <span style="color: red">*</span></label>
								<input type="Password" name="password_confirmation" class="form-control"  placeholder="Must be 8 characters long" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Select Internet Profile <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'select_internet_profile');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<select class="form-control" name="profile" required>
									<option value="">Select Profile</option>
									@php
									if(Auth::user()->status == "dealer"){
									$profileList = App\model\Users\DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->orderby('groupname')->get();
								}else if(Auth::user()->status == "subdealer"){
								$profileList = App\model\Users\SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->orderby('groupname')->get();
							}else if(Auth::user()->status == "trader"){
							$profileList = App\model\Users\SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->orderby('groupname')->get();
						}else{
						$profileList = App\model\Users\DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->orderby('groupname')->get();
					}
					@endphp
					@foreach($profileList as $data)
					@php $name=$data->name @endphp
					<option value="{{$data->name}}">{{$name}}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	<div style="display: flex; justify-content: space-between; align-items: center;flex-wrap:wrap">
		<div>
			<input type="checkbox" id="t&c">
			<label for="t&c" style="font-weight:normal">I accept <a href="#tc_cont_modal" data-toggle="modal" class="theme-color" style="font-weight:bold">terms and conditions</a></label>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Submit</button>
			<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
		</div>
	</div>
</form>
</div>
</div>
</div>
</div>
<script type="text/javascript">
	function usercheckavailable(username){
		var val=$('#subdealerusername').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('users.checkunique.ajax.post')}}",
				data:'username='+username,
				success: function(data){
					$('#usercheck').html(data);
				}
			});
		}
		else{
			$('#usercheck').html('');
		}
	}
</script>