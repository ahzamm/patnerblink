@extends('admin.layouts.app')

@section('title') Action Logs @endsection

@section('content')
<div class="page-container row-fluid container-fluid">
    <section id="main-content">
        <section class="wrapper main-wrapper">
            <div class="header_view">
                <h2>Action Logs
                    <span class="info-mark" onmouseenter="popup_function(this, 'action_logs_admin_side');" onmouseleave="popover_dismiss();">
                        <i class="las la-info-circle"></i>
                    </span>
                </h2>
            </div>

            <!-- Filters Section -->

            <section class="box" style="padding: 20px; overflow: hidden;">
                <div class="filter-container" style="margin-bottom: 20px;">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="performedBy">Performed By</label>
                                <input type="text" id="performedBy" class="form-control" placeholder="Enter user name">
                            </div>
                            <div class="col-md-3">
                                <label for="dateRange">Date Range</label>
                                <input type="text" id="dateRange" class="form-control" placeholder="Select date range">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group position-relative">
                                    <label for="operation-type-dropdown">Select Operation Type</label>
                                    <select id="operation-type-dropdown" class="form-select js-select2">
                                        <option value="">-- Select Operation Type --</option>
                                        <option value="create">CREATE</option>
                                        <option value="update">UPDATE</option>
                                        <option value="delete">DELETE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" id="applyFilters" class="btn btn-primary">Apply Filters</button>
                                <button type="button" id="resetFilters" class="btn btn-secondary ms-2">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <section class="box" style="padding: 20px; overflow: hidden;">
                <div id="tablediv">
                    <table id="action-logs-table" class="table table-striped dt-responsive display w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Model</th>
                                <th>Before Update</th>
                                <th>After Update</th>
                                <th>Operation</th>
                                <th>Performed By</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </section>
        </section>
    </section>
</div>
@endsection

@section('ownjs')
<script>
    $(document).ready(function () {
        // Initialize Date Range Picker
        $('#dateRange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD',
            }
        });

        $('#dateRange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#dateRange').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        // Initialize DataTable
        const table = $('#action-logs-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.action-logs.data') }}",
                type: "GET",
                data: function (d) {
                    d.performed_by = $('#performedBy').val();
                    d.date_range = $('#dateRange').val();
                    d.operation = $('#operation-type-dropdown').val(); // Fetch operation filter value
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'model', name: 'model' },
                { data: 'beforeupdate', name: 'beforeupdate', orderable: false, searchable: true },
                { data: 'afterupdate', name: 'afterupdate', orderable: false, searchable: true },
                { data: 'operation', name: 'operation' },
                { data: 'performed_by', name: 'performed_by' },
            ]
        });

        // Apply Filters
        $('#applyFilters').on('click', function () {
            table.ajax.reload();
        });

        // Reset Filters
        $('#resetFilters').on('click', function () {
            $('#performedBy').val('');
            $('#operation-type-dropdown').val('').trigger('change'); // Reset Select2 dropdown
            $('#dateRange').val('');
            table.ajax.reload();
        });
    });
</script>
@endsection
