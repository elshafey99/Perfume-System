<?php

namespace App\Http\Resources\Api\DailyClosing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyClosingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'closing_date' => $this->closing_date?->format('Y-m-d'),
            'total_sales' => (float) $this->total_sales,
            'total_cash' => (float) $this->total_cash,
            'total_card' => (float) $this->total_card,
            'total_invoices' => $this->total_invoices,
            'total_refunds' => (float) $this->total_refunds,
            'total_expenses' => (float) $this->total_expenses,
            'net_total' => (float) ($this->total_sales - $this->total_refunds - $this->total_expenses),
            'notes' => $this->notes,
            'closed_by' => $this->whenLoaded('closedByUser', function () {
                return [
                    'id' => $this->closedByUser->id,
                    'name' => $this->closedByUser->name,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
