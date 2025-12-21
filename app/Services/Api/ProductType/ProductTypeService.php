<?php

namespace App\Services\Api\ProductType;

use App\Repositories\Api\ProductType\ProductTypeRepository;
use App\Models\ProductType;
use Illuminate\Support\Str;

class ProductTypeService
{
    protected ProductTypeRepository $productTypeRepository;

    public function __construct(ProductTypeRepository $productTypeRepository)
    {
        $this->productTypeRepository = $productTypeRepository;
    }

    /**
     * Get all product types
     */
    public function getAll(?int $perPage = null, ?bool $activeOnly = null): array
    {
        if ($perPage) {
            $productTypes = $this->productTypeRepository->getAll($perPage, $activeOnly);
        } else {
            $productTypes = $this->productTypeRepository->getAllWithoutPagination($activeOnly);
        }

        return [
            'success' => true,
            'data' => $productTypes,
        ];
    }

    /**
     * Get product type by ID
     */
    public function getById(int $id): array
    {
        $productType = $this->productTypeRepository->findById($id);

        if (!$productType) {
            return [
                'success' => false,
                'message' => __('product_types.product_type_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $productType,
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
        while ($this->productTypeRepository->findByCode($code)) {
            $code = $baseCode . '_' . $counter;
            $counter++;
        }

        return $code;
    }

    /**
     * Create new product type
     */
    public function create(array $data): array
    {
        // Always generate code automatically from name (ignore provided code)
        $data['code'] = $this->generateCode($data['name']);

        try {
            $productType = $this->productTypeRepository->create($data);

            return [
                'success' => true,
                'data' => $productType,
                'message' => __('product_types.product_type_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('product_types.product_type_creation_failed'),
            ];
        }
    }

    /**
     * Update product type
     */
    public function update(int $id, array $data): array
    {
        $productType = $this->productTypeRepository->findById($id);

        if (!$productType) {
            return [
                'success' => false,
                'message' => __('product_types.product_type_not_found'),
            ];
        }

        // Check if code already exists (excluding current product type)
        if (isset($data['code'])) {
            $existingProductType = $this->productTypeRepository->findByCode($data['code']);
            if ($existingProductType && $existingProductType->id !== $productType->id) {
                return [
                    'success' => false,
                    'message' => __('product_types.code_already_exists'),
                ];
            }
        }

        try {
            $this->productTypeRepository->update($productType, $data);

            return [
                'success' => true,
                'data' => $productType->fresh(),
                'message' => __('product_types.product_type_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('product_types.product_type_update_failed'),
            ];
        }
    }

    /**
     * Delete product type
     */
    public function delete(int $id): array
    {
        $productType = $this->productTypeRepository->findById($id);

        if (!$productType) {
            return [
                'success' => false,
                'message' => __('product_types.product_type_not_found'),
            ];
        }

        // Check if product type has products
        if ($this->productTypeRepository->hasProducts($productType)) {
            return [
                'success' => false,
                'message' => __('product_types.product_type_has_products'),
            ];
        }

        try {
            $this->productTypeRepository->delete($productType);

            return [
                'success' => true,
                'message' => __('product_types.product_type_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('product_types.product_type_deletion_failed'),
            ];
        }
    }
}
