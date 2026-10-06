<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Job\LevelCommission;
use App\Job\StockBuyBonus;
use App\Models\BondWallet;
use App\Models\Broker;
use App\Models\Currency;
use App\Models\Product;
use App\Models\StockExchange;
use App\Models\StockMember;
use App\Models\StockSellRequest;
use App\Models\StockTransaction;
use App\Models\StockTransfer;
use App\Models\StockVideo;
use App\Models\StockWallet;
use App\Models\Transaction;
use App\Models\UserStock;
use App\Models\Wallet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class StockController extends Controller
{
    protected $api_key;

     public function __construct()
     {
         $this->api_key=stockPriceApi();
     }


    public function index()
    {
        $pageTitle = "Stocks";
        $stocks = Product::orderByDesc('created_at')->where('use_for', 'stock')->get();
        $stock_member = StockMember::where('user_id', auth()->user()->id)->first();

        return \Inertia\Inertia::render('User/Stock/Market', [
            'stocks' => $stocks->map(fn ($s) => [
                'id'    => $s->id,
                'name'  => $s->name,
                'code'  => $s->stock_code,
                'image' => getImage(getFilePath('currency') . '/' . $s->image, getFileSize('currency')),
                'url'   => route('user.stock.details', [$s->slug]),
            ])->values(),
            'isMember' => (bool) $stock_member,
            'urls' => ['member' => route('user.stock.member'), 'my' => route('user.stock.my'), 'history' => route('user.stock.transactions')],
        ]);
    }


    public function bonds()
    {
        // One query for all four bond categories (was four), only the columns the page uses.
        $products = Product::where('use_for', 'bond')
            ->whereIn('bond_type', ['bonds', 'economy', 'indices', 'options'])
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'slug', 'image', 'stock_code', 'bond_type', 'short_description']);

        $card = fn ($p) => [
            'id'    => $p->id,
            'name'  => $p->name,
            'code'  => $p->stock_code,
            'image' => getImage(getFilePath('currency') . '/' . $p->image, getFileSize('currency')),
            // short_description is CMS HTML; drop placeholder text like ",,,,"
            'about' => preg_match('/[a-z0-9]/i', $t = trim(strip_tags((string) $p->short_description))) ? \Illuminate\Support\Str::limit($t, 120) : null,
            'url'   => route('user.bond.details', [$p->slug]),
        ];

        return \Inertia\Inertia::render('User/Bond/Market', [
            // Tab labels as on the Blade page: Bond, Economy, Indices, Options.
            'categories' => collect(['bonds' => 'Bond', 'economy' => 'Economy', 'indices' => 'Indices', 'options' => 'Options'])
                ->map(fn ($label, $key) => ['key' => $key, 'label' => $label, 'items' => $products->where('bond_type', $key)->map($card)->values()])
                ->values(),
            'isMember' => StockMember::where('user_id', auth()->id())->exists(),
            'urls' => [
                'member'  => route('user.stock.member'),
                'my'      => route('user.my.bonds'),
                'history' => route('user.bond.transactions'),
            ],
        ]);
    }

    public function generateQrCode(Request $request)
    {

        $stock=UserStock::where('user_id', auth()->user()->id)->where('id', $request->id)->first();

        if(!$stock){
            return response()->json(['status'=>'failed','message'=>'Invalid Stock']);
        }

        $url=route('public.certificate',[$stock->certificate_id]);
        $qrCodes = QrCode::size(60)->generate($url);
        $base64Svg=base64_encode($qrCodes);

        return response()->json(['status'=>'success', 'data'=>$base64Svg]);
    }

    public function buyMemberShip(Request $request)
    {

        DB::beginTransaction();

        try {
            $currency = Currency::where('symbol', 'USDT')->first();
            if (!$currency) {
                $notifye[] = ['error', 'Can not buy stock at this moment'];
                return redirect()->back()->withNotify($notifye);
            }

            if(auth()->user()->referrer){
                $referrer=auth()->user()->referrer;
                $referrer_wallet = StockWallet::where('user_id', $referrer->id)->first();
                if($referrer_wallet){
                    $interestAmount=(20 * 30) / 100;
                    $final_ins_balance = $referrer_wallet->amount + $interestAmount;
                    $referrer_wallet->amount = $final_ins_balance;
                    $referrer_wallet->save();

                    $interestTran = new StockTransaction();
                    $interestTran->user_id = $referrer->id;
                    $interestTran->type = 'interest';
                    $interestTran->remark = 'Got Stock Membership Purchase Interest From: '.auth()->user()->fullname;;
                    $interestTran->amount = $interestAmount;
                    $interestTran->save();


                    LevelCommission::dispatch($interestAmount, $referrer->id);
                }
            }



            $user_wallet = Wallet::where('user_id', auth()->user()->id)->where('currency_id', $currency->id)->first();
            if (!$user_wallet) {
                $notifyw[] = ['error', 'Can not buy stock at this moment'];
                return redirect()->back()->withNotify($notifyw);
            }

            if ($user_wallet->balance < 20) {
                $notifyLowBal[] = ['error', 'Please deposit and try again'];
                return redirect()->back()->withNotify($notifyLowBal);
            }
            $reduce_balance = $user_wallet->balance - 20;
            $user_wallet->balance = $reduce_balance;
            $user_wallet->save();


            $member = new StockMember();
            $member->user_id = auth()->user()->id;
            $member->status = 'approve';
            $member->amount = 20;
            $member->save();




            $transaction               = new Transaction();
            $transaction->user_id      = auth()->user()->id;
            $transaction->amount       = $member->amount;
            $transaction->post_balance = $user_wallet->balance;
            $transaction->charge       = 0.00;
            $transaction->trx_type     = '-';
            $transaction->details      = showAmount($member->amount,currencyFormat:false) . ' Buy Stock Membership';
            $transaction->trx          = Str::random(17);
            $transaction->remark       = 'buy_membership';
            $transaction->wallet_id    = $user_wallet->id;
            $transaction->save();



            $notifyS[] = ['success', 'Congratulations! Now you can use stock feature'];
            DB::commit();
            return redirect()->route('user.stock.index')->withNotify($notifyS);
        } catch (\Exception $ex) {
            DB::rollBack();
            $notifyLowBal[] = ['error', 'Something went wrong try again'];
            return redirect()->back()->withNotify($notifyLowBal);
        }

    }

    public function details($slug)
    {
        $stock = Product::where('slug', $slug)->firstOrFail(['id', 'name', 'slug', 'image', 'stock_code', 'use_for', 'short_description', 'description']);

        // bonds have their own detail route; keep old stock links to bonds working
        if ($stock->use_for == 'bond') {
            return to_route('user.bond.details', $stock->slug);
        }

        $videos = StockVideo::where('product_id', $stock->id)->pluck('video');

        // Same Vue page as bond details, in "stock" mode (adds the live-market buy option).
        return \Inertia\Inertia::render('User/Bond/Details', [
            'kind' => 'stock',
            'bond' => [
                'id'          => $stock->id,
                'name'        => $stock->name,
                'code'        => $stock->stock_code,
                'image'       => getImage(getFilePath('currency') . '/' . $stock->image, getFileSize('currency')),
                'category'    => 'Stock',
                'summary'     => $stock->short_description,
                'description' => $stock->description,
                'info'        => trim(strip_tags((string) $stock->short_description)),
            ],
            'videos'   => $videos->map(fn ($v) => getImage(getFilePath('currency') . '/' . $v, getFileSize('currency')))->values(),
            'isMember' => \App\Models\StockMember::where('user_id', auth()->id())->exists(),
            'urls' => [
                'buy'    => route('user.stock.buy'),
                'member' => route('user.stock.member'),
                'my'     => route('user.stock.my'),
                'market' => route('user.stock.index'),
            ],
        ]);
    }


    public function transfer_view($id)
    {
        $stock = UserStock::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$stock) {
            $notifye[] = ['error', 'Something went wrong, try again after sometimes'];
            return redirect()->back()->withNotify($notifye);
        }

        $pageTitle = 'Transfer Stock';



        $currency = Currency::where('symbol', 'USDT')->first();
        if (!$currency) {
            $notifye[] = ['error', 'Can not buy stock at this moment'];
            return redirect()->back()->withNotify($notifye);
        }

        $user_wallet = Wallet::where('user_id', auth()->user()->id)->where('currency_id', $currency->id)->first();
        if (!$user_wallet) {
            $notifyw[] = ['error', 'Can not buy stock at this moment'];
            return redirect()->back()->withNotify($notifyw);
        }

        $charge_amount=($stock->invest_amount * 3) / 100;

        if($user_wallet->balance <= $charge_amount){
            $notifyw[] = ['error', 'Insufficient balance'];
            return redirect()->back()->withNotify($notifyw);
        }

        $brokers = Broker::where('status', 'active')->orderByDesc('created_at')->pluck('name');
        $stock->loadMissing('product:id,name,image,stock_code');

        return \Inertia\Inertia::render('User/Stock/Exchange', [
            'stock' => [
                'id'      => $stock->id,
                'name'    => @$stock->product->name,
                'code'    => @$stock->product->stock_code,
                'image'   => $stock->product ? getImage(getFilePath('currency') . '/' . $stock->product->image, getFileSize('currency')) : null,
                'amount'  => (float) $stock->invest_amount,
                'type'    => $stock->type,
                'date'    => \App\Support\InertiaData::date($stock->created_at),
            ],
            'holder'  => auth()->user()->fullname,
            'brokers' => $brokers->values(),
            'charge'  => (float) $charge_amount,
            'balance' => (float) $user_wallet->balance,
            'urls'    => ['submit' => route('user.stock.exchange.request'), 'my' => route('user.stock.my')],
        ]);
    }

    public function exchangeRequest(Request $request)
    {

        try{
            $request->validate([
                'broker'=>'required',
                'stock_id'=>'required',
            ]);

            $user=auth()->user();
            $user_stock=UserStock::where('id', $request->stock_id)->where('user_id', $user->id)->firstOrFail();
            $charge_amount=($user_stock->invest_amount * 3) / 100;



            $currency = Currency::where('symbol', 'USDT')->first();
            if (!$currency) {
                $notifye[] = ['error', 'Can not buy stock at this moment'];
                return redirect()->back()->withNotify($notifye);
            }

            $user_wallet = Wallet::where('user_id', auth()->user()->id)->where('currency_id', $currency->id)->first();
            if (!$user_wallet) {
                $notifyw[] = ['error', 'Can not buy stock at this moment'];
                return redirect()->back()->withNotify($notifyw);
            }

            $deduct_amount= $user_wallet->balance - 3;
            $user_wallet->balance=$deduct_amount;
            $user_wallet->save();


            $exchange=new StockExchange();
            $exchange->user_id=$user->id;
            $exchange->user_stock_id=$user_stock->id;
            $exchange->product_id=$user_stock->product_id;
            $exchange->status='pending';
            $exchange->charge=$charge_amount;
            $exchange->stock_type=$user_stock->type;
            $exchange->broker=$request->broker;
            $exchange->others=json_encode($request->only('comment','receive_amount','contact_email'));
            $exchange->save();


            $notifye[] = ['success', 'Your stock exchange request successfully submitted, You\'ll get update within 24 hour'];
            return redirect()->route('user.stock.my')->withNotify($notifye);

        }catch(\Exception $ex){
            $notifye[] = ['error', 'Something went wrong, try again after sometimes'];
            return redirect()->route('user.stock.my')->withNotify($notifye);
        }
    }

    public function buyStock(Request $request)
    {
        if($request->type == 'fix' && (!$request->invest_amount || $request->invest_amount <= 0)){
            $messageee = 'Enter valid amount to buy this stock';
            $notifys[] = ['error', $messageee];
            return redirect()->back()->withNotify($notifys);
        }


        DB::beginTransaction();

        try {
            $currency = Currency::where('symbol', 'USDT')->first();
            if (!$currency) {
                $notifye[] = ['error', 'Can not buy stock at this moment'];
                return redirect()->back()->withNotify($notifye);
            }

            $user_wallet = Wallet::where('user_id', auth()->user()->id)->where('currency_id', $currency->id)->first();
            if (!$user_wallet) {
                $notifyw[] = ['error', 'Can not buy stock at this moment'];
                return redirect()->back()->withNotify($notifyw);
            }

            $product = Product::findOrFail($request->id);

            $productRealPrice=0;
            if ($request->type && $request->type == 'unfix' && $request->unfix_amount != 'custom') {
                try {
                    $client = new Client(['verify' => false]);
                    $urls = "https://finnhub.io/api/v1/quote?symbol=".$product->stock_code."&token=$this->api_key";
                    $response = $client->request('GET', $urls);
                    $response = $response->getBody()->getContents();
                    $response = json_decode($response, true);
                    $invest_amount = isset($response['c']) ? $response['c'] : $product->price;
                } catch (\Exception $ex) {
                    $invest_amount = $product->price;
                }
            }else if ($request->type && $request->type == 'unfix' && $request->unfix_amount == 'custom') {
                if($request->unfix_invest_amount){
                    $invest_amount=$request->unfix_invest_amount;

                        $client = new Client(['verify' => false]);
                        $urls = "https://finnhub.io/api/v1/quote?symbol=".$product->stock_code."&token=$this->api_key";
                        $response = $client->request('GET', $urls);
                        $response = $response->getBody()->getContents();
                        $response = json_decode($response, true);
                        $productRealPrice=isset($response['c']) ? $response['c']:0;

                }
            } else {
                $invest_amount = $request->invest_amount?$request->invest_amount:$product->price;
            }



            if ($invest_amount <= 0) {
                $message = 'You can not buy this stock at this moment';
                $notify[] = ['error', $message];
                return redirect()->back()->withNotify($notify);
            }

            $final_price = $invest_amount;
            if ($request->type == 'unfix' && $request->unfix_amount == '25') {
                $final_price = ($invest_amount * 25) / 100;
            } elseif ($request->type == 'unfix' && $request->unfix_amount == '50') {
                $final_price = ($invest_amount * 50) / 100;
            } elseif ($request->type == 'unfix' && $request->unfix_amount == '100') {
                $final_price = ($invest_amount * 100) / 100;
            } elseif ($request->type == 'unfix' && $request->unfix_amount == 'custom') {
                $final_price = $invest_amount;
            }



            if ($user_wallet->balance < $final_price) {
                $notifyIn[] = ['error', 'Insufficient balance'];
                return redirect()->back()->withNotify($notifyIn);
            }

            $reduce_balance = $user_wallet->balance - $final_price;
            $user_wallet->balance = $reduce_balance;
            $user_wallet->save();


            $userStock = new UserStock();
            $userStock->certificate_id=strtoupper(Str::random(25));
            $userStock->user_id = auth()->user()->id;
            $userStock->product_id = $product->id;
            if ($request->type && $request->type == 'unfix') {
                if($request->unfix_invest_amount){

                    $stock_real_price= $productRealPrice > 0?$productRealPrice:$product->price; // price per share
                    $unfix_investment_price = $request->unfix_invest_amount;

                    $real_percentage = ($unfix_investment_price / $stock_real_price) * 100;

                    $userStock->type = 'unfix';
                    $userStock->invest_percent = number_format($real_percentage, 0);
                    $userStock->stack_price = $product->price;
                }else{
                    $userStock->type = 'unfix';
                    $userStock->invest_percent = $request->unfix_amount;
                    $userStock->stack_price = $invest_amount;
                }

            } else {
                $userStock->type = 'fix';
                if($request->invest_time && $request->invest_time=='month'){
                    $validTime=now()->addMonth();
                }elseif($request->invest_time && $request->invest_time=='half_year'){
                    $validTime=now()->addMonth(6);
                }else{
                    $validTime=now()->addYear();
                }
                $userStock->invest_date = $validTime;
                $userStock->interest_date = now()->addDay();
            }
            $userStock->product_price = $product->price;
            $userStock->invest_amount = $final_price;
            $userStock->status = 'buy';
            $userStock->interest = $product->fix_rate;
            $userStock->use_for = $product->use_for;
            $userStock->save();



//            if ($userStock->type = 'fix') {
                $stockTran = new StockTransaction();
                $stockTran->user_id = auth()->user()->id;
                $stockTran->type = 'buy';
                $stockTran->stock_type = $request->type;
                $stockTran->amount = $final_price;
                $stockTran->stock_id = $userStock->id;
                $stockTran->use_for = $userStock->use_for;
                $stockTran->save();
//            }


            $transaction               = new Transaction();
            $transaction->user_id      = auth()->user()->id;
            $transaction->amount       = $final_price;
            $transaction->post_balance = $user_wallet->balance;
            $transaction->charge       = 0.00;
            $transaction->trx_type     = '-';
            $transaction->details      = showAmount($final_price,currencyFormat:false) . ' Buy Stock '.$product->name;
            $transaction->trx          = Str::random(17);
            if($product->use_for=='bond'){
                $transaction->remark       = 'buy_bond';
            }else{
                $transaction->remark       = 'buy_stock';
            }
            $transaction->wallet_id    = $user_wallet->id;
            $transaction->save();


            if($product->use_for=='bond'){
                $ex_w=BondWallet::where('user_id', $user_wallet->user_id)->where('status', 'active')->first();
                if(!$ex_w) {
                    $b_new_wallet = new BondWallet();
                    $b_new_wallet->amount = 00.00;
                    $b_new_wallet->user_id = auth()->user()->id;
                    $b_new_wallet->save();
                }
            }

            //TODO::Send Buy Bonus
//            $bonus_amount=gs('return_bonus')?gs('return_bonus'):'0.08';
//            $main_user_stock_wallet = StockWallet::where('user_id', auth()->user()->id)->first();
//            $one_commission=($final_price * $bonus_amount) / 100;
//            $new_stock_wallet_balance = $main_user_stock_wallet->amount + $one_commission;
//
//            $main_user_stock_wallet->amount = $new_stock_wallet_balance;
//            $main_user_stock_wallet->save();
//
//            $interestTran = new StockTransaction();
//            $interestTran->user_id = auth()->user()->id;
//            $interestTran->type = 'interest';
//            $interestTran->remark = 'Mutual Fund Bonus';
//            $interestTran->amount = $one_commission;
//            $interestTran->save();




            DB::commit();
//            if(auth()->user()->referrer) {
//                StockBuyBonus::dispatch($final_price, auth()->user()->id);
//            }


            if($product->use_for=='bond'){
                $message = 'Congratulations! Bond successfully purchased';
                $notify[] = ['success', $message];
                return redirect()->route('user.bonds')->withNotify($notify);
            }


            $message = 'Congratulations! Stock successfully purchased';
            $notify[] = ['success', $message];
            return redirect()->route('user.stock.index')->withNotify($notify);
        } catch (\Exception $ex) {
            DB::rollBack();
            $message = 'Something went wrong, try again after sometimes';
            $notify[] = ['error', $message];
            return redirect()->route('user.stock.index')->withNotify($notify);
        }

    }


    public function reactive($id, Request $request)
    {



        try{
            $userStock = UserStock::where('user_id', auth()->user()->id)->where('id', $id)->first();
            if (!$userStock) {
                $message = 'Please to sell a valid stock';
                $notify[] = ['error', $message];
                return redirect()->back()->withNotify($notify);
            }



            $validTime='365';
            if($request->invest_time && $request->invest_time=='month'){
                $validTime=now()->addMonth();
            }elseif($request->invest_time && $request->invest_time=='half_year'){
                $validTime=now()->addMonth(6);
            }elseif($request->invest_time && $request->invest_time=='three_month'){
                $validTime=now()->addMonth(3);
            }else{
                $validTime=now()->addYear();
            }

                $userStock->invest_date=$validTime;
                $userStock->created_at=now();
                $userStock->save();





            $message = 'Congratulations! Stock successfully reactivated';
            $notify[] = ['success', $message];
            return redirect()->route('user.stock.my')->withNotify($notify);
        }catch(\Exception $e){

            return redirect()->route('user.stock.my')->withNotify(['error'=>'Something went wrong try again after sometimes']);
        }

    }


    public function sellStock($id)
    {
        $userStock = UserStock::where('user_id', auth()->user()->id)->where('id', $id)->first();
        if (!$userStock) {
            $message = 'Please to sell a valid stock';
            $notify[] = ['error', $message];
            return redirect()->back()->withNotify($notify);
        }
        $preRequest = StockSellRequest::where('user_id', auth()->user()->id)->where('user_stock_id', $userStock->id)->first();
        if ($preRequest && $preRequest->status='pending') {
            $errmessage = 'You already created a sell request for this stock. Wait for update';
            $notify[] = ['error', $errmessage];
            return redirect()->back()->withNotify($notify);
        }

        if ($preRequest && $preRequest->status=='approved') {
            $errmessage = 'You already sold this stock';
            $notify[] = ['error', $errmessage];
            return redirect()->back()->withNotify($notify);
        }

        $sellRequest = new StockSellRequest();
        $sellRequest->user_id = $userStock->user_id;
        $sellRequest->product_id = $userStock->product_id;
        $sellRequest->user_stock_id = $userStock->id;
        $sellRequest->type = $userStock->type;
        $sellRequest->save();


        $message = 'Congratulations! Stock sell request is being process. Soon you will get update';
        $notify[] = ['success', $message];
        return redirect()->back()->withNotify($notify);

    }

    public function myStocks()
    {
        return \Inertia\Inertia::render('User/Stock/MyStocks', \App\Support\Holdings::portfolio(auth()->id(), 'stock') + [
            'kind' => 'stock',
            'urls' => [
                'qr'        => route('user.stock.qr.code'),
                'livePrice' => route('user.stock.live.price'),
                'market'    => route('user.stock.index'),
                'history'   => route('user.stock.transactions'),
            ],
        ]);
    }


    public function stockTransactions(Request $request)
    {
        return \Inertia\Inertia::render('User/Stock/History', [
            'kind' => 'stock',
            'tab'  => 'transactions',
            'rows' => \App\Support\Holdings::history(auth()->id(), 'stock', 'transactions', $request),
            'urls' => ['transactions' => route('user.stock.transactions'), 'interests' => route('user.stock.transaction.trx'), 'my' => route('user.stock.my')],
        ]);
    }

    public function stockTransferHistory(Request $request)
    {
        $pageTitle = "Stock Transfer History";
        $transactions = StockTransfer::orderByDesc('created_at')->where('user_id', auth()->user()->id);



        if($request->filter_date){
            $dates=explode('-', $request->filter_date);
            $startDate=isset($dates['0'])?$dates['0']:now()->subDays(7);
            $endDate=isset($dates['1'])?$dates['1']:now();
            $transactions=$transactions->whereBetween('created_at', [$startDate, $endDate]);
        }

        $transactions = $transactions->paginate(getPaginate())->withQueryString();

        return \Inertia\Inertia::render('User/Stock/Transfers', [
            'rows'   => \App\Support\InertiaData::paginate($transactions, fn ($t) => [
                'id'     => $t->id,
                'amount' => (float) $t->amount,
                'charge' => (float) $t->charge,
                'date'   => \App\Support\InertiaData::date($t->created_at),
            ]),
            'totals' => [
                'amount' => (float) StockTransfer::where('user_id', auth()->id())->sum('amount'),
                'charge' => (float) StockTransfer::where('user_id', auth()->id())->sum('charge'),
            ],
            'urls' => ['wallet' => route('user.wallet.stock')],
        ]);
    }

    public function stockInterest(Request $request)
    {
        return \Inertia\Inertia::render('User/Stock/History', [
            'kind' => 'stock',
            'tab'  => 'interests',
            'rows' => \App\Support\Holdings::history(auth()->id(), 'stock', 'interests', $request),
            'urls' => ['transactions' => route('user.stock.transactions'), 'interests' => route('user.stock.transaction.trx'), 'my' => route('user.stock.my')],
        ]);
    }

    public function dailyInterest(Request $request)
    {
        $pageTitle = "Daily Interest";
        // range on created_at (not DATE()) so the (user_id, type, created_at) index is used
        $today     = StockTransaction::where('user_id', auth()->id())->where('type', 'interest')
            ->whereBetween('created_at', [today(), today()->endOfDay()]);
        $total     = (clone $today)->sum('amount');
        $interests = (clone $today)->with('user_stock.product:id,name,image')->orderByDesc('id')->paginate(20);

        return \Inertia\Inertia::render('User/Stock/DailyInterest', [
            'total' => (float) $total,
            'rows'  => \App\Support\InertiaData::paginate($interests, fn ($i) => [
                'id'     => $i->id,
                'name'   => @$i->user_stock->product->name ?: 'Stock interest',
                'image'  => @$i->user_stock->product ? getImage(getFilePath('currency') . '/' . $i->user_stock->product->image, getFileSize('currency')) : null,
                'amount' => (float) $i->amount,
                'remark' => $i->remark,
                'date'   => \App\Support\InertiaData::date($i->created_at),
            ]),
            'urls' => ['history' => route('user.stock.transaction.trx'), 'my' => route('user.stock.my')],
        ]);
    }

    public function stockPdf($id)
    {
        $stock_product = UserStock::orderByDesc('created_at')->where('user_id', auth()->user()->id)->where('id', $id)->firstOrFail();

        if(!$stock_product->product){
            $message = 'Can not download certificate, try again after sometimes';
            $notify[] = ['error', $message];
            return redirect()->back()->withNotify($notify);
        }

        $url=route('public.certificate',[$stock_product->certificate_id]);


        $data['stock_product']=$stock_product;
        $data['product']=$product=$stock_product->product;
        $stock_product_image=getFilePath('currency') .'/'.$product->certificate_image;

        $imageData = base64_encode(file_get_contents($stock_product_image));
        $data['base64Image'] = 'data:image/png;base64,' . $imageData;

        $data['bgImage']=asset('core/public/f4.jpeg');
//        $data['bgImage']=asset('core/public/certificate.png');


        $qrCodes = QrCode::size(60)->generate($url);
        $data['base64Svg']=base64_encode($qrCodes);


//        return view('pdf', $data);


//        if($stock_product->product->name=='Google' || $stock_product->product->name=='Microsoft'){
//            $pdf = Pdf::loadView('google', $data);
//        }if($stock_product->product->name=='Google' || $stock_product->product->name=='Microsoft'){
//            $pdf = Pdf::loadView('google', $data);
//        }

        $pdf = Pdf::loadView('pdf', $data);
        return $pdf->setPaper('A4')->stream();

    }
    public function public_certificate($id)
    {
        $stock_product = UserStock::where('certificate_id', $id)->firstOrFail();


        if(!$stock_product->product){
            $message = 'Can not download certificate, try again after sometimes';
            $notify[] = ['error', $message];
            return redirect()->back()->withNotify($notify);
        }



        $data['stock_product']=$stock_product;
        $data['product']=$product=$stock_product->product;
        $stock_product_image=getFilePath('currency') .'/'.$product->certificate_image;

        $imageData = base64_encode(file_get_contents($stock_product_image));
        $data['base64Image'] = 'data:image/png;base64,' . $imageData;

        try{
            $client = new Client(['verify' => false]);
            $stockSymbol = $product->stock_code; // Assuming $product->stock_code contains the stock symbol (e.g., "GOOGL")
            $apiKey = '$this->api_key';

// 1. Get Quote Information (Current Price, High, Low)
            $quoteUrl = "https://finnhub.io/api/v1/quote?symbol={$stockSymbol}&token={$apiKey}";
            $quoteResponse = $client->request('GET', $quoteUrl);
            $quoteData = json_decode($quoteResponse->getBody()->getContents(), true);

// 2. Get Company Profile Information (Market Cap, Company Name)
            $profileUrl = "https://finnhub.io/api/v1/stock/profile2?symbol={$stockSymbol}&token={$apiKey}";
            $profileResponse = $client->request('GET', $profileUrl);
            $profileData = json_decode($profileResponse->getBody()->getContents(), true);

// 3. Get Financial Metrics (PE Ratio, EPS, etc.)
            $metricsUrl = "https://finnhub.io/api/v1/stock/metric?symbol={$stockSymbol}&metric=all&token={$apiKey}";
            $metricsResponse = $client->request('GET', $metricsUrl);
            $metricsData = json_decode($metricsResponse->getBody()->getContents(), true);

// Consolidate Data
            $data['Company_Name'] = isset($profileData['name'])?$profileData['name']:'N/A';
                $data['Current_Price'] = isset($quoteData['c'])?$quoteData['c']:'N/A';
                $data['Market_Cap'] = isset($profileData['marketCapitalization'])?$profileData['marketCapitalization']:'N/A';
                $data['WeekRange']= (isset($metricsData['metric']['52WeekHigh'])?$metricsData['metric']['52WeekHigh']:'N/A') . " - " . (isset($metricsData['metric']['52WeekLow'])?$metricsData['metric']['52WeekLow']:'N/A');
                $data['Dividend_Yield'] = isset($metricsData['metric']['dividendYieldIndicatedAnnual'])?$metricsData['metric']['dividendYieldIndicatedAnnual']:'N/A';
                $data['Revenue_TTM'] = isset($metricsData['metric']['revenueTTM'])?$metricsData['metric']['revenueTTM']:'N/A';
                $data['Net_Income_TTM'] = isset($metricsData['metric']['netIncomeTTM'])?$metricsData['metric']['netIncomeTTM']:'N/A';
                $data['EPS_TTM'] = isset($metricsData['metric']['epsTTM'])?$metricsData['metric']['epsTTM']:'N/A';
                $data['PE_Ratio'] = isset($metricsData['metric']['peNormalizedAnnual'])?$metricsData['metric']['peNormalizedAnnual']:'N/A';
        }catch(\Exception $exception){
        }

        $url=route('public.certificate',[$stock_product->certificate_id]);
        $qrCodes = QrCode::size(60)->generate($url);
        $data['base64Svg']=base64_encode($qrCodes);


//        'bonds','economy','indices','options'


        if($product->use_for=='bond' && $product->bond_type=='bonds'){
            return view('bond', $data);
        }elseif($product->use_for=='bond' && $product->bond_type=='economy'){
            return view('economy_bond', $data);
        }elseif($product->use_for=='bond' && $product->bond_type=='indices'){
            return view('options_bond', $data);
        }elseif($product->use_for=='bond' && $product->bond_type=='options'){
            return view('options_bond', $data);
        }


        return view('stock_pdf_2', $data);


        $pdf = Pdf::loadView('google', $data);
        return $pdf->setPaper('A4')->stream();

    }


    public function trasferStockAmount(Request $request)
    {
        DB::beginTransaction();

        try{

            $stock_wallet = StockWallet::where('user_id', auth()->user()->id)->first();
            if (!$stock_wallet) {
                return returnBack('User wallet not found try again after sometimes', 'error');
            }
            if (!$request->amount || $request->amount > $stock_wallet->amount) {
                return returnBack('Enter Valid Amount And Try Again', 'error');
            }


            $currency = Currency::where('symbol', 'USDT')->first();
            if (!$currency) {
                $notifye[] = ['error', 'Can not process at this moment'];
                return redirect()->back()->withNotify($notifye);
            }

            $user_wallet = Wallet::where('user_id', auth()->user()->id)->where('currency_id', $currency->id)->first();
            if (!$user_wallet) {
                $notifyw[] = ['error', 'Can not process at this moment'];
                return redirect()->back()->withNotify($notifyw);
            }

            $stock_transfer_charge=gs('stock_transfer_charge')?gs('stock_transfer_charge'):'5';

            $transfer_amount=$request->amount;
            $charge_amount=($transfer_amount * $stock_transfer_charge) / 100;
            $grand_transfer_amount=$transfer_amount - $charge_amount;
            $new_stock_amount=$stock_wallet->amount - $grand_transfer_amount;
            $insertAmount=$new_stock_amount - $charge_amount;


            if($new_stock_amount <= 0){
                $notifyws[] = ['error', 'Insufficient wallet balance'];
                return redirect()->back()->withNotify($notifyws);
            }

            $stock_wallet->amount=$insertAmount;
            $stock_wallet->save();


            $old_usdt=$user_wallet->balance + $grand_transfer_amount;
            $user_wallet->balance=$old_usdt;
            $user_wallet->save();

            $transfer_history=new StockTransfer();
            $transfer_history->user_id=auth()->user()->id;
            $transfer_history->wallet_id=$stock_wallet->id;
            $transfer_history->amount=$grand_transfer_amount;
            $transfer_history->charge=$charge_amount;
            $transfer_history->trx=strtoupper(Str::random(24));
            $transfer_history->remark='Stock Wallet Amount Transfer To Spot Wallet | Amount: '.$transfer_amount;
            $transfer_history->save();


            $transaction               = new Transaction();
            $transaction->user_id      = auth()->user()->id;
            $transaction->amount       = $grand_transfer_amount;
            $transaction->post_balance = $user_wallet->balance;
            $transaction->charge       = $charge_amount;
            $transaction->trx_type     = '-';
            $transaction->details      = showAmount($grand_transfer_amount,currencyFormat:false) . ' Transferred to Spot Wallet';
            $transaction->trx          = $transfer_history->trx;
            $transaction->remark       = 'stock_to_spot';
            $transaction->wallet_id    = $user_wallet->id;
            $transaction->save();



            DB::commit();
            return redirect()->back()->with('success', 'Amount Successfully Transferred');
        }catch(\Exception $ex){
            DB::rollBack();

            return redirect()->back()->withErrors(['error'=> 'Something went wrong']);
        }
    }


    public function getLivePrice(Request $request)
    {

        try {
            $client = new Client(['verify' => false]);
            $urls = "https://finnhub.io/api/v1/quote?symbol=".$request->code."&token=$this->api_key";
            $response = $client->request('GET', $urls);
            $response = $response->getBody()->getContents();
            $response = json_decode($response, true);
            $invest_amount = isset($response['c']) ? $response['c'] : 0;

            return response()->json(['amount'=>$invest_amount, 'status'=>'success']);
        } catch (\Exception $ex) {
            return response()->json(['amount'=>0, 'status'=>'failed']);

        }

    }

}
