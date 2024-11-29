<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\MaintenanceMode;
use App\model\Users\MaintenanceLog;

use Auth;

use Artisan;

class MaintenanceController extends Controller
{
    public function __construct()
    {

        // $this->middleware('checkuseraccess', ['only' => ['index']]);
    }
    public function index()
    {
        $date_time = date('Y-m-d G:i');
        $mode = MaintenanceMode::first();
        $mode_log = MaintenanceLog::all(); 
        $client_ip = $this->get_client_ip();
        return view('admin.maintenance.index', compact('mode','mode_log','client_ip','date_time'));
    }
    public function store(Request $request)
    {
      
        $time = $request->get('maintenance_time');
        $add_ips = $request->allowed_ip;
        $status = $request->get('status');
        $get_own_ip=$this->get_client_ip();
// dd($get_own_ip);
        $status = $request->status;
        if (empty($status)) {
            $status = 'disable';
        }

        $add_ips = $request->allowed_ip;
        MaintenanceMode::whereNotNull('id')->where('status', 'enable')->delete();

            $mode = new  MaintenanceMode;
            $mode->allowed_ips = $request->allowed_ip.','.$get_own_ip;
            $mode->activation_time = $time;
            $mode->own_ip = $get_own_ip;
            $mode->status = $status;
            $mode->save();

        $log = new MaintenanceLog;
        $log->status = $status;
        $log->activation_time = $time;
        $log->own_ip = $get_own_ip;
        $log->allowed_ips = $request->allowed_ip.','.$get_own_ip;
        $log->save();
        //
        if ($status == 'enable') {
            Artisan::call('down');
        }
        //
        return redirect()->route('maintenance.index');
    }
    public function deactivate(Request $request)
    {

        $time = $request->get('time');
        $mode = MaintenanceMode::whereNotNull('id')->where('status', 'enable')->first();
        $date_time = date('Y-m-d G:i:s');
      
        MaintenanceMode::whereNotNull('id')->update(['status' => 'disable']);
       
        $log = new MaintenanceLog;
        $log->status = 'disable';
        // $log->activation_time = $date_time;
        $log->deactivation_time =$date_time;
        $log->own_ip = $this->get_client_ip();
        $log->allowed_ips = $mode->allowed_ips;
        $log->save();
      
                MaintenanceMode::whereNotNull('id')->delete();
                // if ($mode->status == 'disable') {
                //     Artisan::call('up');
                // }
                Artisan::call('up'); 
              
        return redirect()->route('maintenance.index');
    }

    public function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
        dd($ipaddress);
	}
}
