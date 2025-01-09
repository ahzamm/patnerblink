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
@extends('admin.layouts.app')
@section('title')
    Dashboard
@endsection
@section('owncss')
    <style type="text/css">
        #loadingnew {
            display: none;
        }

        table.tax_table {
            margin-top: 0 !important;
        }

        .tax_table td {
            border: 1px solid #000;
        }

        .tax_table>thead>tr>th {
            position: sticky;
            top: 0;
        }
    </style>
@endsection
@section('content')
    <div class="page-container row-fluid container-fluid">
        <section id="main-content">
            <section class="wrapper main-wrapper">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <section class="box">
                    <div class="header_view" style="padding-top: 20px;">
                        <h2 style="font-size: 26px;">Users Menu Management
                            <span class="info-mark" onmouseenter="popup_function(this, 'menu_userside_management_admin_side');" onmouseleave="popover_dismiss();"><i
                                   class="las la-info-circle"></i></span>
                        </h2>
                    </div>
                    <hr>
                    <div class="content-body">
                        <div style="display:flex;align-items:flex-start;justify-content:center; flex-wrap: wrap">
                            <div class="table-responsive" style="flex:1 1 auto">
                                <div class="header_view">
                                    <h2 style="font-size: 20px;">Main Menu</h2>
                                </div>
                                <a href="{{ '#my-menu' }}" data-toggle="modal" class="btn  btn-primary" style="margin-bottom:10px;margin-left:15px"><i class="fa fa-plus">
                                    </i> Add Main Menu</a>
                                <button type="button" class="btn btn-info btn-icon float-end btn-sm" onclick="$('#sortModal').modal('show');">
                                    <span class="btn-icon-label"><i class="fa-solid fa-up-down-left-right"></i></span> Sort
                                </button>
                                <table class="table table-bordered dt-responsive display w-100" id="menu-management">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Menu</th>
                                            <th>Has Sub-Menu</th>
                                            <th>Icon</th>
                                            <th>Order</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated by DataTables -->
                                    </tbody>
                                </table>

                            </div>
                            <div class="table-responsive" style="flex:1 1 auto">
                                <div class="header_view">
                                    <h2 style="font-size: 20px;">Sub Menu</h2>
                                </div>
                                <a href="{{ '#my-sub-menu' }}" data-toggle="modal" class="btn btn-primary" style="margin-bottom:10px;margin-left:15px"><i
                                       class="fa fa-plus"> </i> Add Sub-Menu</a>
                                <table class="table table-bordered dt-responsive display w-100" id="sub-menu-management">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sub-Menu</th>
                                            <th>Main Menu</th>
                                            <th>Route</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; ?>
                                        @foreach ($sub_menu_management as $s_menu)
                                            <tr>
                                                <td>{{ $count }}</td>
                                                <td id="submenu">{{ $s_menu->submenu }}</td>
                                                <td id="get-sub-menu">{{ $s_menu->menu->menu }}</td>
                                                <td id="route">{{ $s_menu->route_name }}</td>
                                                <td>
                                                    <button class="btn btn-xs btn-info update-s-btn" data-sub-id="{{ $s_menu->id }}"><i class="fa fa-edit"></i>
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php $count++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </section>
        </section>
        <!-- Sort Modal -->
        <div id="sortModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="sortModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sortModalLabel"><i class="fa-solid fa-up-down-left-right text-white"></i> Sort Main Menu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="sortMainMenuForm">
                            <ul id="sortableMenu" class="list-group">
                                @foreach ($menu_management as $menu)
                                    <li class="list-group-item" data-id="{{ $menu->id }}">
                                        <i class="fa fa-bars"></i> {{ $menu->menu }}
                                    </li>
                                @endforeach
                            </ul>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@include('admin.menu-management.model-menu')
