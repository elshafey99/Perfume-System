<?php

namespace App\Http\Resources\AdvertisementCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', app()->getLocale());

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $lang),
            'description' => $this->getTranslation('description', $lang),
            'is_active' => (bool) $this->is_active,
        ];
    }
}
