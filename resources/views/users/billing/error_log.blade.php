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
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="page-container row-fluid container-fluid">
        <!-- CONTENT START -->
        <section id="main-content">
            <section class="wrapper main-wrapper row">
                <div class="header_view">
                    <h2>Invalid Login
                        <span class="info-mark" onmouseenter="popup_function(this, 'invalid_consumers');" onmouseleave="popover_dismiss();"><i
                               class="las la-info-circle"></i></span>
                    </h2>
                </div>
                @include('users.layouts.session')
                <table id="example-2" class="table table-striped dt-responsive display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Serial#</th>
                            <th>Consumer (ID)</th>
                            <th>Mobile Number</th>
                            <th>Assigned (MAC Address) </th>
                            <th>Requesting (MAC Address)</th>
                            <th>Valid Reason</th>
                            <th>Attempted Password</th>
                            <th>Login Attempt</th>
                            <th>Login Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div aria-hidden="true" role="dialog" tabindex="-1" id="changeuserPass" class="modal fade" style="display: none;">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
                                    <h4 class="modal-title" style="text-align: center;color: white">Change User Password</h4>
                                </div>
                                <div class="modal-body" style="padding-top:15px;padding-bottom:0px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="text-center" style="font-weight: bold" id="user-name-data">
                                                </h2>
                                                <hr style="margin-top: 20px;background-color: #0d4dab87">
                                                <form action="{{ route('users.billing.change.user.pass') }}" method="POST" class="form-group">
                                                    @csrf
                                                    <input type="hidden" name="user" value="" id="user-id-data">
                                                    <div class="form-group" style="position:relative">
                                                        <label for="pass">New Password <span style="color:red">*</span></label>
                                                        <input type="password" name="pass" id="password" class="form-control"
                                                               placeholder="Password must be 8 characters long"> <i class="fa fa-eye-slash password"
                                                           style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('password');"> </i>
                                                    </div>
                                                    <div class="form-group" style="position:relative">
                                                        <label for="pass">Retype Password <span style="color:red">*</span></label>
                                                        <input type="password" name="repass" id="confirm_password" class="form-control"
                                                               placeholder="Password must be 8 characters long"> <i class="fa fa-eye-slash confirm_password"
                                                           style="position: absolute;top: 35px;right: 12px;" onclick="togglePassword('confirm_password');"> </i>
                                                        <span id='message'></span>
                                                    </div>
                                                    <div class="form-group pull-right">
                                                        <button type="submit" id="btnPass" class="btn btn-primary">Update</button>
                                                        <button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    </section>
    </section>
    <!-- CONTENT END -->
    </div>
@endsection
@section('ownjs')
    <script type="text/javascript">
        function change_pass(id, username) {
            $('#changeuserPass').modal('show');
            var id_user = $('#user-id-data').val(username);
            var username = $('#user-name-data').text(username);
        }

        $(document).ready(function() {
            $('#example-2').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.get_error_log') }}',
                columns: [
                    { data: 'serial', name: 'serial' },
                    { data: 'username', name: 'username' },
                    { data: 'mobile_number', name: 'mobile_number' },
                    { data: 'assigned_mac', name: 'assigned_mac' },
                    { data: 'requesting_mac', name: 'requesting_mac' },
                    { data: 'valid_reason', name: 'valid_reason' },
                    { data: 'attempted_password', name: 'attempted_password' },
                    { data: 'login_attempt', name: 'login_attempt' },
                    { data: 'login_time', name: 'login_time' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[0, 'asc']],
                responsive: true
            });
        });
    </script>
@endsection
<!-- Code Finalize -->
