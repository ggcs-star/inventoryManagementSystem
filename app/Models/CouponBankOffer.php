<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponBankOffer extends Model
{
    protected $fillable = [
        'coupon_id',

        'bank_id',    
        'card_type',     

        'type',          
        'value',
        'max_discount',

        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

  
    public function bank()
{
    return $this->belongsTo(Bank::class);
}

}
