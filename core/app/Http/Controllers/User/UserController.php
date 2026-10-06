<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Models\DeviceToken;
use App\Models\FavoritePair;
use App\Models\Form;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\StockMember;
use App\Models\StockWallet;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserStock;
use App\Models\Wallet;
use App\Models\Referral;
use App\Models\WithdrawMethod;
use App\Models\Blog;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Support\InertiaData;

class UserController extends Controller
{

    public function getAllCoins(Request $request)
    {

        $request['coin_name']=strtoupper($request['coin_name']);

        if($request->coin_name) {
            $coins = CoinPair::where('status', '1')->where('symbol', 'like', '%' . $request->coin_name . '%')->orderByDesc('created_at')->select(['id','created_at', 'symbol'])->get();
        }else {
            $coins = CoinPair::where('status', '1')->orderByDesc('created_at')->select(['id','created_at', 'symbol'])->get();
        }


        if($coins){
            return response()->json(['data'=>$coins, 'status' => 'success'], 200);
        }else{
            return response()->json(['data'=>[], 'status' => 'failed'], 200);
        }
    }


    public function moreService()
    {

        $data['pageTitle']='More Services';

        return view('Template::user.more_service', $data);

    }

    public function home(Request $request)
    {
        // ?legacy=1 (or the old ?type=) keeps the previous Blade dashboards reachable.
        if ($request->type || $request->legacy) {
            return $this->legacyHome($request);
        }

        $user = auth()->user();

        $order = Order::where('user_id', $user->id);

        $estimatedBalance = Wallet::where('user_id', $user->id)
            ->join('currencies', 'wallets.currency_id', 'currencies.id')
            ->sum(DB::raw('currencies.rate * wallets.balance'));

        $wallets = Wallet::where('user_id', $user->id)->spot()
            ->where('balance', '>', 0)
            ->with('currency:id,name,symbol,image,rate')
            ->orderBy('balance', 'desc')
            ->take(6)->get()
            ->map(fn ($w) => [
                'symbol' => $w->currency->symbol ?? '',
                'name'   => $w->currency->name ?? '',
                'image'  => $w->currency->image_url ?? null,
                'balance' => (float) $w->balance,
                'value'  => (float) $w->balance * (float) ($w->currency->rate ?? 0),
            ])
            ->sortByDesc('value')->values();

        $pairs = CoinPair::where('status', Status::ENABLE)->whereHas('marketData')
            ->select('id', 'coin_id', 'symbol')
            ->with('coin:id,name,symbol,image', 'marketData:id,pair_id,price,percent_change_24h,market_cap,volume_24h')
            ->get()
            ->map(fn ($p) => [
                'symbol'    => $p->symbol,
                'name'      => $p->coin->name ?? $p->symbol,
                'base'      => $p->coin->symbol ?? explode('_', $p->symbol)[0],
                'image'     => $p->coin->image_url ?? null,
                'price'     => (float) $p->marketData->price,
                'change24h' => (float) $p->marketData->percent_change_24h,
                'marketCap' => (float) $p->marketData->market_cap,
                'volume'    => (float) $p->marketData->volume_24h,
            ]);

        $sides = [Status::BUY_SIDE_ORDER => 'buy', Status::SELL_SIDE_ORDER => 'sell'];
        $types = [Status::ORDER_TYPE_LIMIT => 'Limit', Status::ORDER_TYPE_MARKET => 'Market', Status::ORDER_TYPE_STOP_LIMIT => 'Stop limit'];
        $statuses = [Status::ORDER_OPEN => 'open', Status::ORDER_COMPLETED => 'completed', Status::ORDER_PENDING => 'pending', Status::ORDER_POSITIONED => 'positioned', Status::ORDER_CANCELED => 'canceled'];

        return Inertia::render('User/Dashboard', [
            'balance' => [
                'estimated' => (float) $estimatedBalance,
                'stock'     => (float) StockWallet::where('user_id', $user->id)->sum('amount'),
                'currency'  => gs('cur_text'),
            ],
            'stats' => [
                'openOrders'      => (clone $order)->open()->count(),
                'completedOrders' => (clone $order)->completed()->count(),
                'canceledOrders'  => (clone $order)->canceled()->count(),
                'totalTrades'     => Trade::where('trader_id', $user->id)->count(),
            ],
            'wallets' => $wallets,
            'pairs'   => $pairs,
            'recentOrders' => (clone $order)->with('pair:id,symbol')->orderBy('id', 'DESC')->take(8)->get()
                ->map(fn ($o) => [
                    'id'     => $o->id,
                    'pair'   => str_replace('_', '/', $o->pair->symbol ?? ''),
                    'side'   => $sides[$o->order_side] ?? 'buy',
                    'type'   => $types[$o->order_type] ?? '',
                    'rate'   => (float) $o->rate,
                    'amount' => (float) $o->amount,
                    'filled' => (float) $o->filed_percentage,
                    'status' => $statuses[$o->status] ?? 'open',
                    'date'   => $o->created_at?->toIso8601String(),
                ]),
            'recentTransactions' => Transaction::where('user_id', $user->id)->orderBy('id', 'DESC')->take(8)->get()
                ->map(fn ($t) => [
                    'trx'     => $t->trx,
                    'amount'  => (float) $t->amount,
                    'charge'  => (float) $t->charge,
                    'balance' => (float) $t->post_balance,
                    'type'    => $t->trx_type,
                    'details' => __($t->details),
                    'remark'  => $t->remark,
                    'date'    => $t->created_at?->toIso8601String(),
                ]),
            'urls' => [
                'trade'      => route('trade', ['symbol' => '__SYMBOL__']),
                'ercFeed'    => route('erc.latest.transaction'),
                'classic'    => route('user.classic.trading'),
                'tokenSplash' => route('user.token.splash.trading'),
                'puzzle'     => route('user.puzzle.hunt.trading'),
                'futures'    => route('futures'),
            ],
        ]);
    }

