<?php

namespace App\Repositories\Api\Composition;

use App\Models\Composition;
use App\Helpers\FileHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CompositionRepository
{
    /**
     * Get all compositions with pagination
     */
    public function getAll(int $perPage = 15, ?bool $activeOnly = null, ?bool $magicRecipesOnly = null): LengthAwarePaginator
    {
        $query = Composition::with(['product', 'ingredients.ingredientProduct'])
            ->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        if ($magicRecipesOnly !== null && $magicRecipesOnly) {
            $query->where('is_magic_recipe', true);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all compositions without pagination
     */
    public function getAllWithoutPagination(?bool $activeOnly = null, ?bool $magicRecipesOnly = null): Collection
    {
        $query = Composition::with(['product', 'ingredients.ingredientProduct'])
            ->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        if ($magicRecipesOnly !== null && $magicRecipesOnly) {
            $query->where('is_magic_recipe', true);
        }

        return $query->get();
    }

    /**
     * Get magic recipes only
     */
    public function getMagicRecipes(?int $perPage = null): Collection|LengthAwarePaginator
    {
        $query = Composition::with(['product', 'ingredients.ingredientProduct'])
            ->where('is_magic_recipe', true)
            ->where('is_active', true)
            ->orderBy('name');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Find composition by ID
     */
    public function findById(int $id): ?Composition
    {
        return Composition::with(['product', 'ingredients.ingredientProduct'])->find($id);
    }

    /**
     * Create new composition
     */
    public function create(array $data): Composition
    {
        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = $this->generateCode();
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $imagePath = FileHelper::uploadImage($data['image'], 'uploads/compositions');
            $data['image'] = $imagePath;
        }

        return Composition::create($data);
    }

    /**
     * Update composition
     */
    public function update(Composition $composition, array $data): bool
    {
        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $oldImage = $composition->image;
            $imagePath = FileHelper::uploadImage($data['image'], 'uploads/compositions', $oldImage);
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                unset($data['image']); // Keep old image if upload failed
            }
        }

        return $composition->update($data);
    }

    /**
     * Delete composition
     */
    public function delete(Composition $composition): bool
    {
        // Check if composition has sales
        if ($composition->saleItems()->count() > 0) {
            return false;
        }

        return $composition->delete();
    }

    /**
     * Check if composition has sales
     */
    public function hasSales(Composition $composition): bool
    {
        return $composition->saleItems()->count() > 0;
    }

    /**
     * Generate unique code
     */
    private function generateCode(): string
    {
        $prefix = 'COMP';
        $maxAttempts = 100;
        $attempt = 0;

        do {
            $code = $prefix . '-' . strtoupper(Str::random(6));
            $exists = Composition::where('code', $code)->exists();
            $attempt++;
        } while ($exists && $attempt < $maxAttempts);

        // Fallback if all attempts failed
        if ($exists) {
            $code = $prefix . '-' . time();
        }

        return $code;
    }
}

