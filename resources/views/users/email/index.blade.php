@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <!-- <a href="{{('#servicesModal')}}" data-toggle="modal" class="pr-3 mt-3">
        <button class="btn btn-default" style="border: 1px solid black">
        <i class="fas fa-globe-asia"></i> Add Servers</button>
      </a> -->
      <div class="header_view">
        <h2>Emails
        <span class="info-mark" onmouseenter="popup_function(this, 'city_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <!-- <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#email">Emails</a></li> -->
        <!-- <li><a data-toggle="tab" href="#setting">Settings</a></li>
        <li><a data-toggle="tab" href="#banlist">Banlist</a></li>
        <li><a data-toggle="tab" href="#template">Templates</a></li>
        <li><a data-toggle="tab" href="#diagnostic">Diagnostic</a></li>
        <li><a data-toggle="tab" href="#topic">Help Topic</a></li> -->
      <!-- </ul> -->
      <section class="box" style="margin-top: 0">
        <div class="content-body" style="padding-top: 20px">
          <div class="tab-content">
						<div id="email" class="tab-pane fade in active">
              <div class="error-container">

              </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                
                <h3>Email Addresses</h3>
                <a href="{{('#emailModal')}}" data-toggle="modal" class="pr-3 mt-3">
                  <button class="btn btn-default" style="border: 1px solid black">
                  <i class="fas fa-envelope"></i> Add New Email</button>
                </a>
              </div>
              <table id="example-1" class="table dt-responsive w-100 display">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Reseller</th>
                    <th>Logo</th>
                    <th>Email Title</th>
                    <th>Port</th>
                    <th>Email ID</th>
                    <th>SMTP Server</th>
                    <th>SMTP Encryption</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data['emails'] as $emails)
                  <tr>
                    <td style="width: 100px">{{$loop->iteration}}</td>
                    <td>{{$emails->resellerid}}</td>
                    <td><img src="{{$emails->logo}}" style="width:40px; height:40px; object-fit:cover;" alt=""></td>
                    <td>{{$emails->title}}</td>
                    <td>{{$emails->port}}</td>
                    <td>{{$emails->email}}</td>
                    <td>{{$emails->host}}</td>
                    <td>{{$emails->encryption}}</td>
                    <td>
                    <!-- href="{{route('email.delete' , $emails->id)}}" -->
                      <div class="d-flex">
                      <a href="{{('#updateEmail')}}" data-toggle="modal" class=" updateemail pr-3 mt-3 btn-success btn " style="margin-bottom:10px!important;" data-id="{{$emails->id}}">
                    <i class="fas fa-pen"></i></a> <a  data-id="{{$emails->id}}" class="btn btn-danger btnDelete" title="Delete Settings"><i class="fa fa-trash"></i></a> 
                      </div>
                      </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </section>
  </section>
</div>

<!-- Mdelete modal start -->
<div class="modal fade" id="deleteModel" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Do you realy want to delete this?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" id="deletbtn" class="btn btn-danger">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!-- delete modal ends -->


@include('users.email.add-email-modal')
@include('users.email.add-template-modal')
@include('users.email.add-helptopic-modal')
@include('users.email.email-setting-modal')
@include('users.email.ban-email-modal')
@include('users.email.update-email-modal')
@endsection


@section('ownjs')

<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
   
  })
</script>
<script>
  let deleteUrl  = "{{route("email.delete")}}";
  $(document).on('click' , '.btnDelete' , function(e){
    if(confirm("Are you sure to Delete it !")){
      let dataId = $(this).data('id');
      $.ajax({
        url : deleteUrl + '/' + dataId,
        type:'Get',
        success:function(response){
        if(response == "success"){
          $('.error-container').html('<div class="alert alert-success rounded shadow">Email Setting has been deleted</div>')
          setTimeout(() => {
              $('.error-container').fadeOut();
              location.reload()
          }, 2000);
        }
        else if(response == 'exists'){
          $('.error-container').html('<div class="alert alert-warning rounded shadow">Failed to Delete Email Setting atleast 1 setting must be stored..!</div>')
          setTimeout(() => {
              $('.error-container').fadeOut();
              location.reload();
          }, 2000);
        }
        else{
          $('.error-container').html('<div class="alert alert-danger rounded shadow">Failed to Delete Email Setting</div>')
          setTimeout(() => {
              $('.error-container').fadeOut();
              location.reload();
          }, 2000);
        }
        }
      })
    }
    
  })
</script>
<script>
     let editUrl        = "{{route('users.email.edit')}}";
    let updateButton   = $('.updateemail');
    let updateForm     = $("#updateEmailForm");
    $(updateButton).on('click' , function(e){
      e.preventDefault();
      var dataIdValue = $(this).data('id');
      $.ajax({
        url : editUrl + '/'  + dataIdValue ,
        type:'Get',
        success:function(response){
          $(updateForm).html(response); 
        }
      })
     
    })
</script>
@if(Session::has('error'))  
  <script>
    $('.error-container').html('<div class="alert alert-danger rounded shadow">{{Session::get('error')}}</div>')
    setTimeout(() => {
        $('.error-container').fadeOut()
    }, 2000);
  </script>            
@endif
@if($errors->any())
<script>
    $('.error-container').html('<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>')
    setTimeout(() => {
        $('.error-container').fadeOut()
    }, 2000);
</script>
@endif
@if(Session::has('success'))  
  <script>
    $('.error-container').html('<div class="alert alert-success rounded shadow">{{Session::get('success')}}</div>')
    setTimeout(() => {
        $('.error-container').fadeOut()
    }, 2000);
  </script>            
@endif

            

@endsection
