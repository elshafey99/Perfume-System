<?php

namespace App\Repositories\Api\Sale;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Composition;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SaleRepository
{
    /**
     * Get all sales with pagination
     */
    public function getAll(
        int $perPage = 15,
        ?string $status = null,
        ?string $paymentStatus = null,
        ?int $customerId = null,
        ?int $employeeId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $search = null
    ): LengthAwarePaginator {
        $query = Sale::with(['customer', 'employee', 'items.product', 'items.composition'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        if ($dateFrom) {
            $query->whereDate('sale_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('sale_date', '<=', $dateTo);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Find sale by ID
     */
    public function findById(int $id): ?Sale
    {
        return Sale::with(['customer', 'employee', 'items.product', 'items.composition'])->find($id);
    }

    /**
     * Find sale by invoice number
     */
    public function findByInvoiceNumber(string $invoiceNumber): ?Sale
    {
        return Sale::with(['customer', 'employee', 'items.product', 'items.composition'])
            ->where('invoice_number', $invoiceNumber)
            ->first();
    }

    /**
     * Create new sale
     */
    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            // Generate invoice number
            $data['invoice_number'] = $this->generateInvoiceNumber();
            
            // Set sale date if not provided
            if (empty($data['sale_date'])) {
                $data['sale_date'] = now();
            }

            // Set employee_id from authenticated user if not provided
            if (empty($data['employee_id'])) {
                $data['employee_id'] = auth()->id();
            }

            // Extract items before creating sale
            $items = $data['items'] ?? [];
            unset($data['items']);

            // Create sale
            $sale = Sale::create($data);

            // Add items
            foreach ($items as $itemData) {
                $this->addItemToSale($sale, $itemData);
            }

            // Recalculate totals
            $this->recalculateTotals($sale);

            return $sale->fresh(['customer', 'employee', 'items.product', 'items.composition']);
        });
    }

    /**
     * Update sale
     */
    public function update(Sale $sale, array $data): bool
    {
        return DB::transaction(function () use ($sale, $data) {
            // Remove items from data if present (handle separately)
            unset($data['items']);

            $sale->update($data);
            $this->recalculateTotals($sale);

            return true;
        });
    }

    /**
     * Cancel sale
     */
    public function cancel(Sale $sale): bool
    {
        return DB::transaction(function () use ($sale) {
            // Restore stock for all items
            foreach ($sale->items as $item) {
                if ($item->product_id) {
                    $this->restoreStock($item->product_id, $item->quantity);
                } elseif ($item->composition_id) {
                    $this->restoreCompositionStock($item->composition_id, $item->quantity);
                }
            }

            $sale->status = 'cancelled';
            $sale->payment_status = 'refunded';
            return $sale->save();
        });
    }

    /**
     * Add item to sale
     */
    public function addItemToSale(Sale $sale, array $itemData): SaleItem
    {
        // Get product/composition name and price if not provided
        if (!empty($itemData['product_id'])) {
            $product = Product::find($itemData['product_id']);
            if ($product) {
                $itemData['product_name'] = $itemData['product_name'] ?? $product->name;
                $itemData['unit_price'] = $itemData['unit_price'] ?? $product->selling_price;
            }
        } elseif (!empty($itemData['composition_id'])) {
            $composition = Composition::find($itemData['composition_id']);
            if ($composition) {
                $itemData['product_name'] = $itemData['product_name'] ?? $composition->name;
                $itemData['unit_price'] = $itemData['unit_price'] ?? $composition->selling_price;
                $itemData['is_composition'] = true;
            }
        }

        // Calculate total
        $itemData['total'] = $itemData['quantity'] * $itemData['unit_price'];
        $itemData['sale_id'] = $sale->id;

        // Create item
        $item = SaleItem::create($itemData);

        // Deduct stock
        if (!empty($itemData['product_id'])) {
            $this->deductStock($itemData['product_id'], $itemData['quantity']);
        } elseif (!empty($itemData['composition_id'])) {
            $this->deductCompositionStock($itemData['composition_id'], $itemData['quantity']);
        }

        return $item;
    }

    /**
     * Update sale item
     */
    public function updateItem(SaleItem $item, array $data): bool
    {
        return DB::transaction(function () use ($item, $data) {
            $oldQuantity = $item->quantity;
            $newQuantity = $data['quantity'] ?? $oldQuantity;
            $quantityDiff = $newQuantity - $oldQuantity;

            // Update stock if quantity changed
            if ($quantityDiff != 0) {
                if ($item->product_id) {
                    if ($quantityDiff > 0) {
                        $this->deductStock($item->product_id, $quantityDiff);
                    } else {
                        $this->restoreStock($item->product_id, abs($quantityDiff));
                    }
                } elseif ($item->composition_id) {
                    if ($quantityDiff > 0) {
                        $this->deductCompositionStock($item->composition_id, $quantityDiff);
                    } else {
                        $this->restoreCompositionStock($item->composition_id, abs($quantityDiff));
                    }
                }
            }

            // Update item
            if (isset($data['unit_price']) || isset($data['quantity'])) {
                $data['total'] = ($data['quantity'] ?? $item->quantity) * ($data['unit_price'] ?? $item->unit_price);
            }

            $item->update($data);

            // Recalculate sale totals
            $this->recalculateTotals($item->sale);

            return true;
        });
    }

    /**
     * Remove item from sale
     */
    public function removeItem(SaleItem $item): bool
    {
        return DB::transaction(function () use ($item) {
            $sale = $item->sale;

            // Restore stock
            if ($item->product_id) {
                $this->restoreStock($item->product_id, $item->quantity);
            } elseif ($item->composition_id) {
                $this->restoreCompositionStock($item->composition_id, $item->quantity);
            }

            $item->delete();

            // Recalculate sale totals
            $this->recalculateTotals($sale);

            return true;
        });
    }

    /**
     * Record payment
     */
    public function recordPayment(Sale $sale, float $amount, ?string $paymentMethod = null): bool
    {
        $sale->paid_amount += $amount;
        
        if ($paymentMethod) {
            $sale->payment_method = $paymentMethod;
        }

        // Update payment status
        if ($sale->paid_amount >= $sale->total) {
            $sale->payment_status = 'paid';
        } elseif ($sale->paid_amount > 0) {
            $sale->payment_status = 'partial';
        }

        return $sale->save();
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $maxAttempts = 100;
        $attempt = 0;

        do {
            $lastSale = Sale::whereDate('created_at', today())->count();
            $number = $prefix . '-' . $date . '-' . str_pad((string)($lastSale + 1 + $attempt), 4, '0', STR_PAD_LEFT);
            $exists = Sale::where('invoice_number', $number)->exists();
            $attempt++;
        } while ($exists && $attempt < $maxAttempts);

        if ($exists) {
            $number = $prefix . '-' . $date . '-' . time();
        }

        return $number;
    }

    /**
     * Recalculate sale totals
     */
    private function recalculateTotals(Sale $sale): void
    {
        $sale->refresh();
        
        // Calculate subtotal from items
        $subtotal = $sale->items->sum('total');
        $sale->subtotal = $subtotal;

        // Calculate discount
        $discountAmount = 0;
        if ($sale->discount > 0) {
            if ($sale->discount_type === 'percentage') {
                $discountAmount = $subtotal * ($sale->discount / 100);
            } else {
                $discountAmount = $sale->discount;
            }
        }

        // Calculate tax
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = $taxableAmount * ($sale->tax_rate / 100);
        $sale->tax_amount = $taxAmount;

        // Calculate total
        $sale->total = $taxableAmount + $taxAmount;

        // Update payment status if needed
        if ($sale->paid_amount >= $sale->total && $sale->total > 0) {
            $sale->payment_status = 'paid';
        } elseif ($sale->paid_amount > 0) {
            $sale->payment_status = 'partial';
        }

        $sale->save();
    }

    /**
     * Deduct stock from product
     */
    private function deductStock(int $productId, float $quantity): void
    {
        $product = Product::find($productId);
        if ($product) {
            $product->current_stock = max(0, $product->current_stock - $quantity);
            $product->save();
        }
    }

    /**
     * Restore stock to product
     */
    private function restoreStock(int $productId, float $quantity): void
    {
        $product = Product::find($productId);
        if ($product) {
            $product->current_stock += $quantity;
            $product->save();
        }
    }

    /**
     * Deduct stock for composition ingredients
     */
    private function deductCompositionStock(int $compositionId, float $quantity): void
    {
        $composition = Composition::with('ingredients.ingredientProduct')->find($compositionId);
        if ($composition) {
            foreach ($composition->ingredients as $ingredient) {
                if ($ingredient->ingredientProduct) {
                    $ingredientQuantity = $ingredient->quantity * $quantity;
                    $this->deductStock($ingredient->ingredient_product_id, $ingredientQuantity);
                }
            }
        }
    }

    /**
     * Restore stock for composition ingredients
     */
    private function restoreCompositionStock(int $compositionId, float $quantity): void
    {
        $composition = Composition::with('ingredients.ingredientProduct')->find($compositionId);
        if ($composition) {
            foreach ($composition->ingredients as $ingredient) {
                if ($ingredient->ingredientProduct) {
                    $ingredientQuantity = $ingredient->quantity * $quantity;
                    $this->restoreStock($ingredient->ingredient_product_id, $ingredientQuantity);
                }
            }
        }
    }

    /**
     * Check if product has sufficient stock
     */
    public function hassufficientStock(int $productId, float $quantity): bool
    {
        $product = Product::find($productId);
        return $product && $product->current_stock >= $quantity;
    }

    /**
     * Get sale items
     */
    public function getItems(Sale $sale): Collection
    {
        return $sale->items()->with(['product', 'composition'])->get();
    }

    /**
     * Find item by ID
     */
    public function findItemById(int $itemId): ?SaleItem
    {
        return SaleItem::with(['sale', 'product', 'composition'])->find($itemId);
    }

    /**
     * Quick sale - create a sale with minimal data
     */
    public function quickSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $data['invoice_number'] = $this->generateInvoiceNumber();
            $data['sale_date'] = now();
            $data['payment_status'] = 'paid';
            $data['status'] = 'completed';

            // Extract item data
            $productId = $data['product_id'] ?? null;
            $compositionId = $data['composition_id'] ?? null;
            $quantity = $data['quantity'];
            $unit = $data['unit'] ?? 'piece';
            $unitPrice = $data['unit_price'] ?? null;

            unset($data['product_id'], $data['composition_id'], $data['quantity'], $data['unit'], $data['unit_price']);

            // Get product/composition details
            if ($productId) {
                $product = Product::find($productId);
                if ($product) {
                    $unitPrice = $unitPrice ?? $product->selling_price;
                    $productName = $product->name;
                }
            } elseif ($compositionId) {
                $composition = Composition::find($compositionId);
                if ($composition) {
                    $unitPrice = $unitPrice ?? $composition->selling_price;
                    $productName = $composition->name;
                }
            }

            $itemTotal = $quantity * $unitPrice;

            // Calculate totals
            $subtotal = $itemTotal;
            $discount = $data['discount'] ?? 0;
            $discountType = $data['discount_type'] ?? 'amount';
            $taxRate = $data['tax_rate'] ?? 15;

            $discountAmount = $discountType === 'percentage' ? $subtotal * ($discount / 100) : $discount;
            $taxableAmount = $subtotal - $discountAmount;
            $taxAmount = $taxableAmount * ($taxRate / 100);
            $total = $taxableAmount + $taxAmount;

            $data['subtotal'] = $subtotal;
            $data['tax_amount'] = $taxAmount;
            $data['total'] = $total;
            $data['paid_amount'] = $total;

            // Create sale
            $sale = Sale::create($data);

            // Create item
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'composition_id' => $compositionId,
                'product_name' => $productName ?? 'منتج',
                'quantity' => $quantity,
                'unit' => $unit,
                'unit_price' => $unitPrice,
                'total' => $itemTotal,
                'is_composition' => $compositionId ? true : false,
            ]);

