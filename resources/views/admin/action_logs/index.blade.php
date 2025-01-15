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
</div>
@endsection

@section('ownjs')
<script type="text/javascript">
    $(document).ready(function () {
        $('#action-logs-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.action-logs.data') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'model', name: 'model' },
                { data: 'beforeupdate', name: 'beforeupdate', orderable: false, searchable: false },
                { data: 'afterupdate', name: 'afterupdate', orderable: false, searchable: false },
                { data: 'operation', name: 'operation' },
                { data: 'performed_by', name: 'performed_by' },
            ],
        });
    });
</script>
@endsection
