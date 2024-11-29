<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Profile;
use App\model\Users\UserInfo;
use App\model\Users\RadCheck;
use App\model\admin\Admin;


class UpdatePasswordController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {

 }

 public function index(Request $request){


    $username=$request->get('username');
    $saveUser = Admin::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('password'));
    $saveUser->password_text = $request->get('password');
    $saveUser->save();
// session()->flash('success','Manager success fully updated.');
    return $request->session()->flush();
    return redirect()->route('admin.login.show');
}




}
