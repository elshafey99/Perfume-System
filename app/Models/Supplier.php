<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'phones',
        'email',
        'website',
        'address',
        'area',
        'tax_number',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'phones' => 'array',
    ];

    /**
     * Get supplier payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(SupplierPayment::class);
    }

    /**
     * Get total purchases from this supplier
     */
    public function getTotalPurchasesAttribute()
    {
        return $this->payments()
                    ->whereIn('type', ['purchase', 'opening_balance'])
                    ->sum('amount');
    }

    /**
     * Get total paid to this supplier
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()
                    ->where('type', 'payment')
                    ->sum('amount');
    }

    /**
     * Get balance due (المتبقي)
     */
    public function getBalanceDueAttribute()
    {
        return $this->total_purchases - $this->total_paid;
    }

    /**
     * Get purchases from this supplier
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get products from this supplier
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

