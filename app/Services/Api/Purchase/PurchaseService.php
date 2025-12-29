<?php

namespace App\Services\Api\Purchase;

use App\Repositories\Api\Purchase\PurchaseRepository;

class PurchaseService
{
    protected PurchaseRepository $purchaseRepository;

    public function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * Get all purchases
     */
    public function getAll(
        int $perPage = 15,
        ?int $supplierId = null,
        ?string $status = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $search = null
    ): array {
        $purchases = $this->purchaseRepository->getAll($perPage, $supplierId, $status, $dateFrom, $dateTo, $search);

        return [
            'success' => true,
            'data' => $purchases,
        ];
    }

    /**
     * Get purchase by ID
     */
    public function getById(int $id): array
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $purchase,
        ];
    }

    /**
     * Create new purchase
     */
    public function create(array $data): array
    {
        try {
            $purchase = $this->purchaseRepository->create($data);

            return [
                'success' => true,
                'data' => $purchase,
                'message' => __('purchases.purchase_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_creation_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update purchase
     */
    public function update(int $id, array $data): array
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_not_found'),
            ];
        }

        if ($purchase->status === 'received') {
            return [
                'success' => false,
                'message' => __('purchases.cannot_modify_received'),
            ];
        }

        if ($purchase->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('purchases.purchase_already_cancelled'),
            ];
        }

        try {
            $this->purchaseRepository->update($purchase, $data);

            return [
                'success' => true,
                'data' => $purchase->fresh(['supplier', 'creator', 'items.product']),
                'message' => __('purchases.purchase_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_update_failed'),
            ];
        }
    }

    /**
     * Cancel purchase
     */
    public function cancel(int $id): array
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_not_found'),
            ];
        }

        if ($purchase->status === 'received') {
            return [
                'success' => false,
                'message' => __('purchases.cannot_cancel_received'),
            ];
        }

        if ($purchase->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('purchases.purchase_already_cancelled'),
            ];
        }

        try {
            $this->purchaseRepository->cancel($purchase);

            return [
                'success' => true,
                'data' => $purchase->fresh(),
                'message' => __('purchases.purchase_cancelled_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_cancellation_failed'),
            ];
        }
    }

    /**
     * Receive purchase
     */
    public function receive(int $id): array
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_not_found'),
            ];
        }

        if ($purchase->status === 'received') {
            return [
                'success' => false,
                'message' => __('purchases.purchase_already_received'),
            ];
        }

        if ($purchase->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('purchases.purchase_already_cancelled'),
            ];
        }

        try {
            $purchase = $this->purchaseRepository->receive($purchase);

            return [
                'success' => true,
                'data' => $purchase,
                'message' => __('purchases.purchase_received_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_receive_failed'),
            ];
        }
    }

    /**
     * Get purchase items
     */
    public function getItems(int $purchaseId): array
    {
        $purchase = $this->purchaseRepository->findById($purchaseId);

        if (!$purchase) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_not_found'),
            ];
        }

        $items = $this->purchaseRepository->getItems($purchase);

        return [
            'success' => true,
            'data' => $items,
        ];
    }

    /**
     * Add item to purchase
     */
    public function addItem(int $purchaseId, array $itemData): array
    {
        $purchase = $this->purchaseRepository->findById($purchaseId);

        if (!$purchase) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_not_found'),
            ];
        }

        if ($purchase->status !== 'pending') {
            return [
                'success' => false,
                'message' => __('purchases.cannot_modify_purchase'),
            ];
        }

        try {
            $item = $this->purchaseRepository->addItemToPurchase($purchase, $itemData);

            return [
                'success' => true,
                'data' => $item->load('product'),
                'message' => __('purchases.item_added_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('purchases.item_addition_failed'),
            ];
        }
    }

    /**
     * Update purchase item
     */
    public function updateItem(int $purchaseId, int $itemId, array $data): array
    {
        $purchase = $this->purchaseRepository->findById($purchaseId);

        if (!$purchase) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_not_found'),
            ];
        }

        if ($purchase->status !== 'pending') {
            return [
                'success' => false,
                'message' => __('purchases.cannot_modify_purchase'),
            ];
        }

        $item = $this->purchaseRepository->findItemById($itemId);

        if (!$item || $item->purchase_id !== $purchase->id) {
            return [
                'success' => false,
                'message' => __('purchases.item_not_found'),
            ];
        }

        try {
            $this->purchaseRepository->updateItem($item, $data);

            return [
                'success' => true,
                'data' => $item->fresh('product'),
                'message' => __('purchases.item_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('purchases.item_update_failed'),
            ];
        }
    }

    /**
     * Remove item from purchase
     */
    public function removeItem(int $purchaseId, int $itemId): array
    {
        $purchase = $this->purchaseRepository->findById($purchaseId);

        if (!$purchase) {
            return [
                'success' => false,
                'message' => __('purchases.purchase_not_found'),
            ];
        }

        if ($purchase->status !== 'pending') {
            return [
                'success' => false,
                'message' => __('purchases.cannot_modify_purchase'),
            ];
        }

        $item = $this->purchaseRepository->findItemById($itemId);

        if (!$item || $item->purchase_id !== $purchase->id) {
            return [
                'success' => false,
                'message' => __('purchases.item_not_found'),
            ];
        }

        try {
            $this->purchaseRepository->removeItem($item);

            return [
                'success' => true,
                'message' => __('purchases.item_removed_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('purchases.item_removal_failed'),
            ];
        }
    }
}
