<?php

namespace App\Http\Controllers;

use App\Job\StockBuyBonus;
use App\Models\BondWallet;
use App\Models\DailyInterest;
use App\Models\StockTransaction;
use App\Models\StockWallet;
use App\Models\UserStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockCronController extends Controller
{
    public function dailyInterest()
    {
        $interestStatus = gs('daily_interest_status')?gs('daily_interest_status'):null;


        $user_stocks = UserStock::with('product')->where('status', 'buy')->whereDate('interest_date', now())->limit(25)->get();
        
        \Log::info('Daily interest started', [
        'count' => $user_stocks->count()
       ]);

        DB::beginTransaction();
        $type='';
        foreach ($user_stocks as $user_stock) {
            if($user_stock->use_for == 'bond'){
                $type = 'Bond';
                $stock_wallet = BondWallet::where('user_id', $user_stock->user_id)->where('status', 'active')->first();
            }else{
                $type = 'Stock';
                $stock_wallet = StockWallet::where('user_id', $user_stock->user_id)->where('status', 'active')->first();
            }

            if ($stock_wallet && $user_stock->product && $user_stock->product->fix_rate) {
                $already_got_interest = DailyInterest::where('user_id', $user_stock->user_id)->where('stock_id', $user_stock->id)->whereDate('created_at', now())->first();


                if (!$already_got_interest) {
                    try {

                        if ($interestStatus && $interestStatus == 'off') {
                            //Update Interest Date
                            $user_stock->interest_date = now()->addDay();
                            $user_stock->save();
                            DB::commit();
                        }elseif ($user_stock->invest_date < now()) {
                            //Update Interest Date
                            $user_stock->interest_date = now()->addDay();
                            $user_stock->save();
                            DB::commit();
                        } else {

//                            $rate = $user_stock->interest?$user_stock->interest:$user_stock->product->fix_rate;
                            $rate = $user_stock->product->fix_rate;
                            $invest_amount = $user_stock->invest_amount;
                            $grand_amount = ($invest_amount * $rate) / 100;

                            $wallet_new_amount = $stock_wallet->amount + $grand_amount;
                            $stock_wallet->amount = $wallet_new_amount;
                            $stock_wallet->save();


                            $dailyInterest = new DailyInterest();
                            $dailyInterest->stock_id = $user_stock->id;
                            $dailyInterest->user_id = $user_stock->user_id;
                            $dailyInterest->amount = $wallet_new_amount;
                            $dailyInterest->remark = 'Got Daily Interest For '.$type.' Item: ' . $user_stock->product->name;
                            $dailyInterest->save();

                            $stockTran = new StockTransaction();
                            $stockTran->user_id = $user_stock->user_id;
                            $stockTran->type = 'interest';
                            $stockTran->stock_type = 'fix';
                            $stockTran->remark = 'Got Daily Interest For '.$type.'  Item: ' . $user_stock->product->name;
                            $stockTran->amount = $grand_amount;
                            $stockTran->stock_id = $user_stock->id;
                            $stockTran->use_for = $user_stock->use_for;
                            $stockTran->save();

                            try {
                                if ($user_stock->user && $user_stock->user->referrer) {
                                    StockBuyBonus::dispatch($grand_amount, $user_stock->user->id, $type);
                                }
                            } catch (\Exception $efg) {

                            }


                            //Update Interest Date
                            $user_stock->interest_date = now()->addDay();
                            $user_stock->save();
                            DB::commit();
                        }
                        
                    // Juman edit
                    }catch (\Throwable $eex) {
                        DB::rollBack();
                        
                        \Log::error('Daily Interest Error', [
                            'message' => $eex->getMessage(),
                            'file' => $eex->getFile(),
                            'line' => $eex->getLine(),
                        ]);
                    }
                     // edit end
                }
            }
        }


    }
}
