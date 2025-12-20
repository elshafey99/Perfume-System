<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StocktakingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stocktaking_id',
        'product_id',
        'recorded_stock',
        'actual_stock',
        'difference',
        'unit',
        'reason',
        'notes',
    ];

    protected $casts = [
        'recorded_stock' => 'decimal:4',
        'actual_stock' => 'decimal:4',
        'difference' => 'decimal:4',
    ];

    /**
     * Get the stocktaking
     */
    public function stocktaking(): BelongsTo
    {
        return $this->belongsTo(Stocktaking::class);
    }

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

