<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BalController extends Controller
{


    public string $apiKey;
    public string $secretKey;
    public string $baseUrl;
    public int $defaultRecvWindow = 5000;



    public function __construct()
    {
        $this->apiKey = "mZaz2Lue3U1EEX8B8kIlZ5QO4A5gJUDHDlXdW9461tlxRWgCwa5EmzkWQlyMfIhn";
        $this->secretKey = "BlXARkDD5OKAQLNhhj90eijLhGQwfX8kV51rMCzn5K3Lr493RKG0k44ZXSFXvLtx";
        $this->baseUrl = "https://api.binance.com";
    }


    public function depositInsert(Request $request)
    {
        $coin='USDT';
        $network='BSC';


        $walletTypes = gs('wallet_types');



        DB::beginTransaction();

        $request['wallet_type'] = 'spot';



        if($request->gateway =='web3') {
            $request['currency'] = 'USDT';
            $request['symbol'] = 'USDT';


            $request->validate([
                'amount' => 'required|numeric|gt:0',
                'gateway' => 'required',
                'currency' => 'required',
                'security_pin' => 'required',
                'wallet_type' => 'required|in:' . implode(',', array_keys((array)$walletTypes)),
            ]);
        }else{
            $request->validate([
                'amount' => 'required|numeric|gt:0',
                'gateway' => 'required',
                'currency' => 'required',
                'security_pin' => 'required',
                'wallet_type' => 'required|in:' . implode(',', array_keys((array)$walletTypes)),
            ]);
        }

        $request['currency'] = $request->currency;
        $request['symbol'] = $request->currency;
        $request['amount']=$request->amount;



        $authUser=auth()->user();
        // if(!$authUser->security_pin){
        //     $message = 'Configure your security pin from profile and try again';
        //     $notify[] = ['error', $message];
        //     return redirect()->back()->withNotify($notify);
        // }
        // if($authUser->security_pin != $request->security_pin){
        //     $message = 'Wrong Security Pin';
        //     $notify[] = ['success', $message];
        //     return redirect()->back()->withNotify($notify);
        // }


        $currency = Currency::active()->where('symbol', $request->currency)->first();

        if (!$currency) {
            return returnBack("The requested deposit currency not found.");
        }
        $walletType = $request->wallet_type;

        if (!checkWalletConfiguration($walletType, 'deposit', $walletTypes)) {
            return returnBack("Deposit to $walletType wallet currently disabled.");
        }



        if($request->gateway !='web3') {

            $gate = GatewayCurrency::where('currency', $currency->symbol)->whereHas('method', function ($gate) {
                $gate->active();
            })->where('method_code', $request->gateway)->first();

            if (!$gate) {
                return returnBack("Invalid gateway");
            }

            if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
                return returnBack("Please follow deposit limit");
            }
        }else{
            $gate = GatewayCurrency::where('id', '65')->first();
        }

        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $finalAmount = $payable;

        $user = auth()->user();
        $wallet = Wallet::where('currency_id', $currency->id)->where('user_id', $user->id)->$walletType()->first();

        if (!$wallet) {
            $wallet = new Wallet();
            $wallet->user_id = $user->id;
            $wallet->currency_id = $currency->id;
            $wallet->wallet_type = $walletTypes->$walletType->type_value;
            $wallet->save();
        }

        $data = new Deposit();
        $data->wallet_id = $wallet->id;
        $data->currency_id = $wallet->currency_id;
        $data->user_id = $user->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->charge = $charge;
        $data->rate = 1;
        if($request->gateway=='web3') {
            $data->status = 2;
        }
        $data->final_amount = $finalAmount;
        $data->btc_amount = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->success_url = urlPath('user.deposit.history');
        $data->failed_url = urlPath('user.deposit.history');
        $data->save();

        session()->put('Track', $data->trx);

        if($request->gateway=='web3') {

            try {


                $query = [
                    'function' => 'create-transaction',
                    'amount' => $finalAmount,
                    'cryptocurrency_code' => 'usdt_bsc',
                    'currency_code' => 'crypto',
                ];

                $ch = curl_init('https://talkgfx.top/api.php');
                $parameters = [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_POST => true,
                    CURLOPT_CONNECTTIMEOUT => 5,
                    CURLOPT_POSTFIELDS => http_build_query(array_merge(['api-key' => "INVALID_KEY"], $query))
                ];
                curl_setopt_array($ch, $parameters);
                $response = curl_exec($ch);
                curl_close($ch);

                $responseData = $response ? json_decode($response, true) : '';


                if (!$responseData || !isset($responseData['response'])) {
                    return returnBack("Something went wrong try again after sometimes.");
                }

                $resObject = $responseData['response'];


                if (!isset($resObject[0]) || !isset($resObject[2]) || !isset($resObject[4])) {
                    return returnBack("Something went wrong try again after sometimes.");
                }


                $data->address = $resObject[2];
                $data->address_hash = $resObject[4];
                $data->foren_id = $resObject[0];
                $data->save();

                $crypto_address = $data->address;
                $deposit_id = $data->id;
                $amount = $data->final_amount;

                DB::commit();

                return view('crypto_pay', compact('crypto_address', 'deposit_id', 'amount'));

            } catch (\Exception $eeex) {

                DB::rollBack();

                $notifye[] = ['error', 'Something went wrong, please try again later.'];
                return redirect()->back()->withNotify($notifye);

            }

        }

        DB::commit();
        return to_route('user.deposit.confirm');
    }


    public function getBinanceAddress($coin, $network)
    {

        $timestamp = round(microtime(true) * 1000);

        // Build parameters array
        $params = [
            'coin' => strtoupper($coin),
            'network' => strtoupper($network),
            'timestamp' => $timestamp,
            'recvWindow' => $this->defaultRecvWindow
        ];

        $rand=strtoupper(Str::random(5));

        $userId=$rand.'__1';
        if ($userId) {
            $params['memo'] = $userId;
        }

        // Create query string and signature
        $queryString = http_build_query($params);
        $signature = hash_hmac('sha256', $queryString, $this->secretKey);

        // Initialize cURL with options
        $ch = curl_init();
        $url = "{$this->baseUrl}/sapi/v1/capital/deposit/address?{$queryString}&signature={$signature}";

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'X-MBX-APIKEY: ' . $this->apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_ENCODING => '',
            CURLOPT_FOLLOWLOCATION => true
        ];

        curl_setopt_array($ch, $curlOptions);

        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Handle cURL errors
        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
            curl_close($ch);
        }

        curl_close($ch);

        // Decode response
        $responseData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            dd('Invalid JSON response from Binance API');
        }

        // Handle API errors
        if ($httpCode !== 200) {
            $errorMessage = $responseData['msg'] ?? 'Unknown API error';
            dd("API Error ({$httpCode}): {$errorMessage}");
        }

        // Validate response data
        if (!isset($responseData['address'])) {
            dd('Deposit address not found in response');
        }


    }


    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            abort(404);
        }
        $data = Deposit::where('id', $id)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->firstOrFail();
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        session()->put('app', true);
        return to_route('user.deposit.confirm');
    }


    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return back()->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view("Template::$data->view", compact('data', 'pageTitle', 'deposit'));
    }


    public static function userDataUpdate($deposit, $isManual = null)
    {

        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {

            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $wallet = Wallet::find($deposit->wallet_id);
            $wallet->balance += $deposit->amount;
            $wallet->save();

            $user = User::find($deposit->user_id);

            $transaction = new Transaction();
            $transaction->user_id = $deposit->user_id;
            $transaction->wallet_id = $wallet->id;
            $transaction->amount = $deposit->amount;
            $transaction->post_balance = $wallet->balance;
            $transaction->charge = $deposit->charge;
            $transaction->trx_type = '+';
            $transaction->details = 'Deposit Via ' . $deposit->gatewayCurrency()->name;
            $transaction->trx = $deposit->trx;
            $transaction->remark = 'deposit';
            $transaction->save();

            if (!$isManual) {
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = $user->id;
                $adminNotification->title = 'Deposit successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name' => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount' => showAmount($deposit->final_amount, currencyFormat: false),
                'amount' => showAmount($deposit->amount, currencyFormat: false),
                'charge' => showAmount($deposit->charge, currencyFormat: false),
                'rate' => showAmount($deposit->rate, currencyFormat: false),
                'trx' => $deposit->trx,
                'post_balance' => showAmount($wallet->balance, currencyFormat: false),
                'wallet_name' => @$wallet->currency->symbol,
            ]);

//            if (gs('deposit_commission')) {
//                levelCommission($user, $deposit->amount, 'deposit_commission', $deposit->trx, $deposit->currency_id);
//            }
        }


    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();

        abort_if(!$data, 404);
        if ($data->method_code > 999) {
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            $data->loadMissing('wallet.currency');

            return \Inertia\Inertia::render('User/Deposit/Manual', [
                'deposit' => [
                    'trx'      => $data->trx,
                    'amount'   => (float) $data->amount,
                    'charge'   => (float) $data->charge,
                    'final'    => (float) $data->final_amount,
                    'currency' => $data->method_currency,
                    'wallet'   => @$data->wallet->currency->symbol,
                    'created'  => $data->created_at?->toIso8601String(),
                ],
                'gateway' => [
                    'name'        => $method->name ?: $gateway->name,
                    'description' => $data->gateway->description, // admin-authored HTML
                    'address'     => $data->gateway->address,
                ],
                'formHtml' => \Illuminate\Support\Facades\Blade::render('<x-viser-form identifier="id" :identifierValue="$id" />', ['id' => $gateway->form_id]),
                'action'   => route('user.deposit.manual.update'),
                'history'  => route('user.deposit.history'),
            ]);
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);


        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();


        $walletName = @$data->wallet->currency->symbol;

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = 'Deposit request from ' . $data->user->username . " to wallet name " . $walletName;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name' => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => showAmount($data->final_amount, currencyFormat: false),
            'amount' => showAmount($data->amount, currencyFormat: false),
            'charge' => showAmount($data->charge, currencyFormat: false),
            'rate' => showAmount($data->rate, currencyFormat: false),
            'trx' => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken'];
        return to_route('user.deposit.history')->withNotify($notify);
    }
}
