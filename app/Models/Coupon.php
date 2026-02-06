<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
    'code',
    'type',
    'value',
    'min_cart_amount',
    'max_discount',
    'usage_limit',
    'used_count',
    'is_active',
    'starts_at',
    'expires_at',
];

protected $casts = [
    'is_active'  => 'boolean',
    'starts_at'  => 'datetime',
    'expires_at' => 'datetime',
];

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'coupon_platform');
    }

    public function bankOffers()
    {
        return $this->hasMany(CouponBankOffer::class);
    }
}
