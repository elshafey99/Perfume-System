<?php

namespace App\Services\Api\Supplier;

use App\Repositories\Api\Supplier\SupplierRepository;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierService
{
    protected SupplierRepository $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Get all suppliers
     */
    public function getAll(?int $perPage = null, ?bool $activeOnly = null): array
    {
        if ($perPage) {
            $suppliers = $this->supplierRepository->getAll($perPage, $activeOnly);
        } else {
            $suppliers = $this->supplierRepository->getAllWithoutPagination($activeOnly);
        }

        return [
            'success' => true,
            'data' => $suppliers,
        ];
    }

    /**
     * Get supplier by ID
     */
    public function getById(int $id): array
    {
        $supplier = $this->supplierRepository->findById($id);

        if (!$supplier) {
            return [
                'success' => false,
                'message' => __('suppliers.supplier_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $supplier,
        ];
    }

    /**
     * Create new supplier
     */
    public function create(array $data): array
    {
        try {
            $supplier = $this->supplierRepository->create($data);

            return [
                'success' => true,
                'data' => $supplier->load(['purchases', 'products']),
                'message' => __('suppliers.supplier_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('suppliers.supplier_creation_failed'),
            ];
        }
    }

    /**
     * Update supplier
     */
    public function update(int $id, array $data): array
    {
        $supplier = $this->supplierRepository->findById($id);

        if (!$supplier) {
            return [
                'success' => false,
                'message' => __('suppliers.supplier_not_found'),
            ];
        }

        try {
            $this->supplierRepository->update($supplier, $data);

            return [
                'success' => true,
                'data' => $supplier->fresh()->load(['purchases', 'products']),
                'message' => __('suppliers.supplier_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('suppliers.supplier_update_failed'),
            ];
        }
    }

    /**
     * Delete supplier
     */
    public function delete(int $id): array
    {
        $supplier = $this->supplierRepository->findById($id);

        if (!$supplier) {
            return [
                'success' => false,
                'message' => __('suppliers.supplier_not_found'),
            ];
        }

        // Check if supplier has purchases
        if ($this->supplierRepository->hasPurchases($supplier)) {
            return [
                'success' => false,
                'message' => __('suppliers.supplier_has_purchases'),
            ];
        }

        // Check if supplier has products
        if ($this->supplierRepository->hasProducts($supplier)) {
            return [
                'success' => false,
                'message' => __('suppliers.supplier_has_products'),
            ];
        }

        try {
            $this->supplierRepository->delete($supplier);

            return [
                'success' => true,
                'message' => __('suppliers.supplier_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('suppliers.supplier_deletion_failed'),
            ];
        }
    }
}

