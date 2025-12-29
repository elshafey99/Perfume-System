<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyPointResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'points' => (float) $this->points,
            'type' => $this->type,
            'type_display' => $this->getTypeDisplay(),
            'reference_type' => $this->reference_type,
            'reference_id' => $this->reference_id,
            'balance_after' => (float) $this->balance_after,
            'expires_at' => $this->expires_at?->format('Y-m-d'),
            'notes' => $this->notes,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function getTypeDisplay(): string
    {
        return match ($this->type) {
            'earned' => 'مكتسبة',
            'redeemed' => 'مستبدلة',
            'expired' => 'منتهية',
            'adjusted' => 'معدلة',
            default => $this->type,
        };
    }
}
