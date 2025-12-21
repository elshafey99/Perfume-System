<?php

namespace App\Services\Api\Product;

use App\Repositories\Api\Product\ProductRepository;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products
     */
    public function getAll(?int $perPage = null, ?bool $activeOnly = null, ?int $categoryId = null, ?string $search = null): array
    {
        if ($perPage) {
            $products = $this->productRepository->getAll($perPage, $activeOnly, $categoryId, $search);
        } else {
            $products = $this->productRepository->getAllWithoutPagination($activeOnly, $categoryId);
        }

        return [
            'success' => true,
            'data' => $products,
        ];
    }

    /**
     * Get product by ID
     */
    public function getById(int $id): array
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return [
                'success' => false,
                'message' => __('products.product_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $product,
        ];
    }

    /**
     * Get product by barcode
     */
    public function getByBarcode(string $barcode): array
    {
        $product = $this->productRepository->findByBarcode($barcode);

        if (!$product) {
            return [
                'success' => false,
                'message' => __('products.product_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $product,
        ];
    }

    /**
     * Get low stock products
     */
    public function getLowStock(?int $perPage = null): array
    {
        $products = $this->productRepository->getLowStock($perPage);

        return [
            'success' => true,
            'data' => $products,
        ];
    }

    /**
     * Create new product
     */
    public function create(array $data): array
    {
        try {
            $product = $this->productRepository->create($data);

            return [
                'success' => true,
                'data' => $product->load(['category', 'supplier']),
                'message' => __('products.product_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('products.product_creation_failed'),
            ];
        }
    }

    /**
     * Update product
     */
    public function update(int $id, array $data): array
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return [
                'success' => false,
                'message' => __('products.product_not_found'),
            ];
        }

        try {
            $this->productRepository->update($product, $data);

            return [
                'success' => true,
                'data' => $product->fresh()->load(['category', 'supplier']),
                'message' => __('products.product_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('products.product_update_failed'),
            ];
        }
    }

    /**
     * Update product stock
     */
    public function updateStock(int $id, float $quantity, string $type = 'set'): array
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return [
                'success' => false,
                'message' => __('products.product_not_found'),
            ];
        }

        try {
            $this->productRepository->updateStock($product, $quantity, $type);

            return [
                'success' => true,
                'data' => $product->fresh(),
                'message' => __('products.stock_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('products.stock_update_failed'),
            ];
        }
    }

    /**
     * Delete product
     */
    public function delete(int $id): array
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return [
                'success' => false,
                'message' => __('products.product_not_found'),
            ];
        }

        // Check if product has sales
        if ($this->productRepository->hasSales($product)) {
            return [
                'success' => false,
                'message' => __('products.product_has_sales'),
            ];
        }

        // Check if product has purchases
        if ($this->productRepository->hasPurchases($product)) {
            return [
                'success' => false,
                'message' => __('products.product_has_purchases'),
            ];
        }

        // Check if product is used as ingredient
        if ($this->productRepository->isUsedAsIngredient($product)) {
            return [
                'success' => false,
                'message' => __('products.product_used_as_ingredient'),
            ];
        }

        try {
            $this->productRepository->delete($product);

            return [
                'success' => true,
                'message' => __('products.product_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('products.product_deletion_failed'),
            ];
        }
    }
}

