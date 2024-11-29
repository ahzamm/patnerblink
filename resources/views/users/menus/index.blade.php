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
@extends('users.layouts.app')
@section('content')
@section('title','All Menus')
<section class="no-padding-top">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">All Head of Accounts</h5>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <div class="">
              <a href="{{route('menus.create')}}" class="btn btn-outline-primary">Add Menu / SubMenus</a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive scrol">
              <table id="example" style="width: 100%;" class="table table-scrolled">
                <thead>
                  <tr>
                    <th>Sno.</th>
                    <th>Parent Menu</th>
                    <th>Total Sub Menus</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($collection as $key => $menu)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$menu->menu}}</td>
                    <td>{{$menu->submenus->count()}}</td>
                    <td>
                      <a href="{{route("menus.edit",$menu->id)}}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                      <button  class="btn btn-sm btn-danger btnDeleteMenu" data-value="{{$menu->id}}"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection()
@push('scripts')
<script>
  $(document).ready(function(){
    $('#example').DataTable();
// Delete Menu Start
$('.btnDeleteMenu').click(function(){
  menuId = $(this).attr('data-value');
  row = $(this);
  swal({
    title: 'Are you sure?',
    text: "You want to delete this record",
    animation: false,
    customClass: 'animated pulse',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Delete it!',
    cancelButtonText: 'No, cancel!',
    confirmButtonClass: 'btn btn-success',
    cancelButtonClass: 'btn btn-danger',
    buttonsStyling: true,
    reverseButtons: true
  }).then( function(result) {
    if (result.value) {
      $.ajax({
        url:'/menus/delete/'+menuId,
        method:'post',
        dataType:'json',
        success:function(res){
          if(res.status)
          {
            $(row).parents('tr').remove();
            swal('Updated!', 'Menu / SubMenus deleted', 'success');
// console.log("delete record");
}
else
{
//$(secondInput).siblings('span').removeClass('d-none');
}
},
error:function(jhxr,status,err){
  console.log(jhxr);
}
})
    } else if (result.dismiss === 'cancel') {
    }
  })
})
// Delete Menu End
});
</script>
@endpush
<!-- Code Finalize -->