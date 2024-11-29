@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
  .th-color{
    color: white !important;
    background-color: #225094;
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
  .active{
    background-color: #225094 !important;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <!-- SIDEBAR - START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="">
        <div class="col-lg-12" >
          <div class="header_view">
            <h2>Update Reseller</h2>
          </div>
          
        </div>
      </div>
      <div class="col-lg-12">
        <section class="box ">
          <div class="content-body">
            <form id="general_validate"
            action="{{route('admin.user.update',['status' => 'reseller','id' => $id])}}" method="POST" >
            @csrf
            <div class="row">
              <!-- <h3>Basic Info:</h3> -->
              <hr>
              <div class="col-md-3">
                <div class="form-group">
                  <label  class="form-label">Manager ID</label>
                  <input type="text" value="{{$reseller->manager_id}}"  name="managerid" class="form-control" placeholder="Manager-ID" required readonly>
                </div>
                <div class="form-group" >
                  <label class="form-label">Reseller ID</label>
                  <input type="text" value="{{$reseller->resellerid}}" name="resellerid" class="form-control" placeholder="Reseller-Id" required>
                </div>
                <div class="form-group">
                  <label class="form-label">First Name</label>
                  <input type="text" value="{{$reseller->firstname}}" name="fname" class="form-control"  placeholder="First Name" required>
                </div>
                <div class="form-group">
                  <label  class="form-label">Last Name</label>
                  <input type="text" value="{{$reseller->lastname}}" name="lname" class="form-control"  placeholder="lastname" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label  class="form-label">Mobile Number</label>
                  <input type="text" value="{{$reseller->mobilephone}}" name="mobile_number" class="form-control"  data-mask="9999 9999999" required>
                </div>
                <div class="form-group">
                  <label  class="form-label">Landline Number</label>
                  <input type="text" value="{{$reseller->homephone}}" name="land_number" class="form-control"  data-mask="(999)99999999" >
                </div>
                <div class="form-group">
                  <label  class="form-label">CNIC Number</label>
                  <input type="text" value="{{$reseller->nic}}" name="nic" class="form-control" data-mask="99999-9999999-9" required>
                </div>
                <div class="form-group">
                  <label  class="form-label">Email Address</label>
                  <input type="email" value="{{$reseller->email}}" name="mail" class="form-control"  placeholder="lbi@gmail.com" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label  class="form-label">Business Address</label>
                  <input type="text" value="{{$reseller->address}}" name="address" class="form-control"  placeholder="Address" required>
                </div>
                <div class="form-group">
                  <label  class="form-label">Assign Reseller Area</label>
                  <input type="text" value="{{$reseller->area}}" name="area" class="form-control"  placeholder="Area " required>
                </div>
                <!-- <div class="form-group">
                  <label  class="form-label">Assign Static IPs Rates</label>
                  <input type="number" name="static_ip" class="form-control"  placeholder="" >
                </div> -->
               
              </div>
              <!--  -->
              <div class="col-md-3">
                <div class="form-group">
                  <label  class="form-label">Username</label>
                  <input type="text" value="{{$reseller->username}}" name="username" class="form-control"  placeholder="username" readonly required>
                </div>
                <div class="form-group">
                  <label  class="form-label">Assign Credit Limit</label>
                  <input type="text" value="{{$userAmount->credit_limit}}" name="limit" class="form-control"  placeholder="Amount"  required>
                </div>


                  <!-- <div class="form-group">
                    <label  class="form-label">Server Type</label>
                    <input type="text" readonly name="nas_type" class="form-control" value="">
                    
                    
                  </div> -->
                </div>
                <div class="col-md-12">
                <hr style="border-bottom: 2px solid #ddd; margin-bottom: 40px;margin-top: 50px;">

                <div class="header_view">
									<h2 style="font-size: 26px;">Static IPs Management</h2>
									<!-- <h2 style="font-size: 26px;">Access Management</h2> -->
								</div>
                  <!-- <h3>Access:</h3> -->
                  <hr>
                  
                  
                  <div class="col-md-offset-4 col-md-4">
                    <div class="form-group">
                      <div style="display:flex; align-items: center; justify-content: center">
                      <div>
                        <label  class="form-label">Gaming IPs</label>
                        <input type="radio" name="ip_type"   placeholder="0" value="gaming" id="ip_type"></div>
                        &nbsp; &nbsp; &nbsp;
                        <div>
                        <label  class="form-label"> Static IPs
                        </label>
                        <input type="radio" name="ip_type"   placeholder="0" value="static" ></div>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label  class="form-label">Number of IPs</label>
                      <input type="number" id="noofip" name="noofip" class="form-control" min="1"  placeholder="0" disabled>
                    </div>
                    <div class="form-group">
                      <label  class="form-label">Assign Static (Single IP) Rates (Rs.)</label>
                      <input type="number" name="static_ip" class="form-control"  placeholder="" >
                    </div>
                    <div class="form-group">
                      <div style="display: flex; align-items: center; justify-content: center">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons" id="ipassign">
                          <label class="btn btn-secondary" style="border-right: 2px solid #fff" disabled>
                            <input type="radio" name="ipassign" value="assign"  autocomplete="off" > Assign Now
                          </label>
                          <label class="btn btn-secondary" id="ipremove" disabled>
                            <input type="radio" name="ipassign" value="remove"  autocomplete="off" > Remove Now
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="col-md-4">
                    <label class="form-label"> Static IPs</label>
                    <div class="form-group">
                      <div class="btn-group btn-group-toggle" data-toggle="buttons" id="ipassign">
                        <label class="btn btn-secondary" >
                          <input type="radio" name="ipassign" value="assign"  autocomplete="off"> Assign
                        </label>
                        <label class="btn btn-secondary" id="ipremove">
                          <input type="radio" name="ipassign" value="remove"  autocomplete="off"> Remove
                        </label>
                      </div>
                    </div>
                  </div> -->
                  <div class="col-md-12">
                  <hr style="border-bottom: 2px solid #ddd; margin-bottom: 40px;margin-top: 50px;">
                    <div class="header_view">
									<!-- <h2 style="font-size: 26px;">Static IPs Management</h2> -->
									<h2 style="font-size: 26px;">Access Management</h2>
								</div>
                <hr>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="switch" style="width: 46px;height: 19px;">
                          <input type="checkbox" >
                          <span class="slider square" ></span>
                        </label>
                        <label class="form-label"> Trader (On & Off)  </label>
                        <br>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="switch" style="width: 46px;height: 19px;">
                          <input type="checkbox" >
                          <span class="slider square" ></span>
                        </label>
                        <label class="form-label"> CNIC & Mobile Verification (On & Off)</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="switch" style="width: 46px;height: 19px;">
                          <input type="checkbox" >
                          <span class="slider square" ></span>
                        </label>
                        <label class="form-label">Never Expire Function (On & Off)</label>
                      </div>
                    </div>
                  </div>
                </div>
                <!--  -->
                <div class="col-md-12">
                <hr style="border-bottom: 2px solid #ddd; margin-bottom: 40px;margin-top: 50px;">

<div class="header_view">
  <h2 style="font-size: 26px;">Assigned Internet Profile</h2>
</div>
                  <!-- <h3>Profile:</h3> -->
                  <!-- <hr> -->
                  <div class="col-md-4" style="padding-right: 0">
                    <div class="form-group" >
                      <div class="button-group">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" style="width: 100%;
    font-size: 15px;
    font-weight: bold;
    color: #fff;
    background: #225094;
    margin-top: 7px;
    padding: 8.5px;">
                          <!-- <span class="glyphicon glyphicon-cog"></span> -->
                          Select Profiles
                          <span class="caret"></span>
                          <span>(Dropdown)</span>
                        </button>
                          <ul class="dropdown-menu">
                            @foreach($profileList as $profile)
                            @php $profile =ucwords($profile->name); @endphp
                            
                            @if($profile != 'Lite' && $profile != 'Social' && $profile != 'Smart' && $profile != 'Super' && $profile != 'Turbo' && $profile != 'Mega' && $profile != 'Jumbo' && $profile != 'Sonic' )
                            <li>
                              <a href="#" class="small"  data-value="option1" tabIndex="-1" style="font-size: 16px;">
                                <input type="checkbox"  class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc(this.value,this.id)"  style="height: 16px;width: 16px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif  />&nbsp;{{$profile}}</a></li>

                                @endif
                                @endforeach
                              </ul>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-8 " style="height: 155px; overflow: auto;padding-left: 0" >
                          <center>
                            <table class="table table-responsive table-bordered" >
                              <thead class="thead table-striped">
                                <tr>
                                  <th scope="col" class="th-color">Internet Profile Name</th>
                                  <th scope="col" class="th-color"> Profile Rate (Rs.)</th>
                                </tr>
                              </thead>
                              <tbody class="tbody">
                                <!-- add row here -->
                                @foreach($assignedProfileRates as $profileRate)

                                
                                @php $npro=$profileRate->profile->name; @endphp

                                 @if($npro != 'lite' && $npro != 'social' && $npro != 'smart' && $npro != 'super' && $npro != 'turbo' && $npro != 'mega' && $npro != 'jumbo' && $npro != 'sonic' )

                                <tr id="{{ucfirst($profileRate->profile->name)}}tr">
                                  <td scope='row'>{{ucfirst($profileRate->profile->name)}}</td>
                                  <td scope='row'> <input type="number" class='form-control'
                                    placeholder='0'
                                    style='border: none; text-align: center;'
                                    name="{{ucfirst($profileRate->profile->name)}}" value="{{$profileRate->rate}}">
                                  </td>
                                </tr>
                                @endif
                                @endforeach
                              </tbody>
                            </table>
                          </center>
                        </div>
                      </div>
                      <div class="col-xs-12">
                        <div class="pull-right ">
                          <button type="submit" class="btn btn-success">Update</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </section></div>
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
            $(".add-more").click(function(){
              var html = $(".copy").html();
              $(".after-add-more").after(html);
            });
            $("body").on("click",".remove",function(){
              $(this).parents(".control-group").remove();
            });
          });
        </script>
        <script type="text/javascript">
          function mycheckfunc(val,id){
            var va="#"+id;
            if($(va).attr("title") == "uncheck"){
      //
      var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row'> <input type='number' class='form-control' required name='"+id+"' placeholder=0 min='50' style='border: none; text-align: center;''></td></tr>";
      $(".tbody").append(markup);
      //
      $(va).attr('title', 'check');
      //
    } else if($(va).attr("title") == "check"){
      //
      var trvar=va+"tr";
      $(trvar).remove();
      //
      $(va).attr('title', 'uncheck');
      //
    }
  }
</script>
<script>
  $(document).ready(function(){
    $("#ipassign").click(function(){
      $("#noofip").prop('required',true);
      $("#ip_type").prop('required',true);
    });
  });
</script>
<script>
  $('input[name="ip_type"]').on('change', function(){
    $('#noofip').prop('disabled', false);
  })
  $('#noofip').on('keyup', function(){
    var value= $(this).val();
    if(value > 0){
      $('#ipassign label').attr('disabled', false);
    }
    else{
      $('#ipassign label').attr('disabled', true);
    }
  })

</script>
@endsection