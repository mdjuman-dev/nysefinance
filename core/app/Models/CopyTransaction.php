<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CopyTransaction extends Model
{
    use HasFactory;

   protected $dates=['created_at'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function trade()
    {
        return $this->belongsTo(CopyTrade::class, 'trade_id', 'id')->withDefault();
    }
}
