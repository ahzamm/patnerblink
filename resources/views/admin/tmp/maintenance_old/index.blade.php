@extends('admin.layouts.app')
@section('content')

<style>
   .material-switch>input[type="checkbox"] {
      display: none;
   }

   .material-switch>label {
      cursor: pointer;
      height: 0px;
      position: relative;
      width: 40px;
   }

   .material-switch>label::before {
      background: rgb(0, 0, 0);
      box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
      border-radius: 8px;
      content: '';
      height: 16px;
      margin-top: -8px;
      position: absolute;
      opacity: 0.3;
      transition: all 0.4s ease-in-out;
      width: 40px;
   }

   .material-switch>label::after {
      background: rgb(255, 255, 255);
      border-radius: 16px;
      box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
      content: '';
      height: 24px;
      left: -4px;
      margin-top: -8px;
      position: absolute;
      top: -4px;
      transition: all 0.3s ease-in-out;
      width: 24px;
   }

   .material-switch>input[type="checkbox"]:checked+label::before {
      background: inherit;
      opacity: 0.5;
   }

   .material-switch>input[type="checkbox"]:checked+label::after {
      background: inherit;
      left: 20px;
   }
</style>
<div class="page-container row-fluid container-fluid">
   <!-- SIDEBAR - START -->
   <section id="main-content">
      <section class="wrapper main-wrapper">
         <div class="">
            <div class="">
               <div class="header_view text-center">
                  <h2>Maintenance Mode
                  <span class="info-mark" onmouseenter="popup_function(this, 'maintenance_mode_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                  </h2>
               </div>
               <div class="">
                  <section class="box ">
                     <div class="content-body">
                        <div class="row">
                           @if(!empty($mode))
                           <div class="col-12 col-md-6">
                              <form action="{{route('maintenance.store')}}" method="post">
                                 @csrf
                                 <div class="form-group">
                                    <label>Allowed IPs Address</label>
                                    <input type='text' class="form-control" id="" name="allowed_ip" required value="{{$mode->allowed_ips}}" />
                                 </div>
                                 <input id="" type="hidden" value="enable" name="status">
                                 @if($mode->status === "enable")
                                 <a href="{{route('maintenance.deactivate')}}" class="btn btn-primary text-white">Deactivate</a>
                                 @else
                                 <input type="submit" value="Active Mode" class="btn btn-danger text-white">
                                 @endif
                              </form>
                           </div>
                           @else
                           <div class="col-12 col-md-6">
                              <form action="{{route('maintenance.store')}}" method="post">
                                 @csrf
                                 <div class="form-group">
                                    <label>Ip Address</label>
                                    <input type='text' class="form-control" id="" name="allowed_ip" required value="" />
                                 </div>
                                 <input id="" type="hidden" value="enable" name="status">
                                 <input type="submit" value="Active Mode" class="btn btn-danger text-white">
                              </div>
                           </form>
                        </div>
                        @endif
                     </div>
                  </div>
               </section>
               <section class="box ">
                  <div class="content-body">
                     <div class="row">
                        <h3>Maintenance Log</h3>
                        <table class="table table-striped table-hover display">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Activated On</th>
                        <th>Allowed IPs </th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach($logs as $key => $valueLog){ ?>
                        <tr>
                           <td>{{$key+1}}</td>
                           <td>{{$valueLog->created_at}}</td>
                           <td>{{$valueLog->allowed_ips}}</td>
                        </tr>
                     <?php } ?>
                    </tbody>
                 </table>
                     </div>
                  </div>
               </section>
            </div>
         </div>
      </div>
   </section>
</section>
</div>

@endsection
@section('ownjs')
<script>
   flatpickr("#datetime", {
      enableTime: true,
      dateFormat: "Y-m-d H:i:ss",
      minDate: "today"
   });


   $('#someSwitchOptionDefault').change(function() {
      var status = $('#someSwitchOptionDefault').val();
      $.ajax({
         type: 'GET',
         url: "{{route('maintenance.deactivate')}}",
         data: {
            status: status,


         },
         success: function(data) {

            if ($.isEmptyObject(data.error)) {
               // alert(data.success);
               $(".print-error-msg").css('display', 'none');
               $(".success-msg").css('display', 'block');
               $('.success-msg').html(data.success);
               $('#message_modal').modal('hide');
               //  location.reload(3000);
            } else {

               printErrorMsg(data.error);
            }

         }
      })
   });
</script>
@endsection