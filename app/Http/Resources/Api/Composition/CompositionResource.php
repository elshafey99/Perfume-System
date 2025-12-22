<?php

namespace App\Http\Resources\Api\Composition;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompositionResource extends JsonResource
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
            'code' => $this->code,
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                ];
            }),
            'bottle_size' => (float) $this->bottle_size,
            'concentration_type' => $this->concentration_type,
            'base_cost' => (float) $this->base_cost,
            'service_fee' => (float) $this->service_fee,
            'selling_price' => (float) $this->selling_price,
            'instructions' => $this->instructions,
            'notes' => $this->notes,
            'image' => $this->image ? asset($this->image) : null,
            'is_magic_recipe' => $this->is_magic_recipe,
            'original_perfume_name' => $this->original_perfume_name,
            'is_active' => $this->is_active,
            'ingredients' => $this->whenLoaded('ingredients', function () {
                return CompositionIngredientResource::collection($this->ingredients);
            }),
            'ingredients_count' => $this->whenCounted('ingredients', function () {
                return $this->ingredients()->count();
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

