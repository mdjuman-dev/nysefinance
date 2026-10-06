<?php

namespace App\Job;


use App\Models\BondWallet;
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


class StockBuyBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    private $amount;
    private $referrer;
    private $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($amount, $referrer, $type)
    {
        $this->amount=$amount;
        $this->referrer=$referrer;
        $this->type=$type;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $referrer=$this->referrer;
        $amount=$this->amount;



        $levOneUser=User::where('id', $referrer)->first();

        if($levOneUser && $levOneUser->referrer){
            //TODO::For  One
            $levOneUserCommission=$levOneUser->referrer;
            if($this->type == 'Bond'){
                $referrer_wallet = BondWallet::where('user_id', $levOneUserCommission->id)->first();
            }else{
                $referrer_wallet = StockWallet::where('user_id', $levOneUserCommission->id)->first();
            }
            if(!$referrer_wallet){
                return;
            }
            $step_one_commission=($amount * 10) / 100;
            $new_wallet_balance = $referrer_wallet->amount + $step_one_commission;

            $referrer_wallet->amount = $new_wallet_balance;
            $referrer_wallet->save();

            $interestTran = new StockTransaction();
            $interestTran->user_id = $levOneUserCommission->id;
            $interestTran->type = 'interest';
            $interestTran->remark = 'Mutual Fund Bonus From: '.$levOneUser->fullname;
            $interestTran->amount = $step_one_commission;
            $interestTran->save();

            //TODO::For  Two
            if($levOneUserCommission && $levOneUserCommission->referrer){
                $levTwoUserCommission=$levOneUserCommission->referrer;
                $step_two_commission=($step_one_commission * 8) / 100;
                if($this->type == 'Bond'){
                    $referrer_wallet_two = BondWallet::where('user_id', $levTwoUserCommission->id)->first();
                }else{
                    $referrer_wallet_two = StockWallet::where('user_id', $levTwoUserCommission->id)->first();
                }
                if($referrer_wallet_two) {
                    $new_wallet_balance_two = $referrer_wallet_two->amount + $step_two_commission;
                    $referrer_wallet_two->amount = $new_wallet_balance_two;
                    $referrer_wallet_two->save();

                    $interestTran = new StockTransaction();
                    $interestTran->user_id = $levTwoUserCommission->id;
                    $interestTran->type = 'interest';
                    $interestTran->remark = 'Mutual Fund Bonus From: ' . $levOneUserCommission->fullname;
                    $interestTran->amount = $step_two_commission;
                    $interestTran->save();
                }

                //TODO::For Three
                if($levTwoUserCommission && $levTwoUserCommission->referrer){
                    $levThreeUserCommission=$levTwoUserCommission->referrer;
                    $step_three_commission=($step_two_commission * 6) / 100;
                    if($this->type == 'Bond'){
                        $referrer_wallet_three = BondWallet::where('user_id', $levThreeUserCommission->id)->first();
                    }else{
                        $referrer_wallet_three = StockWallet::where('user_id', $levThreeUserCommission->id)->first();
                    }
                    if($referrer_wallet_three) {
                        $new_wallet_balance_t = $referrer_wallet_three->amount + $step_three_commission;
                        $referrer_wallet_three->amount = $new_wallet_balance_t;
                        $referrer_wallet_three->save();

                        $interestTran = new StockTransaction();
                        $interestTran->user_id = $levThreeUserCommission->id;
                        $interestTran->type = 'interest';
                        $interestTran->remark = 'Mutual Fund Bonus From: ' . $levTwoUserCommission->fullname;
                        $interestTran->amount = $step_three_commission;
                        $interestTran->save();
                    }

                    //TODO::For Five Four
                    if($levThreeUserCommission && $levThreeUserCommission->referrer){
                        $levFourUserCommission=$levThreeUserCommission->referrer;
                        $step_four_commission=($step_three_commission * 5) / 100;
                        if($this->type == 'Bond'){
                            $referrer_wallet_four = BondWallet::where('user_id', $levFourUserCommission->id)->first();
                        }else{
                            $referrer_wallet_four = StockWallet::where('user_id', $levFourUserCommission->id)->first();
                        }
                        if($referrer_wallet_four) {
                            $new_wallet_balance_f = $referrer_wallet_four->amount + $step_four_commission;
                            $referrer_wallet_four->amount = $new_wallet_balance_f;
                            $referrer_wallet_four->save();

                            $interestTran = new StockTransaction();
                            $interestTran->user_id = $levFourUserCommission->id;
                            $interestTran->type = 'interest';
                            $interestTran->remark = 'Mutual Fund Bonus From: ' . $levThreeUserCommission->fullname;;
                            $interestTran->amount = $step_four_commission;
                            $interestTran->save();
                        }


                        if($levFourUserCommission && $levFourUserCommission->referrer){
                            $levFiveUserCommission=$levFourUserCommission->referrer;
                            $step_five_commission=($step_four_commission * 5) / 100;
                            if($this->type == 'Bond'){
                                $referrer_wallet_five = BondWallet::where('user_id', $levFiveUserCommission->id)->first();
                            }else{
                                $referrer_wallet_five = StockWallet::where('user_id', $levFiveUserCommission->id)->first();
                            }
                            if($referrer_wallet_five) {
                                $new_wallet_balance_five = $referrer_wallet_five->amount + $step_five_commission;
                                $referrer_wallet_five->amount = $new_wallet_balance_five;
                                $referrer_wallet_five->save();

                                $interestTran = new StockTransaction();
                                $interestTran->user_id = $levFiveUserCommission->id;
                                $interestTran->type = 'interest';
                                $interestTran->remark = 'Mutual Fund Bonus From: ' . $levFourUserCommission->fullname;;
                                $interestTran->amount = $step_five_commission;
                                $interestTran->save();
                            }

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
