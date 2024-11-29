<style>
 .modal {
  text-align: center;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}

/* footer footnotes */
footer ol {
  border-top: 1px solid #eee;
  margin-top: 40px;
  padding-top: 15px;
  padding-left: 20px;
}

/* Bootstrap Docs */
.bs-example {
    position: relative;
    padding: 45px 15px 15px;
    margin: 0 -15px 15px;
    border-color: #e5e5e5 #eee #eee;
    border-style: solid;
    border-width: 1px 0;
    -webkit-box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
    box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
}
.bs-example-padded-bottom {
    padding-bottom: 24px;
}
@media (min-width: 768px){
  .bs-example {
      margin-right: 0;
      margin-left: 0;
      background-color: #fff;
      border-color: #ddd;
      border-width: 1px;
      border-radius: 4px 4px 0 0;
      -webkit-box-shadow: none;
      box-shadow: none;
  }
}
.bs-example+.code {
    margin: -15px -15px 15px;
    border-width: 0 0 1px;
    border-radius: 0;
}
@media (min-width: 768px){
  .bs-example+.code {
      margin-top: -16px;
      margin-right: 0;
      margin-left: 0;
      border-width: 1px;
      border-bottom-right-radius: 4px;
      border-bottom-left-radius: 4px;
  }
}
/* CodeMirror Bootstrap Theme */
.cm-s-bootstrap .cm-comment {
	font-style: italic;
	color: #999988;
}
.cm-s-bootstrap .cm-number {
	color: #F60;
}
.cm-s-bootstrap .cm-atom {
	color: #366;
}
.cm-s-bootstrap .cm-variable-2 {
	color: #99F;
}
.cm-s-bootstrap .cm-property {
	color: #99F;
}
.cm-s-bootstrap .cm-string {
	color: #DD1144;
}
.cm-s-bootstrap .cm-keyword {
	color: #069;
}
.cm-s-bootstrap .cm-operator {
	color: #555;
}
.cm-s-bootstrap .cm-qualifier {
	color: #0A8;
}
.border-tops{
  background-image: url(http://cpl.logon.com.pk/img/nav_pic.png)!important;
    border-radius: 19px 20px 0px 0px;
    color: white;
}
</style>
<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="border-radius: 20px;">
          <div class="modal-header border-tops">
            <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel" style="color: white">ONLINE USER DETAIL</h4>
          </div>
          <div class="modal-body text-center">
            <img src="{{ asset('images/l1.gif') }}" id="load" width="60%" height="25%" alt="">
            <div class="row" id="tblData">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <table class="table table-bordered">
                  <tr>
                    <th>Username</th>
                    <td id="username"></td>
                    <th>Full Name</th>
                    <td id="fullname"></td>
                  </tr>
                  <tr>
                    <th>Dealer ID</th>
                    <td id="dealerid"></td>
                    <th>Sub Dealer ID</th>
                    <td id="subdealerid"></td>
                  </tr>
                  <tr>
                    <th>Dynamic IP</th>
                    <td id="dhcpIP"></td>
                    <th>Address</th>
                    <td id="address"></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <img src="{{ asset('/img/newlogo.png') }}" alt="" class="pull-left" width="25%" style="margin: -10px">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </div>