<?php

namespace App\Http\Middleware;

use App\Models\Product;
use App\Models\StockMember;
use Closure;
use Illuminate\Http\Request;
use function Termwind\render;

class CheckStockWallet
{


    public function handle(Request $request, Closure $next)
    {

        if(auth()->check()) {

            $stock_member = StockMember::where('user_id', auth()->user()->id)->first();
            if(!$stock_member) {
                return redirect()->route('user.stock.index')->withErrors(['error'=> 'Please confirm your member to use this feature']);
            }
        }

        return $next($request);
    }
}
