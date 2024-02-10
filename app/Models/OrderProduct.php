<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'quantity',
        'is_gifted',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'is_gifted' => 'boolean',
        'unit_price' => 'double',
        'total_price' => 'double',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
}
