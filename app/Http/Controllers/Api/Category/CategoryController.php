<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Http\Resources\Api\Category\CategoryResource;
use App\Services\Api\Category\CategoryService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get all categories
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $activeOnly = $request->has('active_only') ? $request->boolean('active_only') : null;

        $result = $this->categoryService->getAll($perPage, $activeOnly);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                CategoryResource::collection($data->items()),
                $data,
                __('categories.categories_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            CategoryResource::collection($data),
            __('categories.categories_retrieved_successfully')
        );
    }

    /**
     * Get parent categories only
     */
    public function parents(Request $request): JsonResponse
    {
        $activeOnly = $request->has('active_only') ? $request->boolean('active_only') : null;

        $result = $this->categoryService->getParents($activeOnly);

        return ApiResponse::success(
            CategoryResource::collection($result['data']),
            __('categories.parent_categories_retrieved_successfully')
        );
    }

    /**
     * Get categories by parent ID
     */
    public function byParent(Request $request, int $parentId): JsonResponse
    {
        $activeOnly = $request->has('active_only') ? $request->boolean('active_only') : null;

        $result = $this->categoryService->getByParentId($parentId, $activeOnly);

        return ApiResponse::success(
            CategoryResource::collection($result['data']),
            __('categories.categories_retrieved_successfully')
        );
    }

    /**
     * Get category by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->categoryService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new CategoryResource($result['data']),
            __('categories.category_retrieved_successfully')
        );
    }

    /**
     * Create new category
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $result = $this->categoryService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new CategoryResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update category
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $result = $this->categoryService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new CategoryResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete category
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->categoryService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

