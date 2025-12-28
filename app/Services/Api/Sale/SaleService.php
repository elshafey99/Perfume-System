<?php

namespace App\Services\Api\Sale;

use App\Repositories\Api\Sale\SaleRepository;
use App\Models\Sale;
use App\Models\SaleItem;

class SaleService
{
    protected SaleRepository $saleRepository;

    public function __construct(SaleRepository $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    /**
     * Get all sales
     */
    public function getAll(
        ?int $perPage = 15,
        ?string $status = null,
        ?string $paymentStatus = null,
        ?int $customerId = null,
        ?int $employeeId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $search = null
    ): array {
        $sales = $this->saleRepository->getAll(
            $perPage,
            $status,
            $paymentStatus,
            $customerId,
            $employeeId,
            $dateFrom,
            $dateTo,
            $search
        );

        return [
            'success' => true,
            'data' => $sales,
        ];
    }

    /**
     * Get sale by ID
     */
    public function getById(int $id): array
    {
        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $sale,
        ];
    }

    /**
     * Get sale by invoice number
     */
    public function getByInvoiceNumber(string $invoiceNumber): array
    {
        $sale = $this->saleRepository->findByInvoiceNumber($invoiceNumber);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $sale,
        ];
    }

    /**
     * Create new sale
     */
    public function create(array $data): array
    {
        try {
            // Validate stock availability for all items
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    if (!empty($item['product_id'])) {
                        if (!$this->saleRepository->hassufficientStock($item['product_id'], $item['quantity'])) {
                            return [
                                'success' => false,
                                'message' => __('sales.insufficient_stock'),
                            ];
                        }
                    }
                }
            }

            $sale = $this->saleRepository->create($data);

            return [
                'success' => true,
                'data' => $sale,
                'message' => __('sales.sale_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.sale_creation_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update sale
     */
    public function update(int $id, array $data): array
    {
        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        if ($sale->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_cancelled'),
            ];
        }

        try {
            $this->saleRepository->update($sale, $data);

            return [
                'success' => true,
                'data' => $sale->fresh(['customer', 'employee', 'items.product', 'items.composition']),
                'message' => __('sales.sale_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.sale_update_failed'),
            ];
        }
    }

    /**
     * Cancel sale
     */
    public function cancel(int $id): array
    {
        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        if ($sale->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_cancelled'),
            ];
        }

        try {
            $this->saleRepository->cancel($sale);

            return [
                'success' => true,
                'data' => $sale->fresh(),
                'message' => __('sales.sale_cancelled_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.sale_cancellation_failed'),
            ];
        }
    }

    /**
     * Get sale items
     */
    public function getItems(int $saleId): array
    {
        $sale = $this->saleRepository->findById($saleId);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        $items = $this->saleRepository->getItems($sale);

        return [
            'success' => true,
            'data' => $items,
        ];
    }

    /**
     * Add item to sale
     */
    public function addItem(int $saleId, array $itemData): array
    {
        $sale = $this->saleRepository->findById($saleId);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        if ($sale->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_cancelled'),
            ];
        }

        // Check stock availability
        if (!empty($itemData['product_id'])) {
            if (!$this->saleRepository->hassufficientStock($itemData['product_id'], $itemData['quantity'])) {
                return [
                    'success' => false,
                    'message' => __('sales.insufficient_stock'),
                ];
            }
        }

        try {
            $item = $this->saleRepository->addItemToSale($sale, $itemData);

            return [
                'success' => true,
                'data' => $item->load(['product', 'composition']),
                'message' => __('sales.item_added_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.item_addition_failed'),
            ];
        }
    }

    /**
     * Update sale item
     */
    public function updateItem(int $saleId, int $itemId, array $data): array
    {
        $sale = $this->saleRepository->findById($saleId);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        if ($sale->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_cancelled'),
            ];
        }

        $item = $this->saleRepository->findItemById($itemId);

        if (!$item || $item->sale_id !== $sale->id) {
            return [
                'success' => false,
                'message' => __('sales.item_not_found'),
            ];
        }

        // Check stock if quantity is increasing
        if (isset($data['quantity']) && $data['quantity'] > $item->quantity) {
            $additionalQuantity = $data['quantity'] - $item->quantity;
            if ($item->product_id && !$this->saleRepository->hassufficientStock($item->product_id, $additionalQuantity)) {
                return [
                    'success' => false,
                    'message' => __('sales.insufficient_stock'),
                ];
            }
        }

        try {
            $this->saleRepository->updateItem($item, $data);

            return [
                'success' => true,
                'data' => $item->fresh(['product', 'composition']),
                'message' => __('sales.item_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.item_update_failed'),
            ];
        }
    }

    /**
     * Remove item from sale
     */
    public function removeItem(int $saleId, int $itemId): array
    {
        $sale = $this->saleRepository->findById($saleId);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        if ($sale->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_cancelled'),
            ];
        }

        $item = $this->saleRepository->findItemById($itemId);

        if (!$item || $item->sale_id !== $sale->id) {
            return [
                'success' => false,
                'message' => __('sales.item_not_found'),
            ];
        }

        try {
            $this->saleRepository->removeItem($item);

            return [
                'success' => true,
                'message' => __('sales.item_removed_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.item_removal_failed'),
            ];
        }
    }

    /**
     * Record payment for sale
     */
    public function recordPayment(int $saleId, float $amount, ?string $paymentMethod = null): array
    {
        $sale = $this->saleRepository->findById($saleId);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        if ($sale->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_cancelled'),
            ];
        }

        if ($sale->payment_status === 'paid') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_paid'),
            ];
        }

        try {
            $this->saleRepository->recordPayment($sale, $amount, $paymentMethod);

            return [
                'success' => true,
                'data' => $sale->fresh(),
                'message' => __('sales.payment_recorded_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.payment_recording_failed'),
            ];
        }
    }

    /**
     * Quick sale - simplified sale with one product
     */
    public function quickSale(array $data): array
    {
        // Check stock availability
        if (!empty($data['product_id'])) {
            if (!$this->saleRepository->hassufficientStock($data['product_id'], $data['quantity'])) {
                return [
                    'success' => false,
                    'message' => __('sales.insufficient_stock'),
                ];
            }
        }

        try {
            $sale = $this->saleRepository->quickSale($data);

            return [
                'success' => true,
                'data' => $sale,
                'message' => __('sales.quick_sale_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.sale_creation_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get today's sales summary
     */
    public function getTodaySummary(): array
    {
        try {
            $summary = $this->saleRepository->getTodaySummary();

            return [
                'success' => true,
                'data' => $summary,
                'message' => __('sales.today_summary_retrieved_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.summary_retrieval_failed'),
            ];
        }
    }

    /**
     * Refund sale (full or partial)
     */
    public function refund(int $saleId, ?array $items = null, ?float $refundAmount = null): array
    {
        $sale = $this->saleRepository->findById($saleId);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        if ($sale->status === 'refunded') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_refunded'),
            ];
        }

        if ($sale->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('sales.sale_already_cancelled'),
            ];
        }

        try {
            $sale = $this->saleRepository->refund($sale, $items, $refundAmount);

            return [
                'success' => true,
                'data' => $sale,
                'message' => $items === null 
                    ? __('sales.sale_refunded_successfully') 
                    : __('sales.partial_refund_successful'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.refund_failed'),
            ];
        }
    }

    /**
     * Apply discount to sale
     */
    public function applyDiscount(int $saleId, float $discount, string $discountType = 'amount'): array
    {
        $sale = $this->saleRepository->findById($saleId);

        if (!$sale) {
            return [
                'success' => false,
                'message' => __('sales.sale_not_found'),
            ];
        }

        if ($sale->status === 'cancelled' || $sale->status === 'refunded') {
            return [
                'success' => false,
                'message' => __('sales.cannot_modify_sale'),
            ];
        }

        try {
            $sale = $this->saleRepository->applyDiscount($sale, $discount, $discountType);

            return [
                'success' => true,
                'data' => $sale,
                'message' => __('sales.discount_applied_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.discount_application_failed'),
            ];
        }
    }

    /**
     * Composition sale - sell a pre-made composition
     */
    public function compositionSale(array $data): array
    {
        try {
            $sale = $this->saleRepository->compositionSale($data);

            return [
                'success' => true,
                'data' => $sale,
                'message' => __('sales.composition_sale_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.composition_sale_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Custom blend sale - sell a custom mix of products
     */
    public function customBlend(array $data): array
    {
        // Check stock for all ingredients
        if (isset($data['ingredients'])) {
            foreach ($data['ingredients'] as $ingredient) {
                if (!$this->saleRepository->hassufficientStock($ingredient['product_id'], $ingredient['quantity'])) {
                    return [
                        'success' => false,
                        'message' => __('sales.insufficient_stock'),
                    ];
                }
            }
        }

        try {
            $sale = $this->saleRepository->customBlend($data);

            return [
                'success' => true,
                'data' => $sale,
                'message' => __('sales.custom_blend_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('sales.custom_blend_failed') . ': ' . $e->getMessage(),
            ];
        }
    }
}


