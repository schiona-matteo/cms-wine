<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variant extends Model
{
    protected $fillable = [
        'product_id',
        'code',
        'price',
        'weight',
        'attributes',
        'available_for_order',
    ];

    protected $casts = [
        'price' => 'double',
        'attributes' => 'json',
        'available_for_order' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
