@extends('users.layouts.app')
@section('title')
    Dashboard
@endsection
@section('owncss')
    <!-- //head is not necssary  -->
    <style type="text/css">
        .th-color {
            color: white !important;
            /*font-size: 15px !important;*/
        }

        .header_view {
            margin: auto;
            height: 40px;
            padding: auto;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        h2 {
            color: #225094 !important;
        }

        .dataTables_filter {
            margin-left: 60%;
        }

        tr,
        th,
        td {
            text-align: center;
        }

        select {
            color: black;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 11px !important;
            width: 13px !important;
            left: 3px !important;
            /*bottom: 3px !important;*/
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        #successalert {
            display: none;
        }

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
    @php
    $checkCardType = 'viewTransfer';
    if (Auth::user()->status == 'dealer') {
        $checkBillingType = App\model\Users\DealerProfileRate::where('dealerid', Auth::user()->dealerid)->first()->billing_type;
        $checkCardType = $checkBillingType == 'amount' ? 'viewTransfer' : ($checkBillingType == 'card' ? 'viewCardTransfer' : null);
        // dd($checkCardType);
    }

    @endphp
    <div class="page-container row-fluid container-fluid">
        <!-- SIDEBAR - START -->
        <section id="main-content" class=" ">
            <section class="wrapper main-wrapper row" style=''>
                @if ($checkCardType == 'viewTransfer')
                    <div class="">
                        <div class="col-lg-12">
                            <div class="header_view">
                                <h2>View Transfer</h2>
                            </div>
                            <div class="col-lg-12">
                                @if($errors->any())
                                <h4>{{$errors->first()}}</h4>
                                @endif
                                <section class="box ">
                                    <header class="panel_header">
                                    </header>
                                    <div class="content-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-bordered display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sno</th>
                                                                <th>Username</th>

                                                                <th>Transfer Amount </th>


                                                                <th>Transfer By</th>
                                                                @if (Auth::user()->status == 'reseller' || Auth::user()->status == 'account')
                                                                    <th>Comments</th>
                                                                @endif
                                                                <th>Date</th>
                                                                <th>IP</th>
                                                                @if(Auth::user()->username == 'logonbroadband')
                                                                <th>Action</th>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td id="filter1"></td>
                                                                <td id="filter2"></td>

                                                                <td id="filter3"></td>

                                                                <td id="filter4"></td>
                                                                @if (Auth::user()->status == 'reseller' || Auth::user()->status == 'account')
                                                                    <td id="filter5"></td>
                                                                @endif
                                                                <td id="filter6"></td>
                                                                <td id="filter7"></td>
                                                                @if(Auth::user()->username == 'logonbroadband')
                                                                <td id="filter8"></td>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $sno = 1;
                                                                $ta = 0;
                                                            @endphp
                                                            @foreach ($amountTransactions as $data)
                                                                <tr>
                                                                    <td>{{ $sno++ }}</td>
                                                                    <td>{{ $data->receiver }}</td>
                                                                    <!-- @if ($data->amount == 0)
    $ta = number_format(-$data->cash_amount,2);
@else
    $ta = number_format($data->amount,2);
    @endif -->
                                                                    @php
                                                                        
                                                                        $ta = $data->amount == 0 ? -$data->cash_amount : $data->amount;
                                                                    @endphp
                                                                    <input type="hidden" class="ta"
                                                                        value="{{ $ta }}">


                                                                    <td>{{ number_format($ta, 2) }}</td>

                                                                    <td>{{ $data->action_by_user }}</td>
                                                                    @if (Auth::user()->status == 'reseller' || Auth::user()->status == 'account')
                                                                        <td>{{ $data->comments }}</td>
                                                                    @endif
                                                                    <td>{{ $data->date }}</td>
                                                                    <td>{{ $data->action_ip }}</td>
                                                                    @if(Auth::user()->username == 'logonbroadband')
                                                                    <td><button class="btn-xs btn-danger"
                                                                            style="border-radius: 20px"
                                                                            onclick="deleteConfirmModal({{$data->id}},'{{$data->receiver}}','{{$data->date}}','{{$data->amount}}')"
                                                                            {{ $data->amount == 0 ? 'disabled' : '' }}>Delete</button>
                                                                    </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>

                                                            <tr>
                                                                <th colspan="2"
                                                                    style="text-align:center;font-weight:bold;font-size: 15px;">
                                                                    Total:</th>
                                                                <th id="sum"
                                                                    style="text-align:center;font-weight:bold;font-size: 15px; color: green">
                                                                </th>
                                                                <th> </th>
                                                                @if (Auth::user()->status == 'reseller' || Auth::user()->status == 'account')
                                                                    <th> </th>
                                                                @endif
                                                                <th> </th>
                                                                <th> </th>
                                                                @if(Auth::user()->username == 'logonbroadband')
                                                                <th> </th>
                                                                @endif
                                                            </tr>

                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="">
                        <div class="col-lg-12">
                            <div class="header_view">
                                <h2>View Transfer Profile</h2>
                            </div>
                            <div class="col-lg-12">
                                <section class="box ">
                                    <header class="panel_header">
                                    </header>
                                    <div class="content-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table id="example1" class="table table-bordered display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sno</th>
                                                                <th>Username</th>
                                                                <th>Transfer Profile </th>
                                                                <th>No of Profile</th>
                                                                <th>Date</th>

                                                            </tr>
                                                            <tr>
                                                                <td id="filter1"></td>
                                                                <td id="filter2"></td>
                                                                <td id="filter3"></td>
                                                                <td id="filter4"></td>
                                                                <td id="filter5"></td>
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
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Modal -->
                <div class="modal fade" id="deleteTransferModal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteTransferModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="deleteTransferModalTitle">Alert</h5>
                                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> --}}
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
                <div class="chart-container " style="display: none;">
                    <div class="" style="height:200px" id="platform_type_dates"></div>
                    <div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
                    <div class="" style="height:200px" id="user_type"></div>
                    <div class="" style="height:200px" id="browser_type"></div>
                    <div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
                    <div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
                </div>
            </section>
        </section>
        <!-- END CONTENT -->
        <!---Model Dialog --->
    </div>
    <!---Model Dialog --->
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
			// .search( $(this).val() )
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
                            // Displaying if there are any errors
                            $('#demo').html(error);
                        }
                    });

                    return false;
                });

            } else {

            }
        }

        function deleteConfirmModal(id,username,date,amount) {
          
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
            url: "{{route('user.deleteTransferPost')}}",
            data: $(this).serialize(),
            dataType : 'json',
            success: function(response)
            {
               if(response == 'deleted'){
                location.reload();
               }else{
                alert(response);
               }
           }
       });
     });

    </script>
@endsection
