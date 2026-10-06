<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinStack;
use App\Models\CopyTransaction;
use App\Models\Currency;
use App\Models\StockTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{

    public function index(Request $request)
    {

        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::searchable(['trx', 'user:username'])->filter(['trx_type', 'remark', 'wallet.currency:symbol'])->dateFilter()->orderBy('id', 'desc')->with('user');
        if ($request->user_id) {
            $transactions = $transactions->where('user_id', $request->user_id);
        }
        $transactions = $transactions->paginate(getPaginate());
        $currencies   = Currency::active()->rankOrdering()->get();

        $pageTitle='Transactions';

        return view('admin.transactions.transactions', compact('pageTitle', 'transactions', 'remarks', 'currencies'));
    }

    public function stockTrx(Request $request)
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

            $data['transactions'] = $transactions->paginate(20);
        }

        return view('admin.transactions.stock_transactions', $data);


    }

    public function userPools(Request $request){
        $pageTitle = 'Coin Stack';
        $coin_stacks=CoinStack::orderByDesc('created_at')->paginate(getPaginate());


        return view('admin.transactions.coin_stack', compact('pageTitle', 'coin_stacks'));
    }



    public function copyTradeHistory(Request $request)
    {
        $data['pageTitle'] = "Copy Trade Transactions";

        $transactions=CopyTransaction::orderBy('created_at', 'desc');
        $total_sell=CopyTransaction::orderBy('created_at', 'desc')->where('type','sell');
        $total_buy=CopyTransaction::orderBy('created_at', 'desc')->where('type','buy');
        $total_interest=CopyTransaction::orderBy('created_at', 'desc')->where('type','interest');

        if($request->trade_type){
            $transactions=$transactions->where('trade_type', $request->trade_type);
        }

        if($request->type){
            $transactions=$transactions->where('type', $request->type);
        }

        if($request->user_id){
            $transactions=$transactions->where('user_id', $request->user_id);
            $total_buy=$total_buy->where('user_id', $request->user_id);
            $total_sell=$total_sell->where('user_id', $request->user_id);
            $total_interest=$total_interest->where('user_id', $request->user_id);
        }

        if($request->filter_date){
            $dates=explode('-', $request->filter_date);
            $startDate=isset($dates['0'])?$dates['0']:now()->subDays(7);
            $startDate= \Carbon\Carbon::parse(trim($startDate));
            $endDate=isset($dates['1'])?$dates['1']:now();
            $endDate=Carbon::parse(trim($endDate));

            $transactions=$transactions->whereBetween('created_at', [$startDate, $endDate]);



            $total_buy=$total_buy->whereBetween('created_at', [$startDate, $endDate]);
            $total_sell=$total_sell->whereBetween('created_at', [$startDate, $endDate]);
            $total_interest=$total_interest->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data['transactions']=$transactions->paginate(20);

        $data['users']=User::orderByDesc('created_at')->where('status', '1')->select(['id', 'firstname','lastname','email'])->get();


        $data['total_sell']=$total_sell->sum('amount');
        $data['total_buy']=$total_buy->sum('amount');
        $data['total_interest']=$total_interest->sum('amount');



        return view('admin.transactions.copy_trade', $data);

    }





}
