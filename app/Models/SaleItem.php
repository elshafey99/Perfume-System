<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'composition_id',
        'product_name',
        'quantity',
        'unit',
        'unit_price',
        'total',
        'is_composition',
        'is_custom_blend',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'is_composition' => 'boolean',
        'is_custom_blend' => 'boolean',
    ];

    /**
     * Get the sale
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the composition
     */
    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    /**
     * Get returns for this item
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }
}
