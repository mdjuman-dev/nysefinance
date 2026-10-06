<?php

namespace App\Services\Futures;

use App\Models\CoinPair;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Server-side mark price. Never trust a price sent by the browser.
 * Binance spot ticker first (what the trade screens display), the pair's
 * market_data price as a fallback.
 */
class MarkPrice
{
    /** @return array<int, float> pair_id => price (pairs without a price are omitted) */
    public static function forPairs(Collection $pairs): array
    {
        $pairs = $pairs->filter()->unique('id');
        if ($pairs->isEmpty()) return [];

        $bySymbol = $pairs->keyBy(fn ($p) => strtoupper(str_replace('_', '', $p->symbol)));
        $prices = [];

        try {
            $res = Http::timeout(4)->get('https://api.binance.com/api/v3/ticker/price', [
                'symbols' => json_encode($bySymbol->keys()->values()),
            ]);
            if (!$res->ok()) {
                // An unknown symbol fails the whole batch; retry one by one.
                foreach ($bySymbol as $sym => $pair) {
                    $one = Http::timeout(3)->get('https://api.binance.com/api/v3/ticker/price', ['symbol' => $sym]);
                    if ($one->ok() && ($p = (float) $one->json('price')) > 0) $prices[$pair->id] = $p;
                }
            } else {
                foreach ((array) $res->json() as $row) {
                    $pair = $bySymbol[$row['symbol'] ?? ''] ?? null;
                    if ($pair && ($p = (float) $row['price']) > 0) $prices[$pair->id] = $p;
                }
            }
        } catch (\Throwable $e) {
            // fall through to stored prices
        }

        foreach ($pairs as $pair) {
            if (!isset($prices[$pair->id])) {
                $pair->loadMissing('marketData');
                $stored = (float) ($pair->marketData->price ?? 0);
                if ($stored > 0) $prices[$pair->id] = $stored;
            }
        }

        return $prices;
    }

    public static function forPair(CoinPair $pair): ?float
    {
        return self::forPairs(collect([$pair]))[$pair->id] ?? null;
    }
}
