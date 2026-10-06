<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\UserLogin;
use App\Models\UserReport;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Status;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    use AuthenticatesUsers;


    protected $username;


    public function __construct()
    {
        parent::__construct();
        $this->username = $this->findUsername();
    }

    public function showLoginForm()
    {
        $pageTitle = "Login";
        Intended::identifyRoute();
        $content = getContent('login.content', true);
        return \Inertia\Inertia::render('Auth/Login', \App\Support\AuthPage::props([
            'heading'    => \App\Support\AuthPage::text(@$content->data_values->heading_two ?: 'Log In'),
            'subheading' => \App\Support\AuthPage::text(@$content->data_values->subheading_two ?: 'Securely connect to your account'),
        ]));
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // ✅ Attempt login
        if ($this->attemptLogin($request)) {

            // ✅ Login successful → reset failed count to 0
            $user = $this->guard()->user();
            if ($user && isset($user->failed_password_count)) {
                $user->failed_password_count=0;
                $user->save();
            }

            return $this->sendLoginResponse($request);
        }

        // ❌ Login failed → increase failed attempt count
        $this->incrementLoginAttempts($request);

        // Find user and increment failed count
        $loginField = $this->username(); // usually 'email' or 'username'
        $user = \App\Models\User::where('email', $request->email)->first();
        if(!$user){
            $user = \App\Models\User::where('username', $request->username)->first();
        }

        if ($user) {
            $user->failed_password_count=$user->failed_password_count+1;
            $user->save();
        }

        if($user && $user->failed_password_count && $user->failed_password_count >= 3){

            $userReport= new UserReport();
            $userReport->user_id=$user->id;
            $userReport->type='wrong_password';
            $userReport->status='pending';
            $userReport->details='Try wrong password few times';
            $userReport->save();
        }


        Intended::reAssignSession();

        return $this->sendFailedLoginResponse($request);
    }


    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin($request)
    {

        $validator = Validator::make($request->all(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            Intended::reAssignSession();
            $validator->validate();
        }

    }

    public function logout()
    {
        $this->guard()->logout();
        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return to_route('user.login')->withNotify($notify);
    }


    public function authenticated(Request $request, $user)
    {
        $user->tv = $user->ts == Status::VERIFIED ? Status::UNVERIFIED : Status::VERIFIED;
        $user->save();

//        if($user->id !='1100') {

            $ip = getRealIP();
            $exist = UserLogin::where('user_ip', $ip)->first();
            $userLogin = new UserLogin();
            if ($exist) {
                $userLogin->longitude = $exist->longitude;
                $userLogin->latitude = $exist->latitude;
                $userLogin->city = $exist->city;
                $userLogin->country_code = $exist->country_code;
                $userLogin->country = $exist->country;
            } else {
                $info = json_decode(json_encode(getIpInfo()), true);
                $userLogin->longitude = @implode(',', $info['long']);
                $userLogin->latitude = @implode(',', $info['lat']);
                $userLogin->city = @implode(',', $info['city']);
                $userLogin->country_code = @implode(',', $info['code']);
                $userLogin->country = @implode(',', $info['country']);
            }

            $userAgent = osBrowser();
            $userLogin->user_id = $user->id;
            $userLogin->user_ip = $ip;

            $userLogin->browser = @$userAgent['browser'];
            $userLogin->os = @$userAgent['os_platform'];
            $userLogin->save();
//        }

        $redirection = Intended::getRedirection();

        createWallet();

        return $redirection ? $redirection : to_route('user.home');
    }


}
