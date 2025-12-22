<?php

namespace App\Http\Controllers\Api\Composition;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Composition\StoreCompositionRequest;
use App\Http\Requests\Api\Composition\UpdateCompositionRequest;
use App\Http\Resources\Api\Composition\CompositionResource;
use App\Http\Resources\Api\Composition\CompositionIngredientResource;
use App\Services\Api\Composition\CompositionService;
use App\Services\Api\Composition\CompositionIngredientService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompositionController extends Controller
{
    protected CompositionService $compositionService;
    protected CompositionIngredientService $ingredientService;

    public function __construct(
        CompositionService $compositionService,
        CompositionIngredientService $ingredientService
    ) {
        $this->compositionService = $compositionService;
        $this->ingredientService = $ingredientService;
    }

    /**
     * Get all compositions
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $activeOnly = $request->has('active_only') ? $request->boolean('active_only') : null;
        $magicRecipesOnly = $request->has('magic_recipes_only') ? $request->boolean('magic_recipes_only') : null;

        $result = $this->compositionService->getAll($perPage, $activeOnly, $magicRecipesOnly);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                CompositionResource::collection($data->items()),
                $data,
                __('compositions.compositions_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            CompositionResource::collection($data),
            __('compositions.compositions_retrieved_successfully')
        );
    }

    /**
     * Get magic recipes
     */
    public function getMagicRecipes(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);

        $result = $this->compositionService->getMagicRecipes($perPage);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                CompositionResource::collection($data->items()),
                $data,
                __('compositions.magic_recipes_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            CompositionResource::collection($data),
            __('compositions.magic_recipes_retrieved_successfully')
        );
    }

    /**
     * Get composition by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->compositionService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new CompositionResource($result['data']),
            __('compositions.composition_retrieved_successfully')
        );
    }

    /**
     * Get composition ingredients
     */
    public function getIngredients(int $id): JsonResponse
    {
        $result = $this->ingredientService->getByCompositionId($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            CompositionIngredientResource::collection($result['data']),
            __('compositions.ingredients_retrieved_successfully')
        );
    }

    /**
     * Create new composition
     */
    public function store(StoreCompositionRequest $request): JsonResponse
    {
        $result = $this->compositionService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new CompositionResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update composition
     */
    public function update(UpdateCompositionRequest $request, int $id): JsonResponse
    {
        $result = $this->compositionService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new CompositionResource($result['data']),
            $result['message']
        );
    }

    /**
     * Calculate composition cost
     */
    public function calculateCost(int $id): JsonResponse
    {
        $result = $this->compositionService->calculateCost($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            $result['data'],
            $result['message']
        );
    }

    /**
     * Delete composition
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->compositionService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

