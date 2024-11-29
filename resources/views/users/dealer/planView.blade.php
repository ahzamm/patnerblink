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
<style>
  h2{
    font-family: serif !important;
    color: #4878C0 !important;
  }
</style>
<!-- SMS Module Verification -->
<div aria-hidden="true"  role="dialog" tabindex="-1" id="planView" class="modal fade" style="display: none;">
  <div class="col-md-2"> </div>
  <div class="col-md-8"> 
    <div class="modal-dialog" style="width: 85%">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #4878bf;">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
          <h4 class="modal-title" style="text-align: center;color: white">Consumer Price / Tax Section Plan</h4>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <img src="{{asset('img/MAIN_LOGO.png')}}" alt="" width="175px" height="85px"  style="margin-left: 20px;margin-top: -15px;" >
          </div>
          <div class="col-sm-9" style="margin-left: -80px;margin-top: 20px;">
            <center><span style="margin-left: 60px;font-size: 16px;font-weight: bold">We're changing the world with Technology</span></center>
          </div>
        </div>
        <hr style="margin-top: 0px;margin-bottom: 0px; border: 1;border-top: 1px solid black;">
        {{-- Start New Code --}}
        <div class="modal-body" style="margin-top:-30px;">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h2 id="grName">CX-V1</h2>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <ul class="nav nav-tabs" id="myid">
              </ul>
              <div id="test">
              </div>
            </div>
          </div>
        </div>
        {{-- End New Code --}}
      </div>
    </div>
  </div>
</div>
<script>
  $.ajax({
    type : 'get',
    url : "{{route('users.plan.groupName')}}",
    success:function(res){
      if(res.taxgroup == "A"){
        $('#grName').text('CX-V1');
      }else if(res.taxgroup == "B"){
        $('#grName').text('CX-V2');
      }else if(res.taxgroup == "C"){
        $('#grName').text('CX-V3');
      }else if(res.taxgroup == "D"){
        $('#grName').text('CX-V4');
      }
    }
  });
</script>
<script>
  $.ajax({
    type : 'get',
    url : "{{route('users.plan.viewprofilename')}}",
    success:function(res){
      $.each(res,function(index, item){
        $('#myid').append('<li id="'+item+'"><a data-toggle="tab"  href="#menus2">'+item+'</a></li>');
      });
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    $("ul[id*=myid] li").click(function () {
      $.ajax({
        type : 'get',
        url : "{{route('users.plan.viewplan')}}",
        data:'id='+this.id,
        success:function(res){
          $('#test').html(res);
        }
      });
    });
  });
</script>
<!-- Code Finalize -->