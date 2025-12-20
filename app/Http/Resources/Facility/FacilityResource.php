<?php

namespace App\Http\Resources\Facility;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Building\BuildingResource;
use App\Http\Resources\FacilityType\FacilityTypeResource;

class FacilityResource extends JsonResource
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
            'facility_type_id' => $this->facility_type_id,
            'facility_type' => new FacilityTypeResource($this->whenLoaded('facilityType')),
            'is_available' => $this->is_available,
            'number' => $this->number,
            'last_maintenance_date' => $this->last_maintenance_date?->format('Y-m-d'),
            //'building' => new BuildingResource($this->whenLoaded('building')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
