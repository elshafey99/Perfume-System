<?php

namespace App\Http\Resources\PropertyRole;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $lang = $request->header('Accept-Language', app()->getLocale());
        return [
            'id' => $this->id,
            'name_key' => $this->name_key,
            'display_name' => $this->getTranslation('display_name', $lang),
            'type' => $this->type,
        ];
    }
}
