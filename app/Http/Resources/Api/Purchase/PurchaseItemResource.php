<?php

namespace App\Http\Resources\Api\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'purchase_id' => $this->purchase_id,
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'sku' => $this->product->sku,
                ];
            }),
            'quantity' => (float) $this->quantity,
            'unit' => $this->unit,
            'unit_display' => $this->getUnitDisplay(),
            'cost_price' => (float) $this->cost_price,
            'total' => (float) $this->total,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getUnitDisplay(): string
    {
        return match ($this->unit) {
            'piece' => 'قطعة',
            'gram' => 'جرام',
            'ml' => 'مل',
            'tola' => 'تولة',
            'quarter_tola' => 'ربع تولة',
            default => $this->unit ?? 'قطعة',
        };
    }
}
