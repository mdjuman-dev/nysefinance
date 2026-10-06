<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    use HasFactory;

    public function coupon()
    {
        return $this->belongsTo(Coupon::class)->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
