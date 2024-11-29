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
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="header_view">
        <h2>Receipt Amount <span style="color: lightgray"><small>(History)...</small></span>
          <span class="info-mark" onmouseenter="popup_function(this, 'receipt_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box" style="padding-top:20px">
        <div class="content-body">   
          <table id="example1" class="table table-bordered display dt-responsive data-table w-100">
            <?php $file_name = 'Receipt-Amount-'.date('d-M-Y[H-i-s]').'.csv'; ?>
            <button class="btn btn-primary" style="float: right;margin-right: 15px;margin-bottom:20px;" onclick="exportTableToCSV('#example1','{{$file_name}}')"><i class="fa fa-download"></i> Download</button>
            <thead>
              <tr>
                <th style="width:25px">Receipt#</th>
                <th>Username</th>
                <th>Recieved Amount (PKR)</th>
                <th>Payment Mode</th>
                <th>Bank Detail</th>
                <th>Recieved By</th>
                <th>Comment</th>
                <th>Date</th>
                <th>IP Address</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {{-- @php $sno = 100000; @endphp
                @foreach($paymentTransactions as $data)
                <tr style="color: {{$data->type == 'reversal'?('red'):'black'}}">
                  <td>{{++$sno}}</td>
                  <td class="td__profileName">
                    <form action="{{route('users.billing.reciept')}}" method="POST" target="_blank">
                      @csrf
                      <input type="hidden" name="id" value="{{$data->id}}">
                      <button type="submit" class="btn btn-link">
                        {{$data->sender}}</button></form></td>
                        <td>{{number_format($data->amount,2)}}</td>
                        <td style="color:red">{{ $data->is_bank==1 ? 'BANK' : 'CASH' }}</td>
                        <td>{{$data->receiver}}</td>
                        <td>{{$data->detail}}</td>
                        <td>{{$data->date}}</td>
                        <td>{{$data->action_ip}}</td>
                        @if(Auth::user()->status == "reseller" || Auth::user()->username == "account10")
                        @if($data->status == "POST")
                        <td>{{$data->status}}</td>
                        @else
                        <td>
                          {{-- <form id="myform">
                            <button class="btn btn-link" onclick="formsubmit('{{$data->sender}}','{{$data->amount}}','{{$data->type}}','{{$data->date}}','{{$data->detail}}');">SAVE</button>
                          {{-- </form> 
                          </td>
                          @endif
                          @else
                          @if($data->status == "POST")
                          <td>{{$data->status}}</td>
                          @else
                          <td>{{$data->status}}
                          </td>
                          @endif
                          @endif
                          <td>Receipt</td>
                        </tr>
                        @endforeach --}}
                      </tbody>
                      <tfoot>
                        <tr>
                          <th colspan="2" style="text-align:center;font-weight:bold;font-size: 15px;">Grand Total (PKR):</th>
                          <th style="text-align:center;font-weight:bold;font-size: 15px; color: green"> </th>
                        </tr>
                      </tfoot>  
                    </table>
                  </div>
                </section>
              </section>
            </section>
          </div>
          <!-- Processing -->
          <div class="modal fade bs-example-modal-center custom-center-modal" id="processLayer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-md">
              <div class="modal-content" style="background-color:#02020200 !important;border-color:#02020200 !important; ">
                <div class="modal-body">
                  <center><h1 style="color:white;">Processing....</h1>
                    <p style="color:white;">please wait.</p>
                  </center>
                </div>
              </div>
            </div>
          </div>
          @endsection
          @section('ownjs')
          <script type="text/javascript">
            $(function () {
              var saveOpt = localStorage.getItem("defaultDTentries");
              if(saveOpt == null){ saveOpt = 10;}
              var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "lengthMenu": [[saveOpt,10, 25, 50, 100,-1], [saveOpt, 10,25, 50, 100,'All']],
                ajax: "{{ route('userSideCashReceipt') }}",
                columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'senders', name: 'senders'},
                {data: 'amount', name: 'amount'},
                {data: 'paymentTypes', name: 'paymentTypes'},
                {data: 'bank_name', name: 'bank_name'},
                {data: 'receiver', name: 'receiver'},
                {data: 'detail', name: 'detail'},
                {data: 'date', name: 'date'},
                {data: 'action_ip', name: 'action_ip'},
                {data: 'status1', name: 'status1'},
                {data: 'reciptBtn' , name : 'reciptBtn'}
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                  var api = this.api(), data;
// Remove The Formatting To Get Integer Data For Summation
var intVal = function ( i ) {
  return typeof i === 'string' ?
  i.replace(/[\$,]/g, '')*1 :
  typeof i === 'number' ?
  i : 0;
};
// Total Over All Pages
total = api
.column( 2 )
.data()
.reduce( function (a, b) {
  return intVal(a) + intVal(b);
}, 0 );
// Total Over This Page
pageTotal = api
.column( 2, { page: 'current'} )
.data()
.reduce( function (a, b) {
  return intVal(a) + intVal(b);
}, 0 );
// Update Footer
$( api.column( 2 ).footer() ).html(formatMoney(pageTotal) );
}
});
            });
            $(document).on('change','#example1_length select',function(){
              var opt = $(this).val();
              localStorage.setItem("defaultDTentries",opt);
            });
          </script>
          <script type="text/javascript">
            function formatMoney(n, c, d, t) {
              var c = isNaN(c = Math.abs(c)) ? 2 : c,
              d = d == undefined ? "." : d,
              t = t == undefined ? "," : t,
              s = n < 0 ? "-" : "",
              i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
              j = (j = i.length) > 3 ? j % 3 : 0;
              return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            }
          </script>
          <script type="text/javascript">
            function formsubmit(username,amount,type,date,detail) {
              $.ajax({
                type : "POST",
                url : "{{route('users.billing.save')}}",
                data : {username:username,amount:amount,type:type,date:date,detail:detail},
                success : function(data){

                  location.reload();
                },
                error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
$('#demo').html(error);
}
});
            }
          </script>
          <script type="text/javascript">
            $(document).ready(function(){
              $(document).on('click','.saveBtn',function(){ 
                let id = $(this).attr('data-id');
                if(confirm('Do you really want to Post it?')){
                  $('#processLayer').modal('show');
                  $.ajax({ 
                    type: "POST",
                    url: "{{route('users.billing.save')}}",
                    data: "id="+id,
                    success: function (data) {
                    },complete:function(){
                      location.reload();
                    }
                  });
                  return false;
                }
              });
            });
          </script>
          @endsection
<!-- Code Finalize -->