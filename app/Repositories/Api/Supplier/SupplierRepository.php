<?php

namespace App\Repositories\Api\Supplier;

use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SupplierRepository
{
    /**
     * Get all suppliers with pagination
     */
    public function getAll(int $perPage = 15, ?bool $activeOnly = null): LengthAwarePaginator
    {
        $query = Supplier::orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all suppliers without pagination
     */
    public function getAllWithoutPagination(?bool $activeOnly = null): Collection
    {
        $query = Supplier::orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->get();
    }

    /**
     * Find supplier by ID
     */
    public function findById(int $id): ?Supplier
    {
        return Supplier::with(['purchases', 'products'])->find($id);
    }

    /**
     * Create new supplier
     */
    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    /**
     * Update supplier
     */
    public function update(Supplier $supplier, array $data): bool
    {
        return $supplier->update($data);
    }

    /**
     * Delete supplier
     */
    public function delete(Supplier $supplier): bool
    {
        // Check if supplier has purchases
        if ($supplier->purchases()->count() > 0) {
            return false;
        }

        // Check if supplier has products
        if ($supplier->products()->count() > 0) {
            return false;
        }

        return $supplier->delete();
    }

    /**
     * Check if supplier has purchases
     */
    public function hasPurchases(Supplier $supplier): bool
    {
        return $supplier->purchases()->count() > 0;
    }

    /**
     * Check if supplier has products
     */
    public function hasProducts(Supplier $supplier): bool
    {
        return $supplier->products()->count() > 0;
    }
}
