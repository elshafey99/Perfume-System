<?php

namespace App\Services\Api\Category;

use App\Repositories\Api\Category\CategoryRepository;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories
     */
    public function getAll(?int $perPage = null, ?bool $activeOnly = null): array
    {
        if ($perPage) {
            $categories = $this->categoryRepository->getAll($perPage, $activeOnly);
        } else {
            $categories = $this->categoryRepository->getAllWithoutPagination($activeOnly);
        }

        return [
            'success' => true,
            'data' => $categories,
        ];
    }

    /**
     * Get parent categories only
     */
    public function getParents(?bool $activeOnly = null): array
    {
        $categories = $this->categoryRepository->getParents($activeOnly);

        return [
            'success' => true,
            'data' => $categories,
        ];
    }

    /**
     * Get categories by parent ID
     */
    public function getByParentId(int $parentId, ?bool $activeOnly = null): array
    {
        $categories = $this->categoryRepository->getByParentId($parentId, $activeOnly);

        return [
            'success' => true,
            'data' => $categories,
        ];
    }

    /**
     * Get category by ID
     */
    public function getById(int $id): array
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            return [
                'success' => false,
                'message' => __('categories.category_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $category,
        ];
    }

    /**
     * Create new category
     */
    public function create(array $data): array
    {
        try {
            $category = $this->categoryRepository->create($data);

            return [
                'success' => true,
                'data' => $category->load(['parent', 'children']),
                'message' => __('categories.category_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('categories.category_creation_failed'),
            ];
        }
    }

    /**
     * Update category
     */
    public function update(int $id, array $data): array
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            return [
                'success' => false,
                'message' => __('categories.category_not_found'),
            ];
        }

        // Prevent setting parent_id to itself
        if (isset($data['parent_id']) && $data['parent_id'] == $category->id) {
            return [
                'success' => false,
                'message' => __('categories.cannot_set_self_as_parent'),
            ];
        }

        try {
            $this->categoryRepository->update($category, $data);

            return [
                'success' => true,
                'data' => $category->fresh()->load(['parent', 'children']),
                'message' => __('categories.category_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('categories.category_update_failed'),
            ];
        }
    }

    /**
     * Delete category
     */
    public function delete(int $id): array
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            return [
                'success' => false,
                'message' => __('categories.category_not_found'),
            ];
        }

        // Check if category has products
        if ($this->categoryRepository->hasProducts($category)) {
            return [
                'success' => false,
                'message' => __('categories.category_has_products'),
            ];
        }

        // Check if category has children
        if ($this->categoryRepository->hasChildren($category)) {
            return [
                'success' => false,
                'message' => __('categories.category_has_children'),
            ];
        }

        try {
            $this->categoryRepository->delete($category);

            return [
                'success' => true,
                'message' => __('categories.category_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('categories.category_deletion_failed'),
            ];
        }
    }
}
