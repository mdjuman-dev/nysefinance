<?php

namespace App\Http\Controllers\Gateway\PvPay;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\BalController;
use App\Models\Deposit;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PvPay (https://petveli.com/integrations) — hosted Binance checkout.
 *
 * 1. process(): create a payment with the public key, store its token on the
 *    deposit (btc_wallet) and send the user to checkout_url.
 * 2. ipn(): PvPay POSTs a webhook signed with HMAC-SHA256(raw body, secret key);
 *    a verified "completed" event credits the deposit exactly once.
 * 3. Fallbacks: the return URL and the pvpay:sync command ask the status API, so a
 *    late or lost webhook never leaves a paid deposit uncredited.
 */
class ProcessController extends Controller
{
    const API = 'https://petveli.com/api/v1/payments';
    const CODE = 130;

    /** Gateway keys as saved from Admin → Payment Gateways → Automatic → PvPay. */
    private static function keys(): object
    {
        $gateway = Gateway::where('code', self::CODE)->first();
        $params  = json_decode($gateway->gateway_parameters ?? '{}');

        return (object) [
            'public' => trim((string) @$params->public_key->value),
            'secret' => trim((string) @$params->secret_key->value),
        ];
    }

    private static function client()
    {
        return Http::withToken(self::keys()->public)->acceptJson()->timeout(15);
    }

    public static function process($deposit)
    {
        $keys = self::keys();
        if (!$keys->public || !$keys->secret) {
            return json_encode(['error' => true, 'message' => 'This payment method is not configured yet.']);
        }

        try {
            $response = self::client()->asForm()->post(self::API, [
                'amount'         => number_format((float) $deposit->final_amount, 2, '.', ''),
                'order_id'       => $deposit->trx,
                'customer_email' => @$deposit->user->email,
                'notify_url'     => route('ipn.PvPay'),
                'return_url'     => route('user.deposit.pvpay.return', $deposit->trx),
                'cancel_url'     => route('user.deposit.pvpay.return', $deposit->trx),
            ]);
        } catch (\Throwable $e) {
            Log::warning('PvPay create payment failed: ' . $e->getMessage());
            return json_encode(['error' => true, 'message' => 'Payment provider is unreachable, please try again.']);
        }

        $data = $response->json();
        if (!$response->successful() || empty($data['checkout_url']) || empty($data['token'])) {
            $message = $data['message'] ?? 'Could not start the payment.';
            if (!empty($data['errors'])) {
                $message = collect($data['errors'])->flatten()->first() ?: $message;
            }
            Log::warning('PvPay create payment rejected', ['status' => $response->status(), 'body' => $data]);
            return json_encode(['error' => true, 'message' => $message]);
        }

        // the token is what the status API is queried with
        $deposit->btc_wallet = $data['token'];
        $deposit->save();

        return json_encode(['redirect' => true, 'redirect_url' => $data['checkout_url']]);
    }

    /** Signed webhook from PvPay. Always answers 2xx once the signature is valid so retries stop. */
    public function ipn(Request $request)
    {
        $payload   = $request->getContent();
        $signature = (string) $request->header('X-PvPay-Signature');
        $secret    = self::keys()->secret;

        if (!$secret || !hash_equals(hash_hmac('sha256', $payload, $secret), $signature)) {
            abort(401);
        }

        $event = json_decode($payload, true) ?: [];
        if (($event['timestamp'] ?? 0) < time() - 600) {
            abort(400); // stale, possibly replayed
        }

        if (($event['status'] ?? null) === 'completed') {
            self::complete((string) ($event['order_id'] ?? ''), $event);
        }

        return response()->noContent();
    }

    /** Return / cancel URL: check the real status before telling the user anything. */
    public function back($trx)
    {
        $deposit = Deposit::where('trx', $trx)->where('user_id', auth()->id())->where('method_code', self::CODE)->firstOrFail();

        if ($deposit->status == Status::PAYMENT_INITIATE) {
            self::sync($deposit);
            $deposit->refresh();
        }

        $notify[] = $deposit->status == Status::PAYMENT_SUCCESS
            ? ['success', 'Payment received — ' . showAmount($deposit->amount, currencyFormat: false) . ' ' . $deposit->method_currency . ' added to your wallet']
            : ['info', 'Payment not confirmed yet. Your wallet is credited automatically once PvPay confirms it.'];

        return to_route('user.deposit.history')->withNotify($notify);
    }

    /** Ask the status API about one open deposit and credit it if completed. */
    public static function sync(Deposit $deposit): ?string
    {
        if (!$deposit->btc_wallet) {
            return null;
        }
        try {
            $response = self::client()->get(self::API . '/' . urlencode($deposit->btc_wallet));
        } catch (\Throwable $e) {
            return null;
        }
        if (!$response->successful()) {
            return null;
        }

        $data   = $response->json();
        $status = $data['status'] ?? null;

        if ($status === 'completed' && ($data['order_id'] ?? null) === $deposit->trx) {
            self::complete($deposit->trx, $data);
        } elseif (in_array($status, ['expired', 'cancelled', 'failed'])) {
            // closed without payment: mark it rejected so it stops being polled
            Deposit::where('id', $deposit->id)->where('status', Status::PAYMENT_INITIATE)->update(['status' => Status::PAYMENT_REJECT, 'admin_feedback' => 'PvPay: ' . $status]);
        }

        return $status;
    }

    /**
     * Credit a deposit once: the row is locked so a webhook, the return URL and the
     * sync command arriving together can't double-credit.
     */
    private static function complete(string $trx, array $data): void
    {
        DB::transaction(function () use ($trx, $data) {
            $deposit = Deposit::where('trx', $trx)->where('method_code', self::CODE)->lockForUpdate()->first();
            if (!$deposit || $deposit->status != Status::PAYMENT_INITIATE) {
                return; // unknown or already handled (webhooks may repeat)
            }

            $credited = (float) ($data['credited_amount'] ?? $data['amount'] ?? 0);
            $coin     = strtoupper((string) ($data['coin'] ?? ''));
            if ($coin !== strtoupper($deposit->method_currency) || $credited + 0.00000001 < round((float) $deposit->final_amount, 2)) {
                Log::warning('PvPay amount/coin mismatch', ['trx' => $trx, 'data' => $data, 'expected' => $deposit->final_amount]);
                return;
            }

            // same {name,type,value} shape manual deposits use, so the admin details page shows it
            $deposit->detail = collect([
                'PvPay reference' => $data['reference_code'] ?? null,
                'Method'          => $data['method'] ?? null,
                'Binance tx id'   => $data['binance_tx_id'] ?? null,
                'TXID'            => $data['txid'] ?? null,
            ])->filter()->map(fn ($v, $k) => ['name' => $k, 'type' => 'text', 'value' => (string) $v])->values()->all();
            $deposit->save();

            BalController::userDataUpdate($deposit);
        });
    }
}
