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
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
  .slider:before {
    position: absolute;
    content: "";
    height: 11px !important;
    width: 13px !important;
    left: 3px !important;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }
  #ip select,
  #sno select,
  #username select,
  #nic select,
  #res select,
  #app select,
  #mob select
  {
    display: none;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="">
        <div class="">
          <div class="header_view">
            <h2>Freeze & Unfreeze <small>(Contractors)</small>
              <span class="info-mark" onmouseenter="popup_function(this, 'freez_unfreez_user_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div class="">
            <section class="box">
              <div class="content-body" style="padding-top:20px">
                <div id="msg" style="display: none;" class="alert alert-success alert-dismissible">
                  Successfully changed..
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <div class="">
                  <div class="">
                    <div class="">
                      <table id="example-1" class="table table-striped  display dt-responsive w-100">
                        <thead>
                          <tr>
                            <th>Serial#</th>
                            <th>Reseller (ID)</th>
                            <th>Contractor (ID)</th>
                            <th>Contractor (Login ID)</th>
                            <th>Number of Traders</th>
                            <th>Number of Consumers</th>
                            <th>Action <span style="color: red">*</span></th>
                          </tr>
                        </thead>
                        <tbody>
                          @php
                          $num=1;
                          @endphp
                          @foreach($dealerCollection as $data)
                          <tr>
                            <td>{{$num++}}</td>
                            <td>{{$data->resellerid}}</td>
                            <td class="td__profileName">{{$data->dealerid}}</td>
                            <td>{{$data->username}}</td>
                            <td>{{$data->sub_dealer_id}}</td>   
                            <td><strong>{{ DB::table('user_info')
                              ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                              //  ->where('user_status_info.card_expire_on', '>', today())
                              ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                              ->where(['status' => 'user','resellerid' => $data->resellerid,'dealerid' => $data->dealerid])->count() }}</strong></td>
                              <td>
                                <?php
                                $checkChecked = '';
                                $check ='';
                                $radreply = App\model\Users\FreezeAccount::where(['username' => $data->username])->first();
                                if(!empty($radreply)){
                                  $check = $radreply['freeze'];
                                }
                                $isCheck = $check;     
                                if($isCheck == 'no'){
                                  $checkChecked = '';
                                }elseif($isCheck == 'yes'){
                                  $checkChecked = 'checked';
                                }
                                ?>
                                <label class="switch" style="width: 46px;height: 19px;">
                                  <input type="checkbox" data-value="{{$data->username}}" class="lcs_check" {{$checkChecked}}>
                                  <span class="slider square" ></span>
                                </label>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </section>
          </div>
          @endsection
          @section('ownjs')
          <script type="text/javascript">
            $(document).ready(function() {
              var table = $('#example1').DataTable();
              $("#example1 thead td").each( function ( i ) {
                var select = $('<select class="form-control"><option value="">Show All</option></select>')
                .appendTo( $(this).empty() )
                .on( 'change', function () {
                  table.column( i )
                  .search( $(this).val() )
                  .draw();
                } );
                table.column( i ).data().unique().sort().each( function ( d, j ) {
                  select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
              } );
            } );
          </script>
          <script>
            $(document).on('lcs-statuschange','.lcs_check',function(){
              status =0;
              isCheck = $(this).is(':checked') ? true : false;
              username = $(this).attr('data-value');
              if($(this).prop("checked") == true){
                status = 1;
                console.log(username);
              }
              else if($(this).prop("checked") == false){
                status = 0;
                console.log(username);
              }
              $.ajax({
                type: 'POST',
                url: "{{route('users.freeze.post')}}",
                data:{
                  isChecked:isCheck, username:username
                },
                dataType:'json',
                beforeSend:function(){
                },
                success:function(res){
                  if(res.status)
                  {
                    $('#msg').show();
                    setTimeout(function(){ 
                      $('#msg').fadeOut(); }, 3000);
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