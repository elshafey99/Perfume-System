<?php

namespace App\Http\Resources\FacilityType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityTypeResource extends JsonResource
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
            'name_key' => $this->name_key,
            'name' => $this->name,
            'description' => $this->description,
            'has_quantity'=> $this->has_quantity,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
