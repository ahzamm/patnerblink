<!--
* This file is part of the SQUADCLOUD project.
*
* (c) SQUADCLOUD TEAM
*
* This file contains the configuration settings for the application.
* It includes database connection details, API keys, and other sensitive information.
*
* IMPORTANT: DO NOT MODIFY THIS FILE UNLESS YOU ARE AN AUTHORIZED DEVELOPER.
* Changes made to this file may cause unexpected behavior in the application.
*
* WARNING: DO NOT SHARE THIS FILE WITH ANYONE OR UPLOAD IT TO A PUBLIC REPOSITORY.
*
* Website: https://squadcloud.co
* Created: September, 2024
* Last Updated: 01th September, 2024
* Author: SquadCloud Team <info@squadcloud.co>
*-->
<!-- Code Onset -->
<!-- Modal Start-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
  <div class="modal-dialog modal-md" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="color: white">Add City</h4>
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
            <form id="add-city" method="POST">
              @csrf
              <div class="row register-form">
                <div class="col-md-12">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">City Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="city_name" class="form-control" id="" placeholder="Example : Karachi" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group pull-right">
                    <input type="submit" class="btn btn-primary btn-submit"  value="Submit"/>
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
@section('ownjs')
<script>
  $(document).ready(function() {
    $(".btn-submit").click(function(e){
      e.preventDefault();
      $.ajax({
        url: "{{route('admin.store.city')}}",
        type:'POST',
        data: $( '#add-city' ).serialize(),
        success: function(data) {
// alert(data);
if($.isEmptyObject(data.error)){
// alert(data.success);
$(".print-error-msg").css('display','none');
$(".success-msg").css('display','block');
$('.success-msg').html(data.success);
location.reload(3000);
}else{
  printErrorMsg(data.error);
}
}
});
    }); 
    function printErrorMsg (msg) {
      $(".print-error-msg").find("ul").html('');
      $(".success-msg").css('display','none');
      $(".print-error-msg").css('display','block');
      $.each( msg, function( key, value ) {
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
      });
    }
  });
</script>
<script type="text/javascript">
  function deleteit(id){
    $('#deletbtn').val(id);
    $('#deleteModel').modal('show');
  }
  function deletethis(val) {
    $.ajax({
      type: "POST",
      url: "{{route('admin.city.ajax.post')}}",
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
<!-- Code Finalize -->