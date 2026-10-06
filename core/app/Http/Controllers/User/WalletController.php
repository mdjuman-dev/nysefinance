<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\BondWallet;
use App\Models\CopyTransaction;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\StockTransaction;
use App\Models\StockTransfer;
use App\Models\StockWallet;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserReport;
use App\Models\UserStock;
use App\Models\VerifyOtp;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function list($type = null)
    {
        $types = (array) gs('wallet_types');
        if (!array_key_exists($type, $types)) {
            $notify[] = ['error', "Invalid URL"];
            return back()->withNotify($notify);
        }
        $typeValue = ((array) $types[$type])['type_value'];

        $wallets = $this->walletQuery()->groupBy('wallets.id')->where('wallets.wallet_type', $typeValue)->orderBy('balance', 'desc')->paginate(getPaginate());
        $estimated = Wallet::where('user_id', auth()->id())->where('wallets.wallet_type', $typeValue)->join('currencies', 'wallets.currency_id', 'currencies.id')->sum(DB::raw('currencies.rate * wallets.balance'));

        return $this->renderWalletPage($type, $estimated, $wallets, $typeValue == Status::WALLET_TYPE_SPOT ? 'spot' : 'funding');
    }

    public function stock_wallet($type = null)
    {
        $wallet = StockWallet::where('user_id', auth()->id())->first();
        if (!$wallet) {
            $notify[] = ['error', "Something went wrong"];
            return back()->withNotify($notify);
        }
        return $this->renderHoldingsWallet('stock', (float) $wallet->amount);
    }

    public function bond_wallet($type = null)
    {
        $wallet = BondWallet::where('user_id', auth()->id())->first();
        if (!$wallet) {
            $notify[] = ['error', "Something went wrong"];
            return back()->withNotify($notify);
        }
        return $this->renderHoldingsWallet('bond', (float) $wallet->amount);
    }

    public function copy_wallet($type = null)
    {
        $userId = auth()->id();
        // One grouped query instead of three separate sums.
        $totals = CopyTransaction::where('user_id', $userId)->whereIn('type', ['buy', 'sell', 'interest'])
            ->groupBy('type')->selectRaw('type, SUM(amount) as total')->pluck('total', 'type');
        $interests = CopyTransaction::where('user_id', $userId)->where('type', 'interest')->with('trade:id,name')->latest('id')->paginate(getPaginate());

        return \Inertia\Inertia::render('User/Wallet/Copy', [
            'tabs'   => $this->walletTabs('copy'),
            'frozen' => $this->hasOpenReport($userId),
            'totals' => ['buy' => (float) ($totals['buy'] ?? 0), 'sell' => (float) ($totals['sell'] ?? 0), 'interest' => (float) ($totals['interest'] ?? 0)],
            'interests' => \App\Support\InertiaData::paginate($interests, fn ($i) => [
                'id'     => $i->id,
                'name'   => $i->trade->name ?? '',
                'amount' => (float) $i->amount,
                'date'   => \App\Support\InertiaData::date($i->created_at),
            ]),
        ]);
    }

    /* ---------- shared Vue wallet pages ---------- */

    private function walletTabs(string $active): array
    {
        $tabs = [
            'overview' => ['Overview', route('user.wallet.overview')],
            'spot'     => ['Spot', route('user.wallet.list', 'spot')],
            'funding'  => ['Funding', route('user.wallet.list', 'funding')],
            'stock'    => ['Stock', route('user.wallet.stock')],
            'bond'     => ['Bond', route('user.wallet.bond')],
            'copy'     => ['Copy', route('user.wallet.copy')],
        ];
        return collect($tabs)->map(fn ($t, $key) => ['key' => $key, 'label' => $t[0], 'href' => $t[1], 'active' => $key === $active])->values()->all();
    }

    private function hasOpenReport(int $userId): bool
    {
        return UserReport::where(fn ($q) => $q->where('user_id', $userId)->orWhere('repoted_user_id', $userId))
            ->whereIn('status', ['pending', 'review'])->exists();
    }

    /** Overview / Spot / Funding all use the same Vue page. */
    private function renderWalletPage(string $tab, $estimated, $wallets, string $transferType)
    {
        $gateways = GatewayCurrency::whereHas('method', fn ($gate) => $gate->where('status', Status::ENABLE))->get(['method_code', 'currency', 'name']);
        $withdrawMethods = WithdrawMethod::active()->get();

        return \Inertia\Inertia::render('User/Wallet/Overview', [
            'tab'       => $tab,
            'tabs'      => $this->walletTabs($tab),
            'estimated' => (float) $estimated,
            'frozen'    => $this->hasOpenReport(auth()->id()),
            'wallets'   => \App\Support\InertiaData::paginate($wallets, fn ($w) => [
                'id'      => $w->id,
                'symbol'  => $w->currency->symbol ?? '',
                'name'    => $w->currency->name ?? '',
                'sign'    => $w->currency->sign ?? '',
                'image'   => $w->currency->image_url ?? null,
                'type'    => $w->typeText,
                'balance' => (float) $w->balance,
                'inOrder' => isset($w->in_order) ? (float) $w->in_order : null,
                'url'     => $w->wallet_type == Status::WALLET_TYPE_FUTURE
                    ? route('futures')
                    : route('user.wallet.view', ['type' => $w->typeText, 'currencySymbol' => $w->currency->symbol ?? '']),
            ]),
            'gateways' => $gateways->map(fn ($g) => ['code' => (string) $g->method_code, 'currency' => $g->currency, 'automatic' => $g->method_code < 1000, 'name' => $g->name])->values(),
            'withdrawMethods' => $withdrawMethods->map(fn ($m) => [
                'id'       => $m->id,
                'name'     => __($m->name),
                'currency' => $m->currency,
                'formId'   => $m->form_id,
                'min'      => (float) $m->min_limit,
                'max'      => (float) $m->max_limit,
                'fixed'    => (float) $m->fixed_charge,
                'percent'  => (float) $m->percent_charge,
            ])->values(),
            'open' => request('sc') == 'tr' ? 'deposit' : (request('wh') == 'wi' ? 'withdraw' : null),
            'urls' => [
                'depositInsert'  => route('user.deposit.insert'),
                'withdrawSubmit' => route('user.withdraw.submit'),
                'currencies'     => route('user.currency.all'),
                'formLoad'       => route('user.withdraw.form.load'),
                'coinBalance'    => route('user.wallet.get.coin.balance'),
                'sendOtp'        => route('user.send.otp'),
                'convert'        => route('user.convert'),
                'transfer'       => route('user.wallet.view', ['type' => $transferType, 'currencySymbol' => 'USDT', 'fm' => 'transfer']),
            ],
        ]);
    }

    /** Stock / Bond wallet: balance, totals and the user's holdings. */
    private function renderHoldingsWallet(string $useFor, float $available)
    {
        $userId = auth()->id();

        // One query for every total the page shows (was five).
        $t = UserStock::where('user_id', $userId)->where('use_for', $useFor)->selectRaw("
            SUM(CASE WHEN status = 'buy' THEN invest_amount ELSE 0 END) AS total_buy,
            SUM(CASE WHEN status = 'sell' THEN invest_amount ELSE 0 END) AS total_sell,
            SUM(CASE WHEN status = 'buy' AND type = 'fix' THEN invest_amount ELSE 0 END) AS total_mutual,
            SUM(CASE WHEN status = 'buy' AND type = 'unfix' THEN invest_amount ELSE 0 END) AS total_live
        ")->first();
        // The Blade bond page summed *stock* interest here; use the matching wallet's interest.
        $earned = StockTransaction::where('user_id', $userId)->where('type', 'interest')->where('use_for', $useFor)->sum('amount');

        $holdings = UserStock::where('user_id', $userId)->where('use_for', $useFor)->with('product:id,name,image,stock_code')->latest('id')->paginate(20);
        $interest = StockTransaction::where('type', 'interest')->whereIn('stock_id', collect($holdings->items())->where('type', 'fix')->pluck('id'))
            ->groupBy('stock_id')->selectRaw('stock_id, SUM(amount) as total')->pluck('total', 'stock_id');

        return \Inertia\Inertia::render('User/Wallet/Holdings', [
            'kind'      => $useFor,
            'tabs'      => $this->walletTabs($useFor),
            'frozen'    => $this->hasOpenReport($userId),
            'available' => $available,
            'stats' => [
                'earned' => (float) $earned,
                'buy'    => (float) ($t->total_buy ?? 0),
                'sell'   => (float) ($t->total_sell ?? 0),
                'mutual' => (float) ($t->total_mutual ?? 0),
                'live'   => (float) ($t->total_live ?? 0),
            ],
            'holdings' => \App\Support\InertiaData::paginate($holdings, fn ($h) => [
                'id'         => $h->id,
                'name'       => $h->product->name ?? '',
                'code'       => $h->product->stock_code ?? null,
                'image'      => getImage(getFilePath('currency') . '/' . ($h->product->image ?? ''), getFileSize('currency')),
                'fixed'      => $h->type == 'fix',
                'invest'     => (float) $h->invest_amount,
                'stackPrice' => (float) $h->stack_price,
                'interest'   => $h->type == 'fix' ? (float) ($interest[$h->id] ?? 0) : null,
                'status'     => $h->status,
            ]),
            // Only the stock wallet has a transfer endpoint (it moves from the stock wallet to spot USDT).
            'transferCharge' => (float) (gs('stock_transfer_charge') ?: 5),
            'urls' => [
                'transfer'  => $useFor === 'stock' ? route('user.stock.wallet.transfer') : null,
                'history'   => $useFor === 'stock' ? route('user.stock.transfer.history') : route('user.bond.transactions'),
                'livePrice' => route('user.stock.live.price'),
                'my'        => $useFor === 'stock' ? route('user.stock.my') : route('user.my.bonds'),
            ],
        ]);
    }

    public function overview()
    {
        $user = auth()->user();
        if ($user->kv != '1') {
            return redirect()->route('user.kyc.form')->withErrors(['error' => 'Please Verify KYC Data']);
        }

        $estimated = Wallet::where('user_id', $user->id)->join('currencies', 'wallets.currency_id', 'currencies.id')->sum(DB::raw('currencies.rate * wallets.balance'));
        $wallets = Wallet::where('user_id', $user->id)->with('currency')->orderBy('balance', 'desc')->paginate(getPaginate());

        // The Blade page's Transfer button pointed at the funding wallet here (its $typeValue == 1 check was false for 'spot').
        return $this->renderWalletPage('overview', $estimated, $wallets, 'funding');
    }

    public function transferUser(Request $request)
    {

        if($request->type && $request->type=='uid'){
            $user=User::where('uid', $request->uid)->first();

            if(auth()->user()->uid==$request->uid){
                return response()->json(['status'=> 'failed', 'message'=> 'You cannot transfer to yourself']);
            }


        }else{
            $user=User::where('username', $request->username)->orWhere('email', $request->username)->first();
        }

        if(!$user){
            return response()->json(['status'=> 'failed', 'message' => 'Invalid Receiver']);
        }



        return response()->json(['status'=>'success','fullname'=>$user->fullname,'email'=>$user->email]);

    }

    public function view($type, $curSymbol)
    {
        $user=auth()->user();
        $report=UserReport::where('user_id', $user->id)->whereIn('status', ['pending','review'])->first();
        $reportPtp=UserReport::where('repoted_user_id', $user->id)->whereIn('status', ['pending','review'])->first();
        if($report || $reportPtp){
            return redirect()->back()->withErrors(['error'=>'Your all transaction has been frozen. Wait  until your report solved']);
        }

        $types = (array) gs('wallet_types');
//        $type='spot';

        if (!array_key_exists($type, $types)) {
            $notify[] = ['error', "Invalid URL"];
            return back()->withNotify($notify);
        }

        list('title' => $pageTitle, 'type_value' => $typeValue, 'name' => $walletType) = (array) $types[$type];

        $currency  = Currency::where('symbol', $curSymbol)->firstOrFail();
        $pageTitle = $pageTitle . ": " . $currency->symbol;
        $wallet    = $this->walletQuery()->where('wallets.currency_id', $currency->id)->$type()->first();
        $user      = auth()->user();

        // Older accounts can miss a wallet row for a newer currency/type; open an empty one
        // instead of a 404 (the wallet pages' Transfer button links straight here).
        if (!$wallet?->id && $currency->status == Status::ENABLE) {
            Wallet::unguarded(fn () => Wallet::firstOrCreate(
                ['user_id' => $user->id, 'currency_id' => $currency->id, 'wallet_type' => $typeValue],
                ['balance' => 0]
            ));
            $wallet = $this->walletQuery()->where('wallets.currency_id', $currency->id)->$type()->first();
        }

        abort_if(!$wallet?->id, 404);

        $trxQuery     = Transaction::where('wallet_id', $wallet->id)->with('wallet.currency');
        $transactions = (clone $trxQuery)->latest('id')->paginate(getPaginate());
        $orderQuery   = Order::where('user_id', $user->id);
        $orderQuery   = currencyWiseOrderQuery($orderQuery, $currency);

        $widget['total_order']     = (clone $orderQuery)->count();
        $widget['open_order']      = (clone $orderQuery)->open()->count();
        $widget['completed_order'] = (clone $orderQuery)->completed()->count();
        $widget['canceled_order']  = (clone $orderQuery)->canceled()->count();

        $widget['total_deposit']     = Deposit::successful()->where('wallet_id', $wallet->id)->sum('amount');
        $widget['total_withdraw']    = Withdrawal::approved()->where('wallet_id', $wallet->id)->sum('amount');
        $widget['total_transaction'] = (clone $trxQuery)->count();

        $widget['total_trade'] = Trade::where('trader_id', $user->id)->whereHas('order', function ($q) use ($currency) {
            $q = currencyWiseOrderQuery($q, $currency);
        })->count();

        $gateways        = GatewayCurrency::where('currency', $curSymbol)->whereHas('method', fn ($gate) => $gate->active())->get(['method_code', 'currency', 'name']);
        $withdrawMethods = WithdrawMethod::active()->where('currency', $curSymbol)->get();
        $config          = (array) (((array) $types[$type])['configuration'] ?? []);
        $enabled         = fn ($key) => (bool) (@$config[$key]->status);
        $sym             = $currency->symbol;

        return \Inertia\Inertia::render('User/Wallet/Detail', [
            'type'      => $type,
            'typeTitle' => __($pageTitle),
            'wallet'    => [
                'id'      => $wallet->id,
                'symbol'  => $sym,
                'name'    => $currency->name,
                'sign'    => $currency->sign,
                'image'   => $currency->image_url,
                'rate'    => (float) $currency->rate,
                'balance' => (float) $wallet->balance,
                'inOrder' => (float) ($wallet->in_order ?? 0),
            ],
            'stats' => [
                ['label' => 'Open orders', 'value' => (int) $widget['open_order'], 'icon' => 'ri-time-line', 'href' => route('user.order.open') . '?currency=' . $sym],
                ['label' => 'Completed orders', 'value' => (int) $widget['completed_order'], 'icon' => 'ri-checkbox-circle-line', 'href' => route('user.order.completed') . '?currency=' . $sym],
                ['label' => 'Canceled orders', 'value' => (int) $widget['canceled_order'], 'icon' => 'ri-close-circle-line', 'href' => route('user.order.canceled') . '?currency=' . $sym],
                ['label' => 'Total trades', 'value' => (int) $widget['total_trade'], 'icon' => 'ri-exchange-line', 'href' => route('user.trade.history')],
                ['label' => 'Total deposited', 'value' => (float) $widget['total_deposit'], 'money' => true, 'icon' => 'ri-download-2-line', 'href' => route('user.deposit.history') . '?search=' . $sym],
                ['label' => 'Total withdrawn', 'value' => (float) $widget['total_withdraw'], 'money' => true, 'icon' => 'ri-upload-2-line', 'href' => route('user.withdraw.history') . '?search=' . $sym],
            ],
            'transactions' => \App\Support\InertiaData::paginate($transactions, fn ($t) => [
                'id'      => $t->id,
                'trx'     => $t->trx,
                'type'    => $t->trx_type,
                'amount'  => (float) $t->amount,
                'charge'  => (float) $t->charge,
                'post'    => (float) $t->post_balance,
                'details' => __($t->details),
                'date'    => \App\Support\InertiaData::date($t->created_at),
            ]),
            'totalTransactions' => (int) $widget['total_transaction'],
            'can' => [
                'deposit'        => $enabled('deposit') && $gateways->isNotEmpty(),
                'withdraw'       => $enabled('withdraw') && $withdrawMethods->isNotEmpty(),
                'transferUser'   => $enabled('transfer_other_user'),
                'transferWallet' => $enabled('transfer_other_wallet'),
            ],
            'transfer' => [
                'userCharge'   => (float) gs('other_user_transfer_charge'),
                'walletCharge' => (float) gs('other_wallet_transfer_charge'),
                'currencyId'   => $currency->id,
                'otherWallets' => collect($types)->reject(fn ($w, $k) => $k === $type)
                    ->map(fn ($w, $k) => ['value' => $k, 'label' => __(((array) $w)['title'] ?? ucfirst($k))])->values(),
            ],
            'open'     => request('fm') === 'transfer' ? 'transfer' : null,
            'gateways' => $gateways->map(fn ($g) => ['code' => (string) $g->method_code, 'currency' => $g->currency, 'automatic' => $g->method_code < 1000, 'name' => $g->name])->values(),
            'withdrawMethods' => $withdrawMethods->map(fn ($m) => [
                'id'       => $m->id,
                'name'     => __($m->name),
                'currency' => $m->currency,
                'formId'   => $m->form_id,
                'min'      => (float) $m->min_limit,
                'max'      => (float) $m->max_limit,
                'fixed'    => (float) $m->fixed_charge,
                'percent'  => (float) $m->percent_charge,
            ])->values(),
            'urls' => [
                'back'           => route('user.wallet.list', $type),
                'transactions'   => route('user.transactions') . '?symbol=' . $sym . '&wallet_type=' . $type,
                'trade'          => $sym === 'USDT' ? route('trade') : route('trade', $sym . '_USDT'),
                'depositInsert'  => route('user.deposit.insert'),
                'withdrawSubmit' => route('user.withdraw.submit'),
                'currencies'     => route('user.currency.all'),
                'formLoad'       => route('user.withdraw.form.load'),
                'coinBalance'    => route('user.wallet.get.coin.balance'),
                'sendOtp'        => route('user.send.otp'),
                'transferUser'   => route('user.wallet.transfer'),
                'transferWallet' => route('user.wallet.transfer.to.other.wallet'),
                'findUser'       => route('user.wallet.find.transfer.user'),
            ],
        ]);
    }

    public function transfer(Request $request)
    {
        $user=auth()->user();
        $report=UserReport::where('user_id', $user->id)->whereIn('status', ['pending','review'])->first();
        $reports=UserReport::where('repoted_user_id', $user->id)->whereIn('status', ['pending','review'])->first();

        if($report || $reports){
            return redirect()->back()->withErrors(['error'=>'Your all transaction has been frozen. Wait  until your report solved']);
        }

        // $otp=VerifyOtp::where('user_id', auth()->user()->id)->where('type', 'transfer')->whereDate('created_at', now())->where('otp', $request->transfer_otp)->first();
        // if(!$otp){
        //     $notify[] = ['error', 'Requested transfer otp is invalid'];
        //     return back()->withNotify($notify);
        // }


        $walletTypes = gs('wallet_types');

        $request->validate([
            'transfer_amount' => 'required|numeric|gte:0',
            'currency'        => 'required',
            // 'security_pin'        => 'required',
            'wallet_type'     => 'required|in:' . implode(',', array_keys((array) $walletTypes)),
        ]);

        $from = auth()->user();

        if(!$from->security_pin){
            $message = 'Configure your security pin from profile and try again';
            $notify[] = ['error', $message];
            return redirect()->back()->withNotify($notify);
        }
        if($from->security_pin != $request->security_pin){
            $message = 'Wrong Security Pin';
            $notify[] = ['error', $message];
            return redirect()->back()->withNotify($notify);
        }
//        if(!$request->uid || !$request->username){
//            $notify[] = ['error', 'Enter valid receiver username and password'];
//            return back()->withNotify($notify);
//        }


        $to   = User::active()->where('username', $request->username)->orWhere('uid', $request->uid)->first();
        if (!$to) {
            $notify[] = ['error', 'Receiver not found'];
            return back()->withNotify($notify);
        }
        $currency  = Currency::active()->where('id', $request->currency)->firstOrFail();
        $getAmount = getAmount($request->transfer_amount);

        if ($to->id == $from->id) {
            return returnBack("You can\t transfer $getAmount $currency->symbol to your own wallet");
        }

        $walletType = $request->wallet_type;

        if (!checkWalletConfiguration($walletType, 'transfer_other_user', $walletTypes)) {
            return returnBack("Transfer to $walletType wallet currently disabled.");
        }

        $fromWallet = Wallet::where('user_id', $from->id)->where('currency_id', $currency->id)->$walletType()->firstOrFail();
        $toWallet   = Wallet::where('user_id', $to->id)->where('currency_id', $currency->id)->$walletType()->firstOrFail();

        $amount        = $request->transfer_amount;
        $chargePercent = gs('other_user_transfer_charge');
        $chargeAmount  = ($amount / 100) * $chargePercent;
        $totalAmount   = $amount + $chargeAmount;

        if ($totalAmount > $fromWallet->balance) {
            return returnBack("You do not have sufficient balance for transfer.");
        }

        $trx     = getTrx();
        $details = "transfer $getAmount $currency->symbol to $to->username";
        $this->createTransferTrx($trx, $from, $fromWallet, $amount, "-", $details);

        notify($from, 'TRANSFER_MONEY', [
            'amount'      => showAmount($amount,currencyFormat:false),
            'charge'      => showAmount($chargeAmount,currencyFormat:false),
            'trx'         => $trx,
            'currency'    => @$currency->symbol,
            'to_username' => $to->username,
        ]);

        $details = "charge for transfer $getAmount $currency->symbol to $to->username";
        $this->createTransferTrx($trx, $from, $fromWallet, $chargeAmount, "-", $details);
        $this->createTransferTrx($trx, $to, $toWallet, $amount, "+", "received $getAmount $currency->symbol from  $from->username");

        notify($to, 'RECEIVED_MONEY', [
            'amount'        => showAmount($amount,currencyFormat:false),
            'charge'        => showAmount($chargeAmount,currencyFormat:false),
            'trx'           => $trx,
            'currency'      => @$currency->symbol,
            'from_username' => $from->username,
        ]);

        return returnBack("$getAmount $currency->symbol transfer successfully", 'success');
    }
    public function transferToWallet(Request $request)
    {
        // dd($request->all());

        $walletTypes         = gs('wallet_types');
        $walletTypesToString = implode(',', array_keys((array) $walletTypes));

        $request->validate([
            'transfer_amount' => 'required|numeric|gte:0',
            'currency'        => 'required',
            'from_wallet'     => 'required|in:' . $walletTypesToString,
            'to_wallet'       => 'required|different:from_wallet|in:' . $walletTypesToString,
        ]);

        $fromWalletType = $request->from_wallet;
        $toWalletType   = $request->to_wallet;
        $user           = auth()->user();

        $currency = Currency::where('id', $request->currency)->active()->firstOrFail();

        if (!checkWalletConfiguration($toWalletType, 'transfer_other_wallet', $walletTypes)) {
            return returnBack("Transfer to $toWalletType wallet currently disabled.");
        }

        // The destination wallet row may not exist yet for older accounts: open it empty.
        $toTypeValue = ((array) $walletTypes)[$toWalletType]->type_value;
        Wallet::unguarded(fn () => Wallet::firstOrCreate(
            ['user_id' => $user->id, 'currency_id' => $currency->id, 'wallet_type' => $toTypeValue],
            ['balance' => 0]
        ));

        $amount = $request->transfer_amount;

        // Lock both rows so two quick submits can't spend the same balance twice.
        $error = DB::transaction(function () use ($user, $currency, $fromWalletType, $toWalletType, $amount) {
            $fromWallet = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->$fromWalletType()->lockForUpdate()->firstOrFail();
            $toWallet   = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->$toWalletType()->lockForUpdate()->firstOrFail();

            if ($amount > $fromWallet->balance) {
                return "You do not have sufficient balance for transfer.";
            }

            $trx        = getTrx();
            $detailsOne = "Transfer " . getAmount($amount) . " " . $currency->symbol . " from the " . ucfirst($fromWalletType) . " wallet to the " . ucfirst($toWalletType) . 'Wallet';
            $detailsTwo = "Received " . getAmount($amount) . " " . $currency->symbol . " from the " . ucfirst($fromWalletType) . " Wallet";

            $this->createTransferTrx($trx, $user, $fromWallet, $amount, "-", $detailsOne);
            $this->createTransferTrx($trx, $user, $toWallet, $amount, "+", $detailsTwo);

            return null;
        });

        if ($error) {
            return returnBack($error);
        }

        return returnBack('Transfer successfully', 'success');
    }

    private function createTransferTrx($trx, $user, $wallet, $amount, $type, $details)
    {
        if ($type == '+') {
            $wallet->balance += $amount;
        } else {
            $wallet->balance -= $amount;
        }
        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = $type;
        $transaction->details      = $details;
        $transaction->trx          = $trx;
        $transaction->remark       = 'transfer';
        $transaction->save();
    }

    private function walletQuery()
    {
        return Wallet::with('currency')
            ->where('wallets.user_id', auth()->id())
            ->whereHas('currency', function ($q) {
                $q->active();
            })
            ->select('wallets.*')
            ->leftJoin('orders', function ($join) {
                $join->on('wallets.currency_id', '=', DB::raw('CASE WHEN orders.order_side = ' . Status::BUY_SIDE_ORDER . ' THEN orders.market_currency_id ELSE orders.coin_id END'))
                    ->where('orders.user_id', auth()->id())->where('orders.Status', Status::ORDER_OPEN);
            })
            ->selectRaw('CASE WHEN wallets.wallet_type =  ' . Status::WALLET_TYPE_FUNDING . ' THEN 0 ELSE SUM(CASE WHEN orders.order_side = ? THEN ((orders.amount-orders.filled_amount)*orders.rate) ELSE (orders.amount-orders.filled_amount) END) END as in_order', [Status::BUY_SIDE_ORDER]);
    }

    public function getCoinBalance(Request $request)
    {
        if(!$request->currency){
            return response()->json(['status'=>'failed']);
        }

        $currency=Currency::where('symbol', $request->currency)->first();

        if(!$currency){
            return response()->json(['status'=>'failed']);
        }

        $wallet=Wallet::where('currency_id', $currency->id)->where('user_id', auth()->user()->id)->where('wallet_type', '1')->first();
        if(!$wallet){
            return response()->json(['status'=>'failed']);
        }

        return response()->json(['status'=>'success', 'balance'=>$wallet->balance]);

    }
}
