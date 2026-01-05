<?php

namespace App\Repositories\Api\Expense;

use App\Models\Expense;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ExpenseRepository
{
    /**
     * Get all expenses with pagination
     */
    public function getAll(int $perPage = 15, ?string $category = null, ?string $dateFrom = null, ?string $dateTo = null): LengthAwarePaginator
    {
        $query = Expense::with('creator')->orderBy('expense_date', 'desc');

        if ($category !== null) {
            $query->where('category', $category);
        }

        if ($dateFrom !== null) {
            $query->whereDate('expense_date', '>=', $dateFrom);
        }

        if ($dateTo !== null) {
            $query->whereDate('expense_date', '<=', $dateTo);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get all expenses without pagination
     */
    public function getAllWithoutPagination(?string $category = null): Collection
    {
        $query = Expense::with('creator')->orderBy('expense_date', 'desc');

        if ($category !== null) {
            $query->where('category', $category);
        }

        return $query->get();
    }

    /**
     * Get expenses by category
     */
    public function getByCategory(string $category): Collection
    {
        return Expense::with('creator')
            ->where('category', $category)
            ->orderBy('expense_date', 'desc')
            ->get();
    }

    /**
     * Get total expenses grouped by category
     */
    public function getTotalsByCategory(?string $dateFrom = null, ?string $dateTo = null): Collection
    {
        $query = Expense::selectRaw('category, SUM(amount) as total_amount, COUNT(*) as count');

        if ($dateFrom !== null) {
            $query->whereDate('expense_date', '>=', $dateFrom);
        }

        if ($dateTo !== null) {
            $query->whereDate('expense_date', '<=', $dateTo);
        }

        return $query->groupBy('category')->get();
    }

    /**
     * Find expense by ID
     */
    public function findById(int $id): ?Expense
    {
        return Expense::with('creator')->find($id);
    }

    /**
     * Create new expense
     */
    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    /**
     * Update expense
     */
    public function update(Expense $expense, array $data): bool
    {
        return $expense->update($data);
    }

    /**
     * Delete expense
     */
    public function delete(Expense $expense): bool
    {
        return $expense->delete();
    }
}
