<?php

namespace App\Http\Controllers\User\P2P;

use App\Http\Controllers\Controller;
use App\Models\P2P\Ad;
use App\Models\P2P\Trade;
use App\Models\P2P\TradeFeedBack;

class HomeController extends Controller
{

    public function index()
    {
        $user  = auth()->user();

//        if($user->id !='11'){
//            return redirect()->route('p2p');
//        }

        $trade = Trade::myTrade($user->id);
        $ad    = Ad::where('user_id',$user->id);

        $widget['total_trade']     = (clone $trade)->count();
        $widget['running_trade']    = (clone $trade)->running()->count();
        $widget['completed_trade'] = (clone $trade)->completed()->count();

        $widget['total_ad']     = (clone $ad)->count();
        $widget['active_ad']    = (clone $ad)->active()->count();
        $widget['in_active_ad'] = (clone $ad)->inActive()->count();

        $pageTitle         = "P2P Center";
        $trades            = $trade->latest('id')->take(10)->with('ad.asset', 'ad.fiat', 'buyer', 'seller', 'paymentMethod')->get();
        $widget['feedback'] = userFeedback($user->id);

        return \Inertia\Inertia::render('User/P2P/Center', [
            'isAgent' => checkAgent(),
            'widget'  => [
                'totalTrade'     => (int) $widget['total_trade'],
                'runningTrade'   => (int) $widget['running_trade'],
                'completedTrade' => (int) $widget['completed_trade'],
                'totalAd'        => (int) $widget['total_ad'],
                'activeAd'       => (int) $widget['active_ad'],
                'inactiveAd'     => (int) $widget['in_active_ad'],
                'positive'       => (int) (@$widget['feedback']->positive ?? 0),
                'negative'       => (int) (@$widget['feedback']->negative ?? 0),
                'feedback'       => (int) (@$widget['feedback']->total ?? 0),
            ],
            'trades' => $trades->map(fn ($t) => [
                'id'      => $t->id,
                'uid'     => $t->uid,
                // the ad owner sees the ad side, the counterparty sees the trade side (as in the Blade table)
                'side'    => \App\Support\InertiaData::badgeText($t->ad && $t->ad->user_id == $user->id ? $t->ad->typeBadge : $t->typeBadge),
                'status'  => \App\Support\InertiaData::badgeText($t->statusBadge),
                'buyer'   => $user->id == $t->buyer_id ? 'Me' : trim(($t->buyer->firstname ?? '') . ' ' . ($t->buyer->lastname ?? '')),
                'seller'  => $user->id == $t->seller_id ? 'Me' : trim(($t->seller->firstname ?? '') . ' ' . ($t->seller->lastname ?? '')),
                'price'   => (float) ($t->ad->price ?? 0),
                'fiat'    => $t->ad->fiat->symbol ?? '',
                'asset'   => $t->ad->asset->symbol ?? '',
                'method'  => __($t->paymentMethod->name ?? ''),
                'assetAmount' => (float) $t->asset_amount,
                'fiatAmount'  => (float) $t->fiat_amount,
                'date'    => \App\Support\InertiaData::date($t->created_at),
                'url'     => route('user.p2p.trade.details', $t->id),
            ])->values(),
            'urls' => [
                'market'         => route('p2p'),
                'running'        => route('user.p2p.trade.list', 'running'),
                'completed'      => route('user.p2p.trade.list', 'completed'),
                'ads'            => route('user.p2p.advertisement.index'),
                'newAd'          => route('user.p2p.advertisement.create'),
                'methods'        => route('user.p2p.payment.method.list'),
                'newMethod'      => route('user.p2p.payment.method.create'),
                'feedback'       => route('user.p2p.feedback.list'),
            ],
        ]);
    }

    public function feedbackList()
    {
        $feedbacks = TradeFeedBack::where('user_id', auth()->id())->latest('id')->paginate(getPaginate());
        $summary   = userFeedback(auth()->id());
        $givers    = \App\Models\User::whereIn('id', $feedbacks->pluck('provide_by'))->get(['id', 'firstname', 'lastname', 'username'])->keyBy('id');

        return \Inertia\Inertia::render('User/P2P/Feedback', [
            'summary'   => ['positive' => (int) $summary->positive, 'negative' => (int) $summary->negative, 'total' => (int) $summary->total],
            'feedbacks' => \App\Support\InertiaData::paginate($feedbacks, fn ($f) => [
                'id'       => $f->id,
                'positive' => $f->type == \App\Constants\Status::P2P_TRADE_FEEDBACK_POSITIVE,
                'comment'  => $f->comment,
                'from'     => ($g = $givers[$f->provide_by] ?? null) ? (trim($g->firstname . ' ' . $g->lastname) ?: $g->username) : null,
                'date'     => \App\Support\InertiaData::date($f->created_at),
            ]),
            'urls' => ['center' => route('user.p2p.dashboard'), 'market' => route('p2p')],
        ]);
    }
}
