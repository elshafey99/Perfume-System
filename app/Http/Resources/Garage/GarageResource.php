<?php

namespace App\Http\Resources\Garage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Building\BuildingResource;

class GarageResource extends JsonResource
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
            'property_id' => $this->property_id,
            'building_id' => $this->building_id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'capacity' => $this->capacity,
            'building' => new BuildingResource($this->whenLoaded('building')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}

