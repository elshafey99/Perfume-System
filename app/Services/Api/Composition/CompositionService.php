<?php

namespace App\Services\Api\Composition;

use App\Repositories\Api\Composition\CompositionRepository;
use App\Models\Composition;
use App\Models\CompositionIngredient;
use Illuminate\Pagination\LengthAwarePaginator;

class CompositionService
{
    protected CompositionRepository $compositionRepository;

    public function __construct(CompositionRepository $compositionRepository)
    {
        $this->compositionRepository = $compositionRepository;
    }

    /**
     * Get all compositions
     */
    public function getAll(?int $perPage = null, ?bool $activeOnly = null, ?bool $magicRecipesOnly = null): array
    {
        if ($perPage) {
            $compositions = $this->compositionRepository->getAll($perPage, $activeOnly, $magicRecipesOnly);
        } else {
            $compositions = $this->compositionRepository->getAllWithoutPagination($activeOnly, $magicRecipesOnly);
        }

        return [
            'success' => true,
            'data' => $compositions,
        ];
    }

    /**
     * Get magic recipes
     */
    public function getMagicRecipes(?int $perPage = null): array
    {
        $compositions = $this->compositionRepository->getMagicRecipes($perPage);

        return [
            'success' => true,
            'data' => $compositions,
        ];
    }

    /**
     * Get composition by ID
     */
    public function getById(int $id): array
    {
        $composition = $this->compositionRepository->findById($id);

        if (!$composition) {
            return [
                'success' => false,
                'message' => __('compositions.composition_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $composition,
        ];
    }

    /**
     * Create new composition
     */
    public function create(array $data): array
    {
        try {
            // Extract ingredients from data
            $ingredients = $data['ingredients'] ?? [];
            unset($data['ingredients']);

            // Create composition with ingredients
            $composition = $this->compositionRepository->createWithIngredients($data, $ingredients);

            return [
                'success' => true,
                'data' => $composition,
                'message' => __('compositions.composition_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('compositions.composition_creation_failed') . ': ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update composition
     */
    public function update(int $id, array $data): array
    {
        $composition = $this->compositionRepository->findById($id);

        if (!$composition) {
            return [
                'success' => false,
                'message' => __('compositions.composition_not_found'),
            ];
        }

        try {
            $this->compositionRepository->update($composition, $data);

            return [
                'success' => true,
                'data' => $composition->fresh()->load(['product', 'ingredients.ingredientProduct']),
                'message' => __('compositions.composition_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('compositions.composition_update_failed'),
            ];
        }
    }

    /**
     * Delete composition
     */
    public function delete(int $id): array
    {
        $composition = $this->compositionRepository->findById($id);

        if (!$composition) {
            return [
                'success' => false,
                'message' => __('compositions.composition_not_found'),
            ];
        }

        // Check if composition has sales
        if ($this->compositionRepository->hasSales($composition)) {
            return [
                'success' => false,
                'message' => __('compositions.composition_has_sales'),
            ];
        }

        try {
            $this->compositionRepository->delete($composition);

            return [
                'success' => true,
                'message' => __('compositions.composition_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('compositions.composition_deletion_failed'),
            ];
        }
    }

    /**
     * Calculate composition cost
     */
    public function calculateCost(int $id): array
    {
        $composition = $this->compositionRepository->findById($id);

        if (!$composition) {
            return [
                'success' => false,
                'message' => __('compositions.composition_not_found'),
            ];
        }

        $totalCost = 0;
        $ingredientsCost = [];

        foreach ($composition->ingredients as $ingredient) {
            $product = $ingredient->ingredientProduct;
            $quantity = (float) $ingredient->quantity;

            // Calculate cost based on unit type
            $unitCost = 0;
            switch ($ingredient->unit) {
                case 'gram':
                    $unitCost = $product->price_per_gram ?? ($product->cost_price / 1000); // Assuming cost_price per 1000g
                    break;
                case 'ml':
                    $unitCost = $product->price_per_ml ?? ($product->cost_price / 1000); // Assuming cost_price per 1000ml
                    break;
                case 'piece':
                    $unitCost = $product->cost_price;
                    break;
                default:
                    $unitCost = $product->cost_price;
            }

            $ingredientCost = $quantity * $unitCost;
            $totalCost += $ingredientCost;

            $ingredientsCost[] = [
                'ingredient_id' => $ingredient->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit' => $ingredient->unit,
                'unit_cost' => $unitCost,
                'total_cost' => $ingredientCost,
            ];
        }

        // Add service fee
        $serviceFee = (float) $composition->service_fee;
        $finalCost = $totalCost + $serviceFee;

        return [
            'success' => true,
            'data' => [
                'composition_id' => $composition->id,
                'composition_name' => $composition->name,
                'ingredients_cost' => $ingredientsCost,
                'total_ingredients_cost' => $totalCost,
                'service_fee' => $serviceFee,
                'base_cost' => $finalCost,
                'current_base_cost' => (float) $composition->base_cost,
                'selling_price' => (float) $composition->selling_price,
                'profit' => (float) $composition->selling_price - $finalCost,
                'profit_margin' => $composition->selling_price > 0
                    ? (($composition->selling_price - $finalCost) / $composition->selling_price) * 100
                    : 0,
            ],
            'message' => __('compositions.cost_calculated_successfully'),
        ];
    }
}
