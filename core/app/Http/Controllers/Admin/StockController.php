<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CustomInterest;
use App\Models\Product;
use App\Models\StockExchange;
use App\Models\StockMember;
use App\Models\StockSellRequest;
use App\Models\StockTransaction;
use App\Models\StockWallet;
use App\Models\User;
use App\Models\UserStock;
use App\Models\Wallet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockController extends Controller
{

    protected $api_key;

    public function __construct()
    {
        $this->api_key=stockPriceApi();
    }


    public function index()
    {
        $data['pageTitle'] = "Stocks";

        $data['products'] = Product::orderByDesc('created_at')->where('use_for', 'stock')->paginate(20);

        return view('admin.stock.list', $data);

    }


    public function bondStock()
    {
        $data['pageTitle'] = "Bonds";

        $data['products'] = Product::orderByDesc('created_at')->where('use_for', 'bond')->paginate(20);

        return view('admin.stock.list', $data);

    }

    public function create()
    {
        $data['pageTitle'] = "Stock Create";

        return view('admin.stock.create', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'stock_code' => 'required',
            'description' => 'required',
            'image' => 'required',
            'price' => 'required',
        ]);


        $stock = new Product();
        $stock->name = $request->name;
        $stock->slug = Str::slug($request->name);
        $stock->short_description = $request->short_description;
        $stock->description = $request->description;
        $stock->stock_code = $request->stock_code;
        $stock->price = $request->price;
        $stock->fix_rate = $request->fix_rate;
        $stock->unfix_rate = $request->unfix_rate;
        $stock->use_for = $request->use_for;
        $stock->bond_type = $request->bond_type;
        if ($request->hasFile('image')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->image, $path, $size, @$stock->image);
                $stock->image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        if ($request->hasFile('certificate_image')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->certificate_image, $path, $size, @$stock->certificate_image);
                $stock->certificate_image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $stock->save();

        $message = 'Stock Successfully Created';

        return returnBack($message, 'success');

    }

    public function edit($id)
    {
        $data['pageTitle'] = "Stock Edit";
        $data['product'] = Product::findOrFail($id);

        return view('admin.stock.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'stock_code' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);


        $stock = Product::findOrFail($id);
        $stock->name = $request->name;
        $stock->slug = Str::slug($request->name);
        $stock->short_description = $request->short_description;
        $stock->description = $request->description;
        $stock->stock_code = $request->stock_code;
        $stock->price = $request->price;
        $stock->fix_rate = $request->fix_rate;
        $stock->unfix_rate = $request->unfix_rate;
        $stock->use_for = $request->use_for;
        $stock->bond_type = $request->bond_type;
        if ($request->hasFile('image')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->image, $path, $size, null);
                $stock->image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        if ($request->hasFile('certificate_image')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->certificate_image, $path, $size, null);
                $stock->certificate_image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $stock->save();

        $message = 'Stock Successfully Updated';

        return returnBack($message, 'success');

    }

    public function delete($id)
    {
        $stock = Product::findOrFail($id);


        $stockUsed=UserStock::where('product_id', $stock->id)->first();
        if($stockUsed){
            $notify[] = ['error', 'Stock already used, can not delete it'];
            return back()->withNotify($notify);
        }

        $stock->delete();


        $message = 'Stock Successfully Deleted';

        return returnBack($message, 'success');
    }


    public function stockRequest(Request $request)
    {
        $data['pageTitle'] = "Stock Sell Request";
        $sellRequests = StockSellRequest::with('user', 'product', 'user_stock');

        if($request->email){
            $sellRequests->where('user_id', $request->email);
        }

        if($request->uid){
            $sellRequests->where('user_id', $request->uid);
        }

        $data['sellRequests']=$sellRequests->orderByDesc('created_at')->paginate(20);

        $data['users']=User::select(['id','email','uid'])->orderByDesc('created_at')->get();

        return view('admin.stock.sell_request', $data);

    }

    public function stockStatistic($id){

        $data['pageTitle']='Stock Statistic';

        $data['stock']=$stock=Product::findOrFail($id);

        $userStocksIds=UserStock::where('product_id', $id)->pluck('id');

        $transactions=StockTransaction::with('user', 'user_stock')->whereIn('stock_id', $userStocksIds)->where('type', '!=', 'interest')->orderByDesc('created_at');
        $data['transactions'] = $transactions->paginate(20);

        $data['totalSell']=StockTransaction::whereIn('stock_id', $userStocksIds)->where('type', 'sell')->sum('amount');
        $data['totalBuy']=StockTransaction::whereIn('stock_id', $userStocksIds)->where('type', 'buy')->sum('amount');
        $data['totalInterest']=StockTransaction::whereIn('stock_id', $userStocksIds)->where('type', 'interest')->sum('amount');
        $data['totalExchange']=StockTransaction::whereIn('stock_id', $userStocksIds)->where('type', 'exchange')->sum('amount');




        return view('admin.stock.statistic', $data);
    }



    public function stockExchangeRequest(Request $request)
    {
        $data['pageTitle'] = "Stock Exchange Request";

        if($request->type && $request->type=='approved'){
            $data['exchangeRequests'] = StockExchange::where('status', 'approved')->orderByDesc('created_at')->paginate(20);
        }else{
            $data['exchangeRequests'] = StockExchange::where('status', 'pending')->orderByDesc('created_at')->paginate(20);
        }

        return view('admin.stock.exchange_request', $data);

    }

    public function stockExchangeStatus(Request $request)
    {


        DB::beginTransaction();

        try {
            $exchange_request = StockExchange::where('id', $request->request_id)->where('status', 'pending')->firstOrFail();
            $user_stock = UserStock::where('id', $exchange_request->user_stock_id)->where('status', 'buy')->firstOrFail();

            if ($request->request_status == 'approve') {


                // $currency = Currency::where('symbol', 'USDT')->first();
                // if (!$currency) {
                //     $notifye[] = ['error', 'Can not buy stock at this moment'];
                //     return redirect()->back()->withNotify($notifye);
                // }

                // $user_wallet = Wallet::where('user_id', $exchange_request->user_id)->where('currency_id', $currency->id)->first();
                // if (!$user_wallet) {
                //     $notifyw[] = ['error', 'Can not buy stock at this moment'];
                //     return redirect()->back()->withNotify($notifyw);
                // }

                // $deduct_amount = $user_wallet->balance + $user_stock->invest_amount;
                // $user_wallet->balance = $deduct_amount;
                // $user_wallet->save();

                $user_stock->status = 'exchange';
                $user_stock->save();

                $exchange_request->status = 'approved';
                $exchange_request->save();

            } elseif ($request->request_status == 'reject') {
//                $currency = Currency::where('symbol', 'USDT')->first();
//                if (!$currency) {
//                    $notifye[] = ['error', 'Can not buy stock at this moment'];
//                    return redirect()->back()->withNotify($notifye);
//                }
//
//                $user_wallet = Wallet::where('user_id', $exchange_request->user_id)->where('currency_id', $currency->id)->first();
//                if (!$user_wallet) {
//                    $notifyw[] = ['error', 'Can not buy stock at this moment'];
//                    return redirect()->back()->withNotify($notifyw);
//                }

                // $deduct_amount = $user_wallet->balance - 3;
                // $user_wallet->balance = $deduct_amount;
                // $user_wallet->save();

                $exchange_request->delete();
            }

            $stockTran = new StockTransaction();
            $stockTran->user_id = $user_stock->user_id;
            $stockTran->type = 'exchange';
            $stockTran->stock_type = $user_stock->type;
            $stockTran->amount = $user_stock->invest_amount;
            $stockTran->stock_id = $user_stock->id;
            $stockTran->use_for = $user_stock->use_for;
            $stockTran->save();


            DB::commit();
            $notifyw[] = ['success', 'Status Successfully Changed'];
            return redirect()->back()->withNotify($notifyw);
        }catch(\Exception $ex){

            DB::rollBack();

            $notifyw[] = ['success', 'Something went wrong, try again after sometimes'];
            return redirect()->back()->withNotify($notifyw);

        }

    }
    public function stockMembers(Request $request)
    {
        $data['pageTitle'] = "Stock Members";

        $stockMember=StockMember::with('user');

        if($request->email){
            $stockMember->where('user_id', $request->email);
        }

        if($request->uid){
            $stockMember->where('user_id', $request->uid);
        }

        if($request->status){
            $stockMember->where('status', $request->status);
        }

        $data['users']=User::select(['id','email','uid'])->orderByDesc('created_at')->get();

        $data['stock_members'] = $stockMember->orderByDesc('created_at')->paginate(20);

        return view('admin.stock.stock_members', $data);

    }

    public function stockStatus(Request $request)
    {

        $request->validate([
            'request_status' => 'required',
            'request_id' => 'required',
        ]);


        DB::beginTransaction();


        try {

            $currency=Currency::where('symbol', 'USDT')->first();

            if(!$currency){
                return returnBack('Please enter charge to approve this request', 'error');
            }


            $sellRequest = StockSellRequest::where('id', $request->request_id)->where('status', 'pending')->firstOrFail();
            $product = Product::where('id', $sellRequest->product_id)->firstOrFail();
            $user_stock = UserStock::where('id', $sellRequest->user_stock_id)->firstOrFail();
            if ($request->request_status == 'approve') {
                if (!$request->charge) {
                    return returnBack('Please enter charge to approve this request', 'error');
                }
                $spot_wallet=Wallet::where('user_id', $user_stock->user_id)->where('currency_id', $currency->id)->where('wallet_type', '1')->first();

                if (!$spot_wallet) {
                    return returnBack('User wallet not found try again after sometimes', 'error');
                }


                if($sellRequest->type=='unfix') {
                    try {
                        $client = new Client(['verify' => false]);
                        $urls = "https://finnhub.io/api/v1/quote?symbol=" . $product->stock_code . "&token=$this->api_key";
                        $response = $client->request('GET', $urls);
                        $response = $response->getBody()->getContents();
                        $response = json_decode($response, true);
                        $invest_amount = isset($response['c']) ? $response['c'] : $product->price;
                    } catch (\Exception $exxxx) {
                        $invest_amount = $product->price;
                    }

                    $paidAmount = $user_stock->invest_amount;
                    if ($user_stock->invest_percent == '25') {
                        $paidAmount = ($invest_amount * 25) / 100;
                    } elseif ($user_stock->invest_percent == '50') {
                        $paidAmount = ($invest_amount * 50) / 100;
                    } elseif ($user_stock->invest_percent == '100') {
                        $paidAmount = ($invest_amount * 100) / 100;
                    }else if($user_stock->invest_percent){
                        $paidAmount = ($invest_amount * $user_stock->invest_percent) / 100;
                    }
                }else{
                    $paidAmount = $user_stock->invest_amount;
                }


                $chargeAmount=($paidAmount * $request->charge) / 100;
                $grandAmount = $paidAmount - $chargeAmount;



                //Create Stock Transaction
                $stockTran = new StockTransaction();
                $stockTran->user_id = $user_stock->user_id;
                $stockTran->type = 'sell';
                $stockTran->stock_type = 'unfix';
                $stockTran->amount = $grandAmount;
                $stockTran->charge = $request->charge;
                $stockTran->stock_id = $user_stock->id;
                $stockTran->use_for = $user_stock->use_for;
                $stockTran->save();


                //Add Amount To Stock Wallet
                $stock_wallet_pre_amount = $spot_wallet->balance + $grandAmount;
                $spot_wallet->balance = $stock_wallet_pre_amount;
                $spot_wallet->save();

                //Approve Sell Request
                $sellRequest->status = 'approved';
                $sellRequest->charge = $request->charge;
                $sellRequest->trx_id=Str::random(22);
                $sellRequest->save();

                //Update User Stock Status
                $user_stock->status='sell';
                $user_stock->save();
            }else{
                $sellRequest->status = 'rejected';
                $sellRequest->charge = '0.00';
                $sellRequest->save();

//                $user_stock->status='sell';
//                $user_stock->save();
            }

            DB::commit();

            return returnBack('Sell request successfully '.strtoupper($sellRequest->status), 'success');

        } catch (\Exception $e) {
            DB::rollBack();

            return returnBack('Something went wrong try again after sometimes', 'error');
        }

    }

    public function transactions(Request $request)
    {

        $data['pageTitle'] = "Stock Transactions";
        if($request->user_id) {
            $data['transactions'] = StockTransaction::with('user', 'user_stock')->where('user_id', $request->user_id)->where('type', '!=', 'interest')->orderByDesc('created_at')->paginate(20);
        }else {


            $transactions=StockTransaction::with('user', 'user_stock')->where('type', '!=', 'interest')->orderByDesc('created_at');
            if($request->date){
                $dates=explode('-', $request->date);
                if(isset($dates['0']) && $dates['0']){
                    $startDate=Carbon::parse($dates['0']);
                }else{
                    $startDate=now()->subDays(7);
                }
                if(isset($dates['1']) && $dates['1']){
                    $endDate=Carbon::parse($dates['1']);
                }else{
                    $endDate=now();
                }
                $transactions=$transactions->whereBetween('created_at', [$startDate, $endDate]);
            }

            if($request->trx_type){
                $transactions=$transactions->where('stock_type', $request->trx_type);
            }

            if($request->email){
                $transactions=$transactions->where('user_id', $request->email);
            }


            $data['transactions'] = $transactions->paginate(20);
        }

        $data['users']=User::select(['id','email','uid'])->orderByDesc('created_at')->get();
        $data['products']=Product::select(['id', 'name'])->orderByDesc('created_at')->get();

        return view('admin.stock.transactions', $data);

    }


    public function interests(Request $request)
    {



        $data['pageTitle'] = "Stock Interest";

        $interests=StockTransaction::with('user','user_stock')->where('type', 'interest')->orderByDesc('created_at');

        if($request->user_id && $request->user_id !='null'){
            $interests=$interests->where('user_id', $request->user_id);
        }

        if($request->date){
            $dates=explode('-', $request->date);

            if(isset($dates['0']) && $dates['0']){
                $startDate=Carbon::parse($dates['0']);
            }else{
                $startDate=now()->subDays(7);
            }

            if(isset($dates['1']) && $dates['1']){
                $endDate=Carbon::parse($dates['1']);
            }else{
                $endDate=now();
            }



//            $startDate=isset($dates['0'])?trim($dates['0']):now()->subDays(7);
//            $endDate=isset($dates['1'])?trim($dates['1']):now();

            $interests=$interests->whereBetween('created_at', [$startDate, $endDate]);
        }

        $interests=$interests->paginate(20);


        $data['interests'] = $interests;
        $data['users']=User::where('status', '1')->select(['id','email', 'firstname','lastname'])->orderByDesc('created_at')->get();

        return view('admin.stock.interest', $data);
    }



    public function userStocks(Request $request)
    {
        $data['pageTitle'] = "User Stocks";
        $custom_interests = CustomInterest::orderByDesc('created_at');

        if($request->uid){
            $custom_interests->where('user_id', $request->uid);
        }
        if($request->status){
            $custom_interests->where('status', $request->status);
        }

        if($request->email){
            $custom_interests->where('user_id', $request->email);
        }


        $data['custom_interests']=$custom_interests->paginate(getPaginate());
        $data['users']=User::select(['id','email','uid'])->orderByDesc('created_at')->get();
        return view('admin.stock.user_stocks', $data);

    }

    public function updateUserStocks(Request $request)
    {

        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $custom_interest=CustomInterest::where('user_id', $request->user_id)->where('id', $request->id)->firstOrFail();

        if($request->interest && $request->interest > 0) {
            $custom_interest->interest = $request->interest;
            $custom_interest->status = $request->status;
            $custom_interest->save();
        }

//        Live Market == unfix
//        Mutual == Fix



        $notifyw[] = ['success', 'User Stock Interest updated successfully'];
        return redirect()->back()->withNotify($notifyw);
    }

}