            // Deduct stock
            if ($productId) {
                $this->deductStock($productId, $quantity);
            } elseif ($compositionId) {
                $this->deductCompositionStock($compositionId, $quantity);
            }

            return $sale->fresh(['customer', 'employee', 'items.product', 'items.composition']);
        });
    }

    /**
     * Get today's sales summary
     */
    public function getTodaySummary(): array
    {
        $today = today();

        $sales = Sale::whereDate('sale_date', $today)
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total');
        $totalPaid = $sales->sum('paid_amount');
        $totalTax = $sales->sum('tax_amount');
        $totalDiscount = $sales->sum(function ($sale) {
            if ($sale->discount_type === 'percentage') {
                return $sale->subtotal * ($sale->discount / 100);
            }
            return $sale->discount;
        });

        $paymentMethodBreakdown = $sales->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total'),
            ];
        });

        $statusBreakdown = Sale::whereDate('sale_date', $today)
            ->selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $topProducts = SaleItem::whereHas('sale', function ($q) use ($today) {
            $q->whereDate('sale_date', $today)->where('status', '!=', 'cancelled');
        })
            ->selectRaw('product_id, product_name, SUM(quantity) as total_quantity, SUM(total) as total_revenue')
            ->whereNotNull('product_id')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return [
            'date' => $today->format('Y-m-d'),
            'total_sales' => $totalSales,
            'total_revenue' => round($totalRevenue, 2),
            'total_paid' => round($totalPaid, 2),
            'total_pending' => round($totalRevenue - $totalPaid, 2),
            'total_tax' => round($totalTax, 2),
            'total_discount' => round($totalDiscount, 2),
            'average_sale' => $totalSales > 0 ? round($totalRevenue / $totalSales, 2) : 0,
            'payment_methods' => $paymentMethodBreakdown,
            'status_breakdown' => $statusBreakdown,
            'top_products' => $topProducts,
        ];
    }

    /**
     * Refund sale (full or partial)
     */
    public function refund(Sale $sale, ?array $itemsToRefund = null, ?float $refundAmount = null): Sale
    {
        return DB::transaction(function () use ($sale, $itemsToRefund, $refundAmount) {
            if ($itemsToRefund === null) {
                // Full refund - restore all stock
                foreach ($sale->items as $item) {
                    if ($item->product_id) {
                        $this->restoreStock($item->product_id, (float) $item->quantity);
                    } elseif ($item->composition_id) {
                        $this->restoreCompositionStock($item->composition_id, (float) $item->quantity);
                    }
                }

                $sale->status = 'refunded';
                $sale->payment_status = 'refunded';
            } else {
                // Partial refund - restore specified items
                foreach ($itemsToRefund as $itemData) {
                    $item = $sale->items->find($itemData['item_id']);
                    if ($item) {
                        $refundQty = $itemData['quantity'] ?? $item->quantity;
                        
                        if ($item->product_id) {
                            $this->restoreStock($item->product_id, (float) $refundQty);
                        } elseif ($item->composition_id) {
                            $this->restoreCompositionStock($item->composition_id, (float) $refundQty);
                        }

                        // Update or remove item
                        if ($refundQty >= $item->quantity) {
                            $item->delete();
                        } else {
                            $item->quantity -= $refundQty;
                            $item->total = $item->quantity * $item->unit_price;
                            $item->save();
                        }
                    }
                }

                // Recalculate totals
                $this->recalculateTotals($sale);

                if ($sale->items()->count() === 0) {
                    $sale->status = 'refunded';
                    $sale->payment_status = 'refunded';
                } else {
                    $sale->payment_status = 'partial';
                }
            }

            if ($refundAmount !== null) {
                $sale->paid_amount = max(0, $sale->paid_amount - $refundAmount);
            }

            $sale->save();

            return $sale->fresh(['customer', 'employee', 'items.product', 'items.composition']);
        });
    }

    /**
     * Apply discount to sale
     */
    public function applyDiscount(Sale $sale, float $discount, string $discountType = 'amount'): Sale
    {
        return DB::transaction(function () use ($sale, $discount, $discountType) {
            $sale->discount = $discount;
            $sale->discount_type = $discountType;
            $sale->save();

            $this->recalculateTotals($sale);

            return $sale->fresh(['customer', 'employee', 'items.product', 'items.composition']);
        });
    }

    /**
     * Composition sale - sell a pre-made composition
     */
    public function compositionSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            // Lock the composition to prevent race conditions
            $composition = Composition::lockForUpdate()->find($data['composition_id']);
            
            if (!$composition) {
                throw new \Exception('Composition not found');
            }

            $data['invoice_number'] = $this->generateInvoiceNumber();
            $data['sale_date'] = now();
            $data['payment_status'] = 'paid';
            $data['status'] = 'completed';

            $quantity = $data['quantity'];
            $unit = $data['unit'] ?? 'tola';
            $unitPrice = $data['unit_price'] ?? $composition->selling_price;
            $itemTotal = $quantity * $unitPrice;

            // Calculate totals
            $subtotal = $itemTotal;
            $discount = $data['discount'] ?? 0;
            $discountType = $data['discount_type'] ?? 'amount';
            $taxRate = $data['tax_rate'] ?? 15;

            $discountAmount = $discountType === 'percentage' ? $subtotal * ($discount / 100) : $discount;
            $taxableAmount = $subtotal - $discountAmount;
            $taxAmount = $taxableAmount * ($taxRate / 100);
            $total = $taxableAmount + $taxAmount;

            // Remove item-specific data
            unset($data['composition_id'], $data['quantity'], $data['unit'], $data['unit_price']);

            $data['subtotal'] = $subtotal;
            $data['tax_amount'] = $taxAmount;
            $data['total'] = $total;
            $data['paid_amount'] = $total;

            // Create sale
            $sale = Sale::create($data);

            // Create item
            SaleItem::create([
                'sale_id' => $sale->id,
                'composition_id' => $composition->id,
                'product_name' => $composition->name,
                'quantity' => $quantity,
                'unit' => $unit,
                'unit_price' => $unitPrice,
                'total' => $itemTotal,
                'is_composition' => true,
                'is_custom_blend' => false,
            ]);

            // Deduct stock for composition ingredients
            $this->deductCompositionStock($composition->id, $quantity);

            return $sale->fresh(['customer', 'employee', 'items.product', 'items.composition']);
        });
    }

    /**
     * Custom blend sale - sell a custom mix of products
     */
    public function customBlend(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $data['invoice_number'] = $this->generateInvoiceNumber();
            $data['sale_date'] = now();
            $data['payment_status'] = 'paid';
            $data['status'] = 'completed';

            $blendName = $data['blend_name'] ?? 'خلطة مخصصة';
            $ingredients = $data['ingredients'];

            // Calculate items total
            $subtotal = 0;
            $itemsData = [];

            foreach ($ingredients as $ingredient) {
                // Lock the product to prevent race conditions
                $product = Product::lockForUpdate()->find($ingredient['product_id']);
                if (!$product) {
                    continue;
                }

                $unitPrice = $product->selling_price;
                $itemTotal = $ingredient['quantity'] * $unitPrice;
                $subtotal += $itemTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'unit_price' => $unitPrice,
                    'total' => $itemTotal,
                    'is_composition' => false,
                    'is_custom_blend' => true,
                    'notes' => "جزء من: {$blendName}",
                ];
            }

            // Calculate totals
            $discount = $data['discount'] ?? 0;
            $discountType = $data['discount_type'] ?? 'amount';
            $taxRate = $data['tax_rate'] ?? 15;

            $discountAmount = $discountType === 'percentage' ? $subtotal * ($discount / 100) : $discount;
            $taxableAmount = $subtotal - $discountAmount;
            $taxAmount = $taxableAmount * ($taxRate / 100);
            $total = $taxableAmount + $taxAmount;

            // Remove specific data
            unset($data['blend_name'], $data['ingredients']);

            $data['subtotal'] = $subtotal;
            $data['tax_amount'] = $taxAmount;
            $data['total'] = $total;
            $data['paid_amount'] = $total;
            $data['notes'] = ($data['notes'] ?? '') . " | خلطة مخصصة: {$blendName}";

            // Create sale
            $sale = Sale::create($data);

            // Create items and deduct stock
            foreach ($itemsData as $itemData) {
                $itemData['sale_id'] = $sale->id;
                SaleItem::create($itemData);
                
                // Deduct stock
                $this->deductStock($itemData['product_id'], $itemData['quantity']);
            }

            return $sale->fresh(['customer', 'employee', 'items.product', 'items.composition']);
        });
    }
}


