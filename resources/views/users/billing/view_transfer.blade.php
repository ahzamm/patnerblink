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
</style>
@endsection
@section('content')
@php
$checkCardType = 'viewTransfer';
if (Auth::user()->status == 'dealer') {
$checkBillingType = App\model\Users\DealerProfileRate::where('dealerid', Auth::user()->dealerid)->first()->billing_type;
$checkCardType = $checkBillingType == 'amount' ? 'viewTransfer' : ($checkBillingType == 'card' ? 'viewCardTransfer' : null);
}
@endphp
<div class="page-container row-fluid container-fluid">
    <!-- CONTENT START -->
    <section id="main-content" class=" ">
        <section class="wrapper main-wrapper row" style=''>
            @if ($checkCardType == 'viewTransfer')
            <div class="header_view">
                <h2>Transfer Amount <span style="color: lightgray"><small>(History)</small></span>
                    <span class="info-mark" onmouseenter="popup_function(this, 'transfer_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                </h2>
            </div>
            <section class="box" style="padding-top:20px">
                <div class="content-body">
                    <table id="example12" class="table table-bordered display data-table dt-responsive w-100">
                        <?php $file_name = 'Transfer-Amount-'.date('d-M-Y[H-i-s]').'.csv'; ?>
                        <button class="btn btn-primary" style="float: right;margin-right: 15px;margin-bottom:20px" onclick="exportTableToCSV('#example12','{{$file_name}}')"><i class="fa fa-download"></i> Download</button>
                        <thead>
                            <tr>
                                <th>Serial#</th>
                                <th>Account (ID)</th>
                                <th>Transferred Amount (PKR) </th>
                                <th>Transferred By</th>
                                <th>Action (Date & Time)</th>
                                <th>Action (IP Address)</th>
                                {{-- @if (Auth::user()->username == 'logonbroadband') --}}
                                {{-- <th>Action</th> --}}
                                {{-- @endif --}}
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"
                                style="text-align:center;font-weight:bold;font-size: 15px;background-color: #095a1d !important;color: #fff !important;">
                                <b>Grand Total (PKR):</b></td>
                                <td id="sum"
                                style="text-align:center;font-weight:bold;font-size: 15px; background-color: #095a1d !important;color: #fff !important;">
                            </td>
                            <td colspan="3"> </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
        @else
        <div class="">
            <div class="header_view">
                <h2>View Transfer Profile</h2>
            </div>
            <section class="box ">
                <header class="panel_header">
                </header>
                <div class="content-body">
                    <table id="example1" class="table table-bordered display dt-responsive w-100">
                        <thead>
                            <tr>
                                <th>Serial#</th>
                                <th>Username</th>
                                <th>Transfer Profile </th>
                                <th>No of Profile</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $sno = 1;
                            $total = 0;
                            @endphp
                            @foreach ($transferCard as $cards)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td>{{ $cards->sub_dealer_id }}</td>
                                <td>{{ $cards->name }}</td>
                                <td>{{ $cards->profilecount }}</td>
                                <td>{{ $cards->date }}</td>
                            </tr>
                            @php
                            $total += $cards->profilecount;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2"
                                style="text-align:center;font-weight:bold;font-size: 15px;">
                            Total Profiles:</th>
                            <th> </th>
                            <th id=""
                            style="text-align:center;font-weight:bold;font-size: 15px; color: green">
                        {{ $total }}</th>
                        <th> </th>
                    </tr>
                </tfoot>
            </table>     
        </div>
    </section>
</div>
@endif
<!-- Modal -->
<div class="modal fade" id="deleteTransferModal" tabindex="-1" role="dialog"
aria-labelledby="deleteTransferModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-danger" id="deleteTransferModalTitle">Alert</h5>
        </div>
        <form id="deleteForm">
            <div class="modal-body">
                <h3>Do You Want To Delete Transfered Amount</h3>
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="receivers" name="receivers">
                <input type="hidden" id="amountPost" name="amount">
                <table class="table">
                    <thead>
                        <th>Username</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                        <td><span id="usernames"></span></td>
                        <td><span id="amount"></span></td>
                        <td><span id="date"></span></td>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>
</div>
</section>
</section>
<!-- CONTENT END -->
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
    $(function() {
        var saveOpt = localStorage.getItem("defaultDTentries");
        if (saveOpt == null) {
            saveOpt = 10;
        }
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            "lengthMenu": [
            [saveOpt, 10, 25, 50, 100, -1],
            [saveOpt, 10, 25, 50, 100, 'All']
            ],
            ajax: "{{ route('viewTransferserver') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'receiver',
                name: 'receiver',
                class: 'td__profileName'
            },
            {
                data: 'ta',
                name: 'ta'
            },
            {
                data: 'action_by_user',
                name: 'action_by_user'
            },
            {
                data: 'date',
                name: 'date'
            },
            {
                data: 'action_ip',
                name: 'detail'
            },
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                data;
// Remove The Formatting To Get Integer Data For Summation
var intVal = function(i) {
    return typeof i === 'string' ?
    i.replace(/[\$,]/g, '') * 1 :
    typeof i === 'number' ?
    i : 0;
};
// Total Over All Pages
total = api
.column(2)
.data()
.reduce(function(a, b) {
    return intVal(a) + intVal(b);
}, 0);
// Total Over This Page
pageTotal = api
.column(2, {
    page: 'current'
})
.data()
.reduce(function(a, b) {
    return intVal(a) + intVal(b);
}, 0);
// Update Footer
$(api.column(2).footer()).html(formatMoney(pageTotal));
},
});
    });
    $(document).on('change', '#example12_length select', function() {
        var opt = $(this).val();
        localStorage.setItem("defaultDTentries", opt);
    });
</script>
<script type="text/javascript">
    $('#example1').on('draw.dt', function() {
        var sum = 0;
        $(".ta").each(function() {
            sum += parseFloat($(this).val());
        });
        $('#sum').html(formatMoney(sum));
    });
</script>
{{-- <script type="">
    $(document).ready(function() {
        var table = $('#example1').DataTable({"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]});
        $("#example1 thead td").each( function ( i ) {
            var select = $('<select class="form-control"><option value="">Show All</option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column( i )
                .search("^" + this.value + "$", true, false, true)
// .search( $(this).val() )
.draw();
} );
            table.column( i ).data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        } );
    } );
</script> --}}
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
    function xyz(username, amount) {
        var submit = confirm("Are you sure want to post this?? \n" + formatMoney(amount) + " \n" + inWords(amount));
        if (submit == true) {
            $("#myform").submit(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('users.billing.save') }}",
                    data: $("#myform").serialize(),
                    success: function(data) {
                        location.reload();
                    },
                    error: function(jqXHR, text, error) {
// Displaying If There Are Any Errors
$('#demo').html(error);
}
});
                return false;
            });
        } else {}
    }
    function deleteConfirmModal() {
        var id = $(".modal-data").attr('data-id');
        var username = $(".modal-data").attr('data-receiver');
        var amount = $(".modal-data").attr('data-amount');
        var date = $(".modal-data").attr('data-date');
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
    }
    $('#deleteForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ route('user.deleteTransferPost') }}",
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response == 'deleted') {
                    location.reload();
                } else {
                    alert(response);
                }
            }
        });
    });
</script>
@endsection
<!-- Code Finalize -->