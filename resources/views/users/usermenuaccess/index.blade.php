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
@section('owncss')
<style type="text/css">
   .modal-body{
      padding: 8px !important;
   }
   .bottom {
      box-shadow: 0px 15px 10px -15px #111;    
   }
   .modal-dialog{
      overflow-y: initial !important
   }
   .modal-body{
      height: 68vh;
      overflow-y: auto;
   }
</style>
@endsection
@section('title','User Access')
<div class="page-container row-fluid container-fluid">
   <section id="main-content">
      <section class="wrapper main-wrapper row">
         <div class="header_view text-center">
            <h2>Access Management 
               <span class="info-mark" data-toggle="tooltip" data-placement="top" title="
               <p class='mb-0'>You can open / close access of users from below access options</p>" 
               data-html="true"><i class="las la-info-circle"></i></span>
            </h2>
         </div>
         <table id="example-1" style="text-align: center !important;" class="table table-hover table-bordered dt-responsive display w-100" >
            <thead>
               <tr>
                  <th>Serial#</th>
                  <th>Username</th>
                  <th>Status</th>
                  <?php if(Auth::user()->status == 'manager' || Auth::user()->status == 'reseller'){?>
                     <th>Number of Consumers</th>
                  <?php } ?>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($users as $key => $user)
               <tr>
                  <td>{{$key+1}}</td>
                  <td>{{$user->username}}</td>
                  <td><?= ($user->status == 'dealer') ? 'contractor' : (($user->status == 'subdealer') ? 'trader' : $user->status);
                  ?></td>
                  {{-- <td>{{App\model\Users\UserInfo::where(['status' => 'subdealer','resellerid' => $user->resellerid,'dealerid' => $user->dealerid])->count()}}</td> --}}
                  <?php if($user->status == 'reseller'){?>
                     <td>{{ DB::table('user_info')
                        ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                        //  ->where('user_status_info.card_expire_on', '>', today())
                        ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                        ->where(['status' => 'user','resellerid' => $user->resellerid])->count() }}</td>
                     <?php }else if($user->status == 'dealer'){ ?>
                        <td>{{ DB::table('user_info')
                           ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                           //  ->where('user_status_info.card_expire_on', '>', today())
                           ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                           ->where(['status' => 'user','resellerid' => $user->resellerid,'dealerid' => $user->dealerid])->count() }}</td>
                        <?php }?>
                        <td>
                           <button class="btn btn-sm btn-primary btnShowAccessModal" data-value="{{$user->id}}"><i class="fa fa-lock"></i> Access Management</button>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </section>
         </section>
         <!-- Modal Start -->
         <div class="modal fade" id="modalShowAccess" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
               <div class="modal-content">
                  <div class="modal-header bottom">
                     <h3 class="modal-title" id="exampleModalScrollableTitle" style="color: white; text-align: center;">Access Management</h3>
                  </div>
                  <div class="modal-body" id="modalBody">
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal End -->
      </div>
      @endsection()
      @section('ownjs')
      <script>
         $(document).on('click','.btnShowAccessModal',function(){
            $('#modalShowAccess').modal('show');
            id = $(this).attr('data-value');
            $.ajax({
               type: 'GET',
               url: "{{route('useraccess.show_old')}}",
               data: {id:id},
               dataType:'html',
               success:function(res){
                  $('#modalBody').html(res);
                  $('.lcs_check').lc_switch();
               },
               error:function(jhxr,status,err){
                  console.log(jhxr);
               },
               complete:function(){
                  $('#allowAccessTable').DataTable();
               }
            })
         });
         $(document).on('change','.lcss_check',function(){
            status =0;
            accessId = $(this).attr('data-value');
            submenuid = $(this).attr('data-id');
            user_id = $(this).attr('user-id');
            if($(this).prop("checked") == true){
               status = 1;
               console.log(status);
            }
            else if($(this).prop("checked") == false){
               status = 0;
               console.log(status);
            }
            $.ajax({
               type: 'POST',
               url: "{{route('useraccess.update')}}",
               data:{
                  access_status : status, id:accessId,submenuid:submenuid,userid:user_id
               },
               dataType:'json',
               beforeSend:function(){
               },
               success:function(res){
                  if(res.status)
                  {
                     Messenger().post({message:"Access Status Change Successfully.. !",type:"success"});
                  }
               },
               error:function(jhxr,status,err){
                  console.log(jhxr);
               },
               complete:function(){
               }
            })
         })
      </script>
      @endsection
<!-- Code Finalize -->