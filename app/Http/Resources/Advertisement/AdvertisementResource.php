<?php

namespace App\Http\Resources\Advertisement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\FileHelper;
class AdvertisementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', app()->getLocale());
        $category = $this->category;

        return [
            'id' => $this->id,
            'advertisement_category' => $category ? [
                'id' => $category->id,
                'name' => $category->getTranslation('name', $lang),
            ] : null,
            'title' => $this->getTranslation('title', $lang),
            'description' => $this->getTranslation('description', $lang),
            'date' => $this->date?->toDateString(),
            'image' => $this->image ? FileHelper::url($this->image) : null,
            'attachment' => $this->whenLoaded('attachments', function () {
                return $this->attachments->map(function ($att) {
                    return [
                        'id' => $att->id,
                        'path' => FileHelper::url($att->path),
                    ];
                });
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
