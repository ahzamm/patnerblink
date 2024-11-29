@extends('admin.layouts.app')
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
  h2, h1{
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
  #ip select{
    display: none;
  }
  #sno select{
    display: none;
  }
  #username select{
    display: none;
  }
  #nic select{
    display: none;
    } 
    #res select{
      display: none;
      } 
      #app select{
        display: none;
      }#mob select{
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
                
                <h1>Update Ticker</h1>
              </div>
              
              <div class="col-lg-12">
                <section class="box ">
                  <header class="panel_header">
       
                    <center><h3></h3> </center>
                  </header>
                 
                  <div class="content-body">
                      <div class="Box">
                          <center><b><p>Ticker are Applied on <span style="color: red">Reseller, Dealer and Sub Dealer </span>you can write in both language or any one </p></b></center>
                        </div>
                    <div class="row">
                        <form action="{{route('admin.AccessManagement.tickerToDb')}}" method="get">
                      <div class="col-lg-6 col-md-6 col-sm-6" id="t1">
                        <label for="">Write  Ticker Here <span style="color: red">(English)</span>  
                        <textarea name="eng" id="article-ckeditor"></textarea></label>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6" id="t2">
                      
                        <label for="">Write  Ticker Here <span style="color: red">(Urdu)</span>  
                          <textarea name="urdu" id="excerpt-editor"></textarea></label>
                      </div>
                       
                      <div class="col-lg-12- col-md-12 col-sm-12 " style="margin-top: 10px;">
                          <button class="btn btn-primary pull-right" type="submit"><span class="fa fa-check"> Change Ticker</span></button>
                        <label for="">
                        <input type="checkbox" name="tic" id="tic" onchange="changeTic(this);">Delete Ticker
                      </label>
                      </div>
                     
                    </form>
                    </div>
                  </div>
                </section>
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
    	</div>
  </section>
</section>

<!-- END CONTENT -->
<!---Model Dialog --->


</div>
<!---Model Dialog --->
{{-- @include('users.reseller.model_dealer')--}}

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
      function changeTic(checkbox){
        status = checkbox.checked;
        if(status == 'true'){
        $('#t1').hide();
        $('#t2').hide();
        }else{
          $('#t1').show();
          $('#t2').show();
        }
      }
      </script>
    
@endsection