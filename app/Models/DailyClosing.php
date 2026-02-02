<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'closing_date',
        'closed_by',
        'total_sales',
        'total_cash',
        'total_card',
        'total_invoices',
        'total_refunds',
        'total_expenses',
        'notes',
    ];

    protected $casts = [
        'closing_date' => 'date',
        'total_sales' => 'decimal:2',
        'total_cash' => 'decimal:2',
        'total_card' => 'decimal:2',
        'total_invoices' => 'integer',
        'total_refunds' => 'decimal:2',
        'total_expenses' => 'decimal:2',
    ];

    /**
     * Get the user who closed this day
     */
    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
