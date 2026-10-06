<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\Salary;
use Illuminate\Http\Request;

class RewardController extends Controller
{


    public function index(Request $request)
    {


        $data['pageTitle']='Rewards';
        $data['rewards']=Reward::orderByDesc('coin')->paginate(getPaginate());

        return view('admin.users.reward', $data);
    }

    public function update(Request $request){

        $reward=Reward::where('id', $request->reward_id)->where('user_id', $request->user_id)->firstOrFail();

        if($request->reward_amount){
            $reward->coin=$request->reward_amount;
            $reward->save();
        }


        $message = 'Congratulations! Reward Successfully Updated.';
        $notify[] = ['success', $message];
        return redirect()->back()->withNotify($notify);



    }



}
