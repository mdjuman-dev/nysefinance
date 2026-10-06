<?php

namespace App\Job;


use App\Models\Currency;
use App\Models\StockTransaction;
use App\Models\StockWallet;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class LevelCommission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    private $amount;
    private $referrer;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($amount, $referrer)
    {
        $this->amount=$amount;
        $this->referrer=$referrer;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $referrer=$this->referrer;
        $amount=2;
        $currency = Currency::where('symbol', 'USDT')->first();
        if (!$currency) {
            return;
        }


        $levOneUser=User::where('id', $referrer)->first();

        if($levOneUser && $levOneUser->referrer){
            //TODO::For Two One
            $levOneUserCommission=$levOneUser->referrer;
            $referrer_wallet = StockWallet::where('user_id', $levOneUserCommission->id)->first();
            $step_one_commission=($amount * 5) / 100;
            $new_wallet_balance = $referrer_wallet->amount + $step_one_commission;

            $referrer_wallet->amount = $new_wallet_balance;
            $referrer_wallet->save();

            $interestTran = new StockTransaction();
            $interestTran->user_id = $levOneUserCommission->id;
            $interestTran->type = 'interest';
            $interestTran->remark = 'Got Stock Membership Purchase Interest From: '.$levOneUser->fullname;
            $interestTran->amount = $step_one_commission;
            $interestTran->save();

            //TODO::For Three Two
            if($levOneUserCommission->referrer){
                $levTwoUserCommission=$levOneUserCommission->referrer;
                $step_two_commission=($amount * 4) / 100;
                $referrer_wallet_two = StockWallet::where('user_id', $levTwoUserCommission->id)->first();
                if($referrer_wallet_two) {
                    $new_wallet_balance_two = $referrer_wallet_two->amount + $step_two_commission;
                    $referrer_wallet_two->amount = $new_wallet_balance_two;
                    $referrer_wallet_two->save();

                    $interestTran = new StockTransaction();
                    $interestTran->user_id = $levTwoUserCommission->id;
                    $interestTran->type = 'interest';
                    $interestTran->remark = 'Got Stock Membership Purchase Interest From: ' . $levOneUserCommission->fullname;
                    $interestTran->amount = $step_two_commission;
                    $interestTran->save();
                }

                //TODO::For Four
                if($levTwoUserCommission->referrer){
                    $levThreeUserCommission=$levTwoUserCommission->referrer;
                    $step_three_commission=($amount * 3) / 100;
                    $referrer_wallet_three = StockWallet::where('user_id', $levThreeUserCommission->id)->first();
                    if($referrer_wallet_three) {
                        $new_wallet_balance_t = $referrer_wallet_three->amount + $step_three_commission;
                        $referrer_wallet_three->amount = $new_wallet_balance_t;
                        $referrer_wallet_three->save();

                        $interestTran = new StockTransaction();
                        $interestTran->user_id = $levThreeUserCommission->id;
                        $interestTran->type = 'interest';
                        $interestTran->remark = 'Got Stock Membership Purchase Interest From: ' . $levTwoUserCommission->fullname;
                        $interestTran->amount = $step_three_commission;
                        $interestTran->save();
                    }

                    //TODO::For Five Three
                    if($levThreeUserCommission->referrer){
                        $levFourUserCommission=$levThreeUserCommission->referrer;
                        $step_four_commission=($amount * 2) / 100;
                        $referrer_wallet_four = StockWallet::where('user_id', $levFourUserCommission->id)->first();
                        if($referrer_wallet_four) {
                            $new_wallet_balance_f = $referrer_wallet_four->amount + $step_four_commission;
                            $referrer_wallet_four->amount = $new_wallet_balance_f;
                            $referrer_wallet_four->save();

                            $interestTran = new StockTransaction();
                            $interestTran->user_id = $levFourUserCommission->id;
                            $interestTran->type = 'interest';
                            $interestTran->remark = 'Got Stock Membership Purchase Interest From: ' . $levThreeUserCommission->fullname;;
                            $interestTran->amount = $step_four_commission;
                            $interestTran->save();
                        }

                    }
                }
            }

        }

    }

    public function failed(\Exception $exception)
    {





    }
}
