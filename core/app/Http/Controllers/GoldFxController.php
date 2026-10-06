<?php

namespace App\Http\Controllers;

use App\Models\CoinStack;
use App\Models\CoinStackTransaction;
use App\Models\CopyTrade;
use App\Models\CopyTransaction;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\LaunchPad;
use App\Models\Pool;
use App\Models\Reward;
use App\Models\RewardHistory;
use App\Models\UserCopyTrade;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GoldFxController extends Controller
{


    public function copyTradeDetails(Request $request)
    {

        $customer = auth()->user();

        $currency = Currency::where('symbol', 'USDT')->first();
        if (!$currency) {
            return response()->json(['status' => 'failed', 'message' => 'Can not buy stock at this moment']);
        }


        $user_wallet = Wallet::where('user_id', $customer->id)->where('currency_id', $currency->id)->first();
        if (!$user_wallet) {
            return response()->json(['status' => 'failed', 'message' => 'Can not buy stock at this moment']);
        }


        $copyTrade = CopyTrade::where('id', $request->id)->where('status', 'active')->first();
        if (!$copyTrade) {
            return response()->json(['status' => 'failed', 'message' => 'This trade is no longer active']);
        }

        $interestType = 0;
        if ($copyTrade->type == 'daily') {
            $interestType = '1D';
        } else if ($copyTrade->type == 'weekly') {
            $interestType = '7D';
        } else if ($copyTrade->type == 'monthly') {
            $interestType = '30D';
        } else if ($copyTrade->type == 'yearly') {
            $interestType = '365D';
        }

        $final_interest = ($copyTrade->amount * $copyTrade->interest) / 100;

        $detailsData = [
            'current_balance' => $user_wallet->balance,
            'interest_type' => strtoupper($copyTrade->type),
            'interest' => $copyTrade->interest,
            'cost' => number_format($copyTrade->amount, 4),
            'description' => $copyTrade->short_details,
            'final_interest' => $final_interest,
        ];


        return response()->json(['status' => 'success', 'data' => $detailsData]);

    }

    public function index()
    {
        $data['pageTitle'] = "GoldFX Trading";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'gold_fx')->orderBy('created_at', 'desc')->get();

        $data['goldfx_trades'] = UserCopyTrade::where('trade_type', 'gold_fx')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();


        return view('Template::user.copy_trading.gold_fx', $data);

    }

    public function otcTrading()
    {
        $data['pageTitle'] = "OTC Trading";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.otc_trading', $data);

    }

    public function puzzleHunt()
    {
        $data['pageTitle'] = "Puzzle Hunt";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'puzzle_hunt')->orderBy('created_at', 'desc')->get();

        $data['puzzle_hunt_trades'] = UserCopyTrade::where('trade_type', 'puzzle_hunt')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.puzzle_hunt', $data);
    }

    public function photo_gallery()
    {

        $data['pageTitle'] = "Photos Gallery";

        return view('Template::user.copy_trading.photo_gallery', $data);
    }


    public function tokenSplash()
    {
        $data['pageTitle'] = "Token Splash";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'token_splash')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'token_splash')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.token_splash', $data);

    }

    public function userTokenSplash()
    {
        $data['pageTitle'] = "Token Splash";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'token_splash')->orderBy('created_at', 'desc')->get();

        $data['token_splash_trades'] = UserCopyTrade::where('trade_type', 'token_splash')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.user_token_splash', $data);

    }

    public function byVotes()
    {
        $data['pageTitle'] = "By Votes";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'by_votes')->orderBy('created_at', 'desc')->get();

        $data['by_votes_buy'] = UserCopyTrade::where('trade_type', 'by_votes')->where('status', 'buy')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $data['by_votes_sell'] = UserCopyTrade::where('trade_type', 'by_votes')->where('status', 'sell')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.by_votes', $data);

    }

    public function launchPool()
    {
        $data['pageTitle'] = "Launch Pool";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        $data['pools'] = Pool::orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.launchpool', $data);

    }
    public function launchpool_history()
    {
        $data['pageTitle'] = "Launch Pool";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        $data['pools_histories'] =CoinStack::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $data['total_invest'] =CoinStack::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->sum('invest_amount');

        return view('Template::user.copy_trading.launchpool_history', $data);

    }

    public function launchpool_trx()
    {
        $data['pageTitle'] = "Launch Pool";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        $data['transactions'] =CoinStackTransaction::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $data['total_invest'] =CoinStack::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->sum('invest_amount');

        return view('Template::user.copy_trading.launchpool_trx', $data);

    }

    public function buyPoolCoin(Request $request)
    {

        DB::beginTransaction();

        try {
            $pool = Pool::where('id', $request->pool_id)->first();
            if (!$pool) {
                return redirect()->back()->with('error', 'Coin not found');
            }

            if (!$request->invest_amount) {
                return redirect()->back()->with('error', 'Enter valid investment amount');
            }

            $currency = Currency::where('symbol', 'USDT')->first();
            if (!$currency) {
                return redirect()->back()->with('error', 'Currency not found');
            }

            $user = auth()->user();
            $wallet = Wallet::where('wallet_type', '1')->where('currency_id', $currency->id)->first();
            if (!$wallet) {
                return redirect()->back()->with('error', 'Wallet not found');
            }


            if ($wallet->balance < $request->invest_amount) {
                return redirect()->back()->with('error', 'Insufficient balance');
            }


            //Wallet Minus
            $deductAmount = $wallet->balance - $request->invest_amount;
            $wallet->balance = $deductAmount;
            $wallet->save();


            $coinStack = new CoinStack();
            $coinStack->user_id = $user->id;
            $coinStack->pool_id = $pool->id;
            $coinStack->price = $pool->price;
            $coinStack->invest_amount = $request->invest_amount;
            $coinStack->interest = $pool->interest;
            $coinStack->payment_date = now()->addDays(30);
            $coinStack->save();


            $coinStackTrx = new CoinStackTransaction();
            $coinStackTrx->user_id = $user->id;
            $coinStackTrx->stack_id = $coinStack->id;
            $coinStackTrx->pool_id = $pool->id;
            $coinStackTrx->amount = $request->invest_amount;
            $coinStackTrx->type = 'buy';
            $coinStackTrx->save();


            DB::commit();
            return redirect()->back()->with('success', 'Coin buy success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function spotX()
    {
        $data['pageTitle'] = "Spot X";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'spot_x')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.spotx', $data);

    }

    public function userSpotX()
    {
        $data['pageTitle'] = "Spot X";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'spot_x')->orderBy('created_at', 'desc')->get();

        $data['spot_x_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.user_spotx', $data);

    }

    public function launchPad()
    {
        $data['pageTitle'] = "LaunchPad";
        $data['laundpads'] = LaunchPad::orderByDesc('created_at')->get();


        return view('Template::user.copy_trading.launchpad', $data);

    }

    public function mt5()
    {
        $data['pageTitle'] = "MT-5";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'spot_x')->orderBy('created_at', 'desc')->get();

        $data['spot_x_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.mt_5', $data);

    }

    public function getFaq(Request $request)
    {

        $faqs = Faq::where('status', 'active');

        if ($request->name) {
            $faqs->where('question', 'like', '%' . $request->name . '%');
        }
        $faqs = $faqs->get();

        $data = [];
        foreach ($faqs as $faq) {
            $data[] = [
                'question' => $faq->question,
                'answer' => $faq->answer,
            ];
        }

        return response()->json(['data' => $data, 'status' => 'success']);

    }

    public function tradeGpt()
    {
        $data['pageTitle'] = "Trade Gpt";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'spot_x')->orderBy('created_at', 'desc')->get();

        $data['spot_x_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.trade_gpt', $data);

    }

    public function treasureHunt()
    {
        $data['pageTitle'] = "Treasure Hunt";

        $reward = Reward::where('user_id', auth()->user()->id)->first();

        if (!$reward) {
            $reward = new Reward();
            $reward->user_id = auth()->user()->id;
            $reward->save();
        }

        $data['reward'] = $reward;

        return view('Template::user.copy_trading.tresure_hunt', $data);
    }


    public function collectReward()
    {

//        $reward = Reward::where('user_id', auth()->user()->id)->first();
//
//        if (!$reward) {
//            $reward = new Reward();
//            $reward->user_id = auth()->user()->id;
//            $reward->coin = 50;
//            $reward->next_reward_date = now()->addDays(1);
//            $reward->save();
//        } else {
//            $reward->coin = $reward->coin + 50;
//            $reward->next_reward_date = now()->addDays(1);
//            $reward->save();
//        }
//
//        $reward_history = new RewardHistory();
//        $reward_history->reward_id = $reward->id;
//        $reward_history->user_id = $reward->user_id;
//        $reward_history->coin = 50;
//        $reward_history->save();


        $notifyS[] = ['success', 'Congratulations! Now you can use stock feature'];
        return redirect()->route('user.treasure.hunt')->withNotify($notifyS);
    }


    public function rewardHub()
    {
        $data['pageTitle'] = "Reward Hub";
        $reward = Reward::where('user_id', auth()->user()->id)->first();

        if (!$reward) {
            $reward = new Reward();
            $reward->user_id = auth()->user()->id;
            $reward->save();
        }

        $data['reward'] = $reward;

        return view('Template::user.copy_trading.reward_hub', $data);

    }

    public function leaderBoard()
    {
        $data['pageTitle'] = "Leader Board";

        $data['rewards'] = Reward::orderByDesc('coin')->get();

        return view('Template::user.copy_trading.leaderboard', $data);

    }

    public function copyTradeHistory(Request $request)
    {
        $data['pageTitle'] = "Copy Trade History";

        $transactions = CopyTransaction::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc');

        if ($request->trade_type) {
            $transactions = $transactions->where('trade_type', $request->trade_type);
        }

        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }

        if ($request->filter_date) {
            $dates = explode('-', $request->filter_date);
            $startDate = isset($dates['0']) ? $dates['0'] : now()->subDays(7);
            $endDate = isset($dates['1']) ? $dates['1'] : now();
            $transactions = $transactions->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data['transactions'] = $transactions->paginate(20);


        return view('Template::user.copy_trading.copy_transactions', $data);

    }


    public function rewardHistory()
    {
        $data['pageTitle'] = "Reward History";

        $data['rewards'] = RewardHistory::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(getPaginate());

        return view('Template::user.copy_trading.reward_history', $data);

    }

    public function inviteFriend()
    {
        $data['pageTitle'] = "Invite Friend";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.invite_friend', $data);

    }

    public function affiliate()
    {
        $data['pageTitle'] = "Affiliate";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.affiliate', $data);

    }

    public function helpCenter()
    {
        $data['pageTitle'] = "Help Center";
        $data['trades'] = CopyTrade::where('status', 'active')->where('trade_type', 'classic')->orderBy('created_at', 'desc')->get();

        $data['classic_trades'] = UserCopyTrade::where('trade_type', 'classic')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return view('Template::user.copy_trading.help_center', $data);

    }


}
