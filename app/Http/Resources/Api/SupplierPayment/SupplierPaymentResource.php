<?php

namespace App\Http\Resources\Api\SupplierPayment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->whenLoaded('supplier', fn() => $this->supplier->name),
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'amount' => (float) $this->amount,
            'payment_method' => $this->payment_method,
            'payment_method_label' => $this->getPaymentMethodLabel(),
            'payment_date' => $this->payment_date,
            'notes' => $this->notes,
            'created_by' => $this->whenLoaded('creator', function () {
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
     * Get type label in Arabic
     */
    protected function getTypeLabel(): string
    {
        return match($this->type) {
            'purchase' => 'فاتورة شراء',
            'payment' => 'دفعة',
            'refund' => 'مرتجع',
            'opening_balance' => 'رصيد افتتاحي',
            default => $this->type,
        };
    }

    /**
     * Get payment method label in Arabic
     */
    protected function getPaymentMethodLabel(): ?string
    {
        if (!$this->payment_method) {
            return null;
        }

        return match($this->payment_method) {
            'cash' => 'نقدي',
            'card' => 'بطاقة',
            'bank_transfer' => 'تحويل بنكي',
            'cheque' => 'شيك',
            default => $this->payment_method,
        };
    }
}
