<head>
    <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
    <style type="">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        div {
            font-size: 95%;
        }
    </style>
</head>
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="col-sm-12 col-md-12">
                        <h3 class="card-header" style="width: 104%;
                        margin-left: -2%; text-align: center;">Trial
                    Balance</h3>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 col-lg-12">
                        Date : {{ date('d-m-Y') }} <br>
                        Time : {{ date('H:i:s') }}<br>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                    <tr style="background-color:#225094d1; color: white">
                        <th width="8%">#</th>
                        <th width="14%">Dealer-Name</th>
                        <th width="15%">Debit</th>
                        <th width="15%">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @php $num = 1;$t_debit = $t_credit = 0; @endphp
                    @foreach ($data as $entry)
                    @php
                    $c = App\model\Users\LedgerReport::where('username', '=', $entry['username'])
                    ->where('date', '<=', $toDate)->sum('credit');
                    $d = App\model\Users\LedgerReport::where('username', '=', $entry['username'])
                    ->where('date', '<=', $toDate)->sum('debit');
                    $cd = $d-$c;
                    $sumCredit = App\model\Users\LedgerReport::where('username', '=', $entry['username'])
                    ->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->sum('credit');
                    $sumDebit = App\model\Users\LedgerReport::where('username', '=', $entry['username'])
                    ->where('date', '>=', $fromDate)->where('date', '<=', $toDate)->sum('debit');
                    $total = $sumDebit - $sumCredit;
                    $total =  $cd;
                    if ($total < 0) {
                    $credit = $total;
                    $debit = 0;
                    // $total_credit = abs($total_credit1);
                    // static $total_c;
                    $t_credit += $total;
                } else {
                $credit = 0;
                $debit = $total;
                // static $total_d;
                $t_debit += $total;
                //    dd($total_d);
            }
            @endphp
            <tr>
                <td class="center">{{ $num++ }}</td>
                <td class="left">{{ $entry->username }}</td>
                <td class="center">{{ number_format($debit) }}</td>
                <td class="right">{{ number_format($credit) }} </td>
            </tr>
            @endforeach
            <tr>
                <td class="center"></td>
                <td class="left"></td>
                <td class="center">{{ number_format($t_debit) }}</td>
                <td class="right">{{ number_format($t_credit) }} </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12">
        <hr>
        <h3 style="text-align: center;color: #4171a9"> Thank's for your business. It's our pleasure to work
            with you !!!
        </h3>
        <hr>
    </div>
</div>
</div>
</div>
</div>