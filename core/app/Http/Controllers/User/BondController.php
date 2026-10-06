<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockVideo;
use Illuminate\Http\Request;
use App\Support\Holdings;
use Inertia\Inertia;

class BondController extends Controller
{

    public function myBonds()
    {
        return Inertia::render('User/Stock/MyStocks', Holdings::portfolio(auth()->id(), 'bond') + [
            'kind' => 'bond',
            'urls' => [
                'qr'        => route('user.stock.qr.code'),
                'livePrice' => route('user.stock.live.price'),
                'market'    => route('user.bonds'),
                'history'   => route('user.bond.transactions'),
            ],
        ]);
    }


    public function details($slug)
    {
        $bond = Product::where('slug', $slug)->firstOrFail(['id', 'name', 'slug', 'image', 'stock_code', 'use_for', 'bond_type', 'short_description', 'description']);
        $videos = StockVideo::where('product_id', $bond->id)->pluck('video');

        return Inertia::render('User/Bond/Details', [
            'bond' => [
                'id'          => $bond->id,
                'name'        => $bond->name,
                'code'        => $bond->stock_code,
                'image'       => getImage(getFilePath('currency') . '/' . $bond->image, getFileSize('currency')),
                'category'    => ['bonds' => 'Bond', 'economy' => 'Economy', 'indices' => 'Indices', 'options' => 'Options'][$bond->bond_type] ?? 'Bond',
                // CMS HTML, rendered as before ({!! !!} in the Blade view)
                'summary'     => $bond->short_description,
                'description' => $bond->description,
                'info'        => trim(strip_tags((string) $bond->short_description)),
            ],
            'videos'   => $videos->map(fn ($v) => getImage(getFilePath('currency') . '/' . $v, getFileSize('currency')))->values(),
            'isMember' => \App\Models\StockMember::where('user_id', auth()->id())->exists(),
            'urls' => [
                'buy'    => route('user.stock.buy'),
                'member' => route('user.stock.member'),
                'my'     => route('user.my.bonds'),
                'market' => route('user.bonds'),
            ],
        ]);
    }


    public function bondTransactions(Request $request)
    {
        return Inertia::render('User/Stock/History', [
            'kind' => 'bond',
            'tab'  => 'transactions',
            'rows' => Holdings::history(auth()->id(), 'bond', 'transactions', $request),
            'urls' => ['transactions' => route('user.bond.transactions'), 'interests' => route('user.bond.interest'), 'my' => route('user.my.bonds')],
        ]);
    }

    public function bondInterest(Request $request)
    {
        return Inertia::render('User/Stock/History', [
            'kind' => 'bond',
            'tab'  => 'interests',
            'rows' => Holdings::history(auth()->id(), 'bond', 'interests', $request),
            'urls' => ['transactions' => route('user.bond.transactions'), 'interests' => route('user.bond.interest'), 'my' => route('user.my.bonds')],
        ]);
    }


}
