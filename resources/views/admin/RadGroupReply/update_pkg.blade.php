<div aria-hidden="true"   role="dialog" tabindex="-1" id="update-pkg-model" class="modal fade" style=" display: none; z-index: 1111;margin-top:35px;" id="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
            <div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
                <ul></ul>
            </div>
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title" style="text-align:center; color: #fff">UPDATE (RAD-GROUP-REPLY ATTRIBUTES)</h4>
            </div>
            <div class="modal-body">
                <form id="update-pkg-data" >
                    <input type="hidden" id="update-id" name="id">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered" style="min-width;700px"> 
                            <thead>
                                <tr>
                                    <th>Group Name <span style="color: red">*</span></th>
                                    <th>Attribute <span style="color: red">*</span></th>
                                    <th>OP <span style="color: red">*</span></th>
                                    <th>Value <span style="color: red">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>  
                                    <td><input type="text" name="groupname" placeholder="Type Your Group Name" class="form-control" id="pkg-groupname"/></td>  
                                    <td><input type="text" name="attribute" placeholder="Type Your Attribute here" class="form-control" id="pkg-attribute"/></td>  
                                    <td><input type="text" name="op" placeholder="Type Your Op here" class="form-control" id="pkg-op"/></td> 
                                    <td><input type="text" name="value" placeholder="Type Value here" class="form-control" id="pkg-value"/></td>
                                </tr>  
                            </tbody>
                        </table> 
                    </div>
                    <div class="row">
                        <div class="col-xs-12" style="margin-top: 20px;">
                            <div class="form-group pull-right">
                                <button type="submit" class="btn btn-primary btn-submit pkg-rad-update">Update</button>
                                <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>