    private function legacyHome(Request $request)
    {

        $pageTitle = 'My Dashboard';
        $user = auth()->user();
        $pairs = CoinPair::whereHas('marketData')
            ->select('id', 'market_id', 'coin_id')
            ->with('market:id,name,currency_id', 'coin:id,name,symbol', 'market.currency:id,name,symbol', 'marketData:id,pair_id,price,percent_change_1h,percent_change_24h,html_classes,market_cap')
            ->get();

        $wallets = $this->wallet();
        $currencies = Currency::rankOrdering()->select('name', 'id', 'symbol')->active()->get();

        $order = Order::where('user_id', $user->id);
        $widget['open_order'] = (clone $order)->open()->count();
        $widget['completed_order'] = (clone $order)->completed()->count();
        $widget['canceled_order'] = (clone $order)->canceled()->count();
        $widget['total_trade'] = Trade::where('trader_id', $user->id)->count();

        $recentOrders = $order->with('pair.coin')->orderBy('id', 'DESC')->take(10)->get();
        $recentTransactions = Transaction::where('user_id', $user->id)->orderBy('id', 'DESC')->take(10)->get();
//        $estimatedBalance   = Wallet::where('user_id', $user->id)->join('currencies', 'wallets.currency_id', 'currencies.id')->spot()->sum(DB::raw('currencies.rate * wallets.balance'));

        $gateways = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method:id,code,crypto')->get();
        $withdrawMethods = WithdrawMethod::active()->get();

        $estimatedBalance = Wallet::where('user_id', $user->id)->join('currencies', 'wallets.currency_id', 'currencies.id')->sum(DB::raw('currencies.rate * wallets.balance'));

        $stock_wallet = StockWallet::where('user_id', $user->id)->sum('amount');

        // Get recent blog posts for dashboard
        $recentBlogs = Blog::published()->with(['user', 'comments.user'])->latest()->take(6)->get();

        if ($request->type) {
            return view('Template::user.dashboard', compact('pageTitle', 'stock_wallet', 'user', 'pairs', 'wallets', 'currencies', 'widget', 'recentOrders', 'recentTransactions', 'estimatedBalance', 'gateways', 'withdrawMethods', 'recentBlogs'));
        } else {
            return view('Template::user.new_dashboard', compact('pageTitle', 'stock_wallet', 'user', 'pairs', 'wallets', 'currencies', 'widget', 'recentOrders', 'recentTransactions', 'estimatedBalance', 'gateways', 'withdrawMethods', 'recentBlogs'));
        }

    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits  = auth()->user()->deposits()->searchable(['trx', 'currency:symbol'])->with(['gateway', 'wallet.currency'])->orderBy('id', 'desc')->paginate(getPaginate());
        return Inertia::render('User/DepositHistory', [
            'deposits' => InertiaData::paginate($deposits, fn ($d) => [
                'id'       => $d->id,
                'trx'      => $d->trx,
                'gateway'  => __($d->gateway?->name),
                'currency' => $d->wallet->currency->symbol ?? $d->method_currency,
                'image'    => $d->wallet->currency->image_url ?? null,
                'wallet'   => trim(($d->wallet->name ?? '') . ' ' . strtoupper($d->wallet->type_text ?? '')),
                'amount'   => (float) $d->amount,
                'charge'   => (float) $d->charge,
                'total'    => (float) $d->amount + (float) $d->charge,
                'status'   => InertiaData::badgeText($d->statusBadge),
                'details'  => $d->method_code >= 1000 ? collect($d->detail ?? [])->where('type', '!=', 'file')->map(fn ($i) => ['name' => $i->name ?? $i['name'] ?? '', 'value' => $i->value ?? $i['value'] ?? ''])->values() : null,
                'feedback' => $d->status == Status::PAYMENT_REJECT ? $d->admin_feedback : null,
                'date'     => InertiaData::date($d->created_at),
            ]),
        ]);
    }


