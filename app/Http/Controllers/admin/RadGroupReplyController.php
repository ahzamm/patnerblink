<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\model\Users\RadGroupReply;
use App\model\Users\UserInfo;
use App\model\Users\Nas;
use App\model\Users\Domain;
use Illuminate\Support\Facades\Validator;
use DataTables;

class RadGroupReplyController extends Controller
{

	// public function index()
	// {
	// 	$get_rad_reply_data = RadGroupReply::all();
	// 	return view('admin.RadGroupReply.index',compact('get_rad_reply_data'));
	// }
	public function data(Request $request)
	{ if ($request->ajax()) {
		$get_rad_reply_data = RadGroupReply::all();
		return Datatables::of($get_rad_reply_data)
		->addIndexColumn()
		->addColumn('action', function($row){
			return"<button data-id='$row->id' type='button'  title='Edit'
			class='btn btn-info update-btn btn-sm attrEdit' data-toggle='modal'><i class='fa fa-edit'></i> Edit
			</button>";
		})
		->rawColumns(['action'])
		->make(true);
	}
	return view('admin.RadGroupReply.index');

}
public function create()
{

	$resellerid = UserInfo::where('status','reseller')->get(['resellerid'])->toArray();
	$resellerid = array_column($resellerid, 'resellerid');
	$brands = Nas::groupBy(['type'])->get(['type'])->toArray();
	$brands = array_column($brands, 'type');
	return view('admin.RadGroupReply.create',compact('resellerid','brands'));
}
public function store(Request $request)
{



	$validator = Validator::make($request->all(),[

		'addmore.*.groupname' => 'required|integer',

		'addmore.*.attribute' => 'required',

		'addmore.*.op' => 'required',

		'addmore.*.value' => 'required',  

	],

	[

		'addmore.*.groupname.required' => 'Group Name Field is required!..',

		'addmore.*.groupname.integer' => 'Group Name Must be integer!..',

		'addmore.*.attribute.required' => 'Attribute Field is required!..',

		'addmore.*.op.required' => 'Op Field is required!..',

		'addmore.*.value.required' => 'Value Field is required!..',  

	]);

	\DB::beginTransaction();

	try {

		if($validator->passes())

		{
			foreach ($request->addmore as $key => $value) {
				$resellerid = Domain::where('resellerid',$value['reseller'])->first(['package_name']);
				$brands = Nas::where('type',$value['brands'])->first(['abbr']);
				$groupname = $value['groupname']/1024;
				$get_group_name = $resellerid['package_name'].'-'.$groupname.'mb'.'-'.$brands['abbr'];
				
				$create_rad_data = [
					'groupname' => $get_group_name,
					'attribute' =>$value['attribute'],
					'op'=> $value['op'],
					'value' => $value['value'],
				];
				$create_rad_data = RadGroupReply::create($create_rad_data);
			}

			\DB::commit();

			return response()->json(['success'=>'Added Package Attributes Successfully.']);

		}

		return response()->json(['error'=>$validator->errors()->all()]);

	}

	catch (\Exception $e) {

		\DB::rollback();

		$res = ['error' => $e->getMessage()];

	}


}
public function edit(Request $request)
{
	$get_profile_id = $request->input('get_id');
	$update_request = RadGroupReply::where('id',$get_profile_id)->first();
	return response()->json($update_request);
}
public function update(Request $request)
{
	$update_id = $request->get('id');
	$data = Validator::make($request->all(),[
		'groupname' => 'required',
		'attribute' => 'required',
		'op' => 'required',
		'value' => 'required',  
	],
	[
		'groupname.required' => 'Group Name Field is required!..',
		'attribute.required' => 'Attribute Field is required!..',
		'op.required' => 'Op Field is required!..',
		'value.required' => 'Value Field is required!..',  
	]);
	if($data->passes())
	{
		$update_pkg_data = [
			'groupname' => $request->get('groupname'),
			'attribute' =>  $request->get('attribute'),
			'op' =>  $request->get('op'),
			'value' =>  $request->get('value'),
		];
            // dd($update_pkg_data);
		$update_pkg_attr_data = RadGroupReply::where('id',$update_id)->update($update_pkg_data);
		return response()->json(['success'=>'Update Pakage Attributes Successfully.']);
	}

	return response()->json(['error'=>$data->errors()->all()]);

}

}
