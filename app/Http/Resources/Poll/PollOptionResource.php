<?php

namespace App\Http\Resources\Poll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PollOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'poll_id' => $this->poll_id,
            'option_text' => $this->getTranslation('option_text', $locale),
            'display_order' => $this->display_order,
            'votes_count' => $this->when($this->relationLoaded('votes'), function () {
                return $this->votes->count();
            }),
            'votes_weight' => $this->when($this->relationLoaded('votes'), function () {
                return $this->votes->sum('vote_weight');
            }),
        ];
    }
}
