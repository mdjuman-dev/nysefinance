<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\SecurityPinReset;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        $pin  = (string) $user->security_pin;

        return \Inertia\Inertia::render('User/Account/Profile', [
            'profile' => [
                'firstname' => $user->firstname,
                'lastname'  => $user->lastname,
                'username'  => $user->username,
                'email'     => $user->email,
                'mobile'    => $user->mobile ? '+' . ltrim($user->dial_code . $user->mobile, '+') : null,
                'country'   => $user->country_name,
                'address'   => is_string($user->address) ? $user->address : null,
                'city'      => $user->city,
                'state'     => $user->state,
                'zip'       => $user->zip,
                'image'     => $user->image ? getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) : null,
                'joined'    => $user->created_at?->toIso8601String(),
                'kyc'       => (int) $user->kv,
                'ev'        => (bool) $user->ev,
                'sv'        => (bool) $user->sv,
                'twofa'     => (bool) $user->ts,
            ],
            // the PIN is never sent back in full
            'pin' => $pin === '' ? null : substr($pin, 0, 1) . str_repeat('•', max(0, strlen($pin) - 2)) . substr($pin, -1),
            'urls' => [
                'save'     => route('user.profile.setting'),
                'pinReset' => route('user.reset.security.pin'),
                'password' => route('user.change.password'),
                'twofa'    => route('user.twofactor'),
                'kyc'      => route('user.kyc.form'),
                'kycData'  => route('user.kyc.data'),
            ],
        ]);
    }

    public function pinRest(Request $request)
    {

        $user = auth()->user();

        $preRequest = SecurityPinReset::where('user_id', $user->id)->whereDate('created_at', now())->first();
        if ($preRequest) {
            $notify[] = ['error', 'Your Daily Security Pin Rest Request Has Been Excessed'];
            return back()->withNotify($notify);
        }

        $newRestReq=new SecurityPinReset();
        $newRestReq->user_id=$user->id;
        $newRestReq->save();


        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = $user->username .' | Request To Rest Security Pin';
        $adminNotification->click_url = urlPath('admin.pin.rest.request');
        $adminNotification->save();



        $notify[] = ['success', 'Congratulations! Soon system will send the new security pin to your email address'];
        return back()->withNotify($notify);


    }

    public function submitProfile(Request $request)
    {

        $user = auth()->user();



        if (!$user->security_pin) {
            $request->validate([
                'firstname' => 'required|string',
                'lastname'  => 'required|string',
                'security_pin'  => 'required',
                'image'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            ], [
                'firstname.required' => 'The first name field is required',
                'lastname.required'  => 'The last name field is required'
            ]);
        }else{
            $request->validate([
                'firstname' => 'required|string',
                'lastname'  => 'required|string',
                'image'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            ], [
                'firstname.required' => 'The first name field is required',
                'lastname.required'  => 'The last name field is required'
            ]);
        }


        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;

        $user->address = $request->address;
        $user->city    = $request->city;
        $user->state   = $request->state;
        $user->zip     = $request->zip;
        // The PIN is set once; resets go through the admin request flow (pinRest).
        if (!$user->security_pin && $request->filled('security_pin')) {
            $user->security_pin = $request->security_pin;
        }


        if ($request->hasFile('image')) {
            try {
                $old         = @$user->image;
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }


        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        return \Inertia\Inertia::render('User/Account/Password', [
            'securePassword' => (bool) gs('secure_password'),
            'action'         => route('user.change.password'),
        ]);
    }

    public function submitPassword(Request $request)
    {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changed successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }
}
