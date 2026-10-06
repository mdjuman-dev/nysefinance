<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    public function user_stock()
    {

        return $this->belongsTo(UserStock::class, 'stock_id', 'id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();

    }
}
