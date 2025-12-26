<?php

namespace App\Http\Resources\Api\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'customer_id' => $this->customer_id,
            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'id' => $this->customer->id,
                    'name' => $this->customer->name,
                    'phone' => $this->customer->phone,
                ];
            }),
            'employee_id' => $this->employee_id,
            'employee' => $this->whenLoaded('employee', function () {
                return [
                    'id' => $this->employee->id,
                    'name' => $this->employee->name,
                ];
            }),
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'discount_type' => $this->discount_type,
            'tax_rate' => (float) $this->tax_rate,
            'tax_amount' => (float) $this->tax_amount,
            'total' => (float) $this->total,
            'payment_method' => $this->payment_method,
            'payment_method_display' => $this->getPaymentMethodDisplay(),
            'payment_status' => $this->payment_status,
            'payment_status_display' => $this->getPaymentStatusDisplay(),
            'paid_amount' => (float) $this->paid_amount,
            'remaining_amount' => (float) max(0, $this->total - $this->paid_amount),
            'sale_date' => $this->sale_date?->format('Y-m-d H:i:s'),
            'notes' => $this->notes,
            'status' => $this->status,
            'status_display' => $this->getStatusDisplay(),
            'items' => SaleItemResource::collection($this->whenLoaded('items')),
            'items_count' => $this->whenLoaded('items', fn() => $this->items->count()),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get payment method display name
     */
    private function getPaymentMethodDisplay(): string
    {
        return match ($this->payment_method) {
            'cash' => 'نقدي',
            'card' => 'بطاقة',
            'bank_transfer' => 'تحويل بنكي',
            'apple_pay' => 'Apple Pay',
            'split' => 'دفع متعدد',
            default => $this->payment_method,
        };
    }

    /**
     * Get payment status display name
     */
    private function getPaymentStatusDisplay(): string
    {
        return match ($this->payment_status) {
            'pending' => 'قيد الانتظار',
            'paid' => 'مدفوع',
            'partial' => 'مدفوع جزئياً',
            'refunded' => 'مسترجع',
            default => $this->payment_status,
        };
    }

    /**
     * Get status display name
     */
    private function getStatusDisplay(): string
    {
        return match ($this->status) {
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            'refunded' => 'مسترجع',
            default => $this->status,
        };
    }
}