    public function show2faForm()
    {
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        return Inertia::render('User/TwoFactor', [
            'enabled' => (bool) $user->ts,
            'secret'  => $secret,
            'qr'      => $qrCodeUrl,
            'urls'    => ['enable' => route('user.twofactor.enable'), 'disable' => route('user.twofactor.disable'), 'password' => route('user.change.password')],
        ]);
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);

        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }


    public function transactions()
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $query        = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark', 'wallet.currency:symbol', 'wallet:wallet_type']);
        $transactions = $query->orderBy('id', 'desc')->with('wallet.currency')->paginate(getPaginate());
        $currencies   = Currency::active()->rankOrdering()->get();

        return Inertia::render('User/Transactions', [
            'transactions' => InertiaData::paginate($transactions, fn ($t) => [
                'id'       => $t->id,
                'trx'      => $t->trx,
                'type'     => $t->trx_type,
                'amount'   => (float) $t->amount,
                'charge'   => (float) $t->charge,
                'balance'  => (float) $t->post_balance,
                'currency' => $t->wallet->currency->symbol ?? '',
                'image'    => $t->wallet->currency->image_url ?? null,
                'wallet'   => trim(($t->wallet->name ?? '') . ' ' . strtoupper($t->wallet->type_text ?? '')),
                'details'  => __($t->details),
                'remark'   => $t->remark,
                'date'     => InertiaData::date($t->created_at),
            ]),
            'currencies' => $currencies->pluck('symbol'),
            'remarks'    => $remarks->pluck('remark')->filter()->map(fn ($r) => ['value' => $r, 'label' => keyToTitle($r)])->values(),
        ]);
    }
    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        return \Inertia\Inertia::render('User/Account/KycForm', [
            'user'     => ['firstname' => auth()->user()->firstname, 'lastname' => auth()->user()->lastname],
            'rejected' => auth()->user()->kyc_rejection_reason,
            'urls'     => ['submit' => route('user.kyc.submit'), 'check' => route('user.kyc.data.check')],
        ]);
    }

    public function kycData()
    {
        $user = auth()->user();

        // kyc_data is either the legacy form-builder list ({name,type,value}) or the
        // flat key => value object the current KYC wizard stores.
        $rows = [];
        $data = $user->kyc_data;
        if (is_string($data)) {
            $data = json_decode($data); // kycSubmit stores an already-encoded JSON string
        }
        foreach ((array) ($data ?? []) as $key => $val) {
            if (is_object($val) && property_exists($val, 'value')) {
                if (!$val->value) continue;
                $rows[] = [
                    'label' => __($val->name),
                    'value' => $val->type == 'checkbox' ? implode(', ', (array) $val->value) : ($val->type == 'file' ? null : (string) $val->value),
                    'file'  => $val->type == 'file' ? route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) : null,
                ];
            } elseif (is_scalar($val) && $val !== '' && !in_array($key, ['_token', 'front_page', 'back_page', 'selfie'])) {
                $rows[] = ['label' => ucwords(str_replace('_', ' ', $key)), 'value' => (string) $val, 'file' => null];
            }
        }

        return \Inertia\Inertia::render('User/Account/KycData', [
            'status'   => (int) $user->kv,
            'rejected' => $user->kyc_rejection_reason,
            'rows'     => $rows,
            'formUrl'  => route('user.kyc.form'),
        ]);
    }

    public function kycDataCheck(Request $request)
    {
        $document_type=strtolower(str_replace(' ','_', $request->document_type));
        $doc_number=$request->doc_number;



        $pre_doc=User::where('doc_type', $document_type)->where('doc_number', $doc_number)->first();


        if(!$pre_doc){
            return response()->json(['status'=>'success', 'message'=>'Valid Document']);
        }

        return response()->json(['status'=>'failed', 'message'=> 'Someone already use this document please provide a different document']);


    }

    public function kycSubmit(Request $request)
    {



//        dd($request->all());
//        $form           = Form::where('act', 'kyc')->firstOrFail();
//        $formData       = $form->form_data;
//        $formProcessor  = new FormProcessor();
//        $validationRule = $formProcessor->valueValidation($formData);
//        $request->validate($validationRule);


        $document_type=strtolower(str_replace(' ','_', $request->document_type));
        $doc_number=null;
        if($document_type=='id_card'){
            $doc_number=$request->id_number;
        }elseif($document_type=='passport'){
            $doc_number=$request->passport_number;
        }elseif($document_type=='driving_licence'){
            $doc_number=$request->dl_number;
        }

        if(!$doc_number){
            return redirect()->back()->withErrors(['error'=> 'Please enter valid '.strtoupper($document_type).' Number']);
        }

        $pre_doc=User::where('doc_type', $document_type)->where('doc_number', $doc_number)->first();

        if($pre_doc){
            return redirect()->back()->withErrors(['error'=> 'Someone already use this document please provide a different document']);
        }


        try {

            $user = auth()->user();

            $user->doc_type=$document_type;
            $user->doc_number=$doc_number;
            $user->save();

            // foreach (@$user->kyc_data ?? [] as $kycData) {
            //     if (isset($kycData->front_document) && $kycData->front_document) {
            //         fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->front_document);
            //     }

            //     if (isset($kycData->back_document) && $kycData->back_document) {
            //         fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->back_document);
            //     }
            //     if (isset($kycData->selfie_document) && $kycData->selfie_document) {
            //         fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->selfie_document);
            //     }
            // }


            // if (!$request->hasFile('front_page') || !$request->hasFile('back_page') || !$request->hasFile('selfie')) {

            //     return redirect()->back()->withErrors(['error' => 'Please upload your documents properly']);
            // }

            unset($request['_token']);


            // if ($request->hasFile('front_page')) {
            //     $directory = date("Y") . "/" . date("m") . "/" . date("d");
            //     $path = getFilePath('verify') . '/' . $directory;
            //     $front_page = $directory . '/' . fileUploader($request->front_page, $path);
            //     $request['front_document'] = $front_page;
            // }

            // if ($request->hasFile('back_page')) {
            //     $directory = date("Y") . "/" . date("m") . "/" . date("d");
            //     $path = getFilePath('verify') . '/' . $directory;
            //     $back_page = $directory . '/' . fileUploader($request->back_page, $path);
            //     $request['back_document'] = $back_page;
            // }
            // if ($request->hasFile('selfie')) {
            //     $directory = date("Y") . "/" . date("m") . "/" . date("d");
            //     $path = getFilePath('verify') . '/' . $directory;
            //     $selfie = $directory . '/' . fileUploader($request->selfie, $path);
            //     $request['selfie_document'] = $selfie;
            // }


            unset($request->front_page);
            unset($request->back_page);
            unset($request->selfie);


            $userData = json_encode($request->all());
            $user->kyc_data = $userData;
            $user->kyc_rejection_reason = null;
            $user->kv = Status::KYC_PENDING;
            $user->save();

            $notify[] = ['success', 'KYC data submitted successfully'];
            return to_route('user.home')->withNotify($notify);

        } catch (\Exception $eex) {
            return redirect()->back()->withErrors(['error' => 'Please refresh and try again']);
        }
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return \Inertia\Inertia::render('Auth/CompleteProfile', \App\Support\AuthPage::props([
            'needsEmail' => !$user->email,
            'countries'  => collect($countries)->map(fn ($c, $code) => ['code' => $code, 'name' => $c->country, 'dial' => $c->dial_code])->values(),
            'detected'   => $mobileCode ?: null,
            'action'     => route('user.data.submit'),
            'logout'     => route('user.logout'),
        ]));
    }

    public function userDataSubmit(Request $request)
    {


        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $validationRule = [
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ];

        if (!$user->email) {
            $validationRule['email'] = 'required|email|unique:users';
        }


        $request->validate($validationRule);

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code     = $request->country_code;
        $user->mobile           = $request->mobile;
        $user->username         = $request->username;
        $user->address          = $request->address;
        $user->city             = $request->city;
        $user->state            = $request->state;
        $user->zip              = $request->zip;
        $user->country_name     = @$request->country;
        $user->dial_code        = $request->mobile_code;
        $user->profile_complete = Status::YES;

        if (!$user->email) {
            $user->email = strtolower($request->email);
            $user->ev    = gs('ev') ? Status::NO : Status::YES;
        }

        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }


    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title     = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }
    public function wallet($skip = 0)
    {

        $wallets = Wallet::where('user_id', auth()->id())
            ->skip($skip)
            ->spot()
            ->take(3)
            ->with('currency:id,name,symbol,image')
            ->select('id', 'balance', 'currency_id')
            ->orderBy('balance', 'desc')
            ->get();

        if (!request()->ajax()) return $wallets;


        return response()->json([
            'success' => true,
            'wallets' => $wallets,
        ]);
    }

    public function addToFavorite($symbol)
    {
        $pair = CoinPair::activeMarket()->activeCoin()->where('symbol', $symbol)->first();
        if (!$pair) {
            return response()->json([
                'success' => false,
                'message' => "Pair not found",
            ]);
        }
        $favoritePair = FavoritePair::where('user_id', auth()->id())->where('pair_id', $pair->id)->first();

        if ($favoritePair) {
            $favoritePair->delete();
            return response()->json([
                'success' => true,
                'deleted' => true,
                'message' => "This pair removed to your favorite list",
            ]);
        }

        $favoritePair          = new FavoritePair();
        $favoritePair->user_id = auth()->id();
        $favoritePair->pair_id = $pair->id;
        $favoritePair->save();

        return response()->json([
            'success' => true,
            'message' => "Pair added to favorite list",
        ]);
    }


    public function allCurrency()
    {
        $query = Currency::active();

        if (request()->type == Status::CRYPTO_CURRENCY) {
            $query->where('type', Status::CRYPTO_CURRENCY)->rankOrdering();
        }

        if (request()->type == Status::FIAT_CURRENCY) {
            $query->where('type', Status::FIAT_CURRENCY)->orderBy('id', 'desc');
        }

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request()->search . '%')->orWhere('symbol', 'like', '%' . request()->search . '%');
            });
        }

        $currencies = $query->paginate(getPaginate());

        return response()->json([
            'success'    => true,
            'currencies' => $currencies,
            'more'       => $currencies->hasMorePages(),
        ]);
    }

    public function FindRefUser($user, $ref)
    {
        $finOne = $user->allReferrals->where('username', $ref)->first();

        if ($finOne) {
            return $finOne;
        }


        if ($user->allReferrals) {
            foreach ($user->allReferrals as $reOne) {

                $finTwo = $reOne->allReferrals->where('username', $ref)->first();

                if ($finTwo) {
                    return $finTwo;
                }

                if ($reOne->allReferrals) {
                    foreach ($reOne->allReferrals as $reTwo) {

                        $finThree = $reTwo->allReferrals->where('username', $ref)->first();
                        if ($finThree) {
                            return $finThree;
                        }

                        foreach ($reTwo->allReferrals as $reThree) {

                            $finFour = $reThree->allReferrals->where('username', $ref)->first();
                            if ($finFour) {
                                return $finFour;
                            }

                            if ($reTwo->allReferrals) {
                                foreach ($reThree->allReferrals as $reFour) {

                                    $finFive = $reFour->allReferrals->where('username', $ref)->first();
                                    if ($finFive) {
                                        return $finFive;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return null;
    }


    public function referrals(Request $request)
    {
        $pageTitle = 'My Referrals';
        $user      = auth()->user();


        if($request->ref && $user->allReferrals && $user->allReferrals->count() > 0){

            $checkFindRef=$this->FindRefUser($user, $request->ref);

            if($checkFindRef){
                $user=$checkFindRef;
            }

            if(!$user->allReferrals){
                $notify[] = ['success', 'No User Found'];
                return back()->withNotify($notify);
            }

        }




        $maxLevel  = Referral::max('level');
        $stock_member_verified=StockMember::where('user_id', $user->id)->first();
        $ref_ids=$user->allReferrals->pluck('id');

        $all_invest=0;
        if($user->allReferrals){
            foreach ($user->allReferrals as $rr){
                $eachIds=$rr->allReferrals->pluck('id');
                if($eachIds) {
                    $user_invest = UserStock::whereIn('user_id', $eachIds)->where('status', 'buy')->sum('invest_amount');
                    if($user_invest) {
                        $all_invest +=$user_invest;
                        }
                }
            }
        }

        $main_user_invest=UserStock::whereIn('user_id', $ref_ids)->where('status', 'buy')->sum('invest_amount');

        if($main_user_invest){
            $all_invest=$all_invest + $main_user_invest;
        }

        $user_invest=$all_invest;

        return Inertia::render('User/Referrals', [
            'viewing'   => [
                'name'     => $user->fullname,
                'username' => $user->username,
                'isSelf'   => $user->id === auth()->id(),
                'member'   => (bool) $stock_member_verified,
            ],
            'referrer'  => $user->ref_by ? $user->referrer->fullname : null,
            'invest'    => (float) $user_invest,
            'direct'    => $user->allReferrals->count(),
            'showBonus' => !(auth()->user()->group_expert && auth()->user()->group_expert == 'yes'),
            'tree'      => $this->referralTree($user, 6),
            'urls'      => ['self' => route('user.referrals')],
        ]);
    }

    /**
     * Referral downline up to $depth levels, built with one query per level
     * (the Blade partial ran two queries per node).
     */
    private function referralTree(User $root, int $depth): array
    {
        $levels = [];
        $parentIds = [$root->id];
        for ($i = 0; $i < $depth && $parentIds; $i++) {
            $users = User::whereIn('ref_by', $parentIds)->select('id', 'firstname', 'lastname', 'username', 'ref_by')->get();
            $levels[] = $users;
            $parentIds = $users->pluck('id')->all();
        }

        $all = collect($levels)->flatten(1);
        $ids = $all->pluck('id');
        $members = StockMember::whereIn('user_id', $ids)->pluck('user_id')->flip();
        $invest = UserStock::whereIn('user_id', $ids)->where('status', 'buy')->groupBy('user_id')
            ->selectRaw('user_id, SUM(invest_amount) as total')->pluck('total', 'user_id');
        // direct-referral counts, including for the deepest level shown
        $counts = User::whereIn('ref_by', $ids)->groupBy('ref_by')->selectRaw('ref_by, COUNT(*) as c')->pluck('c', 'ref_by');

        $byParent = $all->groupBy('ref_by');
        $build = function ($parentId, $level) use (&$build, $byParent, $members, $invest, $counts, $depth) {
            return ($byParent[$parentId] ?? collect())->map(fn ($u) => [
                'id'       => $u->id,
                'name'     => trim($u->firstname . ' ' . $u->lastname) ?: $u->username,
                'username' => $u->username,
                'member'   => isset($members[$u->id]),
                'invest'   => (float) ($invest[$u->id] ?? 0),
                'count'    => (int) ($counts[$u->id] ?? 0),
                'url'      => $level < $depth ? route('user.referrals', ['ref' => $u->username]) : null,
                'children' => $level < $depth ? $build($u->id, $level + 1) : [],
            ])->values()->all();
        };

        return $build($root->id, 1);
    }

}
