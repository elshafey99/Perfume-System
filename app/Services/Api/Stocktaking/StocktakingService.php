<?php

namespace App\Services\Api\Stocktaking;

use App\Repositories\Api\Stocktaking\StocktakingRepository;
use App\Models\Stocktaking;
use App\Models\StocktakingItem;
use Illuminate\Pagination\LengthAwarePaginator;

class StocktakingService
{
    protected StocktakingRepository $stocktakingRepository;

    public function __construct(StocktakingRepository $stocktakingRepository)
    {
        $this->stocktakingRepository = $stocktakingRepository;
    }

    /**
     * Get all stocktakings
     */
    public function getAll(?int $perPage = null, ?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        if ($perPage) {
            $stocktakings = $this->stocktakingRepository->getAll($perPage, $status, $dateFrom, $dateTo);
        } else {
            $stocktakings = $this->stocktakingRepository->getAllWithoutPagination($status);
        }

        return [
            'success' => true,
            'data' => $stocktakings,
        ];
    }

    /**
     * Get stocktaking by ID
     */
    public function getById(int $id): array
    {
        $stocktaking = $this->stocktakingRepository->findById($id);

        if (!$stocktaking) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $stocktaking,
        ];
    }

    /**
     * Get stocktaking items
     */
    public function getItems(int $stocktakingId): array
    {
        $stocktaking = $this->stocktakingRepository->findById($stocktakingId);

        if (!$stocktaking) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_not_found'),
            ];
        }

        $items = $this->stocktakingRepository->getItems($stocktakingId);

        return [
            'success' => true,
            'data' => $items,
        ];
    }

    /**
     * Create new stocktaking
     */
    public function create(array $data): array
    {
        try {
            $stocktaking = $this->stocktakingRepository->create($data);

            return [
                'success' => true,
                'data' => $stocktaking->load(['creator', 'items']),
                'message' => __('stocktakings.stocktaking_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_creation_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update stocktaking
     */
    public function update(int $id, array $data): array
    {
        $stocktaking = $this->stocktakingRepository->findById($id);

        if (!$stocktaking) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_not_found'),
            ];
        }

        // Prevent updating completed stocktakings
        if ($stocktaking->status === 'completed') {
            return [
                'success' => false,
                'message' => __('stocktakings.cannot_update_completed'),
            ];
        }

        try {
            $this->stocktakingRepository->update($stocktaking, $data);

            return [
                'success' => true,
                'data' => $stocktaking->fresh()->load(['creator', 'completer', 'items.product']),
                'message' => __('stocktakings.stocktaking_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_update_failed'),
            ];
        }
    }

    /**
     * Complete stocktaking
     */
    public function complete(int $id, int $completedBy): array
    {
        $stocktaking = $this->stocktakingRepository->findById($id);

        if (!$stocktaking) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_not_found'),
            ];
        }

        if ($stocktaking->status === 'completed') {
            return [
                'success' => false,
                'message' => __('stocktakings.already_completed'),
            ];
        }

        if ($stocktaking->status === 'cancelled') {
            return [
                'success' => false,
                'message' => __('stocktakings.cannot_complete_cancelled'),
            ];
        }

        try {
            $result = $this->stocktakingRepository->complete($stocktaking, $completedBy);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => __('stocktakings.cannot_complete_no_items'),
                ];
            }

            return [
                'success' => true,
                'data' => $stocktaking->fresh()->load(['creator', 'completer', 'items.product']),
                'message' => __('stocktakings.stocktaking_completed_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_completion_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete stocktaking
     */
    public function delete(int $id): array
    {
        $stocktaking = $this->stocktakingRepository->findById($id);

        if (!$stocktaking) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_not_found'),
            ];
        }

        try {
            $result = $this->stocktakingRepository->delete($stocktaking);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => __('stocktakings.cannot_delete_completed'),
                ];
            }

            return [
                'success' => true,
                'message' => __('stocktakings.stocktaking_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('stocktakings.stocktaking_deletion_failed'),
            ];
        }
    }
}

