<?php

namespace App\Job;


use App\Models\Currency;
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


class SendStateMentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    private $user_id;
    private $email;
    private $fullname;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($user_id, $email, $fullname)
    {
        $this->user_id = $user_id;
        $this->email = $email;
        $this->fullname = $fullname;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $user_id = $this->user_id;

            // sendMail($email, $subject, $template)

            $totalTransfer = Transaction::where('user_id', $user_id)->where('trx_type', '-')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->where('remark', 'transfer')
                ->sum('amount');

            $totalReceived = Transaction::where('user_id', $user_id)->where('trx_type', '+')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->where('remark', 'transfer')
                ->sum('amount');

            $totalOrderBuy = Transaction::where('user_id', $user_id)->where('trx_type', '-')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->where('remark', 'order_buy')
                ->sum('amount');

            $totalOrderSell = Transaction::where('user_id', $user_id)->where('trx_type', '-')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->where('remark', 'trade_sell')
                ->sum('amount');

            $totalWithdraw = Transaction::where('user_id', $user_id)->where('trx_type', '-')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->where('remark', 'withdraw')
                ->sum('amount');

            //TODO::Section STock Transaction
            $totalStockBuy = StockTransaction::where('user_id', $user_id)->where('type', 'buy')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->sum('amount');

            $totalStockSell = StockTransaction::where('user_id', $user_id)->where('type', 'sell')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->sum('amount');

            $totalStockExchange = StockTransaction::where('user_id', $user_id)->where('type', 'exchange')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->sum('amount');

            $totalStockInterest = StockTransaction::where('user_id', $user_id)->where('type', 'interest')
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->sum('amount');

            $message = "
                        Hello {$this->fullname},

                        Here’s your weekly analytics report for the last 7 days:

                        **Transaction Summary:**
                        - Total Transfers Sent: {$totalTransfer}
                        - Total Transfers Received: {$totalReceived}
                        - Total Orders (Buy): {$totalOrderBuy}
                        - Total Orders (Sell): {$totalOrderSell}
                        - Total Withdrawals: {$totalWithdraw}

                        **Stock Transactions:**
                        - Total Stock Purchased: {$totalStockBuy}
                        - Total Stock Sold: {$totalStockSell}
                        - Total Stock Exchanges: {$totalStockExchange}
                        - Total Stock Interest Earned: {$totalStockInterest}

                        If you have any questions or need further details, feel free to reach out.
                    ";


            sendMail($this->email, 'Weekly Transaction Analytics Report', $message);
        } catch (\Exception $ex) {
        }
    }

    public function failed(\Exception $exception) {}
}
