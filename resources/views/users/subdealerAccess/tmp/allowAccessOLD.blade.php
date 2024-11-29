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
  h1,h2,h3{
    color: #225094 !important;
  }
  
  .dataTables_filter{
    margin-left: 60%;
  }
  /* tr,th,td{
    text-align: center;
  } */
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
                
                <h1>Sub Dealer Access Management<small></small></h1>
              </div>
              <div class="col-lg-12">
                <section class="box ">
                 
                 
                  <div class="content-body">
                    <div class="row">
                        <h3>User Access</h3>
                        <hr/>
                      <div  class="page-sidebar-wrapper col-md-12" id="main-menu-wrapper">
                          <ul class='wraplist'>
                           
                          @foreach($allData as $data)
                          <li>
                              <a href="javascript:;">
                                  <i class="fa fa-user"></i>
                                 <span class="title h4">{{$data->username}} ({{$data->status}})</span>
                                  <span class="arrow"></span>
                              </a>
                              <ul class="sub-menu">
                              <div style="height:150px; margin-left: 20px; overflow-y:scroll">
                                  <table class="table table-striped  display" style="height:30%">
                                  <thead>
                                    <tr>
                                        <th>#</th>
                                      <th>Super Module</th>
                                      <th>Sub Module</th>
                                      <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if($data->status == 'subdealer')
                                      @php
                                      $num=1;
                                      @endphp
                                      @foreach($access as $datas)
                                    <tr>
                                        <td>{{$num++}}</td>
                                      <td>{{$datas->parentModule}}</td>
                                      <td>{{$datas->childModule}}</td>
                                      <td>
                                          @php
                                   $check ='';
                                $loadData = App\model\Users\AccessPermission::where(['userid' => $data->id, 'accessid' => $datas->id])->first();
                                if(!empty($loadData)){
                                  $check = $loadData['access'];
                                }
                                $isCheck = $check;     
                                  @endphp
                                  @if($isCheck == 0)
                                        <label class="switch" style="width: 46px;height: 19px;">
                                          <input type="checkbox"  name="chk" onchange="changeSubdealerAccess(this, '{{$data->id}}','{{$datas->childModule}}','{{$datas->id}}');snack('green','Successfully Updated','check')">
                                          <span class="slider square" ></span>
                                        </label>
                                        @elseif($isCheck == 1)
                                        <label class="switch" style="width: 46px;height: 19px;">
                                            <input type="checkbox" checked  name="chk" onchange="changeSubdealerAccess(this, '{{$data->id}}','{{$datas->childModule}}','{{$datas->id}}');snack('green','Successfully Updated','check')">
                                            <span class="slider square" ></span>
                                          </label>
                                          @else
                                          <label class="switch" style="width: 46px;height: 19px;">
                                              <input type="checkbox"   name="chk" onchange="changeSubdealerAccess(this, '{{$data->id}}','{{$datas->childModule}}','{{$datas->id}}');snack('green','Successfully Updated','check')">
                                              <span class="slider square" ></span>
                                            </label>
                                            @endif
                                      </td>
                                    </tr>
                                    @endforeach
                                  @endif
                                  </tbody>

                                </table>
                                </div>
                              </ul>
                            </li>
                             @endforeach
                          </ul>
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
        function changeSubdealerAccess(checkBox,id,childModule,accessid)
            {
              let isCheck = checkBox.checked;
                $.ajax({
                    url:"{{route('users.manage.subdealerAccess')}}",
                    method:"POST",
                    data:{isChecked:isCheck, id:id,child:childModule,accessid:accessid},
                    success:function(data){
                //  alert(data);
                    }
                });
            }
        </script>
   
@endsection