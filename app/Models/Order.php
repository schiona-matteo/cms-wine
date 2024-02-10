<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'source',
        'user_id',
        'discount_id',
        'reference',
        'shipment_price',
        'product_price',
        'duties_price',
        'discount_amount',
        'tax_rate',
        'final_price_without_tax',
        'final_price_with_tax',
        'pickup_at_store',
        'should_be_shipped',
        'payment_method',
        'status',
        'lang',
        'should_be_invoiced',
        'external_invoice_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'shipment_price' => 'double',
        'product_price' => 'double',
        'duties_price' => 'double',
        'discount_amount' => 'double',
        'final_price_without_tax' => 'double',
        'final_price_with_tax' => 'double',
        'pickup_at_store' => 'boolean',
        'should_be_shipped' => 'boolean',
        'should_be_invoiced' => 'boolean',
    ];

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
