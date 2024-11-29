<div aria-hidden="true"  role="dialog" tabindex="-1" id="tax-calculator" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8"> 
		<div class="modal-dialog" style="width: 100%">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #4878bf;">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				</div>
				<div class="modal-body">
					@include('users.tax-calculator.import-calculator')
				</div>
			</div>
		</div>
	</div>
</div>
@section('ownjs')
<script type="text/javascript">
	$(document).on('change','#profile',function(){
		var rate = $('#profile').find('option:selected').attr('data-rate');
		$('#dealerRate').val(rate);
	});
	$(document).ready(function() {
		$(document).on('keyup','#consumerPrice',function(){
			$.ajax({
				dataType: "json",
				type: "POST",
				url: "{{route('user.tax_calcultion.store')}}",
				data:$("#myform").serialize(),
				success: function (data) {
					$('#margin').html(data.margin+' Rs.');
					$('#sst').html(data.sst+' Rs.');
					$('#adv').html(data.adv+' Rs.');
					$('#tax').html(data.tax+' Rs.');
					$('#total').html(data.total+' Rs.');
					$('#consumertotal').html(data.consumertotal+' Rs.');
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