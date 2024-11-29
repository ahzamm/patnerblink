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
@extends('layouts.app')
@section('content')
@section('title','Add Menu')
<section class="no-padding-top">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Menus...</h5>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="card" style="border-color: rgb(126, 120, 120);">
                            <div class="card-header">
                                <h5 class="card-title">New Menu Add.</h5>
                            </div>
                            <div class="card-body">              
                                <form action="{{route('menus.store')}}" method="POST" id="AddMenusForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="i-checks float-right">
                                                <input id="hassubmenu" type="checkbox" value="hassubmenu" name="hassubmenu" data-value="false" checked="" class="checkbox-template">
                                                <label for="hassubmenu">Has Sub Menus</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Parent Menu Name</label>
                                                <input name="parentMenu" type="text" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group ">
                                                <label>Variant 2 - Secondary</label>
                                                <select data-style="btn-secondary" name="menuicon" class="icon-selectpicker form-control" data-live-search="true">
                                                    @foreach ($icons as $item)
                                                    <option value="{{$item}}" data-content="<i class='{{$item}}'></i> {{$item}}"></option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="singleRoute" style="display: none">
                                        </div>
                                        <div class="col-md-12" id="subRoute">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Sub Menu Name</th>
                                                        <th>Sub Menu Route</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="submenu-list">
                                                    <tr>
                                                        <td class="td-first">
                                                            <input type="" name="submenu[]" placeholder="Sub Menu Name" class="form-control" required/>
                                                        </td>
                                                        <td class="td-second">
                                                            <input type="" name="submenuroute[]" placeholder="Sub Menu Route" class="form-control" required/>
                                                            <span class="text-danger text-sm d-none">Route name not exist in database</span>
                                                        </td>
                                                        <td><button class="btn btn-success btn-sm my-1" type="button" id="btnAddSubMenu"><i class="fa fa-plus"></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <a class="btn btn-outline-secondary btn-sm float-right ml-2" href="{{route('menus.index')}}">Cancel</a>
                                                <button class="btn btn-outline-primary btn-sm float-right" type="submit" id="menuBtn">Add Menu</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/html" id="data-row">
    <tr>
        <td class="td-first">
            <input type="" name="submenu[]" placeholder="Sub Menu Name" class="form-control" required/>
        </td>
        <td class="td-second">
            <input type="" name="submenuroute[]" placeholder="Sub Menu Route" class="form-control" required/>
            <span class="text-danger text-sm d-none">Route name not exist in database</span>
        </td>
        <td><button class="btn btn-success btn-sm my-1" type="button" id="btnAddSubMenu"><i class="fa fa-plus"></i></button></td>
    </tr>
</script>
<script type="text/html" id="singleMenubody">
    <div class="form-group">
        <label>Route Name</label>
        <input name="parentroutename" type="text" class="form-control" required>
    </div>
</script>
<script type="text/html" id="subMenubody">
    <table class="table">
        <thead>
            <tr>
                <th>Sub Menu Name</th>
                <th>Sub Menu Route</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="submenu-list">
            <tr>
                <td class="td-first">
                    <input type="" name="submenu[]" placeholder="Sub Menu Name" class="form-control" required/>
                </td>
                <td class="td-second">
                    <input type="" name="submenuroute[]" placeholder="Sub Menu Route" class="form-control" required/>
                    <span class="text-danger text-sm d-none">Route name not exist in database</span>
                </td>
                <td><button class="btn btn-success btn-sm my-1" type="button" id="btnAddSubMenu"><i class="fa fa-plus"></i></button></td>
            </tr>
        </tbody>
    </table>
</script>
@endsection
@push('scripts')
<script>
    $(function(){
        $(".icon-selectpicker").selectpicker({style:"btn-secondary",size:6});
    })
    function checkinput(input)
    {
        if(input.val() == "")
        {
            $(input).css('border','1px solid red');
            return false;
        }
        else
        {
            $(input).css('border','1px solid #444951');
            return true;
        }
    }
    $(document).on('click','#btnAddSubMenu',function(){
        fristInput = $(this).parents('tr').find('td.td-first > input');
        secondInput = $(this).parents('tr').find('td.td-second > input');
        button = $(this);
        if(checkinput(fristInput) && checkinput(secondInput))
        {
            $.ajax({
                url:'{{route("menus.checkroute")}}',
                method:'post',
                data:{
                    routename: secondInput.val()
                },
                dataType:'json',
                success:function(res){
                    if(res.status)
                    {
                        $(secondInput).siblings('span').addClass('d-none');
                        fristInput.prop('readonly',true);
                        secondInput.prop('readonly',true);
                        let dataRow = $('#data-row').html();
                        $('#submenu-list').append(dataRow);
                        $(button).removeClass("btn-success").addClass("btn-danger").html("<i class='fa fa-trash'></i>").attr('id','btnDeleteSubMenu');
                    }
                    else
                    {
                        $(secondInput).siblings('span').removeClass('d-none');
                    }
                },
                error:function(jhxr,status,err){
//console.log(jhxr);
}
}) 
        }
    })
    $(document).on('click','#btnDeleteSubMenu',function(){
        $(this).parents('tr').remove();
    })
    $(document).on('submit','#AddMenusForm',function(e){
    })
    $(document).on('change','#hassubmenu',function(){
        datavalue = $(this).attr('data-value');
        if(datavalue == "true")
        {
            $('#singleRoute').css('display','none').html("");
            $('#subRoute').css('display','block').html($('#subMenubody').html());
            $(this).attr('data-value',false);
        }
        else{
            $('#singleRoute').css('display','block').html($('#singleMenubody').html());;
            $('#subRoute').css('display','none').html("");
            $(this).attr('data-value',true);
        }
    })
</script>
@endpush
<!-- Code Finalize -->