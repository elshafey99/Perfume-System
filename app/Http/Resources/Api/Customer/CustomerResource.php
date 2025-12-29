<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'gender' => $this->gender,
            'gender_display' => $this->getGenderDisplay(),
            'address' => $this->address,
            'loyalty_points' => (float) $this->loyalty_points,
            'loyalty_level' => $this->loyalty_level,
            'loyalty_level_display' => $this->getLoyaltyLevelDisplay(),
            'total_purchases' => (float) $this->total_purchases,
            'last_purchase_date' => $this->last_purchase_date?->format('Y-m-d'),
            'preferred_scents' => $this->preferred_scents ?? [],
            'favorite_products' => $this->favorite_products ?? [],
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getGenderDisplay(): ?string
    {
        return match ($this->gender) {
            'male' => 'ذكر',
            'female' => 'أنثى',
            'other' => 'آخر',
            default => null,
        };
    }

    private function getLoyaltyLevelDisplay(): string
    {
        return match ($this->loyalty_level) {
            'bronze' => 'برونزي',
            'silver' => 'فضي',
            'gold' => 'ذهبي',
            'platinum' => 'بلاتيني',
            default => 'برونزي',
        };
    }
}
