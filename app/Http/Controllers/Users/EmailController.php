<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\model\Users\EmailSetting;

class EmailController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    
     public $parentModel = EmailSetting::class;
     public function __construct()
    {

    }
     public function index(){
     $data['emails']    = $this->parentModel::orderBy('id' , 'desc')->paginate(10);
     return view('users.email.index')->with('data',  $data);
    }        
    public function edit($id = null){
        $emailData = $this->parentModel::where("id" , $id)->first();
        $route     = route('admin.update.email' , $emailData->id) ;
        $htmlData=  '  <div class="" id="tblData">
        <div class="alert alert-danger print-error-msg" style="display:none">
          <ul></ul>
        </div>
        <div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
          <ul></ul>
        </div>
        <div class="">
          <form id="" action="'.$route.'" method="POST" enctype="multipart/form-data"> 
          ' . csrf_field() . '
            <div class="row register-form">
            <div class="col-md-6">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Select Reseller <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, "city_name_admin_side");" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select  class="form-control" name="resellerid">
                      <option value="'.$emailData->resellerid.'">'.$emailData->resellerid.'</option>
                      <option value="Logon Broadband">Logon Broadband</option>
                      <option value="Blink Broadband">Blink Broadband</option>
                      <option value="Go Internet">Go Internet</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Select Logo <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, "city_name_admin_side");" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="file" value="" name="logo" class="form-control" id="" placeholder="Example : Karachi" >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Email Title <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, "city_name_admin_side");" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="'.$emailData->title.'" name="title" class="form-control" id="" placeholder="Example : Karachi" >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">SMTP Port <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, "city_name_admin_side");" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="number" value="'.$emailData->port.'" name="port" class="form-control" id="" placeholder="Example : Karachi" >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Email ID <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, "city_name_admin_side");" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="'.$emailData->email.'" name="email" class="form-control" id="" placeholder="Example : Karachi" >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Password <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, "city_name_admin_side");" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="password" value="'.$emailData->password.'" name="password" class="form-control" id="" placeholder="Example : Karachi" >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">SMTP Server <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, "city_name_admin_side");" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="'.$emailData->host.'" name="host" class="form-control" id="" placeholder="Example : Karachi" >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group position-relative">
                  <label for="city_name" class="form-label pull-left">SMTP Encryption <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, "city_name_admin_side");" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select class="form-control" name="encryption">

                      <option value="'.$emailData->encryption.'">'.$emailData->encryption.'</option>
                      <option value="SSL">SSL</option>
                      <option value="TLS">TLS</option>
                     
                  </select>
                </div>
              </div>
              
              
              <div class="col-md-12">
                <div class="pull-right">
                  <input type="submit" class="btn btn-primary btn-submit"  value="Submit"/>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>';
      return $htmlData ;
    }
    public function store(Request $request){
        $data                   = $request->except('_token');
        try {
            $validatedData = $request->validate([
                'logo'  => 'required|max:5000',
                'email' => 'required|email|unique:email_settings,email',
                'title' => 'required',
                'password' => 'required',
                'host' => 'required',
                'encryption'=>'required',
                'port' =>'required'
            ]);
            
            $image                  = $request->file("logo");
            if($image != null){
                $fileName = time().'.'.$image->getClientOriginalExtension();
                $image->move('emailLogo/' , $fileName);
                $data['logo']          = url(asset('emailLogo/' . $fileName)) ; 
           
                $store = $this->parentModel::create($data);
                if($store){
                    return redirect()->back()->with('success' , 'Email Setting Has been saved..!');
                }
                else{
                    return redirect()->back()->with('error' , 'Failed to save Email settings..!');
    
                }
            }
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
       
    }
    public function update(Request $request  , $id = null){
        $data                   = $request->except('_token');
        try {
            $validatedData = $request->validate([
                'logo'  => 'max:5000',
                'email' => 'required|email',
                'title' => 'required',
                'password' => 'required',
                'host' => 'required',
                'encryption'=>'required',
                'port' =>'required'
            ]);
            $update = true;    
            $image                  = $request->file("logo");
            if($image != null){
                $fileName = time().'.'.$image->getClientOriginalExtension();
                $image->move('emailLogo/' , $fileName);
                $data['logo']          = url(asset('emailLogo/' . $fileName)) ; 
                $update                = $this->parentModel::where('id' , $id)->update(["logo" => $data['logo']]);
                
            }
           
                $store = $this->parentModel::where('id' , $id )->update([
                    'email' => $data['email'],
                    'title' => $data['title'] ,
                    'password' => $data['password'] ,
                    'host'=> $data['host'] ,
                    'encryption' => $data['encryption'] ,
                    'port' => $data['port'] ,
                    'resellerid' => $data['resellerid']
                ]);
                if($store || $update){
                    return redirect()->back()->with('success' , 'Email Setting Has been Updated..!');
                }
                else{
                    return redirect()->back()->with('error' , 'Failed to update email setting..!');
    
                }
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
       
    }

    public function destroy($id = null){
        $checkData = $this->parentModel::count();
        if($checkData <= 1){
            return response('exists') ;
       
        }
        $delete  = $this->parentModel::where("id" , $id)->delete();
        if($delete){
            return response('success') ;
        }
        else{
            return response('error') ;

           
        }
    }

}
