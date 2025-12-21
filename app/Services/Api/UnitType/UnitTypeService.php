<?php

namespace App\Services\Api\UnitType;

use App\Repositories\Api\UnitType\UnitTypeRepository;
use App\Models\UnitType;
use Illuminate\Support\Str;

class UnitTypeService
{
    protected UnitTypeRepository $unitTypeRepository;

    public function __construct(UnitTypeRepository $unitTypeRepository)
    {
        $this->unitTypeRepository = $unitTypeRepository;
    }

    /**
     * Get all unit types
     */
    public function getAll(?int $perPage = null, ?bool $activeOnly = null): array
    {
        if ($perPage) {
            $unitTypes = $this->unitTypeRepository->getAll($perPage, $activeOnly);
        } else {
            $unitTypes = $this->unitTypeRepository->getAllWithoutPagination($activeOnly);
        }

        return [
            'success' => true,
            'data' => $unitTypes,
        ];
    }

    /**
     * Get unit type by ID
     */
    public function getById(int $id): array
    {
        $unitType = $this->unitTypeRepository->findById($id);

        if (!$unitType) {
            return [
                'success' => false,
                'message' => __('unit_types.unit_type_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $unitType,
        ];
    }

    /**
     * Generate unique code from name
     */
    protected function generateCode(string $name): string
    {
        $baseCode = Str::slug($name, '_');
        $code = $baseCode;
        $counter = 1;

        // Check if code exists, if yes, append number
        while ($this->unitTypeRepository->findByCode($code)) {
            $code = $baseCode . '_' . $counter;
            $counter++;
        }

        return $code;
    }

    /**
     * Create new unit type
     */
    public function create(array $data): array
    {
        // Generate code automatically if not provided
        if (empty($data['code'])) {
            $data['code'] = $this->generateCode($data['name']);
        } else {
            // Check if code already exists
            if ($this->unitTypeRepository->findByCode($data['code'])) {
                return [
                    'success' => false,
                    'message' => __('unit_types.code_already_exists'),
                ];
            }
        }

        try {
            $unitType = $this->unitTypeRepository->create($data);

            return [
                'success' => true,
                'data' => $unitType,
                'message' => __('unit_types.unit_type_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('unit_types.unit_type_creation_failed'),
            ];
        }
    }

    /**
     * Update unit type
     */
    public function update(int $id, array $data): array
    {
        $unitType = $this->unitTypeRepository->findById($id);

        if (!$unitType) {
            return [
                'success' => false,
                'message' => __('unit_types.unit_type_not_found'),
            ];
        }

        // Check if code already exists (excluding current unit type)
        if (isset($data['code'])) {
            $existingUnitType = $this->unitTypeRepository->findByCode($data['code']);
            if ($existingUnitType && $existingUnitType->id !== $unitType->id) {
                return [
                    'success' => false,
                    'message' => __('unit_types.code_already_exists'),
                ];
            }
        }

        try {
            $this->unitTypeRepository->update($unitType, $data);

            return [
                'success' => true,
                'data' => $unitType->fresh(),
                'message' => __('unit_types.unit_type_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('unit_types.unit_type_update_failed'),
            ];
        }
    }

    /**
     * Delete unit type
     */
    public function delete(int $id): array
    {
        $unitType = $this->unitTypeRepository->findById($id);

        if (!$unitType) {
            return [
                'success' => false,
                'message' => __('unit_types.unit_type_not_found'),
            ];
        }

        // Check if unit type has products
        if ($this->unitTypeRepository->hasProducts($unitType)) {
            return [
                'success' => false,
                'message' => __('unit_types.unit_type_has_products'),
            ];
        }

        try {
            $this->unitTypeRepository->delete($unitType);

            return [
                'success' => true,
                'message' => __('unit_types.unit_type_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('unit_types.unit_type_deletion_failed'),
            ];
        }
    }
}

