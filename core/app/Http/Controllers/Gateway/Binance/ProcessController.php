<?php

namespace App\Http\Controllers\Gateway\Binance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\BalController;
use App\Lib\CurlRequest;
use App\Models\Deposit;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProcessController extends Controller
{

    public function success(Request $request)
    {

        dd($request->all());

    }

    public static function process($deposit)
    {


        $query = [
            'function' => 'create-transaction',
            'amount' => 5,
            'cryptocurrency_code' => 'bnb',
            'currency_code'=>'USDT_TRON',
            'title' => 'title',
            'note' => 'note',
            'user_details' => '123456',

        ];


        $ch = curl_init('https://talkgfx.top/api.php');
        $parameters = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_POSTFIELDS => http_build_query(array_merge(['api-key' => '12345678'], $query))
        ];
        curl_setopt_array($ch, $parameters);
        $response = curl_exec($ch);
        curl_close($ch);

        $response=$response?json_decode($response):'';

        dd($response);




        $success_url = route('binance.success', ['d' => $deposit->id]);
        $cancel_url = route('binance.success');

        $paymentCurrency = $deposit->currency->symbol ? $deposit->currency->symbol : 'USDT';
        $binanceAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);


        $apiKey = $binanceAcc->api_key;
        $secretKey = $binanceAcc->secret_key;

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nonce = '';
        for ($i = 1; $i <= 32; $i++) {
            $pos = mt_rand(0, strlen($chars) - 1);
            $char = $chars[$pos];
            $nonce .= $char;
        }
        $ch = curl_init();
        $timestamp = round(microtime(true) * 1000);
        // Request body
        $request = array(
            "env" => array(
                "terminalType" => "APP"
            ),
            "merchantTradeNo" => $deposit->trx,
            "orderAmount" => number_format($deposit->final_amount, 4),
            "currency" => $paymentCurrency,
            "goods" => array(
                "goodsType" => "01",
                "goodsCategory" => "D000",
                "referenceGoodsId" => "7876763A3B",
                "goodsName" => "Deposit Amount To " . gs('site_name'),
                "goodsDetail" => "Deposit Amount To " . gs('site_name')
            ),
            "successUrl" => $success_url,
            "cancelUrl" => $cancel_url
        );

        $json_request = json_encode($request);
        $payload = $timestamp . "\n" . $nonce . "\n" . $json_request . "\n";
        $binance_pay_key = $apiKey;
        $signature = strtoupper(hash_hmac('SHA512', $payload, $secretKey));
        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "BinancePay-Timestamp: $timestamp";
        $headers[] = "BinancePay-Nonce: $nonce";
        $headers[] = "BinancePay-Certificate-SN: $binance_pay_key";
        $headers[] = "BinancePay-Signature: $signature";

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, "https://bpay.binanceapi.com/binancepay/openapi/v2/order");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);

        $result = curl_exec($ch);

        $result = json_decode($result);
        $send['redirect'] = true;
        $send['redirect_url'] = @$result->data->checkoutUrl;







        $nonce = Str::random(32);
        $timestamp = round(microtime(true) * 1000);
        $request = array(
            "env" => array(
                "terminalType" => "APP"
            ),
            "merchantTradeNo" => $deposit->trx,
            "orderAmount" => $deposit->final_amount,
            "currency" => $paymentCurrency,
            "goods" => array(
                "goodsType" => "01",
                "goodsCategory" => "Z000",
                "referenceGoodsId" => $deposit->trx,
                "goodsName" => "Deposit to " . gs('site_name'),
                "goodsDetail" => "Deposit to " . gs('site_name')
            ),
        );


        $jsonRequest = json_encode($request);
        $payload = $timestamp . "\n" . $nonce . "\n" . $jsonRequest . "\n";
        $apiKey = $binanceAcc->api_key;
        $secretKey = $binanceAcc->secret_key;
        $signature = strtoupper(hash_hmac('SHA512', $payload, $secretKey));

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "BinancePay-Timestamp: $timestamp";
        $headers[] = "BinancePay-Nonce: $nonce";
        $headers[] = "BinancePay-Certificate-SN: $apiKey";
        $headers[] = "BinancePay-Signature: $signature";

        $result = CurlRequest::curlPostContent('https://bpay.binanceapi.com/binancepay/openapi/v2/order', $request, $headers);

        $result = json_decode($result);

        if (@$result->status == "SUCCESS") {
            $send['redirect'] = true;
            $send['redirect_url'] = @$result->data->checkoutUrl;
        } else {
            dd($result);

            $send['error'] = true;
            $send['message'] = (@$result->msg) ? @$result->errorMessage : 'Something went wrong';
        }
        return json_encode($send);
    }

    public function ipn()
    {
        $binance = Gateway::where('alias', 'Binance')->first();
        $binanceAcc = json_decode($binance->gateway_parameters);
        $deposits = Deposit::initiated()->where('method_code', $binance->code)->where('created_at', '>=', now()->subHours(24))->orderBy('last_cron')->limit(10)->get();
        $apiKey = $binanceAcc->api_key->value;
        $secretKey = $binanceAcc->secret_key->value;
        $url = "https://bpay.binanceapi.com/binancepay/openapi/v2/order/query";

        foreach ($deposits as $deposit) {
            $deposit->last_cron = time();
            $deposit->save();
            $nonce = Str::random(32);
            $timestamp = round(microtime(true) * 1000);

            $request = array(
                "merchantTradeNo" => $deposit->trx,
            );

            $jsonRequest = json_encode($request);
            $payload = $timestamp . "\n" . $nonce . "\n" . $jsonRequest . "\n";
            $signature = strtoupper(hash_hmac('SHA512', $payload, $secretKey));
            $headers = array();
            $headers[] = "Content-Type: application/json";
            $headers[] = "BinancePay-Timestamp: $timestamp";
            $headers[] = "BinancePay-Nonce: $nonce";
            $headers[] = "BinancePay-Certificate-SN: $apiKey";
            $headers[] = "BinancePay-Signature: $signature";

            $result = CurlRequest::curlPostContent($url, $request, $headers);
            $result = json_decode($result);
            if (@$result->data && @$result->data->status == "PAID" && @$result->data->orderAmount == $deposit->final_amount) {
                BalController::userDataUpdate($deposit);
            }

        }
    }


    public function ddCode()
    {

//    $paymentCurrency=$deposit->currency->symbol?$deposit->currency->symbol:'USDT';
//    $binanceAcc   = json_decode($deposit->gatewayCurrency()->gateway_parameter);
//    $nonce = Str::random(32);
//    $timestamp = round(microtime(true) * 1000);
//    $request = array(
//        "env" => array(
//            "terminalType" => "APP"
//        ),
//        "merchantTradeNo" => $deposit->trx,
//        "orderAmount" => $deposit->final_amount,
//        "currency" => $paymentCurrency,
//        "goods" => array(
//            "goodsType" =>  "01",
//            "goodsCategory" => "Z000",
//            "referenceGoodsId" =>  $deposit->trx,
//            "goodsName" => "Deposit to " . gs('site_name'),
//            "goodsDetail" => "Deposit to " . gs('site_name')
//        ),
//    );
//
//
//    $jsonRequest        = json_encode($request);
//    $payload            = $timestamp . "\n" . $nonce . "\n" . $jsonRequest . "\n";
//    $apiKey             = $binanceAcc->api_key;
//    $secretKey          = $binanceAcc->secret_key;
//    $signature          = strtoupper(hash_hmac('SHA512', $payload, $secretKey));
//
//    $headers            = array();
//    $headers[]          = "Content-Type: application/json";
//    $headers[]          = "BinancePay-Timestamp: $timestamp";
//    $headers[]          = "BinancePay-Nonce: $nonce";
//    $headers[]          = "BinancePay-Certificate-SN: $apiKey";
//    $headers[]          = "BinancePay-Signature: $signature";
//
//    $result = CurlRequest::curlPostContent('https://bpay.binanceapi.com/binancepay/openapi/v2/order',$request,$headers);
//
//    $result = json_decode($result);
//
//    if (@$result->status == "SUCCESS") {
//        $send['redirect'] = true;
//        $send['redirect_url'] = @$result->data->checkoutUrl;
//    } else {
//        dd($result);
//
//        $send['error']     = true;
//        $send['message'] = (@$result->msg) ? @$result->errorMessage : 'Something went wrong';
//    }
//    return json_encode($send);

    }

}
