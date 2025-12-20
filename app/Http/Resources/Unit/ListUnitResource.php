<?php

namespace App\Http\Resources\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Simple resource for listing units in dropdowns/selectors
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_number' => $this->unit_number,
            'name' => $this->name,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'floor' => [
                'id' => $this->floor?->id,
                'name' => $this->floor?->name,
                'floor_number' => $this->floor?->floor_number,
            ],
            'unit_type' => [
                'id' => $this->unitType?->id,
                'name' => $this->unitType?->display_name,
            ],
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
