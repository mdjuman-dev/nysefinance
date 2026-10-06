<?php

namespace App\Http\Controllers;

use App\Job\ProcessTrx;
use App\Models\Deposit;
use Illuminate\Http\Request;

class CheckTransaction extends Controller
{


    public function __construct()
    {
        $this->apiKey = "mZaz2Lue3U1EEX8B8kIlZ5QO4A5gJUDHDlXdW9461tlxRWgCwa5EmzkWQlyMfIhn";
        $this->secretKey = "BlXARkDD5OKAQLNhhj90eijLhGQwfX8kV51rMCzn5K3Lr493RKG0k44ZXSFXvLtx";
        $this->baseUrl = "https://api.binance.com";
    }




    public function check()
    {


        $pendingDeposits = Deposit::where('status', '2')->whereNotNull('foren_id')->get();


        if ($pendingDeposits->isNotEmpty()) {
            foreach ($pendingDeposits as $pendingDeposit) {
                ProcessTrx::dispatchSync($pendingDeposit->id);
            }
        }


    }



    public function checkDepositStatus(Request $request)
    {

        if(!$request->request_id){
            return response()->json(['status'=>'failed', 'message'=>'Request id is required.']);
        }
        $deposit=Deposit::where('user_id', auth()->user()->id)->where('id', $request->request_id)->first();

        if(!$deposit){
            return response()->json(['status'=>'failed', 'message'=>'Request id not found.']);
        }



        if($deposit->status==1){
            return response()->json(['status'=>'success', 'message'=>'Deposit Request already accepted.']);
        }
        if($deposit->status==3){
            return response()->json(['status'=>'rejected', 'message'=>'Deposit Request already declined.']);
        }

        return response()->json(['status'=>'pending', 'message'=>'wait....']);
    }

}
