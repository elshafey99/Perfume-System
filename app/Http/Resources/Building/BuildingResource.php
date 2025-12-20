<?php

namespace App\Http\Resources\Building;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Floor\FloorResource;

class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Calculate total floors:
        // 1. If we have floors_count (from withCount) -> use it
        // 2. If floors are loaded -> count them
        // 3. Otherwise -> null
        $totalFloors = $this->floors_count
            ?? ($this->relationLoaded('floors') ? $this->floors->count() : null);

        // Get name based on current locale
        $locale = app()->getLocale();
        $name = is_array($this->name)
            ? ($this->name[$locale] ?? $this->name['ar'] ?? $this->name['en'] ?? null)
            : $this->name;

        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'name' => $name,
            'type' => $this->type,
            'total_floors' => $totalFloors,
            'floors' => FloorResource::collection($this->whenLoaded('floors')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
