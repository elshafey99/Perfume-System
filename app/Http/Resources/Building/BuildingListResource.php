<?php

namespace App\Http\Resources\Building;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Get name based on current locale
        $locale = app()->getLocale();
        $name = is_array($this->name)
            ? ($this->name[$locale] ?? $this->name['ar'] ?? $this->name['en'] ?? null)
            : $this->name;

        // Get exterior image from property attachments
        $exteriorImage = null;
        if ($this->property && $this->property->attachments) {
            $exteriorAttachment = $this->property->attachments()
                ->whereHas('category', function ($query) {
                    $query->where('name_key', 'exterior');
                })
                ->first();
            
            if ($exteriorAttachment && $exteriorAttachment->file_path) {
                $exteriorImage = asset($exteriorAttachment->file_path);
            }
        }

        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'name' => $name,
            'type' => $this->type,
            'floors_count' => $this->floors_count ?? 0,
            'image' => $exteriorImage,
        ];
    }
}