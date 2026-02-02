<?php

namespace App\Services\Api\DailyClosing;

use App\Repositories\Api\DailyClosing\DailyClosingRepository;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;

class DailyClosingService
{
    protected DailyClosingRepository $dailyClosingRepository;

    public function __construct(DailyClosingRepository $dailyClosingRepository)
    {
        $this->dailyClosingRepository = $dailyClosingRepository;
    }

    /**
     * Get all daily closings
     */
    public function getAllClosings(int $perPage = 15): array
    {
        $closings = $this->dailyClosingRepository->getAll($perPage);

        return [
            'success' => true,
            'data' => $closings,
        ];
    }

    /**
     * Get today's data before closing
     */
    public function getTodayData(): array
    {
        // Check if today is already closed
        $existingClosing = $this->dailyClosingRepository->getTodayClosing();
        
        if ($existingClosing) {
            return [
                'success' => false,
                'message' => 'اليوم تم إقفاله بالفعل',
                'data' => $existingClosing,
            ];
        }

        $summary = $this->calculateDailySummary(Carbon::today());

        return [
            'success' => true,
            'data' => $summary,
            'message' => 'بيانات اليوم الحالي',
        ];
    }

    /**
     * Close the current day
     */
    public function closeDay(array $data): array
    {
        try {
            // Check if today is already closed
            $existingClosing = $this->dailyClosingRepository->getTodayClosing();
            
            if ($existingClosing) {
                return [
                    'success' => false,
                    'message' => 'لا يمكن إقفال اليوم مرتين',
                ];
            }

            // Calculate today's summary
            $summary = $this->calculateDailySummary(Carbon::today());

            // Prepare closing data
            $closingData = [
                'closing_date' => Carbon::today()->toDateString(),
                'closed_by' => auth()->id(),
                'total_sales' => $summary['total_sales'],
                'total_cash' => $summary['total_cash'],
                'total_card' => $summary['total_card'],
                'total_invoices' => $summary['total_invoices'],
                'total_refunds' => $summary['total_refunds'],
                'total_expenses' => $summary['total_expenses'],
                'notes' => $data['notes'] ?? null,
            ];

            $closing = $this->dailyClosingRepository->create($closingData);

            // Load the relationship
            $closing->load('closedByUser');

            return [
                'success' => true,
                'data' => $closing,
                'message' => 'تم إقفال اليوم بنجاح',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'فشل إقفال اليوم: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get closing by date
     */
    public function getByDate(string $date): array
    {
        $closing = $this->dailyClosingRepository->findByDate($date);

        if (!$closing) {
            return [
                'success' => false,
                'message' => 'لم يتم العثور على إقفال لهذا التاريخ',
            ];
        }

        return [
            'success' => true,
            'data' => $closing,
        ];
    }

    /**
     * Get closing by ID
     */
    public function getById(int $id): array
    {
        $closing = $this->dailyClosingRepository->find($id);

        if (!$closing) {
            return [
                'success' => false,
                'message' => 'لم يتم العثور على الإقفال',
            ];
        }

        return [
            'success' => true,
            'data' => $closing,
        ];
    }

    /**
     * Calculate daily summary for a specific date
     */
    protected function calculateDailySummary(Carbon $date): array
    {
        // Get sales for the day
        $sales = Sale::whereDate('created_at', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        // Get expenses for the day
        $expenses = Expense::whereDate('created_at', $date)->get();

        // Calculate totals
        $totalSales = $sales->sum('total');
        $totalCash = $sales->where('payment_method', 'cash')->sum('total');
        $totalCard = $sales->where('payment_method', 'card')->sum('total');
        $totalInvoices = $sales->count();
        
        // Get refunds (cancelled or refunded sales)
        $refunds = Sale::whereDate('created_at', $date)
            ->where('status', 'cancelled')
            ->get();
        $totalRefunds = $refunds->sum('total');

        $totalExpenses = $expenses->sum('amount');

        return [
            'total_sales' => (float) $totalSales,
            'total_cash' => (float) $totalCash,
            'total_card' => (float) $totalCard,
            'total_invoices' => $totalInvoices,
            'total_refunds' => (float) $totalRefunds,
            'total_expenses' => (float) $totalExpenses,
            'net_total' => (float) ($totalSales - $totalRefunds - $totalExpenses),
        ];
    }
}
