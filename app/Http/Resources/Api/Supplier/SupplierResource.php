<?php

namespace App\Http\Resources\Api\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'phones' => $this->phones, // JSON array
            'email' => $this->email,
            'website' => $this->website,
            'address' => $this->address,
            'area' => $this->area,
            'tax_number' => $this->tax_number,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            
            // Financial Data
            'total_purchases' => (float) $this->total_purchases,
            'total_paid' => (float) $this->total_paid,
            'balance_due' => (float) $this->balance_due,
            
            // Counts
            'purchases_count' => $this->whenCounted('purchases', function () {
                return $this->purchases()->count();
            }),
            'products_count' => $this->whenCounted('products', function () {
                return $this->products()->count();
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}