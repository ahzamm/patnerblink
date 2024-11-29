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
@section('title')
Dashboard
@endsection
@section('owncss')
<style type="text/css">
  #filter1 select,
  #filter3 select,
  #filter5 select,
  #filter6 select,
  #filter7 select,
  #filter8 select,
  #filter9 select {
    display: none;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Delete Transfer Entries</span>
          <span class="info-mark" onmouseenter="popup_function(this, 'delete_transfer_entries');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      @if($errors->any())
      <h4>{{$errors->first()}}</h4>
      @endif
      <section class="box">
        <header class="panel_header"></header>
        <div class="content-body">
          <div class="row">
            <form action="{{route('users.billing.dealerviewtransferpost')}}" method="post">
              @csrf
              <?php
              $selectedReseller = App\model\Users\UserInfo::where('status','reseller')->where('manager_id',Auth::user()->manager_id)->get();
              ?>
              <div class="form-group col-lg-4 col-md-6">
                <label style="font-weight: normal">Select Reseller <span style="color: red">*</span></label>
                <select id="reseller-dropdown" class="form-control" name="reseller_data">
                  <option value="">-- Select reseller --</option>
                  @foreach($selectedReseller  as $reseller)
                  <option value="{{$reseller->username}}">{{$reseller->username}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-lg-4 col-md-6">
                <label style="font-weight: normal">Select Contractor <span style="color: red">*</span></label>
                <select id="dealer-dropdown" class="form-control" name="dealer_data">
                  <option value="">-- Select contractor --</option> 
                </select>
              </div>
              <div class="form-group col-lg-4 col-md-6">
                <label style="font-weight: normal">Select Trader <span style="color: red">*</span></label>
                <select id="trader-dropdown" class="form-control" name="trader_data">
                  <option value="">-- Select trader --</option>
                </select>
              </div>
              <div class="col-md-12 form-group">
                <input class="btn btn-primary pull-right" type="submit" value="Submit">
              </div>
            </form>
          </div>
          @if($status)
          <table id="example1" class="table table-bordered display dt-responsive w-100">
            <thead>
              <tr>
                <th>Serial#</th>
                <th>Account (ID)</th>
                <th>Transfer Amount (PKR) </th>
                <th>Cash Amount (PKR) </th>
                <th>Transfer By</th>
                <th>Action Performed By</th>
                @if (Auth::user()->status == 'reseller' || Auth::user()->status == 'account')
                <th>Comments</th>
                @endif
                <th>Date & Time</th>
                <th>IP Address</th>
                <th>Action</th>
              </tr>
              <tr style="background-color: #fff" id="filter_row">
                <td id="filter1"></td>
                <td id="filter2"></td>
                <td id="filter3"></td>
                <td id="filter3"></td>
                <td id="filter4"></td>
                <td id="filter4"></td>
                @if (Auth::user()->status == 'reseller' || Auth::user()->status == 'account')
                <td id="filter5"></td>
                @endif
                <td id="filter6"></td>
                <td id="filter7"></td>
                <td id="filter8"></td>
              </tr>
            </thead>
            <tbody>
              @php
              $sno = 1;
              $ta = 0;
              @endphp
              @foreach ($amountTransactions as $data)
              @php
              $info = App\model\Users\UserInfo::where('username', $data->action_by_user)->first();
              $action_by_trader = $info->trader_id;
              $action_by_dealer = $info->dealerid;
              $action_by_reseller = $info->resellerid;
              $transferBy = (empty($action_by_trader)) ? $action_by_dealer : $action_by_trader ;
              $transferBy = (empty($transferBy)) ? $action_by_reseller : $transferBy ;
              @endphp
              <tr>
                <td>{{ $sno++ }}</td>
                <td>{{ $data->receiver }}</td>
                @php
                $ta = $data->amount == 0 ? -$data->cash_amount : $data->amount;
                @endphp
                <input type="hidden" class="ta"
                value="{{ $ta }}">
                <td><strong>{{ number_format($data->amount, 2) }}</strong></td>
                <td><strong>{{ number_format($data->cash_amount, 2) }}</strong></td>
                <td>{{ $transferBy }}</td>
                <td>{{ $data->action_by_user }}</td>
                @if (Auth::user()->status == 'reseller' || Auth::user()->status == 'account')
                <td>{{ $data->comments }}</td>
                @endif
                <td>{{ $data->date }}</td>
                <td>{{ $data->action_ip }}</td>
                <td><button class="btn btn-xs btn-danger"
                  onclick="deleteConfirmModal({{$data->id}},'{{$data->receiver}}','{{$data->date}}','{{$data->amount}}','{{$data->sender}}','{{$data->cash_amount}}')" >Delete</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif
        </div>
      </section>
    </section>
  </section>
</div>
<!-- Modal Start -->
<div class="modal fade" id="deleteTransferModal" tabindex="-1" role="dialog" aria-labelledby="deleteTransferModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" style="color:#fff;text-align:center" id="deleteTransferModalTitle">Alert</h3>
        {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> --}}
      </div>
      <form id="deleteForm">
        <div class="modal-body">
          <h4>Do You Want To Delete This Amount</h4>
          <input type="hidden" id="dvt" name="dvt" value="dvt">
          <input type="hidden" id="dealername" name="dealername">
          <input type="hidden" id="id" name="id">
          <input type="hidden" id="receivers" name="receivers">
          <input type="hidden" id="amountPost" name="amount">
          <input type="hidden" id="cash_amount" name="cash_amount">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <th>Account (ID)</th>
                <th>Amount (PKR)</th>
                <th>Date & Time</th>
              </thead>
              <tbody>
                <td><span id="usernames"></span></td>
                <td><span id="amount"></span></td>
                <td><span id="date"></span></td>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger" id="sub">Confirm</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal End -->
