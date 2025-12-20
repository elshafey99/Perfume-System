<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale) ?? $this->getTranslation('name', 'en'),
            'description' => $this->getTranslation('description', $locale) ?? $this->getTranslation('description', 'en'),
            'features' => $this->getTranslation('features', $locale) ?? $this->getTranslation('features', 'en'),
            'max_units' => $this->max_units,
            'trial_days' => $this->trial_days,
            'is_active' => $this->is_active,
            'prices' => PlanPriceResource::collection($this->whenLoaded('planPrices')),
        ];
    }
}
