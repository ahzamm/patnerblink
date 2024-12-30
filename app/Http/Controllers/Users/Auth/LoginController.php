<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\model\Users\LoginAudit;
use Illuminate\Http\Request;
use App\model\Users\Domain;
use App\model\Users\UserInfo;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/users/dashboard';

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

        $domain = Domain::where('domainname', $parse['host'])->first();
        if (!empty($domain->manager_id) && $domain->manager_id == 'logonmanager') {
            $user = UserInfo::where('domainID', $domain->id)
                ->where('manager_id', 'logonmanager')
                ->first();

            if (!empty($domain) && $domain->manager_id == $user->manager_id) {
                return view('users.auth.login', compact('domain'));
            }
        } else {
            $user = UserInfo::where('domainID', $domain->id)
                ->where('resellerid', '!=', null)
                ->first();
            if (!empty($domain) && $domain->resellerid == $user->resellerid) {
                return view('users.auth.login', compact('domain'));
            }
        }
    }

    public function username()
    {
        return 'username';
    }

    public function previewLogin(Request $request)
    {
        $username = $request->route('username');

        $user = UserInfo::where('username', $username)->first();

        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found');
        }

        Auth::login($user);

        $tabIdentifier = $request->session()->getId() . '_' . bin2hex(random_bytes(8));

        session(['tab_identifier' => $tabIdentifier]);

        config(['session.cookie' => 'session_' . $tabIdentifier]);

        $loginAudit = new LoginAudit();
        $loginAudit->username = $user->username;
        $loginAudit->login_time = now();
        $loginAudit->ip = $request->ip();
        $loginAudit->save();

        return $this->sendLoginResponse($request);
    }

    public function login(Request $request)
    {
        session()->flush();

        $user = UserInfo::where('username', $request->username)->first();

        if (empty($user)) {
            return $this->sendFailedLoginResponse($request);
        }
        if ($user->account_disabled === 1) {
            return $this->sendAgentBlock($request);
        }

        if (empty($user->resellerid)) {
            $domain = Domain::where('resellerid', null)
                ->where('manager_id', $user->manager_id)
                ->first();
        } else {
            $domain = Domain::where('resellerid', $user->resellerid)->first();
        }

        $url = url()->current();
        $parse = parse_url($url);

        if (isset($domain->domainname)) {
            if ($domain->domainname != $parse['host']) {
                return $this->sendFailedLoginResponse($request);
            }
        }

        $this->validateLogin($request);
        $update_loginAudit = LoginAudit::where('username', $request->username);
        $update_loginAudit->update([
            'status' => 'OUT',
        ]);

        session()->put('test', session()->getId());
        $session = session()->get('test');

        $loginAudit = new LoginAudit();
        $loginAudit->username = $request->username;
        $loginAudit->login_time = Now();
        $loginAudit->ip = $request->ip();
        $loginAudit->sessionid = $session;
        $loginAudit->save();

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $tabIdentifier = $request->session()->getId() . '_' . bin2hex(random_bytes(8));
            session(['tab_identifier' => $tabIdentifier]);
            config(['session.cookie' => 'session_' . $tabIdentifier]);

            if ($parse['host'] != 'manager.logon.com.pk') {
                if ($domain->domainname == $parse['host']) {
                    return $this->sendLoginResponse($request);
                } else {
                    $this->incrementLoginAttempts($request);
                    return $this->sendFailedLoginResponse($request);
                }
            } else {
                return $this->sendLoginResponse($request);
            }
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        if (Auth::guard('admin')->check()) {
            $request->session()->invalidate();
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect()->route('users.home');
        } else {
            $request->session()->invalidate();
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect()->route('users.home');
        }
    }

    protected function guard()
    {
        return Auth::guard('user');
    }

    protected function sendAgentBlock(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => "Suspended User! You Can't Access this Account",
        ]);
    }
}
