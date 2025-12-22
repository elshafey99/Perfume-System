<?php

namespace App\Repositories\Api\Stocktaking;

use App\Models\Stocktaking;
use App\Models\StocktakingItem;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class StocktakingRepository
{
    /**
     * Get all stocktakings with pagination
     */
    public function getAll(int $perPage = 15, ?string $status = null, ?string $dateFrom = null, ?string $dateTo = null): LengthAwarePaginator
    {
        $query = Stocktaking::with(['creator', 'completer', 'items.product'])
            ->orderBy('stocktaking_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('stocktaking_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('stocktaking_date', '<=', $dateTo);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all stocktakings without pagination
     */
    public function getAllWithoutPagination(?string $status = null): Collection
    {
        $query = Stocktaking::with(['creator', 'completer', 'items.product'])
            ->orderBy('stocktaking_date', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Find stocktaking by ID
     */
    public function findById(int $id): ?Stocktaking
    {
        return Stocktaking::with(['creator', 'completer', 'items.product'])->find($id);
    }

    /**
     * Create new stocktaking
     */
    public function create(array $data): Stocktaking
    {
        // Generate stocktaking number if not provided
        if (empty($data['stocktaking_number'])) {
            $data['stocktaking_number'] = $this->generateStocktakingNumber();
        }

        // Set stocktaking_date if not provided
        if (!isset($data['stocktaking_date'])) {
            $data['stocktaking_date'] = now()->toDateString();
        }

        // Set status to draft if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        return Stocktaking::create($data);
    }

    /**
     * Update stocktaking
     */
    public function update(Stocktaking $stocktaking, array $data): bool
    {
        return $stocktaking->update($data);
    }

    /**
     * Complete stocktaking
     */
    public function complete(Stocktaking $stocktaking, int $completedBy): bool
    {
        // Check if stocktaking has items
        if ($stocktaking->items()->count() === 0) {
            return false;
        }

        // Calculate totals
        $totalItems = $stocktaking->items()->count();
        $totalDifferences = $stocktaking->items()->sum('difference');

        // Update stocktaking
        $stocktaking->status = 'completed';
        $stocktaking->completed_by = $completedBy;
        $stocktaking->completed_at = now();
        $stocktaking->total_items = $totalItems;
        $stocktaking->total_differences = abs($totalDifferences);
        $stocktaking->save();

        // Update product stocks and create inventory transactions
        foreach ($stocktaking->items as $item) {
            $product = $item->product;
            $difference = (float) $item->difference;

            if ($difference != 0) {
                // Update product stock
                $product->current_stock = (float) $item->actual_stock;
                $product->save();

                // Create inventory transaction
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity' => abs($difference),
                    'unit' => $item->unit,
                    'reference_type' => Stocktaking::class,
                    'reference_id' => $stocktaking->id,
                    'notes' => 'تسوية من الجرد - ' . ($item->reason ?? ''),
                    'created_by' => $completedBy,
                    'transaction_date' => now(),
                    'stock_after' => (float) $item->actual_stock,
                ]);
            }
        }

        return true;
    }

    /**
     * Get stocktaking items
     */
    public function getItems(int $stocktakingId): Collection
    {
        return StocktakingItem::with(['product', 'stocktaking'])
            ->where('stocktaking_id', $stocktakingId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Add item to stocktaking
     */
    public function addItem(int $stocktakingId, array $itemData): StocktakingItem
    {
        $stocktaking = $this->findById($stocktakingId);
        
        if (!$stocktaking || $stocktaking->status !== 'draft' && $stocktaking->status !== 'in_progress') {
            throw new \Exception('Cannot add items to completed or cancelled stocktaking');
        }

        $product = Product::findOrFail($itemData['product_id']);
        
        // Set recorded_stock from product current_stock
        $recordedStock = (float) $product->current_stock;
        $actualStock = (float) $itemData['actual_stock'];
        $difference = $actualStock - $recordedStock;

        $itemData['stocktaking_id'] = $stocktakingId;
        $itemData['recorded_stock'] = $recordedStock;
        $itemData['difference'] = $difference;

        // Update stocktaking status to in_progress if it's draft
        if ($stocktaking->status === 'draft') {
            $stocktaking->status = 'in_progress';
            $stocktaking->save();
        }

        return StocktakingItem::create($itemData);
    }

    /**
     * Update stocktaking item
     */
    public function updateItem(StocktakingItem $item, array $data): bool
    {
        $stocktaking = $item->stocktaking;
        
        if ($stocktaking->status === 'completed' || $stocktaking->status === 'cancelled') {
            return false;
        }

        // Recalculate difference if actual_stock changed
        if (isset($data['actual_stock'])) {
            $data['difference'] = (float) $data['actual_stock'] - (float) $item->recorded_stock;
        }

        return $item->update($data);
    }

    /**
     * Delete stocktaking item
     */
    public function deleteItem(StocktakingItem $item): bool
    {
        $stocktaking = $item->stocktaking;
        
        if ($stocktaking->status === 'completed' || $stocktaking->status === 'cancelled') {
            return false;
        }

        return $item->delete();
    }

    /**
     * Delete stocktaking
     */
    public function delete(Stocktaking $stocktaking): bool
    {
        // Only allow deletion if not completed
        if ($stocktaking->status === 'completed') {
            return false;
        }

        return $stocktaking->delete();
    }

    /**
     * Generate unique stocktaking number
     */
    private function generateStocktakingNumber(): string
    {
        $prefix = 'STK';
        $date = now()->format('Ymd');
        $maxAttempts = 100;
        $attempt = 0;

        do {
            $number = $prefix . '-' . $date . '-' . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $exists = Stocktaking::where('stocktaking_number', $number)->exists();
            $attempt++;
        } while ($exists && $attempt < $maxAttempts);

        // Fallback if all attempts failed
        if ($exists) {
            $number = $prefix . '-' . $date . '-' . time();
        }

        return $number;
    }
}

