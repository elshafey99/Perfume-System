<?php

namespace App\Http\Resources\Poll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PollListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Lightweight version for list views.
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', $locale),
            'category' => $this->when($this->relationLoaded('category'), function () use ($locale) {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->getTranslation('name', $locale),
                ];
            }),
            'voting_method' => $this->voting_method,
            'status' => $this->status,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'can_vote' => $this->canVote(),
            'total_votes' => $this->votes_count ?? 0,
            'has_final_decision' => !is_null($this->final_decision),
            'created_at' => $this->created_at?->format('Y-m-d'),
        ];
    }
}
