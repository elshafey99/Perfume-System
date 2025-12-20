<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'birth_date',
        'gender',
        'address',
        'loyalty_points',
        'loyalty_level',
        'total_purchases',
        'last_purchase_date',
        'preferred_scents',
        'favorite_products',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'last_purchase_date' => 'date',
        'loyalty_points' => 'decimal:2',
        'total_purchases' => 'decimal:2',
        'preferred_scents' => 'array',
        'favorite_products' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get sales for this customer
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get loyalty points transactions
     */
    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(LoyaltyPoint::class);
    }
}
