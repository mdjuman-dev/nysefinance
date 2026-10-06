<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\UserCoupon;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CouponController extends Controller
{

    public function index()
    {

        $data['pageTitle'] = 'JackPlay';
        $data['coupons']=Coupon::orderByDesc('created_at')->paginate(getPaginate());

        return view('admin.coupon.list', $data);
    }

    public function create()
    {
        $data['pageTitle'] = 'Add JackPlay';
        return view('admin.coupon.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'icon'=>'required',
            'expire_date'=>'required',
            'wining_date'=>'required',
            'price'=>'required',
        ]);


        $coupon= new Coupon();
        $coupon->name=$request->name;
        $coupon->description=$request->description;
        $coupon->code=strtoupper(Str::random(8));
        $coupon->expire_date=$request->expire_date;
        $coupon->wining_date=$request->wining_date;
        $coupon->price=$request->price;
        $coupon->total_buy=$request->total_buy;
        $coupon->total_buy_amount=$request->total_buy_amount;
        if ($request->hasFile('icon')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->icon, $path, $size, @$coupon->icon);
                $coupon->icon = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $coupon->save();

        $notify[]=['success', 'JackPlay Successfully Created'];

        return redirect()->route('admin.coupon')->withNotify($notify);
    }


    public function edit($id)
    {

        $data['pageTitle'] = 'Edit JackPlay';
        $data['coupon']=Coupon::findOrFail($id);

        return view('admin.coupon.edit', $data);
    }


    public function update($id, Request $request)
    {
        $request->validate([
            'name'=>'required',
            'expire_date'=>'required',
            'wining_date'=>'required',
            'price'=>'required',
        ]);


        $coupon= Coupon::findOrFail($id);
        $coupon->name=$request->name;
        $coupon->description=$request->description;
        $coupon->code=strtoupper(Str::random(8));
        $coupon->expire_date=$request->expire_date;
        $coupon->wining_date=$request->wining_date;
        $coupon->price=$request->price;
        $coupon->total_buy=$request->total_buy;
        $coupon->total_buy_amount=$request->total_buy_amount;
        if ($request->hasFile('icon')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->icon, $path, $size, @$coupon->icon);
                $coupon->icon = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $coupon->save();

        $notify[]=['success', 'Coupon Successfully Updated'];

        return redirect()->route('admin.coupon')->withNotify($notify);
    }

    public function details($id){

        $data['pageTitle'] = 'Details Coupon';
        $coupon=Coupon::findOrFail($id);

        $data['coupon']=$coupon;
        $data['user_coupons']=UserCoupon::with(['user','coupon'])->where('coupon_id',$id)->get();

        return view('admin.coupon.details', $data);
    }


    public function statusWin(Request $request){
        $coupon=Coupon::where('id', $request->coupon_id)->firstOrFail();



        $request->validate([
            'win_price' => "required|numeric|min:{$coupon->price}",
        ]);


        $userCoupon=UserCoupon::where('id', $request->user_coupon_id)->where('coupon_id', $coupon->id)->where('status', 'pending')->firstOrFail();


        $currency=Currency::where('symbol', 'USDT')->first();
        if(!$currency){
            $message[]=['errors'=>'This currency does not exist'];
            return back()->withErrors($message);
        }


        $wallet=Wallet::where('user_id', $userCoupon->user_id)->where('currency_id', $currency->id)->where('wallet_type', '1')->first();
        if(!$wallet){
            $message[]=['errors'=>'Wallet does not exist'];
            return back()->withErrors($message);
        }


        DB::beginTransaction();

        try{

            $userCoupon->status='win';
            $userCoupon->win_price=$request->win_price;
            $userCoupon->details=$request->details;
            $userCoupon->save();

            $mew_balance=$wallet->balance + $request->win_price;

            $wallet->balance=$mew_balance;
            $wallet->save();


            $transaction               = new Transaction();
            $transaction->user_id      = $userCoupon->user_id;
            $transaction->amount       = $request->win_price;
            $transaction->post_balance = $wallet->balance;
            $transaction->charge       = 0.00;
            $transaction->trx_type     = '+';
            $transaction->details      = showAmount($request->win_price,currencyFormat:false) . ' Win Coupon '.$coupon->name;
            $transaction->trx          = Str::random(17);
            $transaction->remark       = 'win_coupon';
            $transaction->wallet_id    = $wallet->id;
            $transaction->save();

            $message[]=['success'=>'Coupon mark as win successfully'];
            DB::commit();
            return redirect()->back()->withNotify($message);

        }catch (\Exception $exception){
            DB::rollBack();
            $message[]=['errors'=>$exception->getMessage()];
            return back()->withErrors($message);
        }

    }
    public function statusLose(Request $request){


        $coupon=Coupon::where('id', $request->coupon_id)->firstOrFail();

        $userCoupons=UserCoupon::where('status', 'pending')->whereNull('win_price')->where('coupon_id', $coupon->id)->get();
        if($userCoupons){
            foreach ($userCoupons as $userCoupon){
                $userCoupon->status='lose';
                $userCoupon->save();
            }
        }


        $message[]=['success', 'JackPlay Successfully Updated'];

        return redirect()->route('admin.coupon')->withNotify($message);

    }


}
