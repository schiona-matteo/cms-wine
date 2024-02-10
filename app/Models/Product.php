<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'category',
        'link_rewrite',
        'name',
        'subtitle_it',
        'subtitle_en',
        'description_it',
        'description_en',
        'settings',
        'available_for_order',
        'limited_buy',
        'limited_buy_for_user',
        'discountable',
        'prevent_bankwire',
        'is_virtual',
        'meta_data',
        'visibility',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'json',
        'meta_data' => 'json',
        'available_for_order' => 'boolean',
        'discountable' => 'boolean',
        'prevent_bankwire' => 'boolean',
        'is_virtual' => 'boolean',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
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
