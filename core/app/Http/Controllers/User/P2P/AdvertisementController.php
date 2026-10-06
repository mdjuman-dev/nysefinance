<?php

namespace App\Http\Controllers\User\P2P;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\P2P\Ad;
use App\Models\P2P\AdPaymentMethod;
use App\Models\P2P\PaymentMethod;
use App\Models\P2P\PaymentWindow;
use App\Models\P2P\UserPaymentMethod;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Advertisement";
        $ads       = Ad::select('p2p_ads.*', "wallets.balance")
            ->publishStatus()
            ->latest('p2p_ads.id')
            ->where('p2p_ads.user_id', auth()->id())
            ->with('asset', 'fiat', 'paymentWindow', 'paymentMethods.paymentMethod')
            ->groupBy('p2p_ads.id')
            ->paginate(getPaginate());

        return \Inertia\Inertia::render('User/P2P/Ads', [
            'ads' => \App\Support\InertiaData::paginate($ads, fn ($ad) => [
                'id'        => $ad->id,
                'type'      => $ad->type == Status::P2P_AD_TYPE_BUY ? 'buy' : 'sell',
                'enabled'   => $ad->status == Status::ENABLE,
                'published' => (bool) $ad->publish_status,
                'complete'  => $ad->complete_step == 3,
                'asset'     => ['symbol' => @$ad->asset->symbol, 'name' => @$ad->asset->name, 'image' => @$ad->asset->image_url],
                'fiat'      => ['symbol' => @$ad->fiat->symbol, 'name' => @$ad->fiat->name],
                'price'     => (float) $ad->price,
                'priceType' => $ad->price_type == Status::P2P_AD_PRICE_TYPE_MARGIN ? 'margin' : 'fixed',
                'margin'    => (float) $ad->price_margin,
                'min'       => (float) $ad->minimum_amount,
                'max'       => (float) $ad->maximum_amount,
                'window'    => @$ad->paymentWindow->minute,
                'methods'   => $ad->paymentMethods->map(fn ($m) => @$m->paymentMethod->name)->filter()->values(),
                'edit'      => route('user.p2p.advertisement.create', $ad->id) . '?step=1',
                'toggle'    => route('user.p2p.advertisement.change.status', $ad->id),
            ]),
            'isAgent' => (bool) checkAgent(),
            'urls'    => ['create' => route('user.p2p.advertisement.create'), 'market' => route('p2p')],
        ]);
    }

    public function create($id = 0)
    {
        if(!checkAgent()){
            return abort('404');
        }
        $step  = request()->step ??  1;
        $steps = [1, 2, 3];

        abort_if((!in_array($step, $steps)) || (!$id && $step != 1), 404);

        if ($id) {
            $ad = Ad::with('asset:id,symbol,image', 'fiat:id,symbol,rate,image', 'asset.marketData:currency_id,symbol,price')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        } else {
            $ad = null;
        }

        $step  = (int) $step;
        $props = [
            'step'  => $step,
            'done'  => session()->has('success') ? session('success') : null,
            'ad'    => $ad ? [
                'id'       => $ad->id,
                'type'     => (int) $ad->type,
                'complete' => $ad->complete_step == 3,
                'asset'    => ['id' => $ad->asset_id, 'symbol' => @$ad->asset->symbol, 'image' => @$ad->asset->image_url],
                'fiat'     => ['id' => $ad->fiat_id, 'symbol' => @$ad->fiat->symbol, 'image' => @$ad->fiat->image_url],
            ] : null,
            'old'   => (object) collect(session()->getOldInput())->except('_token')->all(),
            'urls'  => [
                'save'  => route('user.p2p.advertisement.save', $ad?->id),
                'step'  => $ad ? route('user.p2p.advertisement.create', $ad->id) . '?step=' : null,
                'new'   => route('user.p2p.advertisement.create'),
                'list'  => route('user.p2p.advertisement.index'),
                'methods' => route('user.p2p.payment.method.list'),
            ],
        ];

        if ($step === 1) {
            $props['coins'] = Currency::crypto()->active()->orderBy('symbol')->get(['id', 'symbol', 'name', 'image'])
                ->map(fn ($c) => ['id' => $c->id, 'symbol' => $c->symbol, 'name' => $c->name, 'image' => $c->image_url])->values();
            $props['fiats'] = Currency::fiat()->active()->orderBy('symbol')->get(['id', 'symbol', 'name', 'image'])
                ->map(fn ($c) => ['id' => $c->id, 'symbol' => $c->symbol, 'name' => $c->name, 'image' => $c->image_url])->values();
        } elseif ($step === 2) {
            $market = (float) @$ad->asset->marketData->price;
            $rate   = (float) @$ad->fiat->rate;
            $props['market']  = $rate > 0 ? $market / $rate : 0; // 1 asset in fiat
            $props['values']  = [
                'price'          => (float) $ad->price,
                'price_type'     => (int) ($ad->price_type ?: Status::P2P_AD_PRICE_TYPE_FIXED),
                'margin'         => (float) ($ad->price_margin ?: 100),
                'minimum_amount' => $ad->minimum_amount ? (float) $ad->minimum_amount : '',
                'maximum_amount' => $ad->maximum_amount ? (float) $ad->maximum_amount : '',
                'payment_window' => $ad->payment_window_id,
                'payment_method' => $ad->paymentMethods()->pluck('payment_method_id')->map(fn ($v) => (int) $v)->values(),
            ];
            $props['paymentMethods'] = PaymentMethod::whereJsonContains('supported_currency', @$ad->fiat->symbol)->active()->orderBy('name')->get(['id', 'name'])
                ->map(fn ($m) => ['id' => $m->id, 'name' => __($m->name)])->values();
            $props['paymentWindows'] = PaymentWindow::active()->orderBy('minute')->get(['id', 'minute'])
                ->map(fn ($w) => ['id' => $w->id, 'minute' => (int) $w->minute])->values();
            $props['openMethods'] = session()->pull('EXTERNAL_REDIRECT')[0] ?? null;
        } else {
            $props['values'] = [
                'payment_details'  => (string) $ad->payment_details,
                'terms_of_trade'   => (string) $ad->terms_of_trade,
                'auto_replay_text' => (string) $ad->auto_replay_text,
            ];
        }

        return \Inertia\Inertia::render('User/P2P/AdForm', $props);
    }
    public function save(Request $request, $id = 0)
    {
        if(!checkAgent()){
            return abort('404');
        }

        $request->validate([
            'step' => 'required|in:1,2,3'
        ]);
        $step       = $request->step;
        $steps      = [1 => 'stepOne', 2 => 'stepTwo', 3 => 'stepThree'];
        $methodName = $steps[$step];
        return $this->$methodName($request, $id);
    }

    public function stepOne($request, $id)
    {
        if(!checkAgent()){
            return abort('404');
        }

        $request->validate([
            'type'  => 'required|in:' . Status::P2P_AD_TYPE_SELL . ',' . Status::P2P_AD_TYPE_BUY . '',
            'asset' => 'required|integer',
            'fiat'  => 'required|integer',
        ]);

        $asset = Currency::crypto()->active()->where('id', $request->asset)->firstOrFail();
        $fiat  = Currency::fiat()->active()->where('id', $request->fiat)->firstOrFail();
        $user  = auth()->user();

        if ($id) {
            $ad = Ad::where('id', $id)->where('user_id', $user->id)->firstOrFail();
            abort_if($ad->complete_step == 3, 404);
        } else {
            $ad                = new Ad();
            $ad->complete_step = 1;
            $ad->user_id       = $user->id;
        }
        $ad->type     = $request->type;
        $ad->asset_id = $asset->id;
        $ad->fiat_id  = $fiat->id;
        $ad->save();

        $redirectUrl = route("user.p2p.advertisement.create", $ad->id) . "?step=2";
        return redirect($redirectUrl);
    }

    public function stepTwo($request, $id)
    {
        if(!checkAgent()){
            return abort('404');
        }

        $request->validate([
            'price_type'       => 'required|in:' . Status::P2P_AD_PRICE_TYPE_FIXED . ',' . Status::P2P_AD_PRICE_TYPE_MARGIN . '',
            'price'            => 'required|numeric|gte:0',
            'payment_window'   => 'required|integer',
            'minimum_amount'   => 'required|numeric|gt:0',
            'maximum_amount'   => 'required|numeric|gt:minimum_amount',
            'margin'           => 'required_if:price_type,' . Status::P2P_AD_PRICE_TYPE_MARGIN . '|nullable|numeric|gt:0',
            'payment_method'   => 'required|array|min:1',
            'payment_method.*' => 'required',
        ]);

        $user                = auth()->user();
        $ad                  = Ad::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $paymentWindow       = PaymentWindow::active()->where('id', $request->payment_window)->firstOrFail();
        $paymentMethod       = PaymentMethod::active()->whereIn('id', $request->payment_method)->count();
        $paymentMethodExists = UserPaymentMethod::whereIn('payment_method_id', $request->payment_method)->where('user_id', $user->id)->count();

        abort_if(count($request->payment_method) !=  $paymentMethod, 404);

        if ($ad->type == Status::P2P_AD_TYPE_SELL &&  count($request->payment_method) !=  $paymentMethodExists) {
            $notify[] = ['error', 'Please add all payment method information first'];
            session()->push('EXTERNAL_REDIRECT', route("user.p2p.payment.method.list"));
            return  back()->withNotify($notify)->withInput();
        }

        if ($request->price_type == Status::P2P_AD_PRICE_TYPE_FIXED) {
            $price = $request->price;
        } else {
            $coinPrice     = @$ad->asset->marketData->price;
            $currencyPrice = @$ad->fiat->rate ?? 0;
            $price         = (($coinPrice / $currencyPrice) / 100) * $request->margin;
        }

        $ad->payment_window_id = $paymentWindow->id;
        $ad->price_type        = $request->price_type;
        $ad->price             = $price;
        $ad->minimum_amount    = $request->minimum_amount;
        $ad->maximum_amount    = $request->maximum_amount;
        $ad->price_margin      = $request->margin ?? 0;


        if (1 == $ad->complete_step) {
            $ad->complete_step = 2;
        }
        $ad->save();

        $this->paymentMethods($request, $ad->id);

        $redirectUrl = route("user.p2p.advertisement.create", $ad->id) . "?step=3";
        return redirect($redirectUrl);
    }

    public function stepThree(Request $request, $id = 0)
    {
        if(!checkAgent()){
            return abort('404');
        }

        $request->validate([
            'payment_details'  => 'required|string',
            'terms_of_trade'   => 'required|string',
            'auto_replay_text' => 'nullable|string',
        ]);

        $purifier             = new \HTMLPurifier();
        $user                 = auth()->user();
        $ad                   = Ad::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $ad->payment_details  = $purifier->purify($request->payment_details);
        $ad->terms_of_trade   = $purifier->purify($request->terms_of_trade);
        $ad->auto_replay_text = $purifier->purify($request->auto_replay_text);

        if ($ad->complete_step == 2) {
            $ad->complete_step = 3;
            $notify            = "Ad created successfully. By clicking the below button create more ads.";
            $methodName        = "withSuccess";
        } else {
            $notify[]   = ['success', "Ad updated successfully"];
            $methodName = "withNotify";
        }

        $ad->save();

        // a finished new ad shows the success screen; edits go back to the list
        $redirectUrl = $methodName === "withSuccess" ? route("user.p2p.advertisement.create") : route("user.p2p.advertisement.index");
        return redirect($redirectUrl)->$methodName($notify);
    }

    private function paymentMethods($request, $id)
    {
        $oldPaymentMethod      = AdPaymentMethod::where('ad_id', $id)->pluck('payment_method_id')->toArray() ?? [];
        $requestPaymentMethod = $request->payment_method;
        $now                  = now();

        $removePaymentMethods = array_diff($oldPaymentMethod, $requestPaymentMethod);
        $addPaymentMethods    = array_diff($requestPaymentMethod, $oldPaymentMethod);

        if ($removePaymentMethods) {
            AdPaymentMethod::where('ad_id', $id)->whereIn('payment_method_id', $removePaymentMethods)->delete();
        }

        if ($addPaymentMethods) {
            foreach ($addPaymentMethods as $paymentMethod) {
                $paymentMethods[] = [
                    'payment_method_id' => $paymentMethod,
                    'ad_id'             => $id,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }
            AdPaymentMethod::insert($paymentMethods);
        }
    }

    public function changeStatus($id)
    {
        $user = auth()->user();
        $ad   = Ad::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        if ($ad->status == Status::ENABLE) {
            $ad->status = Status::DISABLE;
            $message    = "Ad disabled successfully";
        } else {
            $ad->status = Status::ENABLE;
            $message    = "Ad enabled successfully";
        }
        $ad->save();

        return returnBack($message, 'success');
    }
}
