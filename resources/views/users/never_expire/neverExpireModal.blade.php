<script src="https://jsuites.net/v4/jsuites.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />
<style type="text/css">
  .toggle-label {
    position: relative;
    display: block;
    width: 140px;
    height: 40px;
    margin-top: 5px;
    border: 1px solid #808080;
    margin-left: 20px;
    cursor: pointer;
    border-radius:5px;
  }
  .toggle-label input[type=checkbox] { 
    opacity: 0;
    position: absolute;
    width: 100%;
    height: 100%;
  }
  .toggle-label input[type=checkbox]+.back {
    position: absolute;
    width: 100%;
    height: 100%;
    background: #ed1c24;
    transition: background 150ms linear;  
  }
  .toggle-label input[type=checkbox]:checked+.back {
    background: #00a651; /*green*/
  }
  .toggle-label input[type=checkbox]+.back .toggle {
    display: block;
    position: absolute;
    content: ' ';
    background: #fff;
    width: 50%; 
    height: 100%;
    transition: margin 150ms linear;
    border: 1px solid #808080;
    border-radius: 0px;
  }
  .toggle-label input[type=checkbox]:checked+.back .toggle {
    margin-left: 69px;
  }
  .toggle-label .label {
    display: block;
    position: absolute;
    width: 50%;
    color: #ddd;
    line-height: 25px;
    text-align: center;
    font-size: 1em;
  }
  .toggle-label .label.on { left: 0px; }
  .toggle-label .label.off { right: 0px; }
  .toggle-label input[type=checkbox]:checked+.back .label.on {
    color: #fff;
  }
  .toggle-label input[type=checkbox]+.back .label.off {
    color: #fff;
  }
  .toggle-label input[type=checkbox]:checked+.back .label.off {
    color: #ccc;
  }
  #neverExpireModal .modal-header {
    padding: 10px 15px
  }
  #neverExpireModal .modal-title {
    color: #fff;
    display:inline-block
  }
  #neverExpireModal .modal-body {
    padding: 15px
  }
  #neverExpireModal .toggle-label {
    width: 125px;
    height: 35px;
    margin-top: 0;
  }
  #neverExpireModal .toggle-label .label.on {
    padding: 4px 1px;
  }
  #neverExpireModal .toggle-label .label.off {
    padding: 4px 1px;
  }
  #neverExpireModal .toggle-label input[type=checkbox]:checked+.back .toggle {
    margin-left: 61px;
  }
</style>
<!-- Never Expire Modal Start -->
<div class="modal" id="neverExpireModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="transform:translateY(10px)">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Never Expire <span class="info-mark" onmouseenter="popup_function(this, 'consumer_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span></h4>
      </div>
      <div class="modal-body">
        <center><b>loading...</b></center>
        <!-- Content Here -->
      </div>
    </div>
  </div>
</div>
<!-- Never Expire Modal End -->
<script>
  $(document).on('click','.nexpmodal',function(){
    let username = $(this).attr('data-username');
    $('#neverExpireModal').modal('show');
    $('#neverExpireModal .modal-body').html('<center><b>loading...</b></center>');
// 
$.ajax({
  method: 'GET',
  url: "{{route('users.never_expire.get_modal_content')}}",
  data:'username='+username,
  success: function(data){
    $('#neverExpireModal .modal-body').html(data);
//
},complete:function(){
  jSuites.calendar(document.getElementById('month_input'),{
    type: 'year-month-picker',
    format: 'YYYY-MM'
  });
},error: function(jqXHR, text, error){
  $('#neverExpireModal .modal-body').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
},  
});
})
// Never Expire Checkbox
$(document).on('change','#expire_cb', function() {
  var ischecked = $(this).is(":checked");
  var username = $(this).attr("data-username");
  var status = '';
  if (ischecked) {
    $('#nePopUpInfodiv').removeClass('hide');
    status = 'enable';
  }else{
    $('#nePopUpInfodiv').addClass('hide');
    status = 'disable';
  }
  enableDisableNeverExpire(username,status,null);
});
function enableDisableNeverExpire(username,status,month) {
  $.ajax({
    url: "{{route('users.never_expire_update')}}",
    method:"post",
    data:{username:username,status:status,month:month},
    success: function(data){
      if(data.includes("Updated")){
        alert(data);
      }
    }
  });
}
function saveNeverExpireMonth(){
  var nemonth = $('#month_input').val();
  var username = $('#expire_cb').attr('data-username');
  enableDisableNeverExpire(username,'enable',nemonth);
}
</script>