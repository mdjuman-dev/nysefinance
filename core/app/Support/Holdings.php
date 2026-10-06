<?php

namespace App\Support;

use App\Models\StockExchange;
use App\Models\StockTransaction;
use App\Models\UserStock;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Shared data for the "My stocks / My bonds" and "history" pages.
 * Same rules as the Blade views, with the per-row queries batched:
 *  - holdings + sold in one query with products eager-loaded (was 2 queries + 1 per row)
 *  - fund interest summed in one grouped query
 *  - history rows eager-load their product (was 2 queries per row)
 */
class Holdings
{
    /** @param string $useFor 'stock' | 'bond' */
    public static function portfolio(int $userId, string $useFor): array
    {
        // Stocks with a pending/approved exchange request are hidden, as before.
        $exchanging = StockExchange::where('user_id', $userId)->whereIn('status', ['pending', 'approved'])->select('user_stock_id');

        $items = UserStock::where('user_id', $userId)
            ->where('use_for', $useFor)
            ->whereIn('status', ['buy', 'sell'])
            ->whereNotIn('id', $exchanging)
            ->with('product:id,name,image,stock_code')
            ->orderByDesc('created_at')
            ->get();

        $holding = $items->where('status', 'buy')->values();
        $sold = $items->where('status', 'sell')->values();

        $interest = StockTransaction::where('type', 'interest')
            ->whereIn('stock_id', $holding->where('type', 'fix')->pluck('id'))
            ->groupBy('stock_id')
            ->selectRaw('stock_id, SUM(amount) as total')
            ->pluck('total', 'stock_id');

        $image = fn ($product) => getImage(getFilePath('currency') . '/' . ($product->image ?? ''), getFileSize('currency'));

        return [
            'totalInvest' => (float) $holding->sum('invest_amount'),
            'holdings' => $holding->map(function ($p) use ($image, $interest) {
                // Fixed (mutual fund) holdings unlock after invest_date.
                $matured = $p->type == 'fix' && $p->invest_date && $p->invest_date <= now();
                $canTrade = $matured || $p->type == 'unfix';
                return [
                    'id'          => $p->id,
                    'name'        => $p->product->name ?? '',
                    'code'        => $p->product->stock_code ?? null,
                    'image'       => $image($p->product),
                    'type'        => $p->type == 'fix' ? 'Mutual fund' : 'Live market',
                    'fixed'       => $p->type == 'fix',
                    'invest'      => (float) $p->invest_amount,
                    'stackPrice'  => (float) $p->stack_price,
                    'interest'    => $p->type == 'fix' ? (float) ($interest[$p->id] ?? 0) : null,
                    'unlocksAt'   => $p->type == 'fix' && $p->invest_date ? Carbon::parse($p->invest_date)->toIso8601String() : null,
                    'matured'     => $matured,
                    'certificate' => route('public.certificate', [$p->certificate_id]),
                    'sellUrl'     => $canTrade ? route('user.stock.sell', [$p->id]) : null,
                    'exchangeUrl' => $canTrade ? route('user.stock.exchange', [$p->id]) : null,
                    // the Blade view only exposed "Reactive" for matured fixed holdings
                    'reactiveUrl' => $matured ? route('user.stock.reactive', [$p->id]) : null,
                    'date'        => InertiaData::date($p->created_at),
                ];
            })->values(),
            'sold' => $sold->map(fn ($p) => [
                'id'          => $p->id,
                'name'        => $p->product->name ?? '',
                'image'       => $image($p->product),
                'invest'      => (float) $p->invest_amount,
                'certificate' => route('public.certificate', [$p->certificate_id]),
                'date'        => InertiaData::date($p->updated_at),
            ])->values(),
        ];
    }

    /** @param string $tab 'transactions' | 'interests' */
    public static function history(int $userId, string $useFor, string $tab, Request $request): array
    {
        $holdingIds = UserStock::where('user_id', $userId)->where('use_for', $useFor)->select('id');

        $query = StockTransaction::where('user_id', $userId)
            ->whereIn('stock_id', $holdingIds)
            ->with('user_stock:id,product_id', 'user_stock.product:id,name')
            ->orderByDesc('created_at');

        $tab === 'interests'
            ? $query->where('type', 'interest')
            : $query->whereIn('type', ['sell', 'buy', 'exchange']);

        // Same "start-end" filter_date format the Blade date picker sent.
        if ($request->filter_date) {
            $dates = explode('-', $request->filter_date);
            $query->whereBetween('created_at', [$dates[0] ?? now()->subDays(7), $dates[1] ?? now()]);
        }

        return InertiaData::paginate($query->paginate(20), fn ($t) => [
            'id'     => $t->id,
            'stock'  => $t->stock_id && $t->user_stock->product ? $t->user_stock->product->name : null,
            'amount' => (float) $t->amount,
            'type'   => $t->type,
            'remark' => $t->remark,
            'date'   => InertiaData::date($t->created_at),
        ]);
    }
}
