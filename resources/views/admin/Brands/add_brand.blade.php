<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
  <div class="modal-dialog modal-md" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="color: white">Add Company Brand Name & Logo</h4>
      </div>
      <div class="modal-body text-center">
        <div class="" id="tblData">
          <div class="alert alert-danger print-error-msg" style="display:none">
            <ul></ul>
          </div>
          <div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
            <ul></ul>
          </div>
          <div class="">
            <form  id="my-form" enctype="multipart/form-data" method="POST">
              @csrf
              <div class="row register-form">
                <div class="col-md-12">
                  <div class="form-group position-relative">
                    <label for="brand_name" class="form-label pull-left">Company Brand Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'company_brand_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="brand_name" class="form-control" required>
                  </div>
                  <div class="form-group position-relative">
                    <label for="image" class="form-label pull-left">Company Brand Logo Image <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'company_brand_logon_upload_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="file" value="" name="image" class="form-control" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class=" pull-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
​
​
@section('ownjs')
​
<script>
  $(document).ready(function() {
    $('#my-form').submit(function(event) {
      event.preventDefault();
      var formData = new FormData($(this)[0]);
      $.ajax({
        url: "{{route('admin.brands.store')}}",              
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
// alert(data);
if($.isEmptyObject(data.error)){
  $(".print-error-msg").css('display','none');
  $(".success-msg").css('display','block');
  $('.success-msg').html(data.success);
  location.reload(3000);
}else{
  printErrorMsg(data.error);
}
}
});
    }); function printErrorMsg (msg) {
      $(".print-error-msg").find("ul").html('');
      $(".success-msg").css('display','none');
      $(".print-error-msg").css('display','block');
      $.each( msg, function( key, value ) {
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
      });
    }
  }
  );
</script>
<script type="text/javascript">
  function deleteit(id){
    $('#deletbtn').val(id);
    $('#deleteModel').modal('show');
  }
  function deletethis(val) {
    $.ajax({
      type: "POST",
      url: "{{route('admin.brands.destroy')}}",
      data:{id:val,"_token": "{{ csrf_token() }}"},
      success: function(data){
        $('#deleteModel').modal('hide');
        $('#tablediv').load(" #tablediv");
        location.reload();
      }
    });
  }
</script>
@endsection