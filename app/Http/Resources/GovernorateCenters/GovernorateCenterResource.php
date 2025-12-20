<?php

namespace App\Http\Resources\GovernorateCenters;

use Illuminate\Http\Resources\Json\JsonResource;

class GovernorateCenterResource extends JsonResource
{
    public function toArray($request)
    {
        $local = app()->getLocale();
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $local) ?? $this->getTranslation('name', 'en'),
            'code' => $this->code,
            'governorate_id' => $this->governorate_id,
        ];
    }
}
