<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinStack extends Model
{
    use HasFactory;

    public function pool()
    {
        return $this->belongsTo(Pool::class, 'pool_id', 'id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }
}
