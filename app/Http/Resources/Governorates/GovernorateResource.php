<?php

namespace App\Http\Resources\Governorates;

use Illuminate\Http\Resources\Json\JsonResource;

class GovernorateResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale) ?? $this->getTranslation('name', 'en'),
            'code' => $this->code,
            'country_id' => $this->country_id,
        ];
    }
}
