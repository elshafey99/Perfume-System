<?php

namespace App\Http\Resources\Api\Composition;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompositionIngredientResource extends JsonResource
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
            'composition_id' => $this->composition_id,
            'ingredient_product_id' => $this->ingredient_product_id,
            'ingredient_product' => $this->whenLoaded('ingredientProduct', function () {
                return [
                    'id' => $this->ingredientProduct->id,
                    'name' => $this->ingredientProduct->name,
                    'sku' => $this->ingredientProduct->sku,
                    'cost_price' => (float) $this->ingredientProduct->cost_price,
                    'price_per_gram' => $this->ingredientProduct->price_per_gram ? (float) $this->ingredientProduct->price_per_gram : null,
                    'price_per_ml' => $this->ingredientProduct->price_per_ml ? (float) $this->ingredientProduct->price_per_ml : null,
                ];
            }),
            'quantity' => (float) $this->quantity,
            'unit' => $this->unit,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

