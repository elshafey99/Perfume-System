<?php

namespace App\Repositories\Api\Purchase;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PurchaseRepository
{
    /**
     * Get all purchases with pagination
     */
    public function getAll(
        int $perPage = 15,
        ?int $supplierId = null,
        ?string $status = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $search = null
    ): LengthAwarePaginator {
        $query = Purchase::with(['supplier', 'creator'])
            ->orderBy('purchase_date', 'desc');

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('purchase_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('purchase_date', '<=', $dateTo);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Find purchase by ID
     */
    public function findById(int $id): ?Purchase
    {
        return Purchase::with(['supplier', 'creator', 'items.product'])->find($id);
    }

    /**
     * Find purchase by invoice number
     */
    public function findByInvoiceNumber(string $invoiceNumber): ?Purchase
    {
        return Purchase::with(['supplier', 'creator', 'items.product'])
            ->where('invoice_number', $invoiceNumber)
            ->first();
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = 'PO-';
        $date = now()->format('Ymd');
        $lastPurchase = Purchase::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastPurchase ? (intval(substr($lastPurchase->invoice_number, -4)) + 1) : 1;

        return $prefix . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create new purchase
     */
    public function create(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            $data['invoice_number'] = $this->generateInvoiceNumber();

            if (empty($data['purchase_date'])) {
                $data['purchase_date'] = now();
            }

            $items = $data['items'] ?? [];
            unset($data['items']);

            $purchase = Purchase::create($data);

            foreach ($items as $itemData) {
                $this->addItemToPurchase($purchase, $itemData);
            }

            $this->recalculateTotals($purchase);

            return $purchase->fresh(['supplier', 'creator', 'items.product']);
        });
    }

    /**
     * Update purchase
     */
    public function update(Purchase $purchase, array $data): bool
    {
        return $purchase->update($data);
    }

    /**
     * Cancel purchase
     */
    public function cancel(Purchase $purchase): bool
    {
        return $purchase->update(['status' => 'cancelled']);
    }

    /**
     * Receive purchase (add to inventory)
     */
    public function receive(Purchase $purchase): Purchase
    {
        return DB::transaction(function () use ($purchase) {
            // Add items to inventory
            foreach ($purchase->items as $item) {
                $this->addToStock($item->product_id, (float) $item->quantity);
            }

            $purchase->status = 'received';
            $purchase->received_date = now();
            $purchase->save();

            return $purchase->fresh(['supplier', 'creator', 'items.product']);
        });
    }

    /**
     * Add item to purchase
     */
    public function addItemToPurchase(Purchase $purchase, array $itemData): PurchaseItem
    {
        $product = Product::find($itemData['product_id']);
        $costPrice = $itemData['cost_price'] ?? $product->cost_price ?? 0;
        $quantity = $itemData['quantity'];
        $total = $quantity * $costPrice;

        $item = PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $itemData['product_id'],
            'quantity' => $quantity,
            'unit' => $itemData['unit'] ?? 'piece',
            'cost_price' => $costPrice,
            'total' => $total,
        ]);

        $this->recalculateTotals($purchase);

        return $item;
    }

    /**
     * Update purchase item
     */
    public function updateItem(PurchaseItem $item, array $data): bool
    {
        if (isset($data['quantity']) || isset($data['cost_price'])) {
            $quantity = $data['quantity'] ?? $item->quantity;
            $costPrice = $data['cost_price'] ?? $item->cost_price;
            $data['total'] = $quantity * $costPrice;
        }

        $result = $item->update($data);
        $this->recalculateTotals($item->purchase);

        return $result;
    }

    /**
     * Remove item from purchase
     */
    public function removeItem(PurchaseItem $item): bool
    {
        $purchase = $item->purchase;
        $result = $item->delete();
        $this->recalculateTotals($purchase);

        return $result;
    }

    /**
     * Recalculate purchase totals
     */
    private function recalculateTotals(Purchase $purchase): void
    {
        $subtotal = $purchase->items()->sum('total');
        $taxRate = 0.15; // 15% VAT
        $taxAmount = $subtotal * $taxRate;
        $total = $subtotal + $taxAmount;

        $purchase->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
        ]);
    }

    /**
     * Add to stock
     */
    private function addToStock(int $productId, float $quantity): void
    {
        $product = Product::find($productId);
        if ($product) {
            $product->current_stock += $quantity;
            $product->save();
        }
    }

    /**
     * Get purchase items
     */
    public function getItems(Purchase $purchase)
    {
        return $purchase->items()->with('product')->get();
    }

    /**
     * Find item by ID
     */
    public function findItemById(int $itemId): ?PurchaseItem
    {
        return PurchaseItem::with('product')->find($itemId);
    }
}
