<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\StoreCustomerRequest;
use App\Http\Requests\Api\Customer\UpdateCustomerRequest;
use App\Http\Requests\Api\Customer\UpdatePreferencesRequest;
use App\Http\Requests\Api\Customer\LoyaltyPointsRequest;
use App\Http\Resources\Api\Customer\CustomerResource;
use App\Http\Resources\Api\Customer\LoyaltyPointResource;
use App\Http\Resources\Api\Sale\SaleResource;
use App\Services\Api\Customer\CustomerService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Get all customers
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $loyaltyLevel = $request->input('loyalty_level');
        $isActive = $request->has('is_active') ? filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN) : null;
        $search = $request->input('search');

        $result = $this->customerService->getAll($perPage, $loyaltyLevel, $isActive, $search);
        $data = $result['data'];

        return ApiResponse::paginated(
            CustomerResource::collection($data->items()),
            $data,
            __('customers.customers_retrieved_successfully')
        );
    }

    /**
     * Get customer by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->customerService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new CustomerResource($result['data']),
            __('customers.customer_retrieved_successfully')
        );
    }

    /**
     * Create new customer
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $result = $this->customerService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new CustomerResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update customer
     */
    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        $result = $this->customerService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new CustomerResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete customer
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->customerService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Search customers by phone
     */
    public function searchByPhone(Request $request): JsonResponse
    {
        $phone = $request->input('phone', '');

        if (strlen($phone) < 3) {
            return ApiResponse::error(__('customers.phone_too_short'), 400);
        }

        $result = $this->customerService->searchByPhone($phone);

        return ApiResponse::success(
            CustomerResource::collection($result['data']),
            __('customers.customers_retrieved_successfully')
        );
    }

    /**
     * Get customer sales history
     */
    public function getSalesHistory(Request $request, int $id): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $result = $this->customerService->getSalesHistory($id, $perPage);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        $data = $result['data'];

        return ApiResponse::paginated(
            SaleResource::collection($data->items()),
            $data,
            __('customers.sales_history_retrieved_successfully')
        );
    }

    /**
     * Get customer preferences
     */
    public function getPreferences(int $id): JsonResponse
    {
        $result = $this->customerService->getPreferences($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            $result['data'],
            __('customers.preferences_retrieved_successfully')
        );
    }

    /**
     * Update customer preferences
     */
    public function updatePreferences(UpdatePreferencesRequest $request, int $id): JsonResponse
    {
        $result = $this->customerService->updatePreferences($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            $result['data'],
            $result['message']
        );
    }

    /**
     * Get loyalty points balance
     */
    public function getLoyaltyBalance(int $id): JsonResponse
    {
        $result = $this->customerService->getLoyaltyBalance($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            $result['data'],
            __('customers.loyalty_balance_retrieved_successfully')
        );
    }

    /**
     * Get loyalty points history
     */
    public function getLoyaltyHistory(Request $request, int $id): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $result = $this->customerService->getLoyaltyHistory($id, $perPage);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        $data = $result['data'];

        return ApiResponse::paginated(
            LoyaltyPointResource::collection($data->items()),
            $data,
            __('customers.loyalty_history_retrieved_successfully')
        );
    }

    /**
     * Earn loyalty points
     */
    public function earnPoints(LoyaltyPointsRequest $request, int $id): JsonResponse
    {
        $points = $request->input('points');
        $notes = $request->input('notes');

        $result = $this->customerService->earnPoints($id, $points, $notes);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            $result['data'],
            $result['message']
        );
    }

    /**
     * Redeem loyalty points
     */
    public function redeemPoints(LoyaltyPointsRequest $request, int $id): JsonResponse
    {
        $points = $request->input('points');
        $notes = $request->input('notes');

        $result = $this->customerService->redeemPoints($id, $points, $notes);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            $result['data'],
            $result['message']
        );
    }
}
