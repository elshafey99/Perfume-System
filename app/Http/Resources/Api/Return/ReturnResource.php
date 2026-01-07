<?php

namespace App\Http\Resources\Api\Return;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnResource extends JsonResource
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
            'return_number' => $this->return_number,
            'sale' => $this->whenLoaded('sale', function () {
                return [
                    'id' => $this->sale->id,
                    'invoice_number' => $this->sale->invoice_number,
                    'total_amount' => $this->sale->total_amount,
                    'customer_name' => $this->sale->customer?->name,
                ];
            }),
            'sale_item' => $this->whenLoaded('saleItem', function () {
                if (!$this->saleItem) return null;
                return [
                    'id' => $this->saleItem->id,
                    'product_name' => $this->saleItem->product?->name,
                    'quantity' => $this->saleItem->quantity,
                    'unit_price' => $this->saleItem->unit_price,
                    'total_price' => $this->saleItem->total_price,
                ];
            }),
            'return_reason' => $this->return_reason,
            // 'return_reason_label' => $this->getReturnReasonLabel(),
            'return_type' => $this->return_type,
            // 'return_type_label' => $this->getReturnTypeLabel(),
            'return_amount' => (float) $this->return_amount,
            'status' => $this->status,
            // 'status_label' => $this->getStatusLabel(),
            'notes' => $this->notes,
            'processor' => $this->whenLoaded('processor', function () {
                if (!$this->processor) return null;
                return [
                    'id' => $this->processor->id,
                    'name' => $this->processor->name,
                ];
            }),
            'processed_at' => $this->processed_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    // /**
    //  * Get localized return reason label
    //  */
    // protected function getReturnReasonLabel(): string
    // {
    //     $labels = [
    //         'defective' => __('returns.reasons.defective'),
    //         'wrong_item' => __('returns.reasons.wrong_item'),
    //         'customer_request' => __('returns.reasons.customer_request'),
    //         'other' => __('returns.reasons.other'),
    //     ];

    //     return $labels[$this->return_reason] ?? $this->return_reason;
    // }

    // /**
    //  * Get localized return type label
    //  */
    // protected function getReturnTypeLabel(): string
    // {
    //     $labels = [
    //         'refund' => __('returns.types.refund'),
    //         'exchange' => __('returns.types.exchange'),
    //         'store_credit' => __('returns.types.store_credit'),
    //     ];

    //     return $labels[$this->return_type] ?? $this->return_type;
    // }

    // /**
    //  * Get localized status label
    //  */
    // protected function getStatusLabel(): string
    // {
    //     $labels = [
    //         'pending' => __('returns.statuses.pending'),
    //         'approved' => __('returns.statuses.approved'),
    //         'rejected' => __('returns.statuses.rejected'),
    //         'completed' => __('returns.statuses.completed'),
    //     ];

    //     return $labels[$this->status] ?? $this->status;
    // }
}
