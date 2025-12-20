<?php

namespace App\Http\Resources\UnitAssignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_id' => $this->unit_id,
            'user_id' => $this->user_id,
            'member_id' => $this->user?->member_id,
            'access_code' => $this->user?->access_code,
            'assignment_type' => $this->assignment_type,
            'is_active' => $this->is_active,
            'user' => $this->when($this->relationLoaded('user'), [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'phone' => $this->user?->phone,
                'email' => $this->user?->email,
                'image' => $this->user?->image,
                'member_id' => $this->user?->member_id,
                'access_code' => $this->user?->access_code,
            ]),
            'unit' => $this->when($this->relationLoaded('unit'), [
                'id' => $this->unit?->id,
                'unit_number' => $this->unit?->unit_number,
                'name' => $this->unit?->name,
                'status' => $this->unit?->status,
            ]),
            'property_code' => $this->getPropertyCode(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get property code from unit -> floor -> building -> property
     */
    protected function getPropertyCode(): ?string
    {
        if ($this->relationLoaded('unit') && $this->unit) {
            // Check if relationships are loaded
            if ($this->unit->relationLoaded('floor') && $this->unit->floor) {
                if ($this->unit->floor->relationLoaded('building') && $this->unit->floor->building) {
                    if ($this->unit->floor->building->relationLoaded('property') && $this->unit->floor->building->property) {
                        return $this->unit->floor->building->property->code;
                    }
                }
            }
        }

        return null;
    }
}