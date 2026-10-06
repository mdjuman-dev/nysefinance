<?php

namespace App\Job;


use App\Constants\Status;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\StockTransaction;
use App\Models\StockWallet;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcessTrx implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $deposit_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($deposit_id)
    {
        $this->deposit_id = $deposit_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $deposit_id = $this->deposit_id;

        $deposit = Deposit::where('id', $deposit_id)->first();

        if ($deposit) {


            $query = [
                'function' => 'get-transaction',
                'transaction_id' => $deposit->foren_id,
            ];


            $ch = curl_init('https://talkgfx.top/api.php');
            $parameters = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POST => true,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_POSTFIELDS => http_build_query(array_merge(['api-key' => env('BOX_KEY')], $query))
            ];
            curl_setopt_array($ch, $parameters);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = $response ? json_decode($response) : '';


            if (isset($response->response) && $response->response) {

                $finalResponse = $response->response;



                if (isset($finalResponse->creation_time) && isset($finalResponse->amount_fiat) && isset($finalResponse->status) && $finalResponse->status == 'C') {

                    $carbonTime = Carbon::parse($finalResponse->creation_time);


                    // dd($carbonTime, $finalResponse->creation_time);


                    $depositReq = Deposit::where('id', $deposit->id)
                    // ->where('created_at', $carbonTime)
                    ->where('status', '2')
                    ->whereRaw('ROUND(final_amount, 4) = ?', [round($finalResponse->amount_fiat, 4)])
                    ->first();



                    if ($depositReq) {

                        $currency = Currency::active()->where('symbol', 'USDT')->first();

                        if ($currency) {

                            $depositReq->status = '1';
                            $depositReq->save();

                            $wallet = Wallet::where('currency_id', $currency->id)->where('user_id', $depositReq->user_id)->first();
                            if ($wallet) {

                                $wallet->balance += $depositReq->amount;
                                $wallet->save();

                                $ttrx = Str::random(15);

                                $transaction = new Transaction();
                                $transaction->user_id = $depositReq->user_id;
                                $transaction->wallet_id = $wallet->id;
                                $transaction->amount = $depositReq->amount;
                                $transaction->post_balance = $wallet->balance;
                                $transaction->charge = $depositReq->charge;
                                $transaction->trx_type = '+';
                                $transaction->details = 'Gateway TRX:' . $ttrx;
                                $transaction->trx = $depositReq->trx;
                                $transaction->remark = 'deposit';
                                $transaction->save();
                            }
                        }
                    }
                }

            }


        }

    }



    public function failed(\Exception $exception) {}
}
