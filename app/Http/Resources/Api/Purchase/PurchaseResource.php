<?php

namespace App\Http\Resources\Api\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'supplier' => $this->whenLoaded('supplier', function () {
                return [
                    'id' => $this->supplier->id,
                    'name' => $this->supplier->name,
                    'phone' => $this->supplier->phone,
                ];
            }),
            'subtotal' => (float) $this->subtotal,
            'tax_amount' => (float) $this->tax_amount,
            'total' => (float) $this->total,
            'purchase_date' => $this->purchase_date?->format('Y-m-d'),
            'expected_delivery_date' => $this->expected_delivery_date?->format('Y-m-d'),
            'received_date' => $this->received_date?->format('Y-m-d'),
            'notes' => $this->notes,
            'status' => $this->status,
            'status_display' => $this->getStatusDisplay(),
            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),
            'items' => PurchaseItemResource::collection($this->whenLoaded('items')),
            'items_count' => $this->whenCounted('items'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getStatusDisplay(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'received' => 'تم الاستلام',
            'cancelled' => 'ملغي',
            default => 'قيد الانتظار',
        };
    }
}
