<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\Countries\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanPriceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'country' => new CountryResource($this->whenLoaded('country')),
            'monthly_price_initial' => (float) $this->monthly_price_initial,
            'monthly_price_renewal' => (float) $this->monthly_price_renewal,
            'yearly_price_initial' => (float) $this->yearly_price_initial,
            'yearly_price_renewal' => (float) $this->yearly_price_renewal,
            'extra_unit_price_monthly' => (float) $this->extra_unit_price_monthly,
            'extra_unit_price_yearly' => (float) $this->extra_unit_price_yearly,
        ];
    }
}
