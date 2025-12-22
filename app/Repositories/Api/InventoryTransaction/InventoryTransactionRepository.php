<?php

namespace App\Repositories\Api\InventoryTransaction;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class InventoryTransactionRepository
{
    /**
     * Get all inventory transactions with pagination
     */
    public function getAll(int $perPage = 15, ?int $productId = null, ?string $type = null, ?string $dateFrom = null, ?string $dateTo = null): LengthAwarePaginator
    {
        $query = InventoryTransaction::with(['product', 'creator'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($productId) {
            $query->where('product_id', $productId);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($dateFrom) {
            $query->whereDate('transaction_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('transaction_date', '<=', $dateTo);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all inventory transactions without pagination
     */
    public function getAllWithoutPagination(?int $productId = null, ?string $type = null): Collection
    {
        $query = InventoryTransaction::with(['product', 'creator'])
            ->orderBy('transaction_date', 'desc');

        if ($productId) {
            $query->where('product_id', $productId);
        }

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    /**
     * Get transactions by product ID
     */
    public function getByProductId(int $productId, ?int $perPage = null): Collection|LengthAwarePaginator
    {
        $query = InventoryTransaction::with(['product', 'creator'])
            ->where('product_id', $productId)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Find transaction by ID
     */
    public function findById(int $id): ?InventoryTransaction
    {
        return InventoryTransaction::with(['product', 'creator', 'reference'])->find($id);
    }

    /**
     * Create new inventory transaction
     */
    public function create(array $data): InventoryTransaction
    {
        // Get product to calculate stock_after
        $product = Product::findOrFail($data['product_id']);
        
        // Calculate stock after transaction
        $quantity = (float) $data['quantity'];
        $stockAfter = $product->current_stock;
        
        // Adjust stock based on transaction type
        switch ($data['type']) {
            case 'sale':
            case 'composition':
            case 'waste':
                $stockAfter -= $quantity;
                break;
            case 'purchase':
            case 'return':
                $stockAfter += $quantity;
                break;
            case 'adjustment':
                // For adjustment, quantity can be positive or negative
                $stockAfter += $quantity;
                break;
        }
        
        // Ensure stock doesn't go negative
        $stockAfter = max(0, $stockAfter);
        
        $data['stock_after'] = $stockAfter;
        
        // Set transaction_date if not provided
        if (!isset($data['transaction_date'])) {
            $data['transaction_date'] = now();
        }
        
        $transaction = InventoryTransaction::create($data);
        
        // Update product stock
        $product->current_stock = $stockAfter;
        $product->save();
        
        return $transaction;
    }

    /**
     * Delete transaction (and reverse stock change)
     */
    public function delete(InventoryTransaction $transaction): bool
    {
        // Reverse stock change
        $product = $transaction->product;
        $quantity = (float) $transaction->quantity;
        
        switch ($transaction->type) {
            case 'sale':
            case 'composition':
            case 'waste':
                // Add back to stock
                $product->current_stock += $quantity;
                break;
            case 'purchase':
            case 'return':
                // Subtract from stock
                $product->current_stock = max(0, $product->current_stock - $quantity);
                break;
            case 'adjustment':
                // Reverse adjustment
                $product->current_stock -= $quantity;
                break;
        }
        
        $product->save();
        
        return $transaction->delete();
    }
}

