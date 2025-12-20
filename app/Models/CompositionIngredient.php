<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'composition_id',
        'ingredient_product_id',
        'quantity',
        'unit',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'sort_order' => 'integer',
    ];

    /**
     * Get the composition
     */
    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    /**
     * Get the ingredient product
     */
    public function ingredientProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ingredient_product_id');
    }
}

