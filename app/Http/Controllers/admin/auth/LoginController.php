<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\model\Users\LoginAudit;
use Illuminate\Http\Request;
use App\model\admin\Admin;
use Illuminate\Validation\ValidationException;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $url = url()->current();
        $parse = parse_url($url);
        if($parse['host'] == 'manager.logon.com.pk' ){
            return view('admin.auth.login');
        }else{
           abort(404); 
        }
    }


     public function username()
    {
        return 'username';
    }

       public function login(Request $request)
    {
        if($request->username == 'admin' || $request->username == 'cyber'){
           return $this->sendFailedLoginResponse($request); 
        }
        //
        $disable = Admin::where('username',$request->username)->where('enable',0)->first();
        if($disable){
            return $this->sendAgentBlock($request); 
        }
        //
        //
        $this->validateLogin($request);

        $loginAudit = new LoginAudit();
        $loginAudit->username = $request->username;
        $loginAudit->login_time = Now();
        $loginAudit->ip = $request->ip();
        $loginAudit->save();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


     public function logout(Request $request)
    {
        $this->guard()->logout();

        if(Auth::guard('user')->check()){
            $request->session()->invalidate();
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect()->route('admin.login.show');
        }else{
            $request->session()->invalidate();
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect()->route('admin.login.show');
        }
    }

    protected function guard()
    {
        return Auth::guard("admin");
    }

    protected function sendAgentBlock(Request $request)
{
    throw ValidationException::withMessages([
        $this->username() => "Suspended User! You Can't Access this Account",
    ]);
}


    }
    
    

