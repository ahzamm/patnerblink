@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
  .th-color{
    color: white !important;
    /*font-size: 15px !important;*/
  }
  .header_view{
    margin: auto;
    height: 40px;
    padding: auto;
    text-align: center;

    font-family:Arial,Helvetica,sans-serif;
  }
  h2{
    color: #225094 !important;
  }
  .dataTables_filter{
    margin-left: 60%;
  }
  tr,th,td{
    text-align: center;
  }
  select{
    color: black;
  }


</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">

  <!-- SIDEBAR - START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>

      <div class="">
        <div class="header_view">
          <h2>Receipt History</h2>
        </div>
        <!--  -->
        <div class="col-lg-12">
          <section class="box ">
            <header class="panel_header">
              <h2 class="title pull-left"></h2>

            </header>
            <div class="content-body">   
              <div class="row">
                <div class="col-md-12">
                  <div style="overflow-x: auto;">
                    <table id="example1" class="table table-bordered display ">
                      <thead>
                        <tr>
                          <th style="width:25px">Receipt#</th>
                          <th>Username</th>
                          <th>Recieve Amount</th>
                          <th>Payment Mode</th>
                          <th>Recieve By</th>
                          <th>Comment</th>
                          <th>Date</th>
                          <th>I.P</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $sno = 100000; @endphp
                        @foreach($paymentTransactions as $data)
                        <tr style="color: {{$data->type == 'reversal'?('red'):'black'}}">
                          <td>{{++$sno}}</td>
                          <td>
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
                         @if(Auth::user()->status == "reseller" || Auth::user()->username == "account09")
                          @if($data->status == "POST")
                          <td>{{$data->status}}</td>
                          @else
                          <td>
                            {{-- <form id="myform"> --}}
                            <button class="btn btn-link" onclick="formsubmit('{{$data->sender}}','{{$data->amount}}','{{$data->type}}','{{$data->date}}','{{$data->detail}}');">SAVE</button>
                          {{-- </form> --}}
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
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <th colspan="2" style="text-align:center;font-weight:bold;font-size: 15px;">Total:</th>
                          <th style="text-align:center;font-weight:bold;font-size: 15px; color: green"> </th>
                        </tr>
                      </tfoot>  
                    </table>
                  </div>

                </div>



              </div>

            </div>
          </section></div>
          <!--  -->
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

  </div>
  @endsection
  @section('ownjs')
 
  <script type="">
    $(document).ready(function() {
      var table = $('#example1').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
              return typeof i === 'string' ?
              i.replace(/[\$,]/g, '')*1 :
              typeof i === 'number' ?
              i : 0;
            };

            // Total over all pages
            total = api
            .column( 2 )
            .data()
            .reduce( function (a, b) {
              return intVal(a) + intVal(b);
            }, 0 );

            // Total over this page
            pageTotal = api
            .column( 2, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
              return intVal(a) + intVal(b);
            }, 0 );

            // Update footer
            $( api.column( 2 ).footer() ).html(formatMoney(pageTotal) );
          }
        } );



        // var table = $('#customertable').DataTable();
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
      //  $("#n").val('');
      //  $("#l").val('');
      // $('#demo').html(data);
      // $('#body').html(data);
      // $('#exampleModal').modal('show');
      location.reload();
      
      },
      error: function(jqXHR, text, error){
  // Displaying if there are any errors
  $('#demo').html(error);
}
    });

    
 

    }
</script>
  @endsection