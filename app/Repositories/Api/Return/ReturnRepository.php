<?php

namespace App\Repositories\Api\Return;

use App\Models\ProductReturn;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ReturnRepository
{
    /**
     * Get all returns with pagination
     */
    public function getAll(int $perPage = 15, ?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): LengthAwarePaginator
    {
        $query = ProductReturn::with(['sale', 'saleItem', 'processor']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all returns without pagination
     */
    public function getAllWithoutPagination(?string $status = null): Collection
    {
        $query = ProductReturn::with(['sale', 'saleItem', 'processor']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Find return by ID
     */
    public function findById(int $id): ?ProductReturn
    {
        return ProductReturn::with(['sale', 'saleItem', 'processor'])->find($id);
    }

    /**
     * Get returns by sale ID
     */
    public function getBySaleId(int $saleId): Collection
    {
        return ProductReturn::with(['sale', 'saleItem', 'processor'])
            ->where('sale_id', $saleId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create new return
     */
    public function create(array $data): ProductReturn
    {
        return ProductReturn::create($data);
    }

    /**
     * Update return
     */
    public function update(ProductReturn $return, array $data): ProductReturn
    {
        $return->update($data);
        return $return;
    }

    /**
     * Delete return
     */
    public function delete(ProductReturn $return): bool
    {
        return $return->delete();
    }

    /**
     * Generate return number
     */
    public function generateReturnNumber(): string
    {
        $prefix = 'RTN-';
        $date = now()->format('Ymd');
        
        $lastReturn = ProductReturn::where('return_number', 'like', $prefix . $date . '%')
            ->orderBy('return_number', 'desc')
            ->first();

        if ($lastReturn) {
            $lastNumber = (int) substr($lastReturn->return_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . '-' . $newNumber;
    }

    /**
     * Get statistics
     */
    public function getStatistics(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $query = ProductReturn::query();

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $returns = $query->get();

        return [
            'total_returns' => $returns->count(),
            'pending_returns' => $returns->where('status', 'pending')->count(),
            'approved_returns' => $returns->where('status', 'approved')->count(),
            'completed_returns' => $returns->where('status', 'completed')->count(),
            'rejected_returns' => $returns->where('status', 'rejected')->count(),
            'total_refund_amount' => $returns->where('status', 'completed')->sum('return_amount'),
            'by_reason' => [
                'defective' => $returns->where('return_reason', 'defective')->count(),
                'wrong_item' => $returns->where('return_reason', 'wrong_item')->count(),
                'customer_request' => $returns->where('return_reason', 'customer_request')->count(),
                'other' => $returns->where('return_reason', 'other')->count(),
            ],
            'by_type' => [
                'refund' => $returns->where('return_type', 'refund')->count(),
                'exchange' => $returns->where('return_type', 'exchange')->count(),
                'store_credit' => $returns->where('return_type', 'store_credit')->count(),
            ],
        ];
    }
}
