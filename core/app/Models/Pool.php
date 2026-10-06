<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    use GlobalStatus;

    protected $fillable = [
        'name',
        'symbol',
        'image',
        'apr',
        'vip_apr',
        'prize_pool'
    ];

    protected $casts = [
        'apr' => 'decimal:2',
        'vip_apr' => 'decimal:2',
        'prize_pool' => 'decimal:2'
    ];
} 