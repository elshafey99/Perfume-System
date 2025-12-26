<?php

namespace App\Http\Resources\Api\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
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
            'sale_id' => $this->sale_id,
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'sku' => $this->product->sku,
                    'barcode' => $this->product->barcode,
                    'image' => $this->product->image ? asset($this->product->image) : null,
                ];
            }),
            'composition_id' => $this->composition_id,
            'composition' => $this->whenLoaded('composition', function () {
                return [
                    'id' => $this->composition->id,
                    'name' => $this->composition->name,
                    'code' => $this->composition->code,
                ];
            }),
            'product_name' => $this->product_name,
            'quantity' => (float) $this->quantity,
            'unit' => $this->unit,
            'unit_display' => $this->getUnitDisplay(),
            'unit_price' => (float) $this->unit_price,
            'total' => (float) $this->total,
            'is_composition' => $this->is_composition,
            'is_custom_blend' => $this->is_custom_blend,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get unit display name
     */
    private function getUnitDisplay(): string
    {
        return match ($this->unit) {
            'piece' => 'قطعة',
            'gram' => 'جرام',
            'ml' => 'مل',
            'tola' => 'تولة',
            'quarter_tola' => 'ربع تولة',
            default => $this->unit,
        };
    }
}
