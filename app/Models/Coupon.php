<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    use HasFactory;

    public const TYPE_PERCENT = 'percent';
    public const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'value' => 'float',
        'max_discount' => 'float',
        'min_order_amount' => 'float',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isUsageLimitReached(): bool
    {
        return $this->usage_limit !== null && $this->used_count >= $this->usage_limit;
    }

    /**
     * Check if this coupon is active, not expired, and below usage limit.
     */
    public function isValidForAmount(float $amount): array
    {
        if (!$this->is_active) {
            return [
                'valid' => false,
                'message' => 'This promo code is currently inactive.',
            ];
        }

        if ($this->isExpired()) {
            return [
                'valid' => false,
                'message' => 'This promo code expired on ' . $this->expires_at->format('M d, Y') . '.',
            ];
        }

        if ($this->isUsageLimitReached()) {
            return [
                'valid' => false,
                'message' => 'This promo code has reached its maximum total redemptions.',
            ];
        }

        if ($this->min_order_amount > 0 && $amount < $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'Minimum order amount to apply this coupon is ₹ ' . number_format($this->min_order_amount, 2),
            ];
        }

        return ['valid' => true, 'message' => 'Coupon code applied successfully!'];
    }

    /**
     * Calculate discount amount for a given order base price.
     */
    public function calculateDiscount(float $basePrice): float
    {
        if ($this->type === self::TYPE_FIXED) {
            $discount = min($basePrice, $this->value);
        } else {
            // Percent
            $discount = round(($basePrice * $this->value) / 100, 2);
            if ($this->max_discount !== null && $this->max_discount > 0 && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        }

        return max(0, min($basePrice, round($discount, 2)));
    }
}
