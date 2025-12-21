<?php

namespace App\Http\Controllers\Api\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Supplier\StoreSupplierRequest;
use App\Http\Requests\Api\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Api\Supplier\SupplierResource;
use App\Services\Api\Supplier\SupplierService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * Get all suppliers
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $activeOnly = $request->has('active_only') ? $request->boolean('active_only') : null;

        $result = $this->supplierService->getAll($perPage, $activeOnly);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                SupplierResource::collection($data->items()),
                $data,
                __('suppliers.suppliers_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            SupplierResource::collection($data),
            __('suppliers.suppliers_retrieved_successfully')
        );
    }

    /**
     * Get supplier by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->supplierService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new SupplierResource($result['data']),
            __('suppliers.supplier_retrieved_successfully')
        );
    }

    /**
     * Create new supplier
     */
    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $result = $this->supplierService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SupplierResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update supplier
     */
    public function update(UpdateSupplierRequest $request, int $id): JsonResponse
    {
        $result = $this->supplierService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new SupplierResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete supplier
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->supplierService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

