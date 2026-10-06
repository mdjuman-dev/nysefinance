<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, UserNotify;

    /**
     * Every new account (web, API, social, Web3) gets a random public UID.
     * Existing accounts keep the UID they already have.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (blank($user->uid)) {
                $user->uid = static::generateUid();
            }
        });
    }

    /**
     * Random 8-digit UID starting 2-9. Legacy UIDs were "10000{id}" (always starting
     * with 1), so the two ranges can never collide; the lookup guards the rest.
     */
    public static function generateUid(): string
    {
        do {
            $uid = (string) random_int(20000000, 99999999);
        } while (static::where('uid', $uid)->exists());

        return $uid;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','ver_code','kyc_data'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'kyc_data' => 'object',
        'ver_code_send_at' => 'datetime'
    ];


    public function userStocks()
    {
        return $this->hasMany(UserStock::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }
    public function wallets()
    {
        return $this->hasMany(Wallet::class,'user_id');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn () => $this->dial_code . $this->mobile,
        );
    }


    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_by', 'id')->withDefault();
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_by','id');
    }

    public function activeReferrals()
    {
        return $this->hasMany(User::class, 'ref_by');
    }

    public function allReferrals()
    {
        return $this->referrals()->with('referrer');
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('ev',Status::VERIFIED)->where('sv',Status::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }


    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

}
