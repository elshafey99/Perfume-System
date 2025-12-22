<?php

namespace App\Http\Controllers\Api\InventoryTransaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InventoryTransaction\StoreInventoryTransactionRequest;
use App\Http\Resources\Api\InventoryTransaction\InventoryTransactionResource;
use App\Services\Api\InventoryTransaction\InventoryTransactionService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    protected InventoryTransactionService $inventoryTransactionService;

    public function __construct(InventoryTransactionService $inventoryTransactionService)
    {
        $this->inventoryTransactionService = $inventoryTransactionService;
    }

    /**
     * Get all inventory transactions
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $productId = $request->input('product_id');
        $type = $request->input('type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $result = $this->inventoryTransactionService->getAll($perPage, $productId, $type, $dateFrom, $dateTo);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                InventoryTransactionResource::collection($data->items()),
                $data,
                __('inventory_transactions.transactions_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            InventoryTransactionResource::collection($data),
            __('inventory_transactions.transactions_retrieved_successfully')
        );
    }

    /**
     * Get transactions by product ID
     */
    public function getByProductId(Request $request, int $productId): JsonResponse
    {
        $perPage = $request->input('per_page', 15);

        $result = $this->inventoryTransactionService->getByProductId($productId, $perPage);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                InventoryTransactionResource::collection($data->items()),
                $data,
                __('inventory_transactions.transactions_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            InventoryTransactionResource::collection($data),
            __('inventory_transactions.transactions_retrieved_successfully')
        );
    }

    /**
     * Get transaction by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->inventoryTransactionService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new InventoryTransactionResource($result['data']),
            __('inventory_transactions.transaction_retrieved_successfully')
        );
    }

    /**
     * Create new inventory transaction
     */
    public function store(StoreInventoryTransactionRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Set created_by to current user
        $data['created_by'] = $request->user()->id;

        $result = $this->inventoryTransactionService->create($data);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new InventoryTransactionResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Delete transaction
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->inventoryTransactionService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

