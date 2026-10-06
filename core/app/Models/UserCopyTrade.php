<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCopyTrade extends Model
{
    use HasFactory;

    public function trade()
    {
        return$this->belongsTo(CopyTrade::class, 'trade_id', 'id')->withDefault();
    }

    public function user()
    {
        return$this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }
}
