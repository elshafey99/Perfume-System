<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'type',
        'amount',
        'payment_method',
        'reference_number',
        'payment_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the supplier
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created this payment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
