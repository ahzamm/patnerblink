@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <!-- <a href="{{('#servicesModal')}}" data-toggle="modal" class="pr-3 mt-3">
        <button class="btn btn-default" style="border: 1px solid black">
        <i class="fas fa-globe-asia"></i> Add Servers</button>
      </a> -->
      <div class="header_view">
        <h2>Emails
        <span class="info-mark" onmouseenter="popup_function(this, 'city_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#email">Emails</a></li>
        <li><a data-toggle="tab" href="#setting">Settings</a></li>
        <li><a data-toggle="tab" href="#banlist">Banlist</a></li>
        <li><a data-toggle="tab" href="#template">Templates</a></li>
        <li><a data-toggle="tab" href="#diagnostic">Diagnostic</a></li>
        <li><a data-toggle="tab" href="#topic">Help Topic</a></li>
      </ul>
      <section class="box" style="margin-top: 0">
        <div class="content-body" style="padding-top: 20px">
          <div class="tab-content">
						<div id="email" class="tab-pane fade in active">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <h3>Email Addresses</h3>
                <a href="{{('#emailModal')}}" data-toggle="modal" class="pr-3 mt-3">
                  <button class="btn btn-default" style="border: 1px solid black">
                  <i class="fas fa-envelope"></i> Add New Email</button>
                </a>
              </div>
              <table id="example-1" class="table dt-responsive w-100 display">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Email</th>
                    <th>Priority</th>
                    <th>Department</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="width: 100px">1</td>
                    <td>csd helpdesk@logon.com.pk (Default)</td>
                    <td>Normal</td>
                    <td>Helpdesk</td>
                    <td>3 July, 2023</td>
                    <td>28 August, 2023</td>
                    <td><a href="{{('#emailSettingModal')}}" data-toggle="modal" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> Email setting</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div id="setting" class="tab-pane">
              <div class="row">
                <div class="col-lg-3 col-md-4">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Default Template Set <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select class="form-control">
                        <option>Select Default Template</option>                        
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-4">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Default System Email <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select class="form-control">
                        <option>Select Default Email</option>                        
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-4">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Default Alert Email <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select class="form-control">
                        <option>Select Default Alert</option>                        
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-4">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Admin's Email Address <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" class="form-control" placeholder="admin@gmail.com" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="position-relative text-left">
                    <label for="" class="form-label pull-left">Verify Email Addresses <span style="color: red">*</span></label>
                    <div style="display:inline-block;margin-left:20px;">
                      <input type="checkbox" name="status" id="emailVerify">
                      <label for="emailVerify"> Verify email address domain</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <h4 style="text-align: left;font-weight: bold;background: #ddd;padding: 5px;">Incoming Emails</h3>
                </div>
                <div class="col-md-6">
                  <div class="position-relative text-left">
                    <label for="" class="form-label pull-left">Email Fetching <span style="color: red">*</span></label>
                    <div style="display:inline-block;margin-left:20px;">
                      <input type="checkbox" name="status" id="statusEnable">
                      <label for="statusEnable"> Enable</label>
                    </div>
                    <div style="display:inline-block">
                      <input type="checkbox" name="status" id="statusDisable">
                      <label for="statusDisable"> Fetch on auto-cron</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="position-relative text-left">
                    <label for="" class="form-label pull-left">Strip Quoted Reply <span style="color: red">*</span></label>
                    <div style="display:inline-block;margin-left:20px;">
                      <input type="checkbox" name="status" id="chkReply">
                      <label for="chkReply"> Enable</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="position-relative text-left">
                    <label for="" class="form-label pull-left">Emailed Tickets Priority<span style="color: red">*</span></label>
                    <div style="display:inline-block;margin-left:20px;">
                      <input type="checkbox" name="status" id="chkTicket">
                      <label for="chkTicket"> Enable</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="position-relative text-left">
                    <label for="" class="form-label pull-left">Accept All Emails<span style="color: red">*</span></label>
                    <div style="display:inline-block;margin-left:20px;">
                      <input type="checkbox" name="status" id="chkTicket">
                      <label for="chkTicket"> Accept email from unknown users</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="position-relative text-left">
                    <label for="" class="form-label pull-left">Accept Email Collaborators<span style="color: red">*</span></label>
                    <div style="display:inline-block;margin-left:20px;">
                      <input type="checkbox" name="status" id="chkTicket">
                      <label for="chkTicket"> Automatically add collaborators from emai fields</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="position-relative text-left">
                    <label for="" class="form-label pull-left">Reply Separator Tag<span style="color: red">*</span></label>
                    <input type="text" name="status" class="form-control" id="">
                  </div>
                </div>
                <div class="col-md-12">
                  <h4 style="text-align: left;font-weight: bold;background: #ddd;padding: 5px;">Outgoing Emails</h4>
                </div>
                <div class="col-lg-4 col-md-6">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Default MTA <span style="color: red">*</span></label>
                    <select class="form-control">
                        <option>None: Use PHP mail function</option>
                        <option>csd</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="position-relative text-left">
                    <label for="" class="form-label pull-left">Attachments<span style="color: red">*</span></label>
                    <div style="display:inline-block;margin-left:20px;">
                      <input type="checkbox" name="status" id="chkAttch">
                      <label for="chkAttch"> Email attachments to the user</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="banlist" class="tab-pane">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <h3>Banned Email Addresses</h3>
                <a href="{{('#banEmailModal')}}" data-toggle="modal" class="pr-3 mt-3">
                  <button class="btn btn-default" style="border: 1px solid black">
                  <i class="fas fa-ban"></i> Ban New Email</button>
                </a>
              </div>
              <table id="example-2" class="table dt-responsive w-100 display">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Email Address</th>
                    <th>Ban Status</th>
                    <th>Ban Date</th>
                    <th>Last Updatedt</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="width: 100px">1</td>
                    <td>helpdesk@logon.com.pk</td>
                    <td>Active</td>
                    <td>3 July, 2023</td>
                    <td>28 August, 2023</td>
                    <td><a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div id="template" class="tab-pane">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <h3>Email Template</h3>
                <a href="{{('#templateModal')}}" data-toggle="modal" class="pr-3 mt-3">
                  <button class="btn btn-default" style="border: 1px solid black">
                  <i class="fas fa-plus"></i> Add New Template</button>
                </a>
              </div>
              <table id="example-2" class="table dt-responsive w-100 display">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="width: 100px">1</td>
                    <td>helpdesk@logon.com.pk</td>
                    <td>Active</td>
                    <td>3 July, 2023</td>
                    <td>28 August, 2023</td>
                    <td>
                      <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> View</a>
                      <a href="#" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit</a>
                      <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div id="diagnostic" class="tab-pane">
              <h3>Test Outgoing Email</h3>
              <div class="row">
                <div class="col-md-12">
                  <p style="text-align: left;font-weight: bold;background: #ddd;padding: 5px;">Use the following form to test whether your Outgoing Email settings are properly established.</p>
                </div>
                <div class="col-md-4">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">From <span style="color: red">*</span></label>
                    <select class="form-control">
                      <option>Select FROM Email</option>
                      <option>logon@lbi.net.pk</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">To <span style="color: red">*</span></label>
                    <input type="text" class="form-control" >
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Subject <span style="color: red">*</span></label>
                    <input type="text" class="form-control" >
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Message <span style="color: red">*</span></label>
                    <textarea class="form-control" rows="5"></textarea>
                  </div>
                </div>
                <div class="col-md-12">
                  <button class="btn btn-primary pull-right">Send</button>
                </div>
              </div>
            </div>
            <div id="topic" class="tab-pane">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <h3>Help Topic</h3>
                <a href="{{('#helpTopicModal')}}" data-toggle="modal" class="pr-3 mt-3">
                  <button class="btn btn-default" style="border: 1px solid black">
                  <i class="fas fa-plus"></i> Add New Topic</button>
                </a>
              </div>
              <table id="example-3" class="table dt-responsive w-100 display">
                <thead>
                  <tr>
                    <th>Serial#</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Creation Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="width: 100px">1</td>
                    <td>This is Help Topic one</td>
                    <td>Active</td>
                    <td>3 July, 2023</td>
                    <td><a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </section>
  </section>
</div>

<!-- Mdelete modal start -->
<div class="modal fade" id="deleteModel" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Do you realy want to delete this?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" id="deletbtn" class="btn btn-danger">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!-- delete modal ends -->


@include('admin.email.add-email-modal')
@include('admin.email.add-template-modal')
@include('admin.email.add-helptopic-modal')
@include('admin.email.email-setting-modal')
@include('admin.email.ban-email-modal')

@endsection

@section('ownjs')
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>

@endsection
