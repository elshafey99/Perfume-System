<?php

namespace App\Repositories\Api\Composition;

use App\Models\CompositionIngredient;
use App\Models\Composition;
use Illuminate\Database\Eloquent\Collection;

class CompositionIngredientRepository
{
    /**
     * Get all ingredients for a composition
     */
    public function getByCompositionId(int $compositionId): Collection
    {
        return CompositionIngredient::with(['ingredientProduct', 'composition'])
            ->where('composition_id', $compositionId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Find ingredient by ID
     */
    public function findById(int $id): ?CompositionIngredient
    {
        return CompositionIngredient::with(['ingredientProduct', 'composition'])->find($id);
    }

    /**
     * Create new ingredient
     */
    public function create(array $data): CompositionIngredient
    {
        // Set sort_order if not provided
        if (!isset($data['sort_order'])) {
            $maxOrder = CompositionIngredient::where('composition_id', $data['composition_id'])
                ->max('sort_order') ?? 0;
            $data['sort_order'] = $maxOrder + 1;
        }

        return CompositionIngredient::create($data);
    }

    /**
     * Update ingredient
     */
    public function update(CompositionIngredient $ingredient, array $data): bool
    {
        return $ingredient->update($data);
    }

    /**
     * Delete ingredient
     */
    public function delete(CompositionIngredient $ingredient): bool
    {
        return $ingredient->delete();
    }

    /**
     * Check if ingredient belongs to composition
     */
    public function belongsToComposition(int $ingredientId, int $compositionId): bool
    {
        return CompositionIngredient::where('id', $ingredientId)
            ->where('composition_id', $compositionId)
            ->exists();
    }
}

