@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
  .th-color{
    color: white !important;
    /*font-size: 15px !important;*/
  }
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
  .border-right{
    border-right: 1px solid #b4b4b4;
    width: 0px;
    background: #000;
    display: inline-block;
    /* position: relative; */
    height: 500px;

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
          <a href="{{route('admin.users.getuseragreements')}}" class="btn btn-default" style="border: 1px solid black"><i class="fa fa-list"></i> Agreement List</a>

          <div class="header_view">
            <h2>Contractor agreement form </h2>
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
            <form target="_blank" action="{{route('admin.postuserform')}}" method="POST">
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
                       <option value="" selected>Select Company <span style="color: red">*</span></option>
                       @foreach($get_brands as $brand)
                       <option value="{{$brand->reseller_id}}" data-id="{{asset('logo').'/'.$brand->image}}">{{$brand->brand_name}}</option>
                       @endforeach
                     </select>
                   </div>
                   <div class="form-group col-md-4">
                    <label for="company_date">Select Contractor <span style="color: red">*</span></label>

                    <select class="form-control form-select" name="dealer_name" required id="dealer-dropdown">
                     <!--  -->
                     
                   </select>
                 </div>
                 <div class="col-md-4 form-group">
                  <p class="text-center"><img src="" class="img" id="image" name="company_logo" style="width: 180px"></p>
                  
                </div>

              </section>
            </div>
            <div class="col-md-12" >
             <section class="box" style="overflow: hidden;">
               <div class="form-group">
                <h3 class="text-center">On Behalf of LOGON BROADBAND (Pvt.) Limited</h3>
              </div>

              <div class="form-group col-lg-4 col-md-6">
               <label for="behalf_name">Name <span style="color: red">*</span></label>
               <input type="text" required class="form-control" name="behalf_name" id="behalf_name">
             </div>

             <div class="form-group col-lg-4 col-md-6">
               <label for="behalf_designation">Designation <span style="color: red">*</span></label>
               <input type="text"  required class="form-control" name="behalf_designation" id="behalf_designation">
             </div>

             
           </section>
         </div>

         <div class="col-md-12" >
           <section class="box" style="overflow: hidden;">
             <div class="form-group">
              <label><strong>Description :</strong></label>
              <textarea class="ckeditor form-control" name="content_name" rows="10">{{$data_agreement->content_name}}</textarea>
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

</section>
</section>
<!-- END CONTENT -->
<!---Model Dialog --->

</div>
<!-- <script
  src="https://code.jquery.com/jquery-3.6.4.min.js"
  integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
  crossorigin="anonymous"></script> -->
  @endsection
  @section('ownjs')
  <!-- <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script> -->

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





   $('#company').on('change', function () {
                var reseller_id = this.value;
                $("#dealer-dropdown").html('');
                $.ajax({
                    url: "{{route('admin.dealer')}}",
                    type: "POST",
                    data: {
                        reseller_id: reseller_id,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#dealer-dropdown').html('<option value="">-- Select Dealer --</option>');
                        $.each(result.dealer, function (key, value) {
                            $("#dealer-dropdown").append('<option value="' + value
                                .dealerid + '">' + value.dealerid + '</option>');
                        });
                         $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
                    }
                });
            });
 </script>
 @endsection

