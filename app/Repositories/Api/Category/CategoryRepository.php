<?php

namespace App\Repositories\Api\Category;

use App\Models\Category;
use App\Helpers\FileHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    /**
     * Get all categories with pagination
     */
    public function getAll(int $perPage = 15, ?bool $activeOnly = null): LengthAwarePaginator
    {
        $query = Category::with(['parent', 'children'])->orderBy('sort_order')->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all categories without pagination
     */
    public function getAllWithoutPagination(?bool $activeOnly = null): Collection
    {
        $query = Category::with(['parent', 'children'])->orderBy('sort_order')->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->get();
    }

    /**
     * Get only parent categories (no children)
     */
    public function getParents(?bool $activeOnly = null): Collection
    {
        $query = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->get();
    }

    /**
     * Get categories by parent ID
     */
    public function getByParentId(int $parentId, ?bool $activeOnly = null): Collection
    {
        $query = Category::where('parent_id', $parentId)
            ->with(['parent', 'children'])
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        return $query->get();
    }

    /**
     * Find category by ID
     */
    public function findById(int $id): ?Category
    {
        return Category::with(['parent', 'children', 'products'])->find($id);
    }

    /**
     * Create new category
     */
    public function create(array $data): Category
    {
        // Handle icon image upload
        if (isset($data['icon']) && $data['icon']) {
            $iconPath = FileHelper::uploadImage($data['icon'], 'uploads/categories/icons');
            $data['icon'] = $iconPath;
        }

        return Category::create($data);
    }

    /**
     * Update category
     */
    public function update(Category $category, array $data): bool
    {
        // Handle icon image upload
        if (isset($data['icon']) && $data['icon']) {
            $oldIcon = $category->icon;
            $iconPath = FileHelper::uploadImage($data['icon'], 'uploads/categories/icons', $oldIcon);
            if ($iconPath) {
                $data['icon'] = $iconPath;
            } else {
                unset($data['icon']); // Keep old icon if upload failed
            }
        }

        return $category->update($data);
    }

    /**
     * Delete category
     */
    public function delete(Category $category): bool
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return false;
        }

        // Check if category has children
        if ($category->children()->count() > 0) {
            return false;
        }

        return $category->delete();
    }

    /**
     * Check if category has products
     */
    public function hasProducts(Category $category): bool
    {
        return $category->products()->count() > 0;
    }

    /**
     * Check if category has children
     */
    public function hasChildren(Category $category): bool
    {
        return $category->children()->count() > 0;
    }
}
