<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'value',
        'liquid_value',
        'status_id',
        'payment_status_id',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'liquid_value' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Order $order) {
            $order->carts()->delete();
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
