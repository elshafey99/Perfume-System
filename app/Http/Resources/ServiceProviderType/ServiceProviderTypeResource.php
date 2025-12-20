<?php

namespace App\Http\Resources\ServiceProviderType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProviderTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', 'en');

        return [
            'id' => $this->id,
            'name_key' => $this->name_key,
            'display_name' => $this->getTranslation('display_name', $lang),
        ];
    }
}
