<?php

namespace App\Repositories\Api\Product;

use App\Models\Product;
use App\Helpers\FileHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    /**
     * Get all products with pagination
     */
    public function getAll(int $perPage = 15, ?bool $activeOnly = null, ?int $categoryId = null, ?string $search = null): LengthAwarePaginator
    {
        $query = Product::with(['category', 'supplier'])->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all products without pagination
     */
    public function getAllWithoutPagination(?bool $activeOnly = null, ?int $categoryId = null): Collection
    {
        $query = Product::with(['category', 'supplier'])->orderBy('name');

        if ($activeOnly !== null) {
            $query->where('is_active', $activeOnly);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->get();
    }

    /**
     * Find product by ID
     */
    public function findById(int $id): ?Product
    {
        return Product::with(['category', 'supplier', 'inventoryTransactions', 'saleItems'])->find($id);
    }

    /**
     * Find product by barcode
     */
    public function findByBarcode(string $barcode): ?Product
    {
        return Product::with(['category', 'supplier'])
            ->where('barcode', $barcode)
            ->first();
    }

    /**
     * Get low stock products
     */
    public function getLowStock(?int $perPage = null): Collection|LengthAwarePaginator
    {
        $query = Product::with(['category', 'supplier'])
            ->whereColumn('current_stock', '<=', 'min_stock_level')
            ->where('is_active', true)
            ->orderBy('current_stock', 'asc');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Create new product
     */
    public function create(array $data): Product
    {
        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $imagePath = FileHelper::uploadImage($data['image'], 'uploads/products');
            $data['image'] = $imagePath;
        }

        return Product::create($data);
    }

    /**
     * Update product
     */
    public function update(Product $product, array $data): bool
    {
        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            $oldImage = $product->image;
            $imagePath = FileHelper::uploadImage($data['image'], 'uploads/products', $oldImage);
            if ($imagePath) {
                $data['image'] = $imagePath;
            } else {
                unset($data['image']); // Keep old image if upload failed
            }
        }

        return $product->update($data);
    }

    /**
     * Update product stock
     */
    public function updateStock(Product $product, float $quantity, string $type = 'add'): bool
    {
        if ($type === 'add') {
            $product->current_stock += $quantity;
        } elseif ($type === 'subtract') {
            $product->current_stock = max(0, $product->current_stock - $quantity);
        } else {
            $product->current_stock = $quantity;
        }

        return $product->save();
    }

    /**
     * Delete product
     */
    public function delete(Product $product): bool
    {
        // Check if product has sales
        if ($product->saleItems()->count() > 0) {
            return false;
        }

        // Check if product has purchases
        if ($product->purchaseItems()->count() > 0) {
            return false;
        }

        // Check if product is used as ingredient
        if ($product->compositionIngredients()->count() > 0) {
            return false;
        }

        return $product->delete();
    }

    /**
     * Check if product has sales
     */
    public function hasSales(Product $product): bool
    {
        return $product->saleItems()->count() > 0;
    }

    /**
     * Check if product has purchases
     */
    public function hasPurchases(Product $product): bool
    {
        return $product->purchaseItems()->count() > 0;
    }

    /**
     * Check if product is used as ingredient
     */
    public function isUsedAsIngredient(Product $product): bool
    {
        return $product->compositionIngredients()->count() > 0;
    }
}

