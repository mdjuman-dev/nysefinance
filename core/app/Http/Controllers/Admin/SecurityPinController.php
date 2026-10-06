<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityPinReset;
use App\Models\User;
use Illuminate\Http\Request;

class SecurityPinController extends Controller
{

    public function resetRequest()
    {
        $pageTitle = 'Pin Rest Request';

        $reset_requests = SecurityPinReset::orderByDesc('created_at')->paginate(getPaginate());


        return view('admin.pin_reset.request', compact('pageTitle', 'reset_requests'));
    }


    public function resetStatus(Request $request){

        $request->validate([
            'id'=>'required',
            'status'=>'required'
        ]);

        $str_random=mt_rand(1000000, 9999999);


        $reset_request=SecurityPinReset::where('status', 'pending')->where('id', $request->id)->firstOrFail();

        $user=User::findOrFail($reset_request->user_id);


        
        if($request->status=='approved'){
            $reset_request->status='approved';
            $reset_request->save();

            $user->security_pin=$str_random;
            $user->save();

            $newPinMessage='Your new security pin is: '.$str_random;
            sendMail($user->email, 'Security Pin Update', $newPinMessage);

        }else{
            $reset_request->status='rejected';
            $reset_request->reason=$request->reason;
            $reset_request->save();
        }

        $notify[] = ['success', 'Reset request status successfully changed'];
        return back()->withNotify($notify);


    }


}
