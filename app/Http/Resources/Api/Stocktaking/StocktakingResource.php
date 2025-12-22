<?php

namespace App\Http\Resources\Api\Stocktaking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StocktakingResource extends JsonResource
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
            'stocktaking_number' => $this->stocktaking_number,
            'stocktaking_date' => $this->stocktaking_date?->format('Y-m-d'),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),
            'completed_by' => $this->completed_by,
            'completer' => $this->whenLoaded('completer', function () {
                return [
                    'id' => $this->completer->id,
                    'name' => $this->completer->name,
                ];
            }),
            'completed_at' => $this->completed_at?->format('Y-m-d H:i:s'),
            'total_items' => $this->total_items,
            'total_differences' => (float) $this->total_differences,
            'items' => $this->whenLoaded('items', function () {
                return StocktakingItemResource::collection($this->items);
            }),
            'items_count' => $this->whenCounted('items', function () {
                return $this->items()->count();
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get status label in Arabic
     */
    private function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => $this->status,
        };
    }
}

