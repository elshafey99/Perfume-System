<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\FileHelper;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'file_url' => $this->getFileUrl(),
            'file_type' => $this->file_type,
            'category' => $this->whenLoadedCategory(),
            'visibility' => $this->visibility,
            'visibility_rules' => $this->getVisibilityRules(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get full URL for file using FileHelper
     */
    protected function getFileUrl(): ?string
    {
        return FileHelper::url($this->file_path);
    }

    protected function whenLoadedCategory(): ?array
    {
        if (!$this->category) {
            return null;
        }

        return [
            'id' => $this->category->id,
            'key' => $this->category->name_key,
            'name' => $this->category->name, // Will return translated name based on current locale
        ];
    }

    /**
     * Get visibility rules for the attachment
     */
    protected function getVisibilityRules(): array
    {
        // If visibility is general, return empty array
        if ($this->visibility === 'general') {
            return [];
        }

        // If visibility is custom, return the rules
        // Check if relationship is loaded
        if (!$this->relationLoaded('visibilityRules')) {
            // Try to load it if not loaded
            $this->load('visibilityRules.role');
        }

        // Get visibility rules
        $rules = $this->visibilityRules ?? collect([]);

        return $rules->map(function ($rule) {
            return [
                'id' => $rule->id,
                'role_id' => $rule->role_id,
                'role_name' => $rule->role ? $rule->role->display_name : null, // Translated name based on locale
                'user_id' => $rule->user_id,
            ];
        })->toArray();
    }
}