@include('admin.menu-management.model-edit-menu')
@include('admin.menu-management.model-edit-sub-menu')
@include('admin.menu-management.model-sub-menu')
@section('ownjs')
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#sub-menu-management').DataTable({
                responsive: true
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.update-btn', function(e) {
                var get_id = $(this).attr('data-id');
                // alert(get_id);
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.Management.menu.edit') }}",
                    type: 'POST',
                    data: {
                        'get_id': get_id
                    },
                    success: function(data) {
                        var id = data['id'];
                        $(".modal-body #menu-id-data").val(id);
                        var main_menu = data['menu'];
                        $(".modal-body #u-m-menu").val(main_menu);
                        var has_submenu = data['has_submenu'];
                        var c = $(".modal-body #u-has_submenu").val(has_submenu);
                        if (has_submenu == 1) {
                            $('.submenu').prop("checked", true);
                        } else {
                            $('.submenu').prop("checked", false);
                        }
                        var icon = data['icon'];
                        $(".modal-body #u-icon").val(icon);
                        var priority = data['priority'];
                        $(".modal-body #u-priority").val(priority);
                        $('#my-edit-menu').modal('show');
                    }
                });
            });
        });
    </script>
    <!-- Sub Menu -->
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on("click", ".update-s-btn", function(e) {
                var get_id = $(this).attr('data-sub-id');
                $('#my-edit-sub-menu').modal('show');
                // alert(get_id);
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.Management.submenu.edit') }}",
                    type: 'POST',
                    data: {
                        'get_id': get_id
                    },
                    success: function(data) {
                        var id = data['id'];
                        $(".modal-body #sub-menu-id").val(id);
                        var submenu_data = data['submenu'];
                        $(".modal-body #u-sub-menu").val(submenu_data);
                        var menu_id = data['menu_id'];
                        $(".modal-body #u-menu-id").val(menu_id);
                        var u_route = data['route_name'];
                        $(".modal-body #u-route").val(u_route);
                        var priority = data['priority'];
                        $(".modal-body #u-priority").val(priority);
                        $('#my-edit-sub-menu').modal('show');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".update-menu").on("click", function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.Management.menu.update') }}",
                    type: 'POST',
                    data: $("#update-menu").serialize(),
                    _token: '{{ csrf_token() }}',
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            // alert(data.success);
                            $(".print-error-msg").css('display', 'none');
                            $(".success-msg").css('display', 'block');
                            $('.success-msg').html(data.success);
                            location.reload(3000);
                        } else {
                            printErrorMsg(data.error);
                        }
                    }
                });
            });

            function printErrorMsg(msg) {
                $(".print-error-msg").find("ul").html('');
                $(".success-msg").css('display', 'none');
                $(".print-error-msg").css('display', 'block');
                $.each(msg, function(key, value) {
                    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                });
            }
        });
    </script>
    <!-- Update Submenu -->
    <script>
        $(document).ready(function() {
            $(".update-sub-menu").on("click", function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.Management.submenu.update') }}",
                    type: 'POST',
                    data: $("#update-sub-menu").serialize(),
                    _token: '{{ csrf_token() }}',
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            // alert(data.success);
                            $(".print-error-msg").css('display', 'none');
                            $(".success-msg").css('display', 'block');
                            $('.success-msg').html(data.success);
                            location.reload(3000);
                        } else {
                            printErrorMsg(data.error);
                        }
                    }
                });
            });

            function printErrorMsg(msg) {
                $(".print-error-msg").find("ul").html('');
                $(".success-msg").css('display', 'none');
                $(".print-error-msg").css('display', 'block');
                $.each(msg, function(key, value) {
                    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                });
            }
        });
    </script>

<script>
    $(document).ready(function () {
         // Initialize sortable
        $("#sortableMenu").sortable();

        // Refresh DataTable on modal close
        $("#sortModal").on("hidden.bs.modal", function () {
            $('#menu-management').DataTable().ajax.reload(null, false); // Reload DataTable without resetting pagination
        });

        $("#sortableMenu").sortable({
            stop: function () {
                const menuOrder = [];
                $("#sortableMenu > li").each(function () {
                    menuOrder.push($(this).data("id"));
                });

                if (menuOrder.length === 0) {
                    toastr.error("No items to sort. Please reorder and try again.");
                    return;
                }

                // Send AJAX request immediately on sort
                $.ajax({
                    url: "{{ route('admin.menu.updateOrder') }}",
                    method: "POST",
                    data: {
                        menu_id: menuOrder,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function () {
                        console.log("Sending sort request to server...");
                    },
                    success: function (response) {
                        console.log("Server Response:", response);
                        if (response.success) {
                            toastr.success(response.message || "Menu order updated successfully.");
                        } else {
                            toastr.error(response.message || "Failed to update menu order");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        console.error("Response Text:", xhr.responseText);
                        toastr.error("An error occurred while updating menu order.");
                    }
                });
            }
        });
});
</script>
<script>
    $(document).ready(function() {
        $('#menu-management').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.Management.menu') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'menu', name: 'menu' },
                { data: 'has_submenu', name: 'has_submenu' },
                { data: 'icon', name: 'icon' },
                { data: 'priority', name: 'priority' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            responsive: true
        });
    });
</script>

@endsection
<!-- Code Finalize -->
