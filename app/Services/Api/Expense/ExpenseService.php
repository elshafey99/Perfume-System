<?php

namespace App\Services\Api\Expense;

use App\Repositories\Api\Expense\ExpenseRepository;
use App\Models\Expense;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;

class ExpenseService
{
    protected ExpenseRepository $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Get all expenses
     */
    public function getAll(?int $perPage = null, ?string $category = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        if ($perPage) {
            $expenses = $this->expenseRepository->getAll($perPage, $category, $dateFrom, $dateTo);
        } else {
            $expenses = $this->expenseRepository->getAllWithoutPagination($category);
        }

        return [
            'success' => true,
            'data' => $expenses,
        ];
    }

    /**
     * Get expenses by category
     */
    public function getByCategory(string $category): array
    {
        $expenses = $this->expenseRepository->getByCategory($category);

        return [
            'success' => true,
            'data' => $expenses,
        ];
    }

    /**
     * Get totals by category
     */
    public function getTotalsByCategory(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $totals = $this->expenseRepository->getTotalsByCategory($dateFrom, $dateTo);

        return [
            'success' => true,
            'data' => $totals,
        ];
    }

    /**
     * Get expense by ID
     */
    public function getById(int $id): array
    {
        $expense = $this->expenseRepository->findById($id);

        if (!$expense) {
            return [
                'success' => false,
                'message' => __('expenses.expense_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $expense,
        ];
    }

    /**
     * Create new expense
     */
    public function create(array $data): array
    {
        try {
            // Handle receipt image upload
            if (isset($data['receipt_image']) && $data['receipt_image']) {
                $data['receipt_image'] = FileHelper::uploadImage($data['receipt_image'], 'uploads/expenses');
            }

            // Set created_by to current user
            $data['created_by'] = Auth::id();

            $expense = $this->expenseRepository->create($data);

            return [
                'success' => true,
                'data' => $expense->load('creator'),
                'message' => __('expenses.expense_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('expenses.expense_creation_failed'),
            ];
        }
    }

    /**
     * Update expense
     */
    public function update(int $id, array $data): array
    {
        $expense = $this->expenseRepository->findById($id);

        if (!$expense) {
            return [
                'success' => false,
                'message' => __('expenses.expense_not_found'),
            ];
        }

        try {
            // Handle receipt image upload
            if (isset($data['receipt_image']) && $data['receipt_image']) {
                $data['receipt_image'] = FileHelper::updateFile(
                    $data['receipt_image'],
                    $expense->receipt_image,
                    'uploads/expenses'
                );
            }

            $this->expenseRepository->update($expense, $data);

            return [
                'success' => true,
                'data' => $expense->fresh()->load('creator'),
                'message' => __('expenses.expense_updated_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('expenses.expense_update_failed'),
            ];
        }
    }

    /**
     * Delete expense
     */
    public function delete(int $id): array
    {
        $expense = $this->expenseRepository->findById($id);

        if (!$expense) {
            return [
                'success' => false,
                'message' => __('expenses.expense_not_found'),
            ];
        }

        try {
            // Delete receipt image if exists
            if ($expense->receipt_image) {
                FileHelper::delete($expense->receipt_image);
            }

            $this->expenseRepository->delete($expense);

            return [
                'success' => true,
                'message' => __('expenses.expense_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('expenses.expense_deletion_failed'),
            ];
        }
    }
}
