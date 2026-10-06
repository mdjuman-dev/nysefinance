<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InertiaData
{
    /**
     * Shape a paginator for the Vue <Pagination> / <DataList> components.
     */
    public static function paginate(LengthAwarePaginator $paginator, callable $map): array
    {
        $paginator->withQueryString();

        return [
            'data' => collect($paginator->items())->map($map)->values(),
            'meta' => [
                'current' => $paginator->currentPage(),
                'last'    => $paginator->lastPage(),
                'from'    => $paginator->firstItem(),
                'to'      => $paginator->lastItem(),
                'total'   => $paginator->total(),
                'prev'    => $paginator->previousPageUrl(),
                'next'    => $paginator->nextPageUrl(),
                'links'   => collect($paginator->linkCollection())
                    ->slice(1, -1) // drop the « » entries, prev/next are separate
                    ->map(fn ($l) => ['url' => $l['url'], 'label' => $l['label'], 'active' => $l['active']])
                    ->values(),
            ],
        ];
    }

    /** Text of the first .badge in the models' HTML statusBadge attributes. */
    public static function badgeText(?string $html): string
    {
        return preg_match('/class="badge[^"]*"[^>]*>([^<]+)</', (string) $html, $m) ? trim($m[1]) : trim(strip_tags((string) $html));
    }

    public static function date($date): ?string
    {
        return $date ? $date->toIso8601String() : null;
    }
}
