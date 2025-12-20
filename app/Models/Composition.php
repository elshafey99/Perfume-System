<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Composition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'product_id',
        'bottle_size',
        'concentration_type',
        'base_cost',
        'service_fee',
        'selling_price',
        'instructions',
        'notes',
        'image',
        'is_magic_recipe',
        'original_perfume_name',
        'is_active',
    ];

    protected $casts = [
        'bottle_size' => 'decimal:2',
        'base_cost' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_magic_recipe' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get composition ingredients
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(CompositionIngredient::class);
    }

    /**
     * Get sale items
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}

