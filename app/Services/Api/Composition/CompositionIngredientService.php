<?php

namespace App\Services\Api\Composition;

use App\Repositories\Api\Composition\CompositionIngredientRepository;
use App\Repositories\Api\Composition\CompositionRepository;
use App\Models\CompositionIngredient;

class CompositionIngredientService
{
    protected CompositionIngredientRepository $ingredientRepository;
    protected CompositionRepository $compositionRepository;

    public function __construct(
        CompositionIngredientRepository $ingredientRepository,
        CompositionRepository $compositionRepository
    ) {
        $this->ingredientRepository = $ingredientRepository;
        $this->compositionRepository = $compositionRepository;
    }

    /**
     * Get ingredients by composition ID
     */
    public function getByCompositionId(int $compositionId): array
    {
        // Check if composition exists
        $composition = $this->compositionRepository->findById($compositionId);
        if (!$composition) {
            return [
                'success' => false,
                'message' => __('compositions.composition_not_found'),
            ];
        }

        $ingredients = $this->ingredientRepository->getByCompositionId($compositionId);

        return [
            'success' => true,
            'data' => $ingredients,
        ];
    }

    /**
     * Get ingredient by ID
     */
    public function getById(int $id): array
    {
        $ingredient = $this->ingredientRepository->findById($id);

        if (!$ingredient) {
            return [
                'success' => false,
                'message' => __('compositions.ingredient_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $ingredient,
        ];
    }

    /**
     * Create new ingredient
     */
    public function create(int $compositionId, array $data): array
    {
        // Check if composition exists
        $composition = $this->compositionRepository->findById($compositionId);
        if (!$composition) {
            return [
                'success' => false,
                'message' => __('compositions.composition_not_found'),
            ];
        }

        $data['composition_id'] = $compositionId;

        try {
            $ingredient = $this->ingredientRepository->create($data);

            return [
                'success' => true,
                'data' => $ingredient->load(['ingredientProduct', 'composition']),
                'message' => __('compositions.ingredient_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('compositions.ingredient_creation_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update ingredient
     */
    public function update(int $compositionId, int $ingredientId, array $data): array
    {
        // Check if ingredient belongs to composition
        if (!$this->ingredientRepository->belongsToComposition($ingredientId, $compositionId)) {
            return [
                'success' => false,
                'message' => __('compositions.ingredient_not_found'),
            ];
        }

        $ingredient = $this->ingredientRepository->findById($ingredientId);

        if (!$ingredient) {
            return [
                'success' => false,
                'message' => __('compositions.ingredient_not_found'),
            ];
        }

        try {
            $this->ingredientRepository->update($ingredient, $data);

            return [
                'success' => true,
                'data' => $ingredient->fresh()->load(['ingredientProduct', 'composition']),
                'message' => __('compositions.ingredient_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('compositions.ingredient_update_failed'),
            ];
        }
    }

    /**
     * Delete ingredient
     */
    public function delete(int $compositionId, int $ingredientId): array
    {
        // Check if ingredient belongs to composition
        if (!$this->ingredientRepository->belongsToComposition($ingredientId, $compositionId)) {
            return [
                'success' => false,
                'message' => __('compositions.ingredient_not_found'),
            ];
        }

        $ingredient = $this->ingredientRepository->findById($ingredientId);

        if (!$ingredient) {
            return [
                'success' => false,
                'message' => __('compositions.ingredient_not_found'),
            ];
        }

        try {
            $this->ingredientRepository->delete($ingredient);

            return [
                'success' => true,
                'message' => __('compositions.ingredient_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('compositions.ingredient_deletion_failed'),
            ];
        }
    }
}

