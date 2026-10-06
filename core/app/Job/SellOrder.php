<?php

namespace App\Job;


use App\Constants\Status;
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
use Illuminate\Support\Facades\DB;


class SellOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($order)
    {
        $this->order = $order;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $sellSideOrder=$this->order;

        $tradeSideSell = Status::SELL_SIDE_ORDER;

        try {


            $rate = $sellSideOrder->rate;
            $tradeAmount = $sellSideOrder->amount;


            $totalSellingAmount = $sellSideOrder->amount * $rate;
            $charge = 0;


            if ($sellSideOrder->charge > 0) {
                $sellingPercentage = ($tradeAmount / $sellSideOrder->amount) * 100;
                $charge = ($sellSideOrder->charge / 100) * $sellingPercentage;
            }

            $totalSellingAmount = $totalSellingAmount - $charge;


            $SellSideSellerWallet = Wallet::where('user_id', $sellSideOrder->user_id)->where('currency_id', $sellSideOrder->pair->market->currency_id)->spot()->first();
            $buyerWallet = Wallet::where('user_id', $sellSideOrder->user_id)->where('currency_id', $sellSideOrder->pair->coin->id)->spot()->first();


//                        if ($buyerWallet->balance < $sellSideOrder->amount) {
//                            continue;
//                        }

            //Sell Token Added USDT
            $addedAmount = $SellSideSellerWallet->balance + $totalSellingAmount;
            $SellSideSellerWallet->balance = $addedAmount;
            $SellSideSellerWallet->save();

            //Sell Token
//            $deductSoledToken = $buyerWallet->balance - $sellSideOrder->amount;
//            $buyerWallet->balance = $deductSoledToken;
//            $buyerWallet->save();


            $this->createTrade($tradeSideSell, $sellSideOrder, $rate, $tradeAmount, $sellSideOrder->user_id, $charge);
            $this->updateOrder($sellSideOrder, $tradeAmount);


            $details = showAmount($tradeAmount, currencyFormat: false) . ' ' . $sellSideOrder->pair->coin->symbol . ' Sell completed on pair ' . $sellSideOrder->pair->symbol;
            $this->createTrx($sellSideOrder, $SellSideSellerWallet, $totalSellingAmount, 'trade_sell', $charge, $details, $tradeAmount, "Sell");


            return 'success';
        } catch (\Exception $err) {
            return 'error';
        }


    }


    private function createTrx($order, $wallet, $amount, $remark, $charge = 0, $details, $tradeAmount, $orderSide)
    {
//        $wallet->balance += $amount;
//        $wallet->save();

//        if ($orderSide = 'Sell') {
//            dd($wallet->balance, $amount);
//        }

        $this->transactions[] = [
            'user_id' => $order->user_id,
            'wallet_id' => $wallet->id,
            'amount' => $amount,
            'post_balance' => $wallet->balance,
            'charge' => 0,
            'trx_type' => '+',
            'details' => $details,
            'trx' => getTrx(),
            'remark' => $remark,
            'created_at' => now()
        ];

        if ($charge > 0) {

            $wallet->balance -= $charge;
            $wallet->save();

            $this->transactions[] = [
                'user_id' => $order->user_id,
                'wallet_id' => $wallet->id,
                'amount' => $charge,
                'post_balance' => $wallet->balance,
                'charge' => 0,
                'trx_type' => '-',
                'details' => "Charge for" . $details,
                'trx' => getTrx(),
                'remark' => $remark,
                'created_at' => now()
            ];
        }

//        notify($order->user, 'ORDER_COMPLETE', [
//            'pair' => $order->pair->symbol,
//            'amount' => showAmount($tradeAmount, currencyFormat: false),
//            'total' => showAmount($order->total, currencyFormat: false),
//            'rate' => showAmount($order->rate, currencyFormat: false),
//            'price' => showAmount($order->price, currencyFormat: false),
//            'coin_symbol' => @$order->pair->coin->symbol,
//            'order_side' => $orderSide,
//            'market_currency_symbol' => @$order->pair->market->currency->symbol,
//            'market' => @$order->pair->market->name,
//            'filled_amount' => showAmount(@$order->filled_amount, currencyFormat: false),
//            'filled_percentage' => getAmount(@$order->filed_percentage),
//        ]);



    }

    private function createTrade($tradeSide, $order, $rate, $amount, $traderId, $charge = 0)
    {
        $trade = [
            'trader_id' => $traderId,
            'pair_id' => $order->pair_id,
            'trade_side' => $tradeSide,
            'order_id' => $order->id,
            'rate' => $rate,
            'amount' => $amount,
            'total' => $rate * $amount,
            'charge' => $charge,
            'created_at' => now()
        ];
        $this->tradeWithSymbol[@$order->pair->symbol][] = $trade;
        $this->trades[] = $trade;
    }

    private function updateOrder($order, $amount)
    {
        $filedAmount = $order->filled_amount + $amount;
        $filePercentage = ($filedAmount / $order->amount) * 100;

        if ($filedAmount == $order->amount) {
            $order->status = Status::ORDER_COMPLETED;
        }
        $order->filled_amount = $filedAmount;
        $order->filed_percentage = $filePercentage;
        $order->save();
        return $order;
    }



    public function failed(\Exception $exception)
    {


    }
}
