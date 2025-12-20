<?php

namespace App\Http\Resources\Countries;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale) ?? $this->getTranslation('name', 'en'),
            'code' => $this->code,
            'image' => $this->image ? asset($this->image) : null,
        ];
    }
}
