<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FetchTrxHistory extends Controller
{

    public function checkCryptoDepositStatus(Request $request)
    {

        $query = [
            'function' => 'get-transaction',
            'transaction_id' => '27',
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
        $response = $response ? json_decode($response) : '';

        dd($response);



        if(!auth()->user() || !$request->request_id){
            return response()->json(['status'=>'failed']);
        }
        $deposit=Deposit::where('user_id', auth()->user()->id)->where('id', $request->request_id)->where('status', '1')->first();

        if($deposit){
            return response()->json(['status'=>'success']);
        }

        return response()->json(['status'=>'failed']);

    }


    public function payAmnt()
    {


        $secretKey = env('secretKey');
        $baseUrl = env('baseUrl');
        $apiKey = env('apiKey');



        $timestamp = now()->timestamp * 1000;

        $params = [
            'amount' => 10.00,
            'asset' => 'USDT',
            'timestamp' => $timestamp
        ];

        $queryString = http_build_query($params);
        $signature = hash_hmac('sha256', $queryString, $secretKey);
        $client = new Client([
            'base_uri' => $baseUrl,
            'verify' => false
        ]);

            $response = $client->request('GET', '/sapi/v1/pay/createPaymentLink', [
                'query' => array_merge($params, ['signature' => $signature]),
                'headers' => [
                    'X-MBX-APIKEY' => $apiKey,
                ]
            ]);

        $responseData = $response->getBody()->getContents();
//        $responseData=$responseData?json_decode($responseData):'';


            dd($responseData);




    }


    public function vsAc()
    {
        $secretKey = env('secretKey');
        $baseUrl = 'https://api3.binance.com';
        $apiKey = env('apiKey');


        $client = new Client([
            'base_uri' => $baseUrl,
            'verify' => false
        ]);

        $timestamp = now()->timestamp * 1000;

        $params = [
            'timestamp' => $timestamp
        ];


        $params['subAccountName'] = 'sagor.picotech@gmail.com';


        $queryString = http_build_query($params);
        $signature = hash_hmac('sha256', $queryString, $secretKey);


            $response = $client->request('POST', '/sapi/v1/sub-account/virtualSubAccount', [
                'query' => array_merge($params, ['signature' => $signature]),
                'headers' => [
                    'X-MBX-APIKEY' => $apiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            return json_decode($response->getBody(), true);


    }

    public function getHistory(Request $request)
    {


        $resdata='[{"id":"4355638769505340417","amount":"0.01","coin":"USDT","network":"BSC","status":1,"address":"0x713376b9704053f5526d4c47779c1c5d59d91466","addressTag":"","txId":"Off-chain transfer 235915249242","insertTime":1737574702000,"transferType":1,"confirmTimes":"1/15","unlockConfirm":0,"walletType":0},{"id":"4339490643153386496","amount":"50","coin":"USDT","network":"TRX","status":1,"address":"TD8tmwSQAN9QCV8nnPyakY1bp51Yd3UkdC","addressTag":"","txId":"3a4bec96a893125f6bac781c369e0be343a332c129788c6cbb784ddde046d632","insertTime":1736612198000,"transferType":0,"confirmTimes":"1/1","unlockConfirm":0,"walletType":0},{"id":"4293282807113342977","amount":"43","coin":"USDT","network":"TRX","status":1,"address":"TD8tmwSQAN9QCV8nnPyakY1bp51Yd3UkdC","addressTag":"","txId":"c3011d1c2c185175fb5e1a6dd04643f55cb2e34a65277cf58c474c1d7c434a5e","insertTime":1733857997000,"transferType":0,"confirmTimes":"1/1","unlockConfirm":0,"walletType":0},{"id":"4252739653671144704","amount":"46","coin":"USDT","network":"TRX","status":1,"address":"TD8tmwSQAN9QCV8nnPyakY1bp51Yd3UkdC","addressTag":"","txId":"62119e3b998277b0e4069dce39efcca0ceef55798810d6b6aeb77c885bbd55fe","insertTime":1731441436000,"transferType":0,"confirmTimes":"1/1","unlockConfirm":0,"walletType":0}]';

        $resdata=json_decode($resdata);

        foreach ($resdata as $res){
            if(isset($res->insertTime) && $res->insertTime) {
                $timestamp = $res->insertTime; // From the response
                $dateTime = date('Y-m-d H:i:s', $timestamp / 1000);

                dd($dateTime, $res);

            }
        }

        dd($resdata);








        $secretKey = env('secretKey');
        $baseUrl = env('baseUrl');
        $apiKey = env('apiKey');

        $endpoint = '/sapi/v1/capital/deposit/hisrec';

        // $startTime = strtotime('today midnight') * 1000;



        // Generate timestamp
        $endTime = round(microtime(true) * 1000);

        // Prepare query parameters
        $queryParams = [
            'coin' => 'USDT',
            'status' => 1,
            'limit' => 100,
            'timestamp' => $endTime
        ];

        // Generate signature
        $queryString = http_build_query($queryParams);
        $signature = hash_hmac('sha256', $queryString, $secretKey);

        // Prepare full URL
        $fullUrl = "/sapi/v1/capital/deposit/hisrec?{$queryString}&signature={$signature}";

        // Send request
        $client = new Client([
            'base_uri' => $baseUrl,
            'verify' => false  // Equivalent to SSL_VERIFYPEER = false
        ]);
        $response = $client->request('GET', $fullUrl, [
            'headers' => [
                'X-MBX-APIKEY' => $apiKey
            ]
        ]);

        $response=$response->getBody()->getContents();

        dd($response);

        $response=$response?json_decode($response):'';


        dd($response);










//        $endTime = round(microtime(true) * 1000);
//        // Create the query string including memo/tag
//        $query = http_build_query([
//            'coin' => "USDT",
//            'status' => 1,
//            // 'startTime' => $startTime,
//            'limit' => 100,
//            'timestamp'=>$endTime
//        ]);
//
//        // Generate the signature
//        $signature = hash_hmac('sha256', $query, $secretKey);
//
//        try {
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/sapi/v1/capital/deposit/hisrec?" . $query . "&signature=" . $signature);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
//            curl_setopt($ch, CURLOPT_HTTPHEADER, [
//                'X-MBX-APIKEY: ' . $apiKey
//            ]);
//            $response = curl_exec($ch);
//            if (curl_errno($ch)) {
//                throw new \Exception(curl_error($ch));
//            }
//            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//            curl_close($ch);
//
//            // Decode the response
//            $responseBody = json_decode($response, true);
//
//            if ($httpCode == 200) {
//                return response()->json($responseBody);
//            } else {
//                return response()->json($responseBody, $httpCode);
//            }
//        } catch (\Exception $e) {
//            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
//        }
    }

}
