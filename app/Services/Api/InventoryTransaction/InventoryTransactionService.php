<?php

namespace App\Services\Api\InventoryTransaction;

use App\Repositories\Api\InventoryTransaction\InventoryTransactionRepository;
use App\Models\InventoryTransaction;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryTransactionService
{
    protected InventoryTransactionRepository $inventoryTransactionRepository;

    public function __construct(InventoryTransactionRepository $inventoryTransactionRepository)
    {
        $this->inventoryTransactionRepository = $inventoryTransactionRepository;
    }

    /**
     * Get all inventory transactions
     */
    public function getAll(?int $perPage = null, ?int $productId = null, ?string $type = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        if ($perPage) {
            $transactions = $this->inventoryTransactionRepository->getAll($perPage, $productId, $type, $dateFrom, $dateTo);
        } else {
            $transactions = $this->inventoryTransactionRepository->getAllWithoutPagination($productId, $type);
        }

        return [
            'success' => true,
            'data' => $transactions,
        ];
    }

    /**
     * Get transactions by product ID
     */
    public function getByProductId(int $productId, ?int $perPage = null): array
    {
        $transactions = $this->inventoryTransactionRepository->getByProductId($productId, $perPage);

        return [
            'success' => true,
            'data' => $transactions,
        ];
    }

    /**
     * Get transaction by ID
     */
    public function getById(int $id): array
    {
        $transaction = $this->inventoryTransactionRepository->findById($id);

        if (!$transaction) {
            return [
                'success' => false,
                'message' => __('inventory_transactions.transaction_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $transaction,
        ];
    }

    /**
     * Create new inventory transaction
     */
    public function create(array $data): array
    {
        try {
            $transaction = $this->inventoryTransactionRepository->create($data);

            return [
                'success' => true,
                'data' => $transaction->load(['product', 'creator']),
                'message' => __('inventory_transactions.transaction_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('inventory_transactions.transaction_creation_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete transaction
     */
    public function delete(int $id): array
    {
        $transaction = $this->inventoryTransactionRepository->findById($id);

        if (!$transaction) {
            return [
                'success' => false,
                'message' => __('inventory_transactions.transaction_not_found'),
            ];
        }

        try {
            $this->inventoryTransactionRepository->delete($transaction);

            return [
                'success' => true,
                'message' => __('inventory_transactions.transaction_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('inventory_transactions.transaction_deletion_failed'),
            ];
        }
    }
}

