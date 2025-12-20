<?php

namespace App\Http\Resources\ComplaintSuggestionCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintSuggestionCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', app()->getLocale());
        $lang = in_array($lang, ['ar', 'en']) ? $lang : app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $lang),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
