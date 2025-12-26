<?php

namespace App\Http\Controllers\Api\Sale;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Sale\StoreSaleRequest;
use App\Http\Requests\Api\Sale\UpdateSaleRequest;
use App\Http\Requests\Api\Sale\StoreSaleItemRequest;
use App\Http\Requests\Api\Sale\UpdateSaleItemRequest;
use App\Http\Requests\Api\Sale\RecordPaymentRequest;
use App\Http\Requests\Api\Sale\QuickSaleRequest;
use App\Http\Requests\Api\Sale\RefundSaleRequest;
use App\Http\Requests\Api\Sale\ApplyDiscountRequest;
use App\Http\Resources\Api\Sale\SaleResource;
use App\Http\Resources\Api\Sale\SaleItemResource;
use App\Services\Api\Sale\SaleService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Get all sales
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');
        $customerId = $request->input('customer_id');
        $employeeId = $request->input('employee_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $search = $request->input('search');

        $result = $this->saleService->getAll(
            $perPage,
            $status,
            $paymentStatus,
            $customerId,
            $employeeId,
            $dateFrom,
            $dateTo,
            $search
        );

        $data = $result['data'];

        return ApiResponse::paginated(
            SaleResource::collection($data->items()),
            $data,
            __('sales.sales_retrieved_successfully')
        );
    }

    /**
     * Get sale by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->saleService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            __('sales.sale_retrieved_successfully')
        );
    }

    /**
     * Create new sale
     */
    public function store(StoreSaleRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Set employee_id from authenticated user
        $data['employee_id'] = auth()->id();

        $result = $this->saleService->create($data);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update sale
     */
    public function update(UpdateSaleRequest $request, int $id): JsonResponse
    {
        $result = $this->saleService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            $result['message']
        );
    }

    /**
     * Cancel sale
     */
    public function cancel(int $id): JsonResponse
    {
        $result = $this->saleService->cancel($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            $result['message']
        );
    }

    /**
     * Get sale items
     */
    public function getItems(int $id): JsonResponse
    {
        $result = $this->saleService->getItems($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            SaleItemResource::collection($result['data']),
            __('sales.items_retrieved_successfully')
        );
    }

    /**
     * Add item to sale
     */
    public function addItem(StoreSaleItemRequest $request, int $id): JsonResponse
    {
        $result = $this->saleService->addItem($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleItemResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update sale item
     */
    public function updateItem(UpdateSaleItemRequest $request, int $id, int $itemId): JsonResponse
    {
        $result = $this->saleService->updateItem($id, $itemId, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleItemResource($result['data']),
            $result['message']
        );
    }

    /**
     * Remove item from sale
     */
    public function removeItem(int $id, int $itemId): JsonResponse
    {
        $result = $this->saleService->removeItem($id, $itemId);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Record payment for sale
     */
    public function recordPayment(RecordPaymentRequest $request, int $id): JsonResponse
    {
        $amount = $request->input('amount');
        $paymentMethod = $request->input('payment_method');

        $result = $this->saleService->recordPayment($id, $amount, $paymentMethod);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            $result['message']
        );
    }

    /**
     * Get sale by invoice number
     */
    public function getByInvoiceNumber(string $invoiceNumber): JsonResponse
    {
        $result = $this->saleService->getByInvoiceNumber($invoiceNumber);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            __('sales.sale_retrieved_successfully')
        );
    }

    /**
     * Quick sale - simplified sale with one product
     */
    public function quickSale(QuickSaleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['employee_id'] = auth()->id();

        $result = $this->saleService->quickSale($data);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Get today's sales summary
     */
    public function todaySummary(): JsonResponse
    {
        $result = $this->saleService->getTodaySummary();

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            $result['data'],
            $result['message']
        );
    }

    /**
     * Refund sale (full or partial)
     */
    public function refund(RefundSaleRequest $request, int $id): JsonResponse
    {
        $items = $request->input('items');
        $refundAmount = $request->input('refund_amount');

        $result = $this->saleService->refund($id, $items, $refundAmount);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            $result['message']
        );
    }

    /**
     * Apply discount to sale
     */
    public function applyDiscount(ApplyDiscountRequest $request, int $id): JsonResponse
    {
        $discount = $request->input('discount');
        $discountType = $request->input('discount_type');

        $result = $this->saleService->applyDiscount($id, $discount, $discountType);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new SaleResource($result['data']),
            $result['message']
        );
    }
}

