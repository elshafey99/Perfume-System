<?php

namespace App\Services\Api\Return;

use App\Repositories\Api\Return\ReturnRepository;
use App\Models\ProductReturn;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnService
{
    protected ReturnRepository $returnRepository;

    public function __construct(ReturnRepository $returnRepository)
    {
        $this->returnRepository = $returnRepository;
    }

    /**
     * Get all returns
     */
    public function getAll(?int $perPage = null, ?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        if ($perPage) {
            $returns = $this->returnRepository->getAll($perPage, $status, $dateFrom, $dateTo);
        } else {
            $returns = $this->returnRepository->getAllWithoutPagination($status);
        }

        return [
            'success' => true,
            'data' => $returns,
        ];
    }

    /**
     * Get return by ID
     */
    public function getById(int $id): array
    {
        $return = $this->returnRepository->findById($id);

        if (!$return) {
            return [
                'success' => false,
                'message' => __('returns.return_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $return,
        ];
    }

    /**
     * Get returns by sale ID
     */
    public function getBySaleId(int $saleId): array
    {
        $returns = $this->returnRepository->getBySaleId($saleId);

        return [
            'success' => true,
            'data' => $returns,
        ];
    }

    /**
     * Create new return
     */
    public function create(array $data): array
    {
        try {
            // Validate sale exists
            $sale = Sale::find($data['sale_id']);
            if (!$sale) {
                return [
                    'success' => false,
                    'message' => __('returns.sale_not_found'),
                ];
            }

            // Check if sale is completed
            if ($sale->status !== 'completed') {
                return [
                    'success' => false,
                    'message' => __('returns.sale_not_completed'),
                ];
            }

            // Check for existing pending/approved returns for same sale/item
            $existingReturnQuery = ProductReturn::where('sale_id', $data['sale_id'])
                ->whereIn('status', ['pending', 'approved']);
            
            if (isset($data['sale_item_id']) && $data['sale_item_id']) {
                $existingReturnQuery->where('sale_item_id', $data['sale_item_id']);
            } else {
                $existingReturnQuery->whereNull('sale_item_id');
            }

            if ($existingReturnQuery->exists()) {
                return [
                    'success' => false,
                    'message' => __('returns.duplicate_return_exists'),
                ];
            }

            // Calculate total existing approved/completed return amounts for this sale
            $existingReturnAmount = ProductReturn::where('sale_id', $data['sale_id'])
                ->whereIn('status', ['approved', 'completed'])
                ->sum('return_amount');

            // Validate sale item if provided
            if (isset($data['sale_item_id']) && $data['sale_item_id']) {
                $saleItem = $sale->items()->find($data['sale_item_id']);
                if (!$saleItem) {
                    return [
                        'success' => false,
                        'message' => __('returns.sale_item_not_found'),
                    ];
                }

                // Get existing returns for this specific item
                $existingItemReturnAmount = ProductReturn::where('sale_id', $data['sale_id'])
                    ->where('sale_item_id', $data['sale_item_id'])
                    ->whereIn('status', ['approved', 'completed'])
                    ->sum('return_amount');

                // Calculate remaining item amount
                $remainingItemAmount = $saleItem->total - $existingItemReturnAmount;

                // Auto-set return amount to remaining item total if not provided
                if (!isset($data['return_amount']) || $data['return_amount'] === null) {
                    $data['return_amount'] = $remainingItemAmount;
                }

                // Check return amount doesn't exceed remaining item total
                if ($data['return_amount'] > $remainingItemAmount) {
                    return [
                        'success' => false,
                        'message' => __('returns.return_amount_exceeds_remaining_item_total'),
                    ];
                }
            } else {
                // Calculate remaining sale amount
                $remainingAmount = $sale->total - $existingReturnAmount;

                // Auto-set return amount to remaining sale total if not provided
                if (!isset($data['return_amount']) || $data['return_amount'] === null) {
                    $data['return_amount'] = $remainingAmount;
                }

                // Check return amount doesn't exceed remaining sale total
                if ($data['return_amount'] > $remainingAmount) {
                    return [
                        'success' => false,
                        'message' => __('returns.return_amount_exceeds_remaining_total'),
                    ];
                }
            }

            // Generate return number
            $data['return_number'] = $this->returnRepository->generateReturnNumber();
            $data['status'] = 'pending';

            $return = $this->returnRepository->create($data);

            return [
                'success' => true,
                'data' => $return->load(['sale', 'saleItem', 'processor']),
                'message' => __('returns.return_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('returns.return_creation_failed'),
            ];
        }
    }

    /**
     * Approve return
     */
    public function approve(int $id): array
    {
        $return = $this->returnRepository->findById($id);

        if (!$return) {
            return [
                'success' => false,
                'message' => __('returns.return_not_found'),
            ];
        }

        if ($return->status !== 'pending') {
            return [
                'success' => false,
                'message' => __('returns.return_not_pending'),
            ];
        }

        try {
            $this->returnRepository->update($return, [
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            return [
                'success' => true,
                'data' => $return->fresh()->load(['sale', 'saleItem', 'processor']),
                'message' => __('returns.return_approved_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('returns.return_approval_failed'),
            ];
        }
    }

    /**
     * Reject return
     */
    public function reject(int $id, ?string $notes = null): array
    {
        $return = $this->returnRepository->findById($id);

        if (!$return) {
            return [
                'success' => false,
                'message' => __('returns.return_not_found'),
            ];
        }

        if ($return->status !== 'pending') {
            return [
                'success' => false,
                'message' => __('returns.return_not_pending'),
            ];
        }

        try {
            $updateData = [
                'status' => 'rejected',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ];

            if ($notes) {
                $updateData['notes'] = $return->notes 
                    ? $return->notes . "\n---\nRejection reason: " . $notes 
                    : "Rejection reason: " . $notes;
            }

            $this->returnRepository->update($return, $updateData);

            return [
                'success' => true,
                'data' => $return->fresh()->load(['sale', 'saleItem', 'processor']),
                'message' => __('returns.return_rejected_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('returns.return_rejection_failed'),
            ];
        }
    }

    /**
     * Process return (complete the refund/exchange/store credit)
     */
    public function process(int $id): array
    {
        $return = $this->returnRepository->findById($id);

        if (!$return) {
            return [
                'success' => false,
                'message' => __('returns.return_not_found'),
            ];
        }

        if ($return->status !== 'approved') {
            return [
                'success' => false,
                'message' => __('returns.return_not_approved'),
            ];
        }

        try {
            DB::beginTransaction();

            // Handle based on return type
            switch ($return->return_type) {
                case 'refund':
                    // Mark as completed - actual refund handling would be done externally
                    break;

                case 'exchange':
                    // Mark as completed - exchange handling would be done externally
                    break;

                case 'store_credit':
                    // Add store credit to customer if applicable
                    $sale = $return->sale;
                    if ($sale && $sale->customer_id) {
                        // Could add loyalty points or store credit here
                    }
                    break;
            }

            // Update return status
            $this->returnRepository->update($return, [
                'status' => 'completed',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'data' => $return->fresh()->load(['sale', 'saleItem', 'processor']),
                'message' => __('returns.return_processed_successfully'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('returns.return_processing_failed'),
            ];
        }
    }

    /**
     * Delete return
     */
    public function delete(int $id): array
    {
        $return = $this->returnRepository->findById($id);

        if (!$return) {
            return [
                'success' => false,
                'message' => __('returns.return_not_found'),
            ];
        }

        // Only pending returns can be deleted
        if ($return->status !== 'pending') {
            return [
                'success' => false,
                'message' => __('returns.only_pending_can_be_deleted'),
            ];
        }

        try {
            $this->returnRepository->delete($return);

            return [
                'success' => true,
                'message' => __('returns.return_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('returns.return_deletion_failed'),
            ];
        }
    }

    /**
     * Get return statistics
     */
    public function getStatistics(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $statistics = $this->returnRepository->getStatistics($dateFrom, $dateTo);

        return [
            'success' => true,
            'data' => $statistics,
        ];
    }
}
