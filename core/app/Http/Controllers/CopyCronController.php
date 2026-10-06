<?php

namespace App\Http\Controllers;

use App\Models\CopyTransaction;
use App\Models\Currency;
use App\Models\UserCopyTrade;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CopyCronController extends Controller
{
    public function dailyInterest()
    {

        $userCopyTrades=UserCopyTrade::with('trade','user')->where('status', 'buy')
//            ->where('interest_type', 'daily')
            ->whereDate('next_interest_date', now())
            ->limit(25)
            ->get();

//DB::beginTransaction();

//        dd($userCopyTrades);


        if($userCopyTrades){
            foreach($userCopyTrades as $userCopyTrade){


                $customer=$userCopyTrade->user;

                $currency = Currency::where('symbol', 'USDT')->first();
                if($currency) {


                    $user_wallet = Wallet::where('user_id', $customer->id)->where('currency_id', $currency->id)->first();
                    if($user_wallet) {

                        try{
                            if($userCopyTrade->interest==0){
                                $interestAmount=0;
                            }else {
                                $interestAmount = ($userCopyTrade->price * $userCopyTrade->interest) / 100;
                            }


//                            if($userCopyTrade->profit_type=='profit') {

                                $reduce_balance = $user_wallet->balance + $interestAmount;
                                $user_wallet->balance = $reduce_balance;
                                $user_wallet->save();

//                            }else{
//                                $reduce_balance = $user_wallet->balance - $interestAmount;
//                                $user_wallet->balance = $reduce_balance;
//                                $user_wallet->save();
//
//                                $interestAmount=0;
//                            }


                            $transaction = new CopyTransaction();
                            $transaction->user_id = $customer->id;
                            $transaction->trade_id = $userCopyTrade->trade_id;
                            $transaction->user_trade_id = $userCopyTrade->id;
                            $transaction->amount = $interestAmount;
                            $transaction->type = 'interest';
                            $transaction->transaction_id = 'IT' . strtoupper(Str::random(18));
                            $transaction->remarks = 'You Got Interest From '.$userCopyTrade->trade->name;
                            $transaction->save();

                            $interestDay=1;
                            if($userCopyTrade->interest_type=='daily'){
                                $interestDay=1;
                            }else if($userCopyTrade->interest_type=='weekly'){
                                $interestDay=7;
                            }else if($userCopyTrade->interest_type=='monthly'){
                                $interestDay=30;
                            }else if($userCopyTrade->interest_type=='yearly'){
                                $interestDay=365;
                            }

                            $userCopyTrade->next_interest_date=now()->addDays($interestDay);
                            $userCopyTrade->save();

                        }catch(\Exception $e){
                            Log::info('COPY-TRADE ERROR : '.$userCopyTrade->id);
                            continue;
                        }

                    }
                }

            }
        }

        return;

    }
}
