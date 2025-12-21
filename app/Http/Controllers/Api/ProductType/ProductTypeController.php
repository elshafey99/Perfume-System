<?php

namespace App\Http\Controllers\Api\ProductType;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductType\StoreProductTypeRequest;
use App\Http\Requests\Api\ProductType\UpdateProductTypeRequest;
use App\Http\Resources\Api\ProductType\ProductTypeResource;
use App\Services\Api\ProductType\ProductTypeService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    protected ProductTypeService $productTypeService;

    public function __construct(ProductTypeService $productTypeService)
    {
        $this->productTypeService = $productTypeService;
    }

    /**
     * Get all product types
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $activeOnly = $request->has('active_only') ? $request->boolean('active_only') : null;

        $result = $this->productTypeService->getAll($perPage, $activeOnly);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                ProductTypeResource::collection($data->items()),
                $data,
                __('product_types.product_types_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            ProductTypeResource::collection($data),
            __('product_types.product_types_retrieved_successfully')
        );
    }

    /**
     * Get product type by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->productTypeService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ProductTypeResource($result['data']),
            __('product_types.product_type_retrieved_successfully')
        );
    }

    /**
     * Create new product type
     */
    public function store(StoreProductTypeRequest $request): JsonResponse
    {
        $result = $this->productTypeService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new ProductTypeResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update product type
     */
    public function update(UpdateProductTypeRequest $request, int $id): JsonResponse
    {
        $result = $this->productTypeService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ProductTypeResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete product type
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->productTypeService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

