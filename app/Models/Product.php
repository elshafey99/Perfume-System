<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'code',
        'category_id',
        'product_type_id',
        'unit_type_id',
        'conversion_rate',
        'current_stock',
        'min_stock_level',
        'max_stock_level',
        'cost_price',
        'selling_price',
        'price_per_gram',
        'price_per_ml',
        'is_open_price',
        'image',
        'description',
        'brand',
        'is_raw_material',
        'is_composition',
        'is_active',
        'can_return',
        'supplier_id',
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:4',
        'current_stock' => 'decimal:4',
        'min_stock_level' => 'decimal:4',
        'max_stock_level' => 'decimal:4',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'price_per_gram' => 'decimal:2',
        'price_per_ml' => 'decimal:2',
        'is_raw_material' => 'boolean',
        'is_composition' => 'boolean',
        'is_open_price' => 'boolean',
        'is_active' => 'boolean',
        'can_return' => 'boolean',
        'supplier_id' => 'integer',
        'product_type_id' => 'integer',
        'unit_type_id' => 'integer',
    ];

    /**
     * Get the category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the product type
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the unit type
     */
    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class);
    }

    /**
     * Get the supplier
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get inventory transactions
     */
    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Get sale items
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get purchase items
     */
    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Get composition ingredients (where this product is used as ingredient)
     */
    public function compositionIngredients(): HasMany
    {
        return $this->hasMany(CompositionIngredient::class, 'ingredient_product_id');
    }

    /**
     * Get stocktaking items
     */
    public function stocktakingItems(): HasMany
    {
        return $this->hasMany(StocktakingItem::class);
    }
}
