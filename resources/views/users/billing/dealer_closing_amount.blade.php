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
                <h2>Contractor Net Credit Amount
                    <span class="info-mark" onmouseenter="popup_function(this, 'contractor_net_credit_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                </span></h2>
            </div>
            <div>
                @if($errors->any())
                <h4>{{$errors->first()}}</h4>
                @endif
                <section class="box">
                    <header class="panel_header"></header>
                    <div class="content-body">
                        <table id="example1" class="table table-bordered display dt-responsive w-100">
                            <thead>
                                <tr>
                                    <th>Serial#</th>
                                    <th>Account (ID)</th>
                                    <th>Closing Amount (PKR) </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($result as $key => $item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$item['username']}}</td>
                                    <td>{{$item['amount']}}</td>
                                </tr>
                                @empty
                                <td>No Data Found</td>
                                @endforelse
                            </tbody>
                            <tfoot>
                                {{-- <tr>
                                    <th colspan="2"
                                    style="text-align:center;font-weight:bold;font-size: 15px;">
                                Total:</th>
                                <th id="sum"
                                style="text-align:center;font-weight:bold;font-size: 15px; color: green">
                            </th>
                        </tr> --}}
                    </tfoot>
                </table>
            </div>
        </section>
    </div>
</section>
</section>
</div>
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
        var table = $('#example1').DataTable({"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]});
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
            n - i).toFixed(c).slice(3) : "");
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
// Displaying Of There Are Any Errors
$('#demo').html(error);
}
});
                return false;
            });
        } else {
        }
    }
</script>
@endsection
<!-- Code Finalize -->