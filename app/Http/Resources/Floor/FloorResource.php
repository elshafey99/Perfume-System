<?php

namespace App\Http\Resources\Floor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Unit\UnitResource;

class FloorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'building_id' => $this->building_id,
            'name' => $this->name,
            'floor_number' => $this->floor_number,
            'type' => $this->type,
            'total_units' => $this->units_count ?? $this->units->count(),
            'units' => UnitResource::collection($this->whenLoaded('units')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}