<?php

namespace App\Http\Controllers\Api\Purchase;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Purchase\StorePurchaseRequest;
use App\Http\Requests\Api\Purchase\UpdatePurchaseRequest;
use App\Http\Requests\Api\Purchase\StorePurchaseItemRequest;
use App\Http\Requests\Api\Purchase\UpdatePurchaseItemRequest;
use App\Http\Resources\Api\Purchase\PurchaseResource;
use App\Http\Resources\Api\Purchase\PurchaseItemResource;
use App\Services\Api\Purchase\PurchaseService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Get all purchases
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $supplierId = $request->input('supplier_id');
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $search = $request->input('search');

        $result = $this->purchaseService->getAll($perPage, $supplierId, $status, $dateFrom, $dateTo, $search);
        $data = $result['data'];

        return ApiResponse::paginated(
            PurchaseResource::collection($data->items()),
            $data,
            __('purchases.purchases_retrieved_successfully')
        );
    }

    /**
     * Get purchase by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->purchaseService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new PurchaseResource($result['data']),
            __('purchases.purchase_retrieved_successfully')
        );
    }

    /**
     * Create new purchase
     */
    public function store(StorePurchaseRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $result = $this->purchaseService->create($data);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new PurchaseResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update purchase
     */
    public function update(UpdatePurchaseRequest $request, int $id): JsonResponse
    {
        $result = $this->purchaseService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new PurchaseResource($result['data']),
            $result['message']
        );
    }

    /**
     * Cancel purchase
     */
    public function cancel(int $id): JsonResponse
    {
        $result = $this->purchaseService->cancel($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new PurchaseResource($result['data']),
            $result['message']
        );
    }

    /**
     * Receive purchase (add to inventory)
     */
    public function receive(int $id): JsonResponse
    {
        $result = $this->purchaseService->receive($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new PurchaseResource($result['data']),
            $result['message']
        );
    }

    /**
     * Get purchase items
     */
    public function getItems(int $id): JsonResponse
    {
        $result = $this->purchaseService->getItems($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            PurchaseItemResource::collection($result['data']),
            __('purchases.items_retrieved_successfully')
        );
    }

    /**
     * Add item to purchase
     */
    public function addItem(StorePurchaseItemRequest $request, int $id): JsonResponse
    {
        $result = $this->purchaseService->addItem($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new PurchaseItemResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update purchase item
     */
    public function updateItem(UpdatePurchaseItemRequest $request, int $id, int $itemId): JsonResponse
    {
        $result = $this->purchaseService->updateItem($id, $itemId, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new PurchaseItemResource($result['data']),
            $result['message']
        );
    }

    /**
     * Remove item from purchase
     */
    public function removeItem(int $id, int $itemId): JsonResponse
    {
        $result = $this->purchaseService->removeItem($id, $itemId);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}
