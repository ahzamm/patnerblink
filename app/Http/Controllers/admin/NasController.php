<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Profile;
use App\model\Users\Nas;
use Mail;




class NasController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {

 }

 public function index(){


   $naslist = Nas::orderBy('id')->get();  

   return view('admin.users.view_nas',['naslist' => $naslist]);



}

public function edit($id){
    $nas = Nas::find($id);
    return view('admin.users.nas_update',['nas' => $nas]);
}

public function update(Request $request,$id){
    $nas = Nas::find($id);
    $nasname = $request->get('nasname');

    $nas->nasname = $nasname;
    $nas->server = $request->get('server');
    $nas->shortname = $request->get('shortname');
    $nas->type = $request->get('type');
    $nas->ports = $request->get('ports');
    $nas->secret = $request->get('secret');
    $nas->community = $request->get('community');
    $nas->abbr = mb_substr($request->get('type'),0,3,'UTF-8');
    $nas->description = $request->get('description');
    $nas->carrier = $request->get('carrier');
    $nas->radius_src_ip = $request->get('radius_src_ip');
    $nas->api_port  = $request->get('api_port');

    $nas->save();
    return redirect()->route('admin.router.index');
}

public function store(Request $request)
{
    $nas = new Nas();
    $nasname = $request->get('nasname');

    $nas->nasname = $nasname;
    $nas->server = $request->get('server');
    $nas->shortname = $request->get('shortname');
    $nas->type = $request->get('type');
    $nas->ports = $request->get('ports');
    $nas->secret = $request->get('secret');
    $nas->community = $request->get('community');
    $nas->abbr = mb_substr($request->get('type'),0,3,'UTF-8');
    $nas->description = $request->get('description');
    $nas->carrier = $request->get('carrier');
    $nas->radius_src_ip = $request->get('radius_src_ip');
    $nas->api_port  = $request->get('api_port');
    $nas->addedbyip = $request->ip();
    $nas->save();

    return redirect()->route('admin.router.index');
}

public function deletethis(Request $request)
{
   $id =$request->get('id');
   Nas::find($id);


   Nas::where(['id' => $id])->delete();
}


//////////////////// CRON JOB //////////////////////////////////////////////

public function nas_active_users_mail(){
    $email = '';
        //       
    Mail::send('admin/Nas_cron/nas_active_users_contractor_wise',['Email' => $email], function($message) use($email){
        $message->to('syedmohsin@lbi.net.pk');
        $message->cc(['jawadalam@lbi.net.pk','irfan.arif@squadcloud.co','fawadalam@lbi.net.pk']);
        $message->from('automated-noreply@logon.com.pk', 'LOGON');
        $message->subject('NAS Wise Active Users: '.date('D, d M Y - h:i a'));
    });        
        //        
    return response()->json(['success'=> 'Email Sent!']);
}

}



