<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\AdminNotification;
use App\Models\BondWallet;
use App\Models\StockWallet;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Register";
        Intended::identifyRoute();
        $content = getContent('register.content', true);
        return \Inertia\Inertia::render('Auth/Register', \App\Support\AuthPage::props([
            'heading'    => \App\Support\AuthPage::text(@$content->data_values->heading_two ?: 'Sign Up'),
            'subheading' => \App\Support\AuthPage::text(@$content->data_values->subheading_two ?: ''),
            'reward'     => \App\Support\AuthPage::text(@$content->data_values->heading_one ?: ''),
            'reference'  => request('reference') ?: session('reference'),
            'agree'      => (bool) gs('agree'),
            'securePassword' => (bool) gs('secure_password'),
        ]));
    }


    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validate     = Validator::make($data, [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:users',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree
        ],[
            'firstname.required' => 'The first name field is required',
            'lastname.required'  => 'The last name field is required'
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();


        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        event(new Registered($user = $this->create($request->all())));

        Auth::login($user);

//        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?:  redirect($this->redirectPath());
    }



    protected function create(array $data)
    {

        if(isset($data['referBy']) && $data['referBy']) {
            $referUser = User::where('username', $data['referBy'])->first();
            if(!$referUser){
                $referUser=null;
            }
        } else {
            $referUser = null;
        }

        if(!isset($data['referBy']) || !$data['referBy'] || !$referUser){
//            $notify[] = ['error', 'Please use a valid refer link'];
//            return back()->withNotify($notify);
        }



          //User Create
        $user            = new User();
        $user->email     = strtolower($data['email']);
        $user->firstname = $data['firstname'];
        $user->lastname  = $data['lastname'];
        $user->password  = Hash::make($data['password']);
        $user->ref_by    = $referUser ? $referUser->id : 0;
        $user->kv        = gs('kv') ? Status::NO : Status::YES;
        $user->ev        = gs('ev') ? Status::NO : Status::YES;
        $user->sv        = gs('sv') ? Status::NO : Status::YES;
        $user->ts        = Status::DISABLE;
        $user->tv        = Status::ENABLE;
        $user->save(); // uid is assigned by User::booted()


        $new_wallet= new StockWallet();
        $new_wallet->amount=00.00;
        $new_wallet->user_id=$user->id;
        $new_wallet->save();

        $b_new_wallet= new BondWallet();
        $b_new_wallet->amount=00.00;
        $b_new_wallet->user_id=$user->id;
        $b_new_wallet->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();


          //Login Log Create
        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data']  = User::where('email',$request->email)->exists();
            $exist['type']  = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data']  = User::where('mobile',$request->mobile)->where('dial_code',$request->mobile_code)->exists();
            $exist['type']  = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data']  = User::where('username',$request->username)->exists();
            $exist['type']  = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        createWallet();
        return to_route('user.home');
    }

}
