<?php

namespace App\Http\Resources\Api\Expense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\FileHelper;

class ExpenseResource extends JsonResource
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
            'category' => $this->category,
            'category_display' => $this->getCategoryDisplay(),
            'amount' => (float) $this->amount,
            'description' => $this->description,
            'expense_date' => $this->expense_date?->format('Y-m-d'),
            'receipt_image' => FileHelper::url($this->receipt_image),
            'created_by' => $this->created_by,
            'creator' => $this->when($this->relationLoaded('creator') && $this->creator, function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get the display name for the category
     */
    protected function getCategoryDisplay(): string
    {
        $categories = [
            'rent' => 'إيجار',
            'salaries' => 'رواتب',
            'electricity' => 'كهرباء',
            'shipping' => 'شحن',
            'marketing' => 'تسويق',
            'maintenance' => 'صيانة',
            'other' => 'أخرى',
        ];

        return $categories[$this->category] ?? $this->category;
    }
}
