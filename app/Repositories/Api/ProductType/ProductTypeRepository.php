<?php

namespace App\Repositories\Api\ProductType;

use App\Models\ProductType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductTypeRepository
{
    /**
     * Get all product types with pagination
     */
    public function getAll(int $perPage = 15, ?bool $activeOnly = null): LengthAwarePaginator
    {
        $query = ProductType::orderBy('sort_order')->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all product types without pagination
     */
    public function getAllWithoutPagination(?bool $activeOnly = null): Collection
    {
        $query = ProductType::orderBy('sort_order')->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->get();
    }

    /**
     * Find product type by ID
     */
    public function findById(int $id): ?ProductType
    {
        return ProductType::with('products')->find($id);
    }

    /**
     * Find product type by code
     */
    public function findByCode(string $code): ?ProductType
    {
        return ProductType::where('code', $code)->first();
    }

    /**
     * Create new product type
     */
    public function create(array $data): ProductType
    {
        return ProductType::create($data);
    }

    /**
     * Update product type
     */
    public function update(ProductType $productType, array $data): bool
    {
        return $productType->update($data);
    }

    /**
     * Delete product type
     */
    public function delete(ProductType $productType): bool
    {
        // Check if product type has products
        if ($productType->products()->count() > 0) {
            return false;
        }

        return $productType->delete();
    }

    /**
     * Check if product type has products
     */
    public function hasProducts(ProductType $productType): bool
    {
        return $productType->products()->count() > 0;
    }
}

