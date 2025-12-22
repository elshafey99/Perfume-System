<?php

namespace App\Http\Controllers\Api\Composition;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Composition\StoreCompositionIngredientRequest;
use App\Http\Requests\Api\Composition\UpdateCompositionIngredientRequest;
use App\Http\Resources\Api\Composition\CompositionIngredientResource;
use App\Services\Api\Composition\CompositionIngredientService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class CompositionIngredientController extends Controller
{
    protected CompositionIngredientService $ingredientService;

    public function __construct(CompositionIngredientService $ingredientService)
    {
        $this->ingredientService = $ingredientService;
    }

    /**
     * Create new ingredient
     */
    public function store(StoreCompositionIngredientRequest $request, int $id): JsonResponse
    {
        $result = $this->ingredientService->create($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new CompositionIngredientResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update ingredient
     */
    public function update(UpdateCompositionIngredientRequest $request, int $id, int $ingredientId): JsonResponse
    {
        $result = $this->ingredientService->update($id, $ingredientId, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new CompositionIngredientResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete ingredient
     */
    public function destroy(int $id, int $ingredientId): JsonResponse
    {
        $result = $this->ingredientService->delete($id, $ingredientId);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}

