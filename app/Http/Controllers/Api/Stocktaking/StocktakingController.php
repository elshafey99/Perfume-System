<?php

namespace App\Http\Controllers\Api\Stocktaking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Stocktaking\StoreStocktakingRequest;
use App\Http\Requests\Api\Stocktaking\StoreStocktakingItemRequest;
use App\Http\Resources\Api\Stocktaking\StocktakingResource;
use App\Http\Resources\Api\Stocktaking\StocktakingItemResource;
use App\Services\Api\Stocktaking\StocktakingService;
use App\Repositories\Api\Stocktaking\StocktakingRepository;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StocktakingController extends Controller
{
    protected StocktakingService $stocktakingService;
    protected StocktakingRepository $stocktakingRepository;

    public function __construct(StocktakingService $stocktakingService, StocktakingRepository $stocktakingRepository)
    {
        $this->stocktakingService = $stocktakingService;
        $this->stocktakingRepository = $stocktakingRepository;
    }

    /**
     * Get all stocktakings
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $result = $this->stocktakingService->getAll($perPage, $status, $dateFrom, $dateTo);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                StocktakingResource::collection($data->items()),
                $data,
                __('stocktakings.stocktakings_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            StocktakingResource::collection($data),
            __('stocktakings.stocktakings_retrieved_successfully')
        );
    }

    /**
     * Get stocktaking by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->stocktakingService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new StocktakingResource($result['data']),
            __('stocktakings.stocktaking_retrieved_successfully')
        );
    }

    /**
     * Get stocktaking items
     */
    public function getItems(int $id): JsonResponse
    {
        $result = $this->stocktakingService->getItems($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            StocktakingItemResource::collection($result['data']),
            __('stocktakings.items_retrieved_successfully')
        );
    }

    /**
     * Create new stocktaking
     */
    public function store(StoreStocktakingRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Set created_by to current user
        $data['created_by'] = $request->user()->id;

        $result = $this->stocktakingService->create($data);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new StocktakingResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Add item to stocktaking
     */
    public function addItem(StoreStocktakingItemRequest $request, int $id): JsonResponse
    {
        try {
            $item = $this->stocktakingRepository->addItem($id, $request->validated());

            return ApiResponse::success(
                new StocktakingItemResource($item->load('product')),
                __('stocktakings.item_added_successfully'),
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    /**
     * Complete stocktaking
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $completedBy = $request->user()->id;

        $result = $this->stocktakingService->complete($id, $completedBy);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new StocktakingResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete stocktaking
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->stocktakingService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

