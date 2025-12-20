<?php

namespace App\Http\Resources\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UnitAssignment\UnitAssignmentResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'floor_id' => $this->floor_id,
            'unit_type_id' => $this->unit_type_id,
            'unit_type' => [
                'id' => $this->unitType?->id,
                'name' => $this->unitType?->display_name
            ],
            'unit_number' => $this->unit_number,
            'name' => $this->name,
            'area_sqm' => $this->area_sqm,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'floor' => $this->when($this->relationLoaded('floor'), [
                'id' => $this->floor?->id,
                'name' => $this->floor?->name,
                'floor_number' => $this->floor?->floor_number,
            ]),
            'assignments' => UnitAssignmentResource::collection($this->whenLoaded('assignments')),
            'active_residents' => $this->when($this->relationLoaded('assignments'), function () {
                return $this->assignments
                    ->where('is_active', true)
                    ->map(function ($assignment) {
                        return [
                            'id' => $assignment->user?->id,
                            'name' => $assignment->user?->name,
                            'phone' => $assignment->user?->phone,
                            'email' => $assignment->user?->email,
                            'image' => $assignment->user?->image,
                            'member_id' => $assignment->member_id,
                        ];
                    })->values();
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get status label in Arabic
     */
    protected function getStatusLabel(): ?string
    {
        if ($this->status === null) {
            return null;
        }

        return match ($this->status) {
            'vacant' => 'شاغرة',
            'occupied' => 'مؤجرة',
            'reserved' => 'محجوزة',
            'maintenance' => 'صيانة',
            default => $this->status,
        };
    }
}