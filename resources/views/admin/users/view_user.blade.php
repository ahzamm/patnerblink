@extends('admin.layouts.app')
@include('admin.layouts.bytesConvert')
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

  #sno select{
    display: none;
  }
  #username select{
    display: none;
  }
  #Full-Name select{
    display: none;
  }
  #address select{
    display: none;
  }
  #Login_Time select{
    display: none;
  }
  #Assign select{
    display: none;
  }
  #Download select{
    display: none;
  }
  #Upload select{
    display: none;
  }
  #expiry select{
    display: none;
  }

 
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">

  <!-- SIDEBAR - START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>

      <div class="">
        <div class="col-lg-12">
        
          <div class="header_view">
            <h2>Consumers</h2>
          </div>


          <div class="table-responsive" >
            <div style="overflow-x: auto;">
              <table id="example1" class="table table-striped dt-responsive display">
                <thead class="text-primary" style="background:#225094c7;">
                  <tr>
                    <th class="th-color">Serial#</th> 
                    <th class="th-color">Username</th>
                    <th class="th-color">Full Name</th>
                    <th class="th-color">Contractor </th>
                    <th class="th-color">Trader </th>
                    <th class="th-color">Package Profile </th>
                    <th class="th-color">Expiry Date</th>
                    <th class="th-color">Usage (Download/Upload)</th>
                   
                    <th class="th-color">Actions</th>
                  </tr>



                  <tr style="background:white !important;">
                  <td id="sno"></td>
                  <td id="username"></td>
                  <td id="Full-Name"></td>
                  <td id=""></td>
                  <td id=""></td>
                  <td id=""></td>
                  <td id="Upload"></td>
                  <td id="expiry"></td>
                  
                  <td id="Assign"></td>
                 
                  
                </tr>
                </thead>
                @php
                $count=1;
                @endphp
                <tbody>
                 @foreach($all as $data)
                 @php
                //  dd($data);
                 $totalDownload = App\model\Users\RadAcct::where(['username' => $data->username])->sum('acctoutputoctets');
                 $totalupload = App\model\Users\RadAcct::where(['username' => $data->username])->sum('acctinputoctets');


                 
         if( $online= App\model\Users\RadAcct::where(['username' => $data->username])->where(['acctstoptime' => NULL])->orderby('acctupdatetime','DESC')->first() ){

         $status='fa fa-toggle-on';
         $colors='green';
       }else{
       $status='fa fa-toggle-off';
         $colors='grey';
     }
          
       
              


                 $groupname = $data->profile;


                 $groupname = str_replace('BE-', '', $groupname);
                 $groupname = str_replace('k', '', $groupname);

                 $profile =App\model\Users\Profile::select('name')->where(['groupname'=> $groupname])->first();
                 
                 if($profile){
                 $name = $profile->name;
                     }else{
                     $name="";
                   }



                 @endphp
                 <tr>
                  <td>{{$count++}} <i class="{{$status}}" style="color:{{$colors}}"></i> </td>
                  <td>{{$data->username}}</td>
                  <td>{{$data->firstname}} {{$data->lastname}}</td>
                  <td>{{$data->dealerid}}</td>
                  <td>{{$data->sub_dealer_id}}</td>
                  <td>{{$name}}</td>
                  <td></td>
                  <td>{{ByteSize($totalDownload)}}/{{ByteSize($totalupload)}}</td>
                 
                  <td>
                    <center>
                      <a href="{{route('admin.user.show',['status' => 'user','id' => $data->id])}}"><button class="btn btn-info btn-xs">
                      <i class="fa fa-user"> </i> View</button></a>
                       @if(Auth::user()->status != "support")
                     <a href="{{route('admin.user.edit',['status' => 'user','id' => $data->id])}}"> <button class="btn btn-primary mb1 bg-olive btn-xs">
                        <i class="fa fa-edit"> </i> Edit
                      </button></a> 
                    @endif
                  </center>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                
              </table>
            </div>

            <div style="float: right;">

              {{$all->links()}}
            </div>
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

</div>
@endsection
@section('ownjs')

<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#example1').DataTable({
      "bPaginate": false,
      "searching": false
    });
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


@endsection