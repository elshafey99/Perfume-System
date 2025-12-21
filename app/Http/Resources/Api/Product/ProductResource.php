<?php

namespace App\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),
            'product_type_id' => $this->product_type_id,
            'product_type' => $this->whenLoaded('productType', function () {
                return [
                    'id' => $this->productType->id,
                    'code' => $this->productType->code,
                    'name' => $this->productType->name,
                ];
            }),
            'unit_type_id' => $this->unit_type_id,
            'unit_type' => $this->whenLoaded('unitType', function () {
                return [
                    'id' => $this->unitType->id,
                    'code' => $this->unitType->code,
                    'name' => $this->unitType->name,
                    'symbol' => $this->unitType->symbol,
                ];
            }),
            'conversion_rate' => $this->conversion_rate,
            'current_stock' => (float) $this->current_stock,
            'min_stock_level' => (float) $this->min_stock_level,
            'max_stock_level' => (float) $this->max_stock_level,
            'cost_price' => (float) $this->cost_price,
            'selling_price' => (float) $this->selling_price,
            'price_per_gram' => $this->price_per_gram ? (float) $this->price_per_gram : null,
            'price_per_ml' => $this->price_per_ml ? (float) $this->price_per_ml : null,
            'image' => $this->image ? asset($this->image) : null,
            'description' => $this->description,
            'brand' => $this->brand,
            'is_raw_material' => $this->is_raw_material,
            'is_composition' => $this->is_composition,
            'is_active' => $this->is_active,
            'can_return' => $this->can_return,
            'supplier_id' => $this->supplier_id,
            'supplier' => $this->whenLoaded('supplier', function () {
                return [
                    'id' => $this->supplier->id,
                    'name' => $this->supplier->name,
                ];
            }),
            'is_low_stock' => $this->current_stock <= $this->min_stock_level,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}