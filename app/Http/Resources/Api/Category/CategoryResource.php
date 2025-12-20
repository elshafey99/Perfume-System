<?php

namespace App\Http\Resources\Api\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'parent' => $this->whenLoaded('parent', function () {
                return [
                    'id' => $this->parent->id,
                    'name' => $this->parent->name,
                ];
            }),
            'children' => $this->whenLoaded('children', function () {
                return CategoryResource::collection($this->children);
            }),
            'icon' => $this->icon ? asset($this->icon) : null,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'products_count' => $this->whenCounted('products', function () {
                return $this->products()->count();
            }),
            'children_count' => $this->whenCounted('children', function () {
                return $this->children()->count();
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

