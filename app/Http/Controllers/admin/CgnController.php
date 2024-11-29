<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use App\model\Users\Profile;
// use App\model\Users\UserInfo;
use Illuminate\Support\Facades\DB;
use App\model\admin\Admin;
use App\model\Users\Nas;
use App\model\Users\StaticIPGid;
use App\model\Users\StaticIPServer;
use App\model\Users\UserUsualIP;
use Illuminate\Support\Facades\Validator;
use DataTables;


class CgnController extends Controller
{
	public function index(Request $request){
		$nas = Nas::all();
		return view('admin.cgn.cgnView',["nas" => $nas]);
	}
	//
	 public function data(Request $request)
 {
	
 	
 	 	 if ($request->ajax()) {
            $data = UserUsualIP::all();
 			if($request->input('ip'))
            {
            	$data = $data->where('ip',$request->input('ip'));
            }
            if($request->input('status') == '0')
            {
            	$status = $request->input('status').'0';
            	$data = $data->where('status',$status);
            }else{
            	$data = $data->where('status',$request->input('status'));
            }
            if($request->input('nas'))
            {
            	$data = $data->where('nas',$request->input('nas'));
            }
            if($request->input('main_group'))
            {
            	$data = $data->where('main_group',$request->input('main_group'));
            }
              if($request->input('sub_group'))
            {
            	$data = $data->where('sub_group',$request->input('sub_group'));
            }
              if($request->input('city'))
            {
            	$data = $data->where('city',$request->input('city'));
            }
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }
        return view('admin.cgn.cgnView');

 }
	//
	public function addCGN(Request $request)
	{
     // return response()->json($request->cgnIp);
		$ip = $request->ip;
		$main = $request->main;
		$sub = $request->sub;
		$s = $request->start;
		$e = $request->end;
		$shortname = $request->nasShortName;
	 //
		for($i=$s;$i<=$e;$i++){
			$ip1 = $ip.'.'.$i;

			for($a=0;$a<=255;$a++){
				$fip = $ip1.'.'.$a;
		// echo '<br>';
				DB::table('user_usual_ips')->insert(['ip' => $fip, 'main_group' => $main, 'sub_group' => $sub, 'status' => 0, 'nas' => $shortname]);
		 //

			}

		}
		echo '<p style="font-size:16;color:green">IPs Added Successfully</p>';
	}


	public function add_static_ip_data()
	{
		$get_nas_data = Nas::all(['shortname'])->toArray();
		$get_nas_data = array_column($get_nas_data, 'shortname');
		$get_gid_data = StaticIPGid::all(['gid'])->toArray();
		$get_gid_data = array_column($get_gid_data, 'gid');

		return view('admin.cgn.add_static_ip',compact('get_nas_data','get_gid_data'));

	}
	public function store_static_ip_data(Request $request)
	{
		$errors = null;	
		$validator = Validator::make($request->all(),[
			'addmore.*.gid' => 'required',
			'addmore.*.serverip' => 'required|ip',
			'addmore.*.ipaddress' => 'required|ip',
			'addmore.*.bras' => 'required',
			'addmore.*.type' => 'required',
		],
		[
			'addmore.*.gid.required' => 'G-Id Field is required!..',
			'addmore.*.serverip.required' => 'Server Ip Field is required!..',
			'addmore.*.serverip.ip' => 'Please type must be a valid Server IP address!..',
			'addmore.*.ipaddress.ip' => 'Please type must be a valid IP address!..',
			'addmore.*.ipaddress.required' => 'Ip Address Field is required!..',
			'addmore.*.type.required' => 'Type Field is required!..',
			'addmore.*.bras.required' => 'Bras Field is required!..',
		]);

		if(!$validator->passes()){

			$errors = $validator->errors()->all();
		}

		$ipaddress = array();
		foreach ($request->addmore as $key => $value) {
			$check_ip = StaticIPServer::where('ipaddress',$value['ipaddress'])->count();
			array_push($ipaddress, $value['ipaddress']);

			if($check_ip > 0 ){
				$myerror = array($value['ipaddress'].' This Ip Address already exist');
				$errors = $myerror;
			}

		}

		if(count($ipaddress) != count(array_unique($ipaddress))){
			$errors = array('Duplicate Ip Address in rows');
		}
		
		\DB::beginTransaction();
		try {
			if (empty($errors)) {
				foreach ($request->addmore as $key => $value) {
					$get_all_ips = $request->addmore[$key]['ipaddress'];

					$create_static = [
						'gid' => $value['gid'],
						'serverip' =>$value['serverip'],
						'ipaddress'=> $value['ipaddress'],
						'type' => $value['type'],
						'status'=>'NEW',
						'bras'=> $value['bras'],
						'added_by'=>auth()->user()->username,
						'added_by_ip'=>$this->get_client_ip(),
						'date_added'=>date("Y:m:d h:i:s")
					];

					$create_data = StaticIPServer::create($create_static);
				}
				\DB::commit();
				return response()->json(['success'=>'Added new records.']);
			}

			else{
				return response()->json(['error'=>$errors]);
			}

		}
		catch (\Exception $e) {
			\DB::rollback();
			$res = ['error' => $e->getMessage()];
		}
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
	}

}
