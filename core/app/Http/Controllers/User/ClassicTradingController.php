<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CopyTrade;
use App\Models\CopyTransaction;
use App\Models\Currency;
use App\Models\UserCopyTrade;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassicTradingController extends Controller
{
    public function classic()
    {
        $data['pageTitle'] = "Classic Trading";
        $data['trades']=CopyTrade::where('status', 'active')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();
        $data['stared_trades']=CopyTrade::where('status', 'active')->where('stared', 'yes')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();

        $data['classic_trades']=UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.classic', $data);

    }

    public function buyCopyTrade(Request $request){

        try{

            $copyTrade = CopyTrade::where('status', 'active')->where('id',$request->id)->firstOrFail();

            $customer=auth()->user();


            DB::beginTransaction();


            $currency = Currency::where('symbol', 'USDT')->first();
            if (!$currency) {
                $notifye[] = ['error', 'Can not buy stock at this moment'];
                return redirect()->back()->withNotify($notifye);
            }

            $user_wallet = Wallet::where('user_id', $customer->id)->where('currency_id', $currency->id)->first();
            if (!$user_wallet) {
                $notifyw[] = ['error', 'Can not buy stock at this moment'];
                return redirect()->back()->withNotify($notifyw);
            }

            if ($user_wallet->balance < $copyTrade->amount) {
                $notifyIn[] = ['error', 'Insufficient balance'];
                return redirect()->back()->withNotify($notifyIn);
            }

            $reduce_balance = $user_wallet->balance - $copyTrade->amount;
            $user_wallet->balance = $reduce_balance;
            $user_wallet->save();



            $userCopyTrade = new UserCopyTrade();
            $userCopyTrade->user_id=$customer->id;
            $userCopyTrade->trade_id=$copyTrade->id;
            $userCopyTrade->price=$copyTrade->amount;
            $userCopyTrade->interest=$copyTrade->interest;
            $userCopyTrade->interest_type=$copyTrade->type;
            $userCopyTrade->interest_date=$copyTrade->start_date;
            $userCopyTrade->next_interest_date=$copyTrade->start_date;
            $userCopyTrade->status='buy';
            $userCopyTrade->trade_type=$copyTrade->trade_type;
            $userCopyTrade->profit_type=$copyTrade->profit_type;
            $userCopyTrade->save();


            $transaction=new CopyTransaction();
            $transaction->user_id=$customer->id;
            $transaction->trade_id=$copyTrade->id;
            $transaction->user_trade_id=$userCopyTrade->id;
            $transaction->amount=$copyTrade->amount;
            $transaction->type='buy';
            $transaction->trade_type=$copyTrade->trade_type;
            $transaction->transaction_id='CT'.strtoupper(Str::random(18));
            $transaction->remarks='Successfully Buy Copy Trade';
            $transaction->save();


            DB::commit();

            $message = 'Congratulations! '.strtoupper($copyTrade->name).' successfully purchased';
            $notify[] = ['success', $message];
            return redirect()->back()->withNotify($notify);

        }catch (\Exception $ex) {
            DB::rollBack();
            $message = 'Something went wrong, try again after sometimes';
            $notify[] = ['error', $message];
            return redirect()->back()->withNotify($notify);
        }
    }

    public function withdrawCopyTrade(Request $request)
    {

        DB::beginTransaction();


        try{
            $customer=auth()->user();
            $userCopyTrade=UserCopyTrade::where('user_id', $customer->id)->where('status', 'buy')->where('id', $request->id)->firstOrFail();

            $alreadySold=CopyTransaction::where('user_trade_id', $userCopyTrade->id)->where('type', 'sell')->first();
            if ($alreadySold) {

                $userCopyTrade->status='sell';
                $userCopyTrade->save();

                DB::commit();
                $notifye[] = ['error', 'You cannot withdraw this copy trade, already sold'];
                return redirect()->back()->withNotify($notifye);
            }


            $currency = Currency::where('symbol', 'USDT')->first();
            if (!$currency) {
                $notifye[] = ['error', 'Can not withdraw at this moment'];
                return redirect()->back()->withNotify($notifye);
            }

            $user_wallet = Wallet::where('user_id', $customer->id)->where('currency_id', $currency->id)->first();
            if (!$user_wallet) {
                $notifyw[] = ['error', 'Can not withdraw at this moment'];
                return redirect()->back()->withNotify($notifyw);
            }


            $reduce_balance = $user_wallet->balance + $userCopyTrade->price;
            $user_wallet->balance = $reduce_balance;
            $user_wallet->save();


            $transaction=new CopyTransaction();
            $transaction->user_id=$customer->id;
            $transaction->trade_id=$userCopyTrade->trade_id;
            $transaction->user_trade_id=$userCopyTrade->id;
            $transaction->amount=$userCopyTrade->price;
            $transaction->type='sell';
            $transaction->transaction_id='CT'.strtoupper(Str::random(18));
            $transaction->remarks='Successfully Withdraw Copy Trade';
            $transaction->save();

            $userCopyTrade->status='sell';
            $userCopyTrade->save();


            DB::commit();
            $message = 'Congratulations! '.strtoupper($userCopyTrade->trade->name).' successfully withdraw';
            $notify[] = ['success', $message];
            return redirect()->back()->withNotify($notify);

        }catch (\Exception $ex) {
            DB::rollBack();
            dd($ex);
            $message = 'Something went wrong, try again after sometimes';
            $notify[] = ['error', $message];
            return redirect()->back()->withNotify($notify);
        }

    }
}
