<?php

namespace App\Repositories\Api\SupplierPayment;

use App\Models\SupplierPayment;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierPaymentRepository
{
    /**
     * Get all payments for a specific supplier with pagination
     */
    public function getBySupplier(int $supplierId, ?string $type = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = SupplierPayment::where('supplier_id', $supplierId)
            ->with('creator')
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get supplier statement (all payments ordered by date)
     */
    public function getStatement(int $supplierId): array
    {
        $payments = SupplierPayment::where('supplier_id', $supplierId)
            ->with('creator')
            ->orderBy('payment_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $runningBalance = 0;
        $statement = [];

        foreach ($payments as $payment) {
            // Calculate running balance
            if (in_array($payment->type, ['purchase', 'opening_balance'])) {
                $runningBalance += $payment->amount;
            } else { // payment or refund
                $runningBalance -= $payment->amount;
            }

            $statement[] = [
                'id' => $payment->id,
                'date' => $payment->payment_date,
                'type' => $payment->type,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'notes' => $payment->notes,
                'created_by' => $payment->creator ? $payment->creator->name : null,
                'balance' => $runningBalance,
            ];
        }

        return $statement;
    }

    /**
     * Create a new supplier payment
     */
    public function create(array $data): SupplierPayment
    {
        return SupplierPayment::create($data);
    }

    /**
     * Find payment by ID
     */
    public function find(int $id): ?SupplierPayment
    {
        return SupplierPayment::find($id);
    }

    /**
     * Delete a payment
     */
    public function delete(SupplierPayment $payment): bool
    {
        return $payment->delete();
    }
}
