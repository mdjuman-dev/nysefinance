<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CopyTrade;
use App\Models\CopyTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CopyController extends Controller
{

    public function index()
    {
        $data['pageTitle'] = "Copy Trade";

        $data['trades'] = CopyTrade::orderByDesc('created_at')->paginate(20);

        $data['total_sell']=CopyTransaction::where('type','sell')->sum('amount');
        $data['total_buy']=CopyTransaction::where('type','buy')->sum('amount');
        $data['total_interest']=CopyTransaction::where('type','interest')->sum('amount');

        return view('admin.copy_trade.list', $data);

    }

    public function sellHistory()
    {
        $data['pageTitle'] = "Copy Trade Analytics";

        $data['trades'] = CopyTrade::orderByDesc('created_at')->paginate(20);

        $data['total_sell']=CopyTransaction::where('type','sell')->sum('amount');
        $data['total_buy']=CopyTransaction::where('type','buy')->sum('amount');
        $data['total_interest']=CopyTransaction::where('type','interest')->sum('amount');


        return view('admin.copy_trade.analytics', $data);

    }


    public function create()
    {
        $data['pageTitle'] = "Copy Trade Create";

        return view('admin.copy_trade.create', $data);
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'trade_type' => 'required|in:classic,puzzle_hunt,by_votes,token_splash,gold_fx,spot_x',
            'type' => 'required',
            'short_details' => 'required',
            'details' => 'required',
            'image' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'interest' => 'required',
        ]);



        if($request->start_date && $request->start_date < now()){
            $notify[] = ['error', 'Start date must be getter than current date.'];
            return back()->withNotify($notify);
        }


        if($request->end_date && $request->end_date < $request->start_date){
            $notify[] = ['error', 'End date must be getter than start date.'];
            return back()->withNotify($notify);
        }


        $copyTrade = new CopyTrade();
        $copyTrade->name = $request->name;
        $copyTrade->slug = Str::slug($request->name);
        $copyTrade->trade_type = $request->trade_type;
        $copyTrade->type = $request->type;
        $copyTrade->stared = $request->stared;
        $copyTrade->amount = $request->amount;
        $copyTrade->status = $request->status;
        $copyTrade->short_details = $request->short_details;
        $copyTrade->details = $request->details;
        $copyTrade->start_date = $request->start_date;
        $copyTrade->end_date = $request->end_date;
        $copyTrade->interest = $request->interest;
        $copyTrade->count_day_one = $request->count_day_one;
        $copyTrade->count_profit_one = $request->count_profit_one;
        $copyTrade->count_day_two = $request->count_day_two;
        $copyTrade->count_profit_two = $request->count_profit_two;
        $copyTrade->dw_day = $request->dw_day;
        $copyTrade->dw_profit = $request->dw_profit;
        $copyTrade->total_prize = $request->total_prize;

        $copyTrade->profit_type = 'profit';
        if ($request->hasFile('image')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->image, $path, $size, @$copyTrade->image);
                $copyTrade->image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $copyTrade->save();

        $message = 'Copy Trade Successfully Created';

        return returnBack($message, 'success');

    }


    public function edit($id)
    {
        $data['pageTitle'] = "Copy Trade Edit";
        $data['trade'] = CopyTrade::findOrFail($id);

        return view('admin.copy_trade.edit', $data);
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'trade_type' => 'required|in:classic,puzzle_hunt,by_votes,token_splash,gold_fx,spot_x',
            'type' => 'required',
            'short_details' => 'required',
            'details' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'interest' => 'required',
        ]);





        if($request->end_date && $request->end_date < $request->start_date){
            $notify[] = ['error', 'End date must be getter than start date.'];
            return back()->withNotify($notify);
        }


        $copyTrade = CopyTrade::findOrFail($id);
        $copyTrade->name = $request->name;
        $copyTrade->slug = Str::slug($request->name);
        $copyTrade->trade_type = $request->trade_type;
        $copyTrade->type = $request->type;
        $copyTrade->amount = $request->amount;
        $copyTrade->stared = $request->stared;
        $copyTrade->status = $request->status;
        $copyTrade->short_details = $request->short_details;
        $copyTrade->details = $request->details;
        $copyTrade->start_date = $request->start_date;
        $copyTrade->end_date = $request->end_date;
        $copyTrade->interest = $request->interest;
        $copyTrade->count_day_one = $request->count_day_one;
        $copyTrade->count_profit_one = $request->count_profit_one;
        $copyTrade->count_day_two = $request->count_day_two;
        $copyTrade->count_profit_two = $request->count_profit_two;
        $copyTrade->dw_day = $request->dw_day;
        $copyTrade->dw_profit = $request->dw_profit;
        $copyTrade->total_prize = $request->total_prize;

        $copyTrade->profit_type = 'profit';
        if ($request->hasFile('image')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->image, $path, $size, @$copyTrade->image);
                $copyTrade->image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $copyTrade->save();


        $message = 'Copy Trade Successfully Updated';

        return returnBack($message, 'success');

    }


    public function delete($id)
    {
        $stock = CopyTrade::findOrFail($id);


//        $stockUsed=UserStock::where('product_id', $stock->id)->first();
//        if($stockUsed){
//            $notify[] = ['error', 'Stock already used, can not delete it'];
//            return back()->withNotify($notify);
//        }

        $stock->delete();


        $message = 'Copy Trade Successfully Deleted';

        return returnBack($message, 'success');
    }

    public function copyTradeHistory(Request $request)
    {
        $data['pageTitle'] = "Copy Trade History";

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
            $startDate=Carbon::parse(trim($startDate));
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



        return view('admin.copy_trade.transactions', $data);

    }


}
