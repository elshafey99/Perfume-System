<?php

namespace App\Services\Api\SupplierPayment;

use App\Repositories\Api\SupplierPayment\SupplierPaymentRepository;
use App\Models\Supplier;

class SupplierPaymentService
{
    protected SupplierPaymentRepository $supplierPaymentRepository;

    public function __construct(SupplierPaymentRepository $supplierPaymentRepository)
    {
        $this->supplierPaymentRepository = $supplierPaymentRepository;
    }

    /**
     * Get all payments for a supplier
     */
    public function getSupplierPayments(int $supplierId, ?string $type = null, int $perPage = 15): array
    {
        $payments = $this->supplierPaymentRepository->getBySupplier($supplierId, $type, $perPage);

        return [
            'success' => true,
            'data' => $payments,
        ];
    }

    /**
     * Add a new payment for a supplier
     */
    public function addPayment(int $supplierId, array $data): array
    {
        try {
            // Verify supplier exists
            $supplier = Supplier::find($supplierId);
            if (!$supplier) {
                return [
                    'success' => false,
                    'message' => 'المورد غير موجود',
                ];
            }

            // Add supplier_id and created_by
            $data['supplier_id'] = $supplierId;
            $data['created_by'] = auth()->id();

            $payment = $this->supplierPaymentRepository->create($data);

            return [
                'success' => true,
                'data' => $payment,
                'message' => 'تم إضافة الدفعة بنجاح',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'فشل إضافة الدفعة: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get supplier statement with running balance
     */
    public function getStatement(int $supplierId): array
    {
        $supplier = Supplier::find($supplierId);
        if (!$supplier) {
            return [
                'success' => false,
                'message' => 'المورد غير موجود',
            ];
        }

        $statement = $this->supplierPaymentRepository->getStatement($supplierId);

        return [
            'success' => true,
            'data' => [
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'current_balance' => $supplier->balance_due,
                ],
                'statement' => $statement,
            ],
        ];
    }

    /**
     * Get supplier balance summary
     */
    public function getBalance(int $supplierId): array
    {
        $supplier = Supplier::find($supplierId);
        if (!$supplier) {
            return [
                'success' => false,
                'message' => 'المورد غير موجود',
            ];
        }

        return [
            'success' => true,
            'data' => [
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'total_purchases' => $supplier->total_purchases,
                'total_paid' => $supplier->total_paid,
                'balance_due' => $supplier->balance_due,
            ],
        ];
    }
}
