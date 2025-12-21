<?php

namespace App\Repositories\Api\UnitType;

use App\Models\UnitType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UnitTypeRepository
{
    /**
     * Get all unit types with pagination
     */
    public function getAll(int $perPage = 15, ?bool $activeOnly = null): LengthAwarePaginator
    {
        $query = UnitType::orderBy('sort_order')->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all unit types without pagination
     */
    public function getAllWithoutPagination(?bool $activeOnly = null): Collection
    {
        $query = UnitType::orderBy('sort_order')->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->get();
    }

    /**
     * Find unit type by ID
     */
    public function findById(int $id): ?UnitType
    {
        return UnitType::with('products')->find($id);
    }

    /**
     * Find unit type by code
     */
    public function findByCode(string $code): ?UnitType
    {
        return UnitType::where('code', $code)->first();
    }

    /**
     * Create new unit type
     */
    public function create(array $data): UnitType
    {
        return UnitType::create($data);
    }

    /**
     * Update unit type
     */
    public function update(UnitType $unitType, array $data): bool
    {
        return $unitType->update($data);
    }

    /**
     * Delete unit type
     */
    public function delete(UnitType $unitType): bool
    {
        // Check if unit type has products
        if ($unitType->products()->count() > 0) {
            return false;
        }

        return $unitType->delete();
    }

    /**
     * Check if unit type has products
     */
    public function hasProducts(UnitType $unitType): bool
    {
        return $unitType->products()->count() > 0;
    }
}

