<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Countries\CountryResource;
use App\Http\Resources\Governorates\GovernorateResource;
use App\Http\Resources\GovernorateCenters\GovernorateCenterResource;

class PropertyResource extends JsonResource
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

            // Basic Information
            'name' => $this->name,
            'code' => $this->code,
            'property_number' => $this->property_number,

            // Location
            'country' => new CountryResource($this->whenLoaded('country')),
            'governorate' => new GovernorateResource($this->whenLoaded('governorate')),
            'city' => new GovernorateCenterResource($this->whenLoaded('city')),
            'address_street' => $this->address_street,
            'district_code' => $this->district_code,

            // Areas
            'area_land' => $this->area_land ? (float) $this->area_land : null,
            'area_built' => $this->area_built ? (float) $this->area_built : null,

            // Specifications
            'specs_details' => $this->specs_details,

            // Boundaries
            'boundaries' => $this->boundaries,

            // Buildings Count
            'residential_buildings_count' => $this->residential_buildings_count,
            'administrative_buildings_count' => $this->administrative_buildings_count,

            // User Counts
            'total_users_count' => $this->total_users_count,
            //'commissioner_count' => $this->commissioner_count,
            'treasurer_count' => $this->treasurer_count,
            'union_member_count' => $this->union_member_count,
            'investors_count' => $this->investors_count,
            'owners_count' => $this->owners_count,
            'tenants_count' => $this->tenants_count,

            // Bank Information
            'bank_name' => $this->bank_name,
            'bank_account_number' => $this->bank_account_number,
            'bank_iban' => $this->bank_iban,

            // Status
            'is_active' => (bool) $this->is_active,
            'status' => $this->getPropertyStatus(),

            // Counts
            'buildings_count' => $this->whenLoaded('buildings', function () {
                return $this->buildings->count();
            }),
            'members_count' => $this->whenLoaded('members', function () {
                return $this->members->count();
            }),
            'attachments_count' => $this->whenLoaded('attachments', function () {
                return $this->attachments->count();
            }),

            // Timestamps
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get property status based on completion
     */
    protected function getPropertyStatus(): string
    {
        // Check if property has attachments (images & documents)
        if ($this->attachments()->count() === 0) {
            return 'pending_attachments';
        }

        // Check if property has buildings
        if ($this->buildings()->count() === 0) {
            return 'pending_buildings_setup';
        }

        // Check if property has members (more than just the creator)
        if ($this->members()->count() <= 1) {
            return 'pending_members';
        }

        return 'completed';
    }
}