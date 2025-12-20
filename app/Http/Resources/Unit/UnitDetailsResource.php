<?php

namespace App\Http\Resources\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_number' => $this->unit_number,
            'name' => $this->name,
            'area_sqm' => $this->area_sqm,
            'status' => $this->status,
            //'status_label' => $this->getStatusLabel(),
            
            'unit_type' => [
                'id' => $this->unitType?->id,
                'name' => $this->unitType?->display_name,
            ],

            'residents' => $this->when($this->relationLoaded('assignments'), function () {
                return $this->assignments
                    ->where('is_active', true)
                    ->map(function ($assignment) {
                        return [
                            'id' => $assignment->user?->id,
                            'member_id' => $assignment->user?->member_id,
                            'name' => $assignment->user?->name,
                            'phone' => $assignment->user?->phone,
                            //'email' => $assignment->user?->email,
                            'image' => $assignment->user?->image ? asset($assignment->user->image) : null,
                        ];
                    })->values();
            }),
        ];
    }

    /**
     * Get status label in Arabic
     */
    // protected function getStatusLabel(): ?string
    // {
    //     if ($this->status === null) {
    //         return null;
    //     }

    //     return match ($this->status) {
    //         'vacant' => 'شاغرة',
    //         'occupied' => 'مؤجرة',
    //         'reserved' => 'محجوزة',
    //         'maintenance' => 'صيانة',
    //         default => $this->status,
    //     };
    // }
}
