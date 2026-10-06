<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuturePosition;
use App\Services\Futures\FuturesEngine;
use Illuminate\Http\Request;

class FuturesController extends Controller
{
    public function positions(Request $request, $scope = 'open')
    {
        $scopes = ['open' => 'Open Positions', 'closed' => 'Closed Positions', 'liquidated' => 'Liquidated Positions', 'all' => 'All Positions'];
        abort_unless(isset($scopes[$scope]), 404);
        $pageTitle = 'Futures — ' . $scopes[$scope];

        $query = FuturePosition::with('user:id,username,firstname,lastname', 'pair:id,symbol')->latest('id');
        if ($scope !== 'all') $query->where('status', $scope);
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('trx', 'like', "%$search%")
                    ->orWhereHas('user', fn ($u) => $u->where('username', 'like', "%$search%"))
                    ->orWhereHas('pair', fn ($p) => $p->where('symbol', 'like', "%$search%"));
            });
        }
        $positions = $query->paginate(getPaginate());

        // Platform view: what users paid in (margin + fees) vs what was paid back.
        $settled = FuturePosition::where('status', '!=', 'open');
        $stats = [
            'open_count'    => FuturePosition::open()->count(),
            'open_margin'   => (float) FuturePosition::open()->sum('margin'),
            'open_notional' => (float) FuturePosition::open()->selectRaw('SUM(size * entry_price) as n')->value('n'),
            'fees'          => (float) FuturePosition::sum('open_fee') + (float) FuturePosition::sum('close_fee'),
            'house_pnl'     => (float) (clone $settled)->selectRaw('SUM(margin + open_fee - payout) as h')->value('h'),
            'liquidations'  => FuturePosition::where('status', 'liquidated')->count(),
        ];

        return view('admin.futures.positions', compact('pageTitle', 'positions', 'stats', 'scope', 'scopes'));
    }

    /** Admin "Settle now" button: run the liquidation / TP / SL sweep immediately. */
    public function sweep()
    {
        $settled = FuturesEngine::sweep();
        $notify[] = ['success', "Sweep finished: $settled position(s) settled"];
        return back()->withNotify($notify);
    }
}
