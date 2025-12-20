<?php

namespace App\Http\Resources\OwnerDashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Plan\PlanResource;

class OwnerDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'property' => [
                'id' => $this->resource['property']->id,
                'name' => $this->resource['property']->getTranslation('name', $locale)
                    ?? $this->resource['property']->getTranslation('name', 'en'),
                'code' => $this->resource['property']->code,
                'address' => $this->formatAddress(),
            ],

            'units_statistics' => [
                'total' => $this->resource['units_stats']['total'],
                'vacant' => $this->resource['units_stats']['vacant'],
                'occupied' => $this->resource['units_stats']['occupied'],
                'reserved' => $this->resource['units_stats']['reserved'],
                'maintenance' => $this->resource['units_stats']['maintenance'],
            ],

            'subscription' => $this->formatSubscription(),

            // 'unit_types_statistics' => $this->resource['unit_types_stats'],

            'buildings_count' => count($this->resource['buildings'] ?? []),

            'garages_count' => $this->resource['garages_count'] ?? 0,
        ];
    }

    /**
     * Format property address as a single line string
     */
    protected function formatAddress(): ?string
    {
        $property = $this->resource['property'];
        $locale = app()->getLocale();

        $parts = [];

        // Street
        if ($property->address_street) {
            $parts[] = $property->address_street;
        }

        // City
        if ($property->city) {
            $parts[] = $property->city->getTranslation('name', $locale)
                ?? $property->city->getTranslation('name', 'en');
        }

        // Governorate
        if ($property->governorate) {
            $parts[] = $property->governorate->getTranslation('name', $locale)
                ?? $property->governorate->getTranslation('name', 'en');
        }

        // Country
        if ($property->country) {
            $parts[] = $property->country->getTranslation('name', $locale)
                ?? $property->country->getTranslation('name', 'en');
        }

        return !empty($parts) ? implode('، ', $parts) : null;
    }

    /**
     * Format subscription information
     */
    protected function formatSubscription(): ?array
    {
        $subscription = $this->resource['subscription'];

        if (!$subscription) {
            return null;
        }

        $locale = app()->getLocale();

        return [
            'id' => $subscription->id,
            'plan' => $subscription->plan ? [
                'id' => $subscription->plan->id,
                'name' => $subscription->plan->getTranslation('name', $locale)
                    ?? $subscription->plan->getTranslation('name', 'en'),
                'max_units' => $subscription->plan->max_units,
            ] : null,
            'status' => $subscription->status,
            'starts_at' => $subscription->starts_at?->format('Y-m-d'),
            'ends_at' => $subscription->ends_at?->format('Y-m-d'),
            'trial_ends_at' => $subscription->trial_ends_at?->format('Y-m-d'),
            'is_trial' => $subscription->trial_ends_at && $subscription->trial_ends_at->isFuture(),
            'auto_renew' => $subscription->auto_renew,
        ];
    }
}
