<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreProductRequest;
use App\Http\Requests\Api\Product\UpdateProductRequest;
use App\Http\Requests\Api\Product\UpdateStockRequest;
use App\Http\Resources\Api\Product\ProductResource;
use App\Services\Api\Product\ProductService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get all products
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $activeOnly = $request->has('active_only') ? $request->boolean('active_only') : null;
        $categoryId = $request->input('category_id');
        $search = $request->input('search');

        $result = $this->productService->getAll($perPage, $activeOnly, $categoryId, $search);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                ProductResource::collection($data->items()),
                $data,
                __('products.products_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            ProductResource::collection($data),
            __('products.products_retrieved_successfully')
        );
    }

    /**
     * Get product by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->productService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ProductResource($result['data']),
            __('products.product_retrieved_successfully')
        );
    }

    /**
     * Get product by barcode
     */
    public function getByBarcode(string $barcode): JsonResponse
    {
        $result = $this->productService->getByBarcode($barcode);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ProductResource($result['data']),
            __('products.product_retrieved_successfully')
        );
    }

    /**
     * Get low stock products
     */
    public function getLowStock(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);

        $result = $this->productService->getLowStock($perPage);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                ProductResource::collection($data->items()),
                $data,
                __('products.low_stock_products_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            ProductResource::collection($data),
            __('products.low_stock_products_retrieved_successfully')
        );
    }

    /**
     * Create new product
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $result = $this->productService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new ProductResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update product
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $result = $this->productService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ProductResource($result['data']),
            $result['message']
        );
    }

    /**
     * Update product stock
     */
    public function updateStock(UpdateStockRequest $request, int $id): JsonResponse
    {
        $quantity = $request->input('quantity');
        $type = $request->input('type', 'set');

        $result = $this->productService->updateStock($id, $quantity, $type);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ProductResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete product
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->productService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

