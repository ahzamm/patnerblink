<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Profile;
use App\model\Users\RadGroupReply;
use Illuminate\Support\Facades\Validator;
class ProfileController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {

 }
//
 public function index(){


    $profileList = Profile::orderBy('groupname')->get();
    $radgroupreply = RadGroupReply::select('groupname')->distinct()->whereNotIn('groupname', ['NEW','EXPIRED','DISABLED','Block','TERMINATE'])->get(); 

    return view('admin.users.view_profile',['profileList' => $profileList,'radgroupreply' => $radgroupreply]);



}
//
public function store(Request $request)
{
    $profile = new Profile();
    $groupname = $request->get('groupname');
    // $pro = $groupname *1024;
        // $profile->groupname = $pro;  /// changed
    $profile->groupname = $groupname;
    $profile->name = $request->get('name');
    $profile->code = null;
    $profile->color = $request->get('color');
    $profile->quota_limit = $request->get('quota_limit');
    $profile->sst = $request->get('sst');
    $profile->adv_tax = $request->get('adv_tax');
    $profile->charges = $request->get('charges');
    $profile->final_rates = $request->get('final_rates');
    $profile->sstB = $request->get('sstB');
    $profile->adv_taxB = $request->get('adv_taxB');
    $profile->chargesB = $request->get('chargesB');
    $profile->final_ratesB = $request->get('final_ratesB');
    $profile->sstc = $request->get('sstc');
    $profile->adv_taxC = $request->get('adv_taxC');
    $profile->chargesC = $request->get('chargesC');
    $profile->final_ratesC = $request->get('final_ratesC');
    /*add new fields*/
    $profile->soc_facebook = $request->get('soc_facebook');
    $profile->soc_youtube = $request->get('soc_youtube');
    $profile->soc_internet = $request->get('soc_internet');
    $profile->soc_netflix = $request->get('soc_netflix');
    $bw_show = $request->get('bw_show') *1024;
    $profile->bw_show = $bw_show;
    $profile->base_price = $request->get('base_price');

    $profile->profile_type = $request->get('profile_type');
    $profile->save();

    return redirect()->route('admin.profile.index');
}

public function deletethis(Request $request)
{
   $id =$request->get('id');
   Profile::find($id);


   Profile::where(['id' => $id])->delete();
}

public function edit(Request $request) 
{
  $get_profile_id = $request->input('get_id');
  $update_request = Profile::where('id',$get_profile_id)->first();
  return response()->json($update_request);
}
public function update(Request $request)
{
 $update_id =$request->get('id');
 $data = Validator::make($request->all(),[
    // 'code' => 'required',
    'color' =>  'required',
    'quota_limit' =>  'required',
    // 'sst' =>  'required',
    // 'adv_tax' =>  'required',
    // 'charges' =>  'required',
    // 'final_rates' =>  'required',
    // 'sstB' =>  'required',
    // 'adv_taxB' =>  'required',
    // 'chargesB' =>  'required',
    // 'final_ratesB' =>  'required',
    // 'sstC' =>  'required',
    // 'adv_taxC' =>  'required',
    // 'chargesC' =>  'required',
    // 'final_ratesC' =>  'required',
    'bw_show' => 'required',
    'profile_type'=>'required',
    'base_price'=>'required'
]);
 if($data->passes())
 {
   $bw = $request->get('bw_show');
   $update_profile = [
    'code' => null,
    'color' =>  $request->get('color'),
    'quota_limit' =>  $request->get('quota_limit'),
    'sst' =>  $request->get('sst'),
    'adv_tax' =>  $request->get('adv_tax'),
    'charges' =>  $request->get('charges'),
    'final_rates' => $request->get('final_rates'),
    'sstB' =>  $request->get('sstB'),
    'adv_taxB' =>  $request->get('adv_taxB'),
    'chargesB' =>  $request->get('chargesB'),
    'final_ratesB' =>  $request->get('final_ratesB'),
    'sstC' =>  $request->get('sstC'),
    'adv_taxC' =>  $request->get('adv_taxC'),
    'chargesC' =>  $request->get('chargesC'),
    'final_ratesC' =>  $request->get('final_ratesC'),
    'bw_show' =>  $bw * 1024,
    'profile_type' =>  $request->get('profile_type'),
    'soc_facebook' =>  $request->get('soc_facebook'),
    'soc_youtube' =>  $request->get('soc_youtube'),
    'soc_internet' =>  $request->get('soc_internet'),
    'soc_netflix' =>  $request->get('soc_netflix'),
    'base_price' =>  $request->get('base_price'),
];
$update_profile_data = Profile::where('id',$update_id)->update($update_profile);
return response()->json(['success'=>'Update Profile Successfully.']);
}
return response()->json(['error'=>$data->errors()->all()]);  
}

}



