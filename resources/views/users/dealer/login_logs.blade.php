<div aria-hidden="true" role="dialog" tabindex="-1" id="loginLogs" class="modal fade" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
                <h4 class="modal-title" style="text-align: center; color: white">
                    Login Logs
                </h4>
            </div>
            <div class="modal-body" style="padding-top: 15px; padding-bottom: 0px;">
                <div class="row">
                    <div class="col-md-12">
                        <table id="loginLogsTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Login Time</th>
                                    <th>Status</th>
                                    <th>IP Address</th>
                                    <th>Platform</th>
                                    <th>Operating System</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTable will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let isTableInitialized = false; // Flag to check if the table is already initialized

        $('#openLoginLogs').on('click', function() {
            if (!isTableInitialized) { // Initialize DataTable only if it hasn't been initialized
                $('#loginLogsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('login.logs.data') }}",
                        type: 'GET'
                    },
                    pageLength: 10, // Load 10 records at a time
                    columns: [
                        { data: 'username', name: 'username' },
                        { data: 'login_time', name: 'login_time' },
                        { data: 'status', name: 'status' },
                        { data: 'ip', name: 'ip' },
                        { data: 'platform', name: 'platform' },
                        { data: 'os', name: 'os' },
                    ],
                    lengthChange: false, // Disable length dropdown
                    paging: true, // Enable pagination
                });
                isTableInitialized = true; // Set flag to true after initialization
            }
        });

        // Optional: Destroy table if modal is hidden (to reload data each time modal is opened)
        $('#loginLogs').on('hidden.bs.modal', function() {
            if (isTableInitialized) {
                $('#loginLogsTable').DataTable().destroy();
                isTableInitialized = false;
            }
        });
    });
</script>
