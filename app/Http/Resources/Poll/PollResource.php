<?php

namespace App\Http\Resources\Poll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'building_id' => $this->building_id,
            'category' => $this->when($this->relationLoaded('category'), function () use ($locale) {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->getTranslation('name', $locale),
                ];
            }),
            'title' => $this->getTranslation('title', $locale),
            'description' => $this->getTranslation('description', $locale),
            'vote_type' => $this->when($this->relationLoaded('voteType'), function () use ($locale) {
                return [
                    'id' => $this->voteType->id,
                    'key' => $this->voteType->key,
                    'name' => $this->voteType->getTranslation('name', $locale),
                ];
            }),
            'voting_method' => $this->voting_method,
            'started_by' => $this->when($this->relationLoaded('starter'), function () {
                return [
                    'id' => $this->starter->id,
                    'name' => $this->starter->name,
                ];
            }),
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'status' => $this->status,
            'can_vote' => $this->canVote(),
            'options' => PollOptionResource::collection($this->whenLoaded('options')),
            'attachments' => PollAttachmentResource::collection($this->whenLoaded('attachments')),
            'results' => $this->when($this->relationLoaded('votes'), function () {
                return $this->calculateResults();
            }),
            'total_votes' => $this->when($this->relationLoaded('votes'), function () {
                return $this->votes->count();
            }),
            'total_weight' => $this->when($this->relationLoaded('votes'), function () {
                return $this->votes->sum('vote_weight');
            }),
            'final_decision' => $this->when($this->final_decision, function () use ($locale) {
                return $this->getTranslation('final_decision', $locale);
            }),
            'decision_notes' => $this->when($this->decision_notes, function () use ($locale) {
                return $this->getTranslation('decision_notes', $locale);
            }),
            'decision_made_at' => $this->decision_made_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Calculate poll results
     */
    protected function calculateResults(): array
    {
        if (!$this->relationLoaded('votes') || !$this->relationLoaded('options')) {
            return [];
        }

        $totalWeight = $this->votes->sum('vote_weight');

        return $this->options->map(function ($option) use ($totalWeight) {
            $optionVotes = $this->votes->where('option_id', $option->id);
            $optionWeight = $optionVotes->sum('vote_weight');

            return [
                'option_id' => $option->id,
                'option_text' => $option->getTranslation('option_text', app()->getLocale()),
                'votes_count' => $optionVotes->count(),
                'votes_weight' => $optionWeight,
                'percentage' => $totalWeight > 0 ? round(($optionWeight / $totalWeight) * 100, 2) : 0,
            ];
        })->toArray();
    }
}
