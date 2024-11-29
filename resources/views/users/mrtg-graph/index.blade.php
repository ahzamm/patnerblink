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
@section('title') Dashboard @endsection
@section('content')
<style>
  .rotate{
    animation: 1s rotateicon ease infinite;
  }
  @keyframes rotateicon {
    from{
      transform: rotate(0deg);
    }
    to{
      transform: rotate(360deg);
    }
  }
  .dynamic_graph_img{
    cursor: pointer;
  }
</style>
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view position-relative">
        <h2>MRTG Graph
          <span class="info-mark" onmouseenter="popup_function(this, 'mrtg_graph_user_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
        <button class="btn refresh "><i class="fa fa-refresh rotate"></i> Refresh</button>
      </div>
      <div class="box">
        <div class="content-body">
          <div class="row reload-graph"></div>
        </div>
      </div>
    </section>
  </section>
</div>
<!-- Modal Start -->
<div class="modal" id="graph_modal">
  <div class="modal-dialog modal-width">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-white" style="color: #fff"><span id="username" style="color:#fff;font-weight:bold;"></span></h4>
        <input type="hidden" id="unique_id">
        <button class="btn single-refresh" id="refresh_button" title="refresh"><i class="fa fa-refresh"></i> Refresh</button>
      </div>
      <div class="modal-body image-graph-model" style="padding-bottom: 0">
        <img src="" style="width: 100%;margin-bottom:5px" id="single-graph" class="img img-responsive dynamic_graph_img-single">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal End -->
@endsection
@section('ownjs')
<script>
  $(document).on('click', '.dynamic_graph_img', function() {
    var url = $(this).attr('src');
    var imgId = $(this).attr('data-id');
    var username = $(this).attr('data-name');
    var id = $(this).attr('id');
    $('.dynamic_graph_img-single').attr('src', url);
    $('.dynamic_graph_img-single').attr('id', id);
    $('#username').text(username);
    $('#graph_modal').modal('show');
  })
  $(document).ready(function() {
    graphFunction();
    function graphFunction() {
      var graphData =  '<?php echo json_encode($urls) ?>';
      $(".reload-graph").empty();
      $.ajax({
        type: 'POST',
        url: "{{route('user.graph.refresh')}}",
        success: function(result) {
          if ($.isEmptyObject(result.error)) {
            var mdClass = '';
            var test = '';
            var image_url = result.url;
            var graphdata = result.data;
            var length = graphdata.length;
            if (length == 1) {
              mdClass = 'col-md-12';
            } else if (length == 0) {
              test = '<h5 class="text-center">Apologies for the inconvenience. The MRTG graph is currently unavailable. Please contact our technical support team and company administrator for assistance.</h5>';
              $('.reload-graph').append(test);
              $('.refresh .fa-refresh').removeClass('rotate');
            } else {
              mdClass = 'col-md-6';
            }
            jQuery.each(JSON.parse(graphData), function( index, value ) {
              var html = `<div class="` + mdClass + ` image mb-5"><img src="` + value + `" style="width: 100%;margin-bottom:5px" id="` + graphdata[index].id + `" class="img img-responsive dynamic_graph_img" data-id="` + graphdata[index].graph_no + `" data-name="` + graphdata[index].user_id + `"></div>`;
              $('.reload-graph').append(html);
              $('.refresh .fa-refresh').removeClass('rotate');
            });
          } else {
            alert(result.error);
          }
        }
      })
    }
    $('.refresh').on('click', function() {
      $('.refresh .fa-refresh').addClass('rotate');
      graphFunction();
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('#refresh_button').on('click', function(e) {
      var username = $('#username').text();
      var cacti_id = $('.dynamic_graph_img-single').attr('id');
      $.ajax({
        type: 'POST',
        data: {
          cacti_id: cacti_id
        },
        url: "{{route('user.show_mrtg_graph')}}",
        success: function(result) {
          if ($.isEmptyObject(result.error)) {
            $('.dynamic_graph_img-single').attr('src', result.url);
          } else {
            alert(result.error);
          }
        }
      })
    });
  });
</script>
@endsection
<!-- Code Finalize -->