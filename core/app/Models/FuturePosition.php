<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuturePosition extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'margin'            => 'float',
        'size'              => 'float',
        'entry_price'       => 'float',
        'liquidation_price' => 'float',
        'maintenance_rate'  => 'float',
        'fee_rate'          => 'float',
        'take_profit'       => 'float',
        'stop_loss'         => 'float',
        'open_fee'          => 'float',
        'close_fee'         => 'float',
        'close_price'       => 'float',
        'realized_pnl'      => 'float',
        'payout'            => 'float',
        'closed_at'         => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pair()
    {
        return $this->belongsTo(CoinPair::class, 'pair_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function isLong(): bool
    {
        return $this->side === 'long';
    }

    /** Unrealized price PnL at $price (before closing fee). */
    public function pnlAt(float $price): float
    {
        return ($this->isLong() ? $price - $this->entry_price : $this->entry_price - $price) * $this->size;
    }
}
