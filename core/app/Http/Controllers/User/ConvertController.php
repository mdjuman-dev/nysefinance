<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Wallet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConvertController extends Controller
{
    public function convert($type = null)
    {
        // Spot wallets the user can convert between (balance first, then by symbol).
        $wallets = Wallet::where('wallets.user_id', auth()->id())->where('wallets.wallet_type', 1)
            ->join('currencies', 'currencies.id', 'wallets.currency_id')->where('currencies.status', 1)
            ->orderByDesc('wallets.balance')->orderBy('currencies.symbol')
            ->get(['wallets.balance', 'currencies.id', 'currencies.symbol', 'currencies.name', 'currencies.image', 'currencies.rate']);

        return \Inertia\Inertia::render('User/Wallet/Convert', [
            'coins' => $wallets->map(fn ($w) => [
                'symbol'  => $w->symbol,
                'name'    => $w->name,
                'image'   => getImage(getFilePath('currency') . '/' . $w->image, getFileSize('currency')),
                'balance' => (float) $w->balance,
                'usd'     => (float) $w->rate,
            ])->values(),
            'urls' => [
                'rate'    => route('user.convert.rate'),
                'convert' => route('user.convert.amount'),
                'wallet'  => route('user.wallet.list', 'spot'),
            ],
        ]);
    }

    public function convertCoinPairs()
    {

        try{

            $allowCurrencies = [
                'DOP','DZD','EGP','GEL','GHS','GTQ','HNL','IQD','IRR','JMD','JOD','KES','KGS','KHR',
                'KWD','KZT','LBP','LKR','MAD','MDL','MKD','MMK','NAD','NIO','NPR','OMR','PAB','QAR',
                'RSD','SAR','SSP','TND','TTD','UGX','UYU','UZS','VES','USD','AUD','X','CAD','CHF',
                'CLP','CNY','CZK','DKK','EUR','GBP','HKD','HUF','IDR','ILS','INR','JPYC','KRW','MXN',
                'MYR','NOK','NZD','PHP','PKR','PLN','RUB','SEK','SGD','THB','TRY','TWD','ZAR','AED',
                'BGN','HRK','MUR','RON','ISK','NGN','COP','ARS','PEN','VND','UAH','BOB','ALL','AMD',
                'AZN','BAM','BDT','BHD','BMD','BYN','CRC','CUP','BTC','ETH','USDT','BNB','SOL','USDC',
                'XRP','DOGE','TRX','TON','ADA','AVAX','SHIB','LINK','BCH','DOT','LEO','NEAR','DAI',
                'LTC','SUI','ZETA','HMSTR','PEPE','LBK','KLAUS','FET','KAS','XMR','AIC','XLM','FDUSD',
                'STX','RENDER','POL','WIF','OKB','IMX','FIL','BABYDOGE','ARB','OP','MNT','CRO','INJ',
                'HBAR','FTM','ATOM','VET','RUNE','BGB','BONK','GRT','SEI','POPCAT','FLOKI','JUP',
                'PYTH','TIA','THETA','HNT','WLD','AR','OM','KCS','ENA','ALGO','ONDO','MEW','BRETT',
                'LDO','MKR','BEAM','BSV','JASMY','APE','MATIC','BTT','CORE','FLOW','GT','GALA','RAY',
                'STRK','USDD','NOT','AXS','QNT','SAFE','PENDLE','AERO','MOG','FLR','EOS','ORDI','NEO',
                'GOAT','BLENDR','DYDX','EGLD'
            ];


            $client = new Client();
            $response = $client->get('https://api.binance.com/api/v3/exchangeInfo');
            $data = json_decode($response->getBody(), true);


            $pairs = [];

            $fromCoinPairs = [];
            $toCoinPairs = [];
            foreach ($data['symbols'] as $symbol) {

                $base = $symbol['baseAsset'];
                $quote = $symbol['quoteAsset'];

                // ✅ Only include pairs if BOTH base or quote are in your allowed list
                if (in_array($quote, $allowCurrencies)) {
                   $toCoinPairs[] = $quote;
                }
                if(in_array($base, $allowCurrencies)){
                    $fromCoinPairs[] = $base;
                }
            }



            $toCoinPairs = array_slice($toCoinPairs, 0, 500);
            $fromCoinPairs = array_slice($fromCoinPairs, 0, 500);

            $toCoinPairs = array_values(array_unique($toCoinPairs));
            $fromCoinPairs = array_values(array_unique($fromCoinPairs));

//            dd($fromCoinPairs, $toCoinPairs);


            return response()->json(['status' => 'success', 'from' => $fromCoinPairs, 'to' => $toCoinPairs]);

        }catch(\Exception $e){
            return response()->json(['status' => 'error']);
        }

    }


    public function convertBalance(Request $request)
    {

        $wallet = Wallet::where('user_id', auth()->user()->id)->where('currency_id', $request->currency_id)->where('wallet_type', '1')->first();

        if (!$wallet) {
            return response()->json(['status' => 'failed', 'message' => 'Wallet not found']);
        }

        return response()->json(['status' => 'success', 'balance' => number_format($wallet->balance, 4)]);

    }


    /**
     * Price of 1 FROM in TO. Uses Binance FROMTO, else the inverse TOFROM pair,
     * else the ratio of the currencies' USD rates kept by the price cron.
     */
    private function quote(Currency $from, Currency $to): ?float
    {
        $client = new Client(['verify' => false, 'timeout' => 6]);
        foreach ([[$from->symbol . $to->symbol, false], [$to->symbol . $from->symbol, true]] as [$pair, $inverse]) {
            try {
                $res   = $client->get('https://api.binance.com/api/v3/ticker/price', ['query' => ['symbol' => strtoupper($pair)]]);
                $price = (float) (json_decode($res->getBody(), true)['price'] ?? 0);
                if ($price > 0) {
                    return $inverse ? 1 / $price : $price;
                }
            } catch (\Exception $e) {
                // pair not listed — try the next source
            }
        }

        return ($from->rate > 0 && $to->rate > 0) ? (float) $from->rate / (float) $to->rate : null;
    }

    public function convertRate(Request $request)
    {
        if (!$request->from_coin || !$request->to_coin || $request->from_coin === $request->to_coin) {
            return response()->json(['status' => 'failed', 'message' => 'Please choose two different coins']);
        }

        $from = Currency::where('symbol', $request->from_coin)->active()->first();
        $to   = Currency::where('symbol', $request->to_coin)->active()->first();
        if (!$from || !$to) {
            return response()->json(['status' => 'failed', 'message' => 'Please enter a valid coin']);
        }

        $rate = $this->quote($from, $to);
        if (!$rate) {
            return response()->json(['status' => 'failed', 'message' => 'Rate unavailable for this pair']);
        }

        $wallet = Wallet::where('user_id', auth()->id())->where('currency_id', $from->id)->where('wallet_type', 1)->first();

        return response()->json([
            'status'           => 'success',
            'convert_rate'     => $rate,
            'available_amount' => (float) ($wallet->balance ?? 0),
        ]);
    }

    public function convertAmount(Request $request)
    {
        $request->validate([
            'from_coin'  => 'required|string',
            'to_coin'    => 'required|string|different:from_coin',
            'fromAmount' => 'required|numeric|gt:0',
        ]);

        $user = auth()->user();
        $from = Currency::where('symbol', $request->from_coin)->active()->first();
        $to   = Currency::where('symbol', $request->to_coin)->active()->first();
        if (!$from || !$to) {
            return returnBack('Currency not found');
        }

        $rate = $this->quote($from, $to);
        if (!$rate) {
            return returnBack('Rate unavailable for this pair, please try again');
        }

        // the receiving spot wallet may not exist yet for this coin
        Wallet::unguarded(fn () => Wallet::firstOrCreate(
            ['user_id' => $user->id, 'currency_id' => $to->id, 'wallet_type' => 1],
            ['balance' => 0]
        ));

        $amount   = (float) $request->fromAmount;
        $toAmount = $amount * $rate;

        $error = DB::transaction(function () use ($user, $from, $to, $amount, $toAmount) {
            $fromWallet = Wallet::where('user_id', $user->id)->where('currency_id', $from->id)->where('wallet_type', 1)->lockForUpdate()->first();
            $toWallet   = Wallet::where('user_id', $user->id)->where('currency_id', $to->id)->where('wallet_type', 1)->lockForUpdate()->first();

            if (!$fromWallet || $fromWallet->balance < $amount) {
                return 'Insufficient balance';
            }

            $fromWallet->balance -= $amount;
            $fromWallet->save();
            $toWallet->balance += $toAmount;
            $toWallet->save();

            $trx     = getTrx();
            $summary = getAmount($amount, 8) . ' ' . $from->symbol . ' = ' . getAmount($toAmount, 8) . ' ' . $to->symbol;

            foreach ([[$fromWallet, $amount, '-'], [$toWallet, $toAmount, '+']] as [$wallet, $value, $sign]) {
                $t               = new Transaction();
                $t->user_id      = $user->id;
                $t->wallet_id    = $wallet->id;
                $t->amount       = $value;
                $t->post_balance = $wallet->balance;
                $t->charge       = 0;
                $t->trx_type     = $sign;
                $t->details      = 'Convert ' . $summary;
                $t->trx          = $trx;
                $t->remark       = 'transfer';
                $t->save();
            }

            return null;
        });

        if ($error) {
            return returnBack($error);
        }

        return returnBack('Converted ' . getAmount($amount, 8) . ' ' . $from->symbol . ' to ' . getAmount($toAmount, 8) . ' ' . $to->symbol, 'success');
    }
}