@endsection
@section('ownjs')
<script type="text/javascript">
  $('#example1').on('draw.dt', function() {
    var sum = 0;
    $(".ta").each(function() {
      sum += parseFloat($(this).val());
    });
    $('#sum').html(formatMoney(sum));
  });
</script>
<script type="">
  $(document).ready(function() {
    var table = $('#example1').DataTable({
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      responsive: true,
      orderCellsTop: true
    });
    $("#example1 thead td").each( function ( i ) {
      var select = $('<select class="form-control"><option value="">Show All</option></select>')
      .appendTo( $(this).empty() )
      .on( 'change', function () {
        table.column( i )
        .search("^" + this.value + "$", true, false, true)
        .draw();
      } );
      table.column( i ).data().unique().sort().each( function ( d, j ) {
        select.append( '<option value="'+d+'">'+d+'</option>' )
      } );
    } );
  } );
</script>
<script type="text/javascript">
  function formatMoney(n, c, d, t) {
    var c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(
      n - i).toFixed(c).slice(2) : "");
  }
</script>
<script type="text/javascript">
  function selectSubDealer() {
    var dealer = $('#dealerids').val();
    $.ajax({
      url: "{{ route('users.getSubDealer') }}",
      type: "POST",
      data: {
        dealer: dealer,
        _token: "{{ csrf_token() }}"
      },
      success: function(data) {
        $('#subDealerData').html('');
        $('#subDealerData').append('<option value="">Select Sub Dealer</option>');
        $.each(data, function(key, value) {
          $('#subDealerData').append('<option value="' + value.username + '">' + value
            .username + '</option>');
        });
      }
    });
  }
  function deleteConfirmModal(id,username,date,amount,dealerid,cash_amount) {
    $('#deleteTransferModal').modal({
      backdrop: 'static',
      keyboard: false
    })
    $("#deleteTransferModal").modal('show');
    $("#id").val(id);
    $("#usernames").html(username);
    $("#receivers").val(username);
    $("#amountPost").val(amount);
    $("#date").html(date);
    $("#amount").html(amount);
    $("#dealername").val(dealerid);
    $("#cash_amount").val(cash_amount);
  }
  $('#deleteForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "{{route('user.deleteTransferPost')}}",
      data: $(this).serialize(),
      dataType : 'json',
      beforeSend: function() {    
        $('#sub').attr('disabled',true);
      },
      success: function(response)
      {
        if(response == 'deleted'){
          window.history.back();
        }else{
          alert(response);
        }
      }
    });
  });
</script>
<script>
  $(document).ready(function () {
    $('#reseller-dropdown').on('change', function () {
      var reseller_id = this.value;
      if(reseller_id == ''){
        $('#btn_generate').prop('disabled', true)
      }else{
        $('#btn_generate').prop('disabled', false)
      }
      $("#dealer-dropdown").html('');
      $.ajax({
        url: "{{route('get.dealer')}}",
        type: "POST",
        data: {
          reseller_id: reseller_id,
          _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (result) {
          $('#dealer-dropdown').html('<option value="">-- Select contractor --</option>');
          $.each(result.dealer, function (key, value) {
            $("#dealer-dropdown").append('<option value="' + value
              .username + '">' + value.username + '</option>');
          });
          $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
        }
      });
    });
/*------------------------------------------
--------------------------------------------
Trader Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#dealer-dropdown').on('change', function () {
  var dealer_id = this.value;
  $("#trader-dropdown").html('');
  $.ajax({
    url: "{{route('get.trader')}}",
    type: "POST",
    data: {
      dealer_id: dealer_id,
      _token: '{{csrf_token()}}'
    },
    dataType: 'json',
    success: function (result) {
      $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
      $.each(result.subdealer, function (key, value) {
        $("#trader-dropdown").append('<option value="' + value
          .username + '">' + value.username + '</option>');
      });
    }
  });
});
});
</script>
@endsection
<!-- Code Finalize -->