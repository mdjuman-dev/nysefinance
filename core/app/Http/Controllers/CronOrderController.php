<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Order;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use net\authorize\util\Log;

class CronOrderController extends Controller
{


//Update Code
    public function tradeBuy()
    {

        DB::beginTransaction();


        try {
            $buySideOrders = Order::with('pair.coin', 'pair.market', 'user')
                ->buySideOrder()
                ->open()
                ->orderBy('id', 'ASC')->get();


            $tradeSideBuy = Status::BUY_SIDE_TRADE;


//            Buy Side Orders
            if ($buySideOrders->isNotEmpty()) {
                foreach ($buySideOrders as $buySideOrder) {
                    $pairId = $buySideOrder->pair_id;
                    $rate = $buySideOrder->rate;
                    $buyAmount = $buySideOrder->amount - $buySideOrder->filled_amount;

//                    if ($buyAmount <= 0) continue;

                    $tradeAmount = $buySideOrder->amount;


                    $this->createTrade($tradeSideBuy, $buySideOrder, $rate, $tradeAmount, $buySideOrder->user_id);

                    $buyerWallet = Wallet::where('user_id', $buySideOrder->user_id)->where('currency_id', $buySideOrder->pair->coin->id)->spot()->first();
                    $sellerWallet = Wallet::where('user_id', $buySideOrder->user_id)->where('currency_id', $buySideOrder->pair->market->currency_id)->spot()->first();


//                    if ($sellerWallet->balance < $buySideOrder->total) {
//                        continue;
//                    }

                    $buySideOrder = $this->updateOrder($buySideOrder, $tradeAmount);


                    //Add Buy Token Amount
                    $addedBuyTokenAmount = $buyerWallet->balance + $buySideOrder->amount;
                    $buyerWallet->balance = $addedBuyTokenAmount;
                    $buyerWallet->save();

                    $details = showAmount($tradeAmount, currencyFormat: false) . ' ' . $buySideOrder->pair->coin->symbol . ' Buy completed on pair ' . $buySideOrder->pair->symbol;
                    $this->createTrx($buySideOrder, $buyerWallet, $tradeAmount, 'trade_buy', 0, $details, $tradeAmount, "Buy");

                }
            }


            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::info($ex->getMessage());
        }

    }

    public function tradeSell()
    {
        DB::beginTransaction();


        try {

            $sellSideOrders = Order::with('pair', 'user')
                ->sellSideOrder()
                ->open()
//                ->whereDate('created_at', ">=", now()->subMinutes(10))
                ->orderBy('id', 'ASC')
                ->get();

            $tradeSideSell = Status::SELL_SIDE_ORDER;


            //Sell Side Orders
            if ($sellSideOrders->isNotEmpty()) {
                foreach ($sellSideOrders as $sellSideOrder) {

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
                        $deductSoledToken = $buyerWallet->balance - $sellSideOrder->amount;
                        $buyerWallet->balance = $deductSoledToken;
                        $buyerWallet->save();


                        $this->createTrade($tradeSideSell, $sellSideOrder, $rate, $tradeAmount, $sellSideOrder->user_id, $charge);
                        $this->updateOrder($sellSideOrder, $tradeAmount);


                        $details = showAmount($tradeAmount, currencyFormat: false) . ' ' . $sellSideOrder->pair->coin->symbol . ' Sell completed on pair ' . $sellSideOrder->pair->symbol;
                        $this->createTrx($sellSideOrder, $SellSideSellerWallet, $totalSellingAmount, 'trade_sell', $charge, $details, $tradeAmount, "Sell");

                    } catch (\Exception $eeex) {

                    }
                }
            }

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::info($ex->getMessage());
        }
    }


    private function createTrx($order, $wallet, $amount, $remark, $charge = 0, $details, $tradeAmount, $orderSide)
    {
//        $wallet->balance += $amount;
//        $wallet->save();

        if ($orderSide = 'Sell') {
//            dd($wallet->balance, $amount);
        }

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



//        if (gs('trade_commission')) {
//            levelCommission($order->user, $tradeAmount, 'trade_commission', $order->trx, $order->coin_id);
//        }
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


}
