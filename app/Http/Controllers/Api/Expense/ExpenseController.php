<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Expense\StoreExpenseRequest;
use App\Http\Requests\Api\Expense\UpdateExpenseRequest;
use App\Http\Resources\Api\Expense\ExpenseResource;
use App\Services\Api\Expense\ExpenseService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Get all expenses
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $category = $request->input('category');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $result = $this->expenseService->getAll($perPage, $category, $dateFrom, $dateTo);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                ExpenseResource::collection($data->items()),
                $data,
                __('expenses.expenses_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            ExpenseResource::collection($data),
            __('expenses.expenses_retrieved_successfully')
        );
    }

    /**
     * Get expenses grouped by category with totals
     */
    public function byCategory(Request $request): JsonResponse
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $result = $this->expenseService->getTotalsByCategory($dateFrom, $dateTo);

        return ApiResponse::success(
            $result['data'],
            __('expenses.expenses_by_category_retrieved_successfully')
        );
    }

    /**
     * Get expense by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->expenseService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ExpenseResource($result['data']),
            __('expenses.expense_retrieved_successfully')
        );
    }

    /**
     * Create new expense
     */
    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $result = $this->expenseService->create($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new ExpenseResource($result['data']),
            $result['message'],
            201
        );
    }

    /**
     * Update expense
     */
    public function update(UpdateExpenseRequest $request, int $id): JsonResponse
    {
        $result = $this->expenseService->update($id, $request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new ExpenseResource($result['data']),
            $result['message']
        );
    }

    /**
     * Delete expense
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->expenseService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }
}
