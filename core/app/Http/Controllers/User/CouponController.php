<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CardApplication;
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

    public function coupons()
    {

        $pageTitle = "JackPlay";

        $coupons=Coupon::orderByDesc('created_at')->where('expire_date', '>', now())->get();


        return view('Template::user.coupon.coupon', compact('pageTitle','coupons'));
    }


    public function myCoupons()
    {

        $pageTitle = "My JackPlay";

        $running_coupons=UserCoupon::with('coupon')->where('user_id', auth()->user()->id)->where('status', 'pending')->get();
        $win_coupons=UserCoupon::with('coupon')->where('user_id', auth()->user()->id)->where('status', 'win')->get();
        $expired_coupons=UserCoupon::with('coupon')->where('user_id', auth()->user()->id)->where('status', 'lose')->get();



        return view('Template::user.coupon.my_coupon', compact('pageTitle','running_coupons', 'expired_coupons','win_coupons'));
    }

    public function couponDetails($id)
    {

        $pageTitle = "Details";

        $coupon=Coupon::where('expire_date', '>', now())->where('id', $id)->firstOrFail();
        $userCoupon=UserCoupon::where('user_id', auth()->id())->where('coupon_id', $coupon->id)->first();


        return view('Template::user.coupon.details', compact('pageTitle','coupon','userCoupon'));
    }

    public function couponBuy(Request $request)
    {

        $coupon=Coupon::where('id', $request->id)->where('expire_date', '>', now())->first();
        if(!$coupon){
            $message[]=['errors'=>'This JackPlay does not exist'];
            return back()->withErrors($message);
        }

        $currency=Currency::where('symbol', 'USDT')->first();
        if(!$currency){
            $message[]=['errors'=>'This currency does not exist'];
            return back()->withErrors($message);
        }


        $wallet=Wallet::where('user_id', auth()->user()->id)->where('currency_id', $currency->id)->where('wallet_type', '1')->first();
        if(!$wallet){
            $message[]=['errors'=>'Wallet does not exist'];
            return back()->withErrors($message);
        }

        $request['quantity']=$request->quantity?$request->quantity:1;

        $grandPrice=$coupon->price * $request->quantity;

        if($wallet->balance < $grandPrice){
            $message[]=['errors'=>'This coupon does not have enough money'];
            return back()->withErrors($message);
        }

       DB::beginTransaction();

        try{
            $mew_balance=$wallet->balance - $grandPrice;

            $wallet->balance=$mew_balance;
            $wallet->save();

            for ($i = 0; $i < $request->quantity; $i++) {

                $userCoupon = new UserCoupon();
                $userCoupon->price=$coupon->price;
                $userCoupon->user_id=auth()->user()->id;
                $userCoupon->coupon_id=$coupon->id;
                $userCoupon->wining_date=$coupon->wining_date;
                $userCoupon->code=strtoupper(Str::random(11));
                $userCoupon->trx_id=Str::random(16);
                $userCoupon->save();


                $transaction               = new Transaction();
                $transaction->user_id      = auth()->user()->id;
                $transaction->amount       = $coupon->price;
                $transaction->post_balance = $wallet->balance;
                $transaction->charge       = 0.00;
                $transaction->trx_type     = '-';
                $transaction->details      = showAmount($coupon->price,currencyFormat:false) . ' Buy Coupon '.$coupon->name;
                $transaction->trx          = Str::random(17);
                $transaction->remark       = 'buy_coupon';
                $transaction->wallet_id    = $wallet->id;
                $transaction->save();

            }


            $message[]=['success'=>'Coupon has been buy successfully'];
            DB::commit();
            return redirect()->route('user.coupons')->withNotify($message);

        }catch (\Exception $exception){
            DB::rollBack();
            $message[]=['errors'=> 'Something went wrong'];
            return back()->withErrors($message);
        }



    }

}
