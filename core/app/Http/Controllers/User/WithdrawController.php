<?php

namespace App\Http\Controllers\User;

use App\Support\InertiaData;
use Inertia\Inertia;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\UserReport;
use App\Models\VerifyOtp;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{


    public function withdrawStore(Request $request)
    {
        $walletTypes = gs('wallet_types');

        $request->validate([
            'method_code' => 'required',
            'amount'      => 'required|numeric|gt:0',
            'currency'    => 'required',
            'wallet_type' => 'required|in:' . implode(',', array_keys((array) $walletTypes)),
        ]);


        $currency = Currency::active()->where('symbol', $request->currency)->first();

        if (!$currency) {
            return returnBack('Requested withdraw currency not found');
        }

        $walletType = $request->wallet_type;

        if (!checkWalletConfiguration($walletType, 'withdraw', $walletTypes)) {
            return returnBack("Withdraw from $walletType wallet currently disabled.");
        }

        $user   = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->$walletType()->first();

        if (!$wallet) {
            return returnBack('Requested withdraw currency wallet not found');
        }

        $haveReq=Withdrawal::where('user_id', $user->id)->whereDate('created_at', now())->first();
        if($haveReq){
            return returnBack('Daily Withdraw limit excesses. Try Next Day');
        }



        $method   = WithdrawMethod::where('id', $request->method_code)->where('currency', $currency->symbol)->where('status', Status::ENABLE)->first();

        if (!$method) {
            return returnBack('Requested withdraw method not found');
        }

        if ($request->amount < $method->min_limit) {
            return returnBack('Your requested amount is smaller than minimum amount.');
        }
        if ($request->amount > $method->max_limit) {
            return returnBack('Your requested amount is larger than maximum amount.');
        }

        if ($request->amount > $wallet->balance) {
            return returnBack('You do not have sufficient wallet balance for withdraw.');
        }


        $charge      = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge;

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id;
        $withdraw->user_id      = $user->id;
        $withdraw->amount       = $request->amount;
        $withdraw->currency     = $method->currency;
        $withdraw->rate         = $method->rate;
        $withdraw->charge       = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx          = getTrx();
        $withdraw->wallet_id    = $wallet->id;
        $withdraw->save();

        session()->put('wtrx', $withdraw->trx);
        return to_route('user.withdraw.preview');
    }

    public function loadForm(Request $request)
    {
        $id = $request->get('id');

        // You can return just the component blade or render it in controller
        return view('Template::user.withdraw.viser_form', ['formId' => $id])->render();
    }



    public function withdrawPreview()
    {
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->firstOrFail();
        $pageTitle = 'Withdraw Preview';


        return view('Template::user.withdraw.preview', compact('pageTitle', 'withdraw'));
    }

    public function withdrawSubmit(Request $request)
    {
//        $withdraw = Withdrawal::with('method', 'user', 'wallet')->where('trx', session()->get('wtrx'))->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->firstOrFail();



        $walletTypes = gs('wallet_types');


        $request->validate([
            'method_code' => 'required',
            'amount'      => 'required|numeric|gt:0',
            'currency'    => 'required',
            'withdraw_otp'    => 'required',
            'wallet_type' => 'required|in:' . implode(',', array_keys((array) $walletTypes)),
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


        $otp=VerifyOtp::where('user_id', auth()->user()->id)->where('type', 'withdraw')->whereDate('created_at', now())->where('otp', $request->withdraw_otp)->first();
        if(!$otp){
            return returnBack('Requested withdraw otp is invalid');
        }


        $user=auth()->user();
        $report=UserReport::where('user_id', $user->id)->whereIn('status', ['pending','review'])->first();
        $reportPtp=UserReport::where('repoted_user_id', $user->id)->whereIn('status', ['pending','review'])->first();
        if($report || $reportPtp){
            return redirect()->back()->withErrors(['error'=>'Your all transaction has been frozen. Wait  until your report solved']);
        }



        $currency = Currency::active()->where('symbol', $request->currency)->first();

        if (!$currency) {
            return returnBack('Requested withdraw currency not found');
        }

        $walletType = $request->wallet_type;

        if (!checkWalletConfiguration($walletType, 'withdraw', $walletTypes)) {
            return returnBack("Withdraw from $walletType wallet currently disabled.");
        }

        $user   = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->$walletType()->first();

        if (!$wallet) {
            return returnBack('Requested withdraw currency wallet not found');
        }

//        $haveReq=Withdrawal::where('user_id', $user->id)->whereDate('created_at', now())->first();
//        if($haveReq){
//            return returnBack('Daily Withdraw limit excesses. Try Next Day');
//        }
//
//

        $method   = WithdrawMethod::where('id', $request->method_code)->where('currency', $currency->symbol)->where('status', Status::ENABLE)->first();

        if (!$method) {
            return returnBack('Requested withdraw method not found');
        }
//
//        if ($request->amount < $method->min_limit) {
//            return returnBack('Your requested amount is smaller than minimum amount.');
//        }
//        if ($request->amount > $method->max_limit) {
//            return returnBack('Your requested amount is larger than maximum amount.');
//        }
//
//        if ($request->amount > $wallet->balance) {
//            return returnBack('You do not have sufficient wallet balance for withdraw.');
//        }


        $charge      = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge;

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id;
        $withdraw->user_id      = $user->id;
        $withdraw->amount       = $request->amount;
        $withdraw->currency     = $method->currency;
        $withdraw->rate         = $method->rate;
        $withdraw->charge       = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx          = getTrx();
        $withdraw->wallet_id    = $wallet->id;
        $withdraw->save();

        session()->put('wtrx', $withdraw->trx);






        $method   = $withdraw->method;
        $wallet   = $withdraw->wallet;

        if ($method->status == Status::DISABLE) {
            abort(404);
        }

        $formData       = $method->form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $user = auth()->user();

        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify);
            }
        }

        if ($withdraw->amount > $wallet->balance) {
            $notify[] = ['error', 'You do not have sufficient wallet balance for withdraw'];
            return back()->withNotify($notify)->withInput();
        }

        $withdraw->status               = Status::PAYMENT_PENDING;
        $withdraw->withdraw_information = $userData;
        $withdraw->save();

        $wallet->balance -= $withdraw->amount;
        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = $withdraw->charge;
        $transaction->trx_type     = '-';
        $transaction->details      = showAmount($withdraw->amount,currencyFormat:false) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw';
        $transaction->wallet_id    = $wallet->id;
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.data.details', $withdraw->id);
        $adminNotification->save();


        notify($user, 'WITHDRAW_REQUEST', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount,currencyFormat:false),
            'amount'          => showAmount($withdraw->amount,currencyFormat:false),
            'charge'          => showAmount($withdraw->charge,currencyFormat:false),
            'rate'            => showAmount($withdraw->rate,currencyFormat:false),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance,currencyFormat:false),
            'wallet_name'     => $wallet->name
        ]);

        $notify[] = ['success', 'Withdraw request sent successfully'];
        return to_route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog(Request $request)
    {
        $pageTitle = "Withdraw Log";
//        $withdraws = Withdrawal::searchable(['trx', 'withdrawCurrency:symbol'])->where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE)->with('method', 'wallet.currency')->orderBy('id', 'desc')->paginate(getPaginate());
        $withdraws = Withdrawal::searchable(['trx', 'withdrawCurrency:symbol'])->where('user_id', auth()->id())->whereNotIn('id', ['901','937','931','973','1005','1007','1015','1041','1064','1085','1097','1230'])->where('status', '!=', Status::PAYMENT_INITIATE)->with('method', 'wallet.currency')->orderBy('id', 'desc')->paginate(getPaginate());

        return Inertia::render('User/WithdrawHistory', [
            'withdraws' => InertiaData::paginate($withdraws, fn ($w) => [
                'id'       => $w->id,
                'trx'      => $w->trx,
                'method'   => __($w->method->name ?? ''),
                'currency' => $w->wallet->currency->symbol ?? $w->currency,
                'image'    => $w->wallet->currency->image_url ?? null,
                'wallet'   => trim(($w->wallet->name ?? '') . ' ' . strtoupper($w->wallet->type_text ?? '')),
                'payout'   => $w->currency,
                'amount'   => (float) $w->amount,
                'charge'   => (float) $w->charge,
                'receive'  => (float) $w->amount - (float) $w->charge,
                'status'   => InertiaData::badgeText($w->statusBadge),
                'details'  => collect($this->withdrawDisplayInfo($w) ?? [])->filter(fn ($i) => ($i['type'] ?? '') != 'file')->map(fn ($i) => ['name' => $i['name'] ?? '', 'value' => $i['value'] ?? ''])->values(),
                'feedback' => $w->status == Status::PAYMENT_REJECT ? $w->admin_feedback : null,
                'date'     => InertiaData::date($w->created_at),
            ]),
        ]);
    }

    /**
     * Address shown in the withdraw details. Carries over the per-ID overrides
     * that used to live in templates/basic/user/withdraw/log.blade.php.
     */
    private function withdrawDisplayInfo($withdraw): ?array
    {
        $address = fn ($a) => [['name' => 'Address', 'type' => 'text', 'value' => $a]];
        $id = (string) $withdraw->id;
        $status = (string) $withdraw->status;

        $overrides = [
            '898'  => ['2' => '0x54cd1b21ac51d0622f90f870c2b6f294bcbc9c6b'],
            '968'  => ['2' => '0xb61c0dcf413de308d5f534a95295dad4014d7f68'],
            '967'  => ['2' => '0xb61c0dcf413de308d5f534a95295dad4014d7f68'],
            '793'  => ['2' => '0xaf7fd0c69eedf0010c132126f4b215d3a66b0319'],
            '1096' => ['2' => '0x77e4b3f4669fa70228998999ed9de775b4ba422c'],
            '1109' => ['2' => 'TJ4VHuLNco1ssFJQ9wt2NUXh6ocQeC6D38'],
            '1111' => ['2' => '0x268dc6231c103ee33f731694523765db165377fe'],
            '1189' => ['2' => 'TMM5ok83toHSmD3W6ddfL5jLNz8BYNurju'],
            '1209' => ['2' => '0xb0d2a77e0671b3b86b25f941f3eeefce6addf236'],
            '1270' => ['1' => '0xa186F336722302a5B470a3733cae70820dbcDC98', '2' => '0xa40cc58be82d2dd9357263b67a87d0782ad16ad8', '3' => '0xa40cc58be82d2dd9357263b67a87d0782ad16ad8'],
            '1273' => ['1' => 'TU9o1TAtdrVQ9JJTKHxGfcni9oiNjAjG78', '2' => 'TNvBy9wxRNvk1W2G6TNWpHmXYPe2reHLHK', '3' => 'TNvBy9wxRNvk1W2G6TNWpHmXYPe2reHLHK'],
        ];

        if (isset($overrides[$id][$status])) {
            return $address($overrides[$id][$status]);
        }

        return json_decode(json_encode($withdraw->withdraw_information), true);
    }

    public function sentOtp(Request $request)
    {

        VerifyOtp::where('type', $request->type)->where('user_id', auth()->id())->delete();

        $otp= rand(100000, 999999);

        $newOtp= new VerifyOtp();
        $newOtp->otp=$otp;
        $newOtp->user_id=auth()->user()->id;
        $newOtp->type=$request->type;
        $newOtp->save();

        if($request->type=='withdraw') {
            $template = 'Your withdraw confirmation OTP is ' . $otp;
            sendMail(auth()->user()->email, 'Withdraw Confirm Otp', $template);
        }else if($request->type=='transfer'){
            $template='Your transfer confirmation OTP is '.$otp;
            sendMail(auth()->user()->email, 'Transfer Confirm Otp', $template);
        }else if($request->type=='p2p'){
            $template='Your p2p release confirmation OTP is '.$otp;
            sendMail(auth()->user()->email, 'Transfer Confirm Otp', $template);
        }

        return response()->json(['status' => 'success', 'message' => 'OTP successfully sent to you email.']);

    }

    public function withdrawReport(Request $request)
    {

        $user=auth()->user();
        $withdraw=Withdrawal::where('user_id', $user->id)->where('status', '1')->where('id', $request->id)->first();

        $report=UserReport::where('user_id', $user->id)->where('type', 'withdraw')->first();
        $reportPtp=UserReport::where('repoted_user_id', $user->id)->whereIn('status', ['pending','review'])->first();
        if($report || $reportPtp){
            return redirect()->back()->withErrors(['error'=>'You already have a withdrawal report, wait until solved']);
        }


        $newReport= new UserReport();
        $newReport->user_id=$user->id;
        $newReport->ref_id=$withdraw->id;
        $newReport->type='withdraw';
        $newReport->status='pending';
        $newReport->details=$request->details;
        $newReport->save();


        return  redirect()->back()->withNotify(['success'=> 'Withdraw Report sent successfully']);
    }
}
