<?php

namespace App\Http\Resources\Api\InventoryTransaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryTransactionResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'sku' => $this->product->sku,
                ];
            }),
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'quantity' => (float) $this->quantity,
            'unit' => $this->unit,
            'reference_type' => $this->reference_type,
            'reference_id' => $this->reference_id,
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),
            'transaction_date' => $this->transaction_date?->format('Y-m-d H:i:s'),
            'stock_after' => (float) $this->stock_after,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get type label in Arabic
     */
    private function getTypeLabel(): string
    {
        return match ($this->type) {
            'sale' => 'بيع',
            'purchase' => 'شراء',
            'return' => 'إرجاع',
            'adjustment' => 'تسوية',
            'composition' => 'تركيبة',
            'waste' => 'هالك',
            default => $this->type,
        };
    }
}
