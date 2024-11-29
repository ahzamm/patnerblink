@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
  
    span#cke_32, #cke_46 ,#cke_21,#cke_19{
    display: none;
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
  .ttable{
    overflow: hidden;
    overflow-y: scroll;
    max-height: 350px;
    width: 100%;
  }
  section.box{
  padding: 15px;
}
.col-md-4{
  padding: 0 5px;
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
        <a target="_blank" href="{{route('admin.users.getuseragreements_view',['id' => $id])}}" class="btn btn-default" style="border: 1px solid black"><i class="fa fa-eye"></i> View PDF</a>
          <div class="header_view">
            <h2>Contractor agreement update form </h2>
          </div>
          <section class="" style="padding: 15px;">
            <div>
              @if(session('error'))
            <div class="alert alert-error alert-dismissible">
              {{session('error')}}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
              {{session('success')}}
            </div>
            @endif
            </div>
          <form action="{{route('usersagreement.update',['id' =>$id])}}" method="POST">
              @csrf
              <div class="row">
                <div class="col-md-12" >
 
                  <section class="box" style="overflow: hidden;">
                    <div class="form-group">

                      <h3 class="text-center">Contractor Detail</h3>
                    </div>
                         <div class="form-group col-md-4">
                      <label for="company_date">Select Brand Name <span style="color: red">*</span></label>

  <select  class="form-control form-select company" name="company_name" id="company" onchange="select_company(this)">
                      <option value="" selected>Select Brand Name</option>
                        @foreach($get_brands as $brand)
                            <option value="{{$brand->brand_name}}" 
@php if($brand->brand_name == $company_name) echo "selected";  @endphp
                              data-id="{{asset('logo').'/'.$brand->image}}">{{$brand->brand_name}}</option>
                        @endforeach
                      </select>
                    </div>
                         <div class="form-group col-md-4">
                      <label for="company_date">Select Contractor <span style="color: red">*</span></label>

                      <select class="form-control form-select" name="dealer_name" required>

                         <option value=""  selected>Select Contractor</option>
                        @foreach($user_data as $user)
                        <option value="{{$user->dealerid}}" @php if($user->dealerid == $dealer_name) echo "selected";  @endphp  >{{$user->dealerid}}</option>
                        <!-- <option value="{{$user->dealerid}}" @if($user->dealerid === $dealer_name) selected @endif>{{$user->dealerid}}</option> -->
                       @endforeach
                      
                      </select>
                    </div>
                        <div class="col-md-4 form-group">
          
                 <p class="text-center"><img src="" class="img"  id="image" name="company_logo" style="width: 180px;"></p>
                  
                    </div>
                  </section>
                </div>
                <div class="col-md-12" >
                 <section class="box" style="overflow: hidden;">
                   <div class="form-group">
                    <h3 class="text-center">On Behalf of LOGON Broadband Pvt Ltd</h3>
                  </div>

                  <div class="form-group col-lg-4 col-md-6">
                   <label for="behalf_name">Name <span style="color: red">*</span></label>
                   <input type="text" required class="form-control" name="behalf_name" id="behalf_name" value="{{$behalf_name}}">
                 </div>

                 <div class="form-group col-lg-4 col-md-6">
                   <label for="behalf_designation">Designation <span style="color: red">*</span></label>
                   <input type="text"  required class="form-control" name="behalf_designation" id="behalf_designation" value="{{$behalf_designation}}">
                 </div>

                 
               </section>
             </div>

                     <div class="col-md-12" >
                 <section class="box" style="overflow: hidden;">
               <div class="form-group">
                                <label><strong>Description :</strong></label>
                                <textarea class="ckeditor form-control" name="content_name">{{$content_name}}</textarea>
                            </div>
                            <div class="col-md-12" style="padding-top: 15px">
                      <button type="submit" class="btn btn-primary pull-right">Submit</button>
                    </div>
             </section>
           </div>
            
</div>

</form>
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
  </section>
</section>
<!-- END CONTENT -->
<!---Model Dialog --->


</div>
@endsection
@section('ownjs')
<script type="text/javascript">
    // $(document).ready(function() {
    //    $('.ckeditor').ckeditor();
    //    $('#cke_32').css('display','none');

    // });
function select_company(select) {

     var company = (select.options[select.selectedIndex].value);
     var com = $('#company option:selected').data('id');
     $("#image").attr("src", com);
  }
</script>
@endsection