<section class="wrapper main-wrapper row">
	<div class="header_view">
		<h2>Consumer Taxation <small style="color: darkgreen; font-size: 14px;">(Calculator)</small>
			<span class="info-mark" onmouseenter="popup_function(this, 'consumer_tax_calculator');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
		</h2>
	</div>
	<section class="box">
		<div class="content-body">
			<div class="table-responsive">
				<table class="table calculate_table" style="margin-bottom: 30px">
					<thead>
						<tr>
							<th style="white-space:nowrap">Select Internet Profile <span style="color: red">*</span></th>
							<th style="white-space:nowrap">Internet Profile Base Price (PKR) <span style="color: red">*</span></th>
							<th style="white-space:nowrap">Consumer Selling Price (PKR) <span style="color: red">*</span></th>
						</tr>
					</thead>
					<tbody>
						<form  id="myform">
							<tr>
								<td>
									<div class="form-group">
										<select name="profile" id="profile" class="form-control" required>
											<option value="">Select Internet Profile</option>
											@foreach($profileList as $data)
											<option value="{{$data->name}}" data-rate="{{$data->base_price_ET}}">{{ucfirst($data->name)}}</option>
											@endforeach
										</select>
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="number" name="dealerRate" id="dealerRate" class="form-control text-center" readonly>
									</div>
								</td>
								<td>
									<div class="form-group" style="position: relative">
										<input type="number" name="consumerPrice" id="consumerPrice" min="100" class="form-control text-center" required placeholder="Enter Price" disabled>
										<p id="selling_error" style="color: red; position:absolute;"></p>
									</div>
								</td>
							</tr>
							<tr id="marginRow">
								<td style="text-align:right;"><b>Contractor Profit Margin (PKR) :</b></td>
								<td></td>
								<td><strong><span id="margin" style="color: #0d4dab; font-size: 16px">0.00</span></strong></td>
							</tr>
							<tr>
								<td style="text-align:right"><b>Sindh Sales Tax <span style="color:darkgreen">(SST)</span> :</b></td>
								<td></td>
								<td><strong><span id="sst">0.00</span></strong></td>
							</tr>
							<tr>
								<td style="text-align:right"><b>Advance Income Tax <span style="color:darkgreen">(AIT)</span> :</b></td>
								<td></td>
								<td><strong><span id="adv">0.00</span></strong></td>
							</tr>
							<tr id="filerRow">
								<td style="text-align:right"><b><span id="text">Filer / Non Filer</span> Tax :</b></td>
								<td></td>
								<td><strong><span id="tax">0.00</span></strong></td>
							</tr>
							<tr>
								<td style="text-align:right; color: darkgreen; font-size: 18px"><b>Total Wallet Deduction :</b></td>
								<td></td>
								<td style="border-bottom: 1px solid #0d4dab"><strong><span id="total" style="font-size: 20px; color: darkgreen">0.00</span></strong></td>
							</tr>
						</form>
					</tbody>
				</table>
			</div>
			<hr>
			<h3 class="text-center">Consumer Monthly Invoice Billing Amount</h3>
			<p class="text-center">You have to received <span style="font-size: 16px; color: darkgreen"><strong id="consumertotal">0.00</strong> </span> Rs. from Consumer.</p>
		</div>
	</section>
</section>
@section('ownjs')
<script type="text/javascript">
	$(document).on('change','#profile',function(){
		var rate = $('#profile').find('option:selected').attr('data-rate');
		$('#dealerRate').val(rate);
		$('#consumerPrice').attr('disabled', false);
	});
	$(document).ready(function() {
// $("#myform").submit(function() {
	$(document).on('keyup','#consumerPrice',function(){
		var dealer_rate = $('#dealerRate').val();
		var consumer_rate = $('#consumerPrice').val();
		if(parseInt(consumer_rate) < parseInt(dealer_rate)){
			$('#consumerPrice').css('border', '1px solid red');
			$('#selling_error').html('Selling Price cannot be less than Profile Base Price');
			$('#margin').html('0 Rs.');
			$('#sst').html('0 Rs.');
			$('#adv').html('0 Rs.');
			$('#tax').html('0 Rs.');
			$('#total').html('0 Rs.');
			$('#consumertotal').html('0 Rs.');
			return;
		}
		$('#selling_error').html('');
		$('#consumerPrice').css('border', '1px solid green');
		$.ajax({
			dataType: "json",
			type: "POST",
			url: "{{route('user.tax_calcultion.store')}}",
			data:$("#myform").serialize(),
			success: function (data) {
				$('#marginRow').show(); 
				if(data.margin <= 0){
					$('#marginRow').hide();
				}else{
					$('#margin').html(data.margin+' Rs.');	
				}
				$('#sst').html(data.sst+' Rs.');
				$('#adv').html(data.adv+' Rs.');
				$('#tax').html(data.tax+' Rs.');
				$('#total').html(data.total+' Rs.');
				$('#consumertotal').html(data.consumertotal+' Rs.');
				$('#filerRow').show();
				if(data.filerText == 'none'){
					$('#filerRow').hide();
				}else{
					$('#filerRow #text').html(data.filerText);
				}
			},
			error: function(jqXHR, text, error){
				console.log(error);
			}
		});
		return false;
	});
});
</script>
@endsection
<!-- Code Finalize -->