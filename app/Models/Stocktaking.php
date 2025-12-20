<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stocktaking extends Model
{
    use HasFactory;

    protected $fillable = [
        'stocktaking_number',
        'stocktaking_date',
        'status',
        'notes',
        'created_by',
        'completed_by',
        'completed_at',
        'total_items',
        'total_differences',
    ];

    protected $casts = [
        'stocktaking_date' => 'date',
        'completed_at' => 'datetime',
        'total_items' => 'integer',
        'total_differences' => 'decimal:4',
    ];

    /**
     * Get the user who created this stocktaking
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who completed this stocktaking
     */
    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get stocktaking items
     */
    public function items(): HasMany
    {
        return $this->hasMany(StocktakingItem::class);
    }
}

