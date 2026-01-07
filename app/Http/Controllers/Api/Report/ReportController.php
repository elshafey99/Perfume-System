<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Services\Api\Report\ReportService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get general sales report
     */
    public function sales(Request $request): JsonResponse
    {
        $result = $this->reportService->getSalesReport(
            $request->input('date_from'),
            $request->input('date_to')
        );

        return ApiResponse::success($result['data'], __('reports.sales_report_generated'));
    }

    /**
     * Get daily sales report
     */
    public function dailySales(Request $request): JsonResponse
    {
        $result = $this->reportService->getDailySalesReport(
            $request->input('date_from'),
            $request->input('date_to')
        );

        return ApiResponse::success($result['data'], __('reports.daily_sales_report_generated'));
    }

    /**
     * Get monthly sales report
     */
    public function monthlySales(Request $request): JsonResponse
    {
        $result = $this->reportService->getMonthlySalesReport(
            $request->input('year')
        );

        return ApiResponse::success($result['data'], __('reports.monthly_sales_report_generated'));
    }

    /**
     * Get sales by product report
     */
    public function salesByProduct(Request $request): JsonResponse
    {
        $result = $this->reportService->getSalesByProduct(
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('limit', 20)
        );

        return ApiResponse::success($result['data'], __('reports.sales_by_product_report_generated'));
    }

    /**
     * Get sales by employee report
     */
    public function salesByEmployee(Request $request): JsonResponse
    {
        $result = $this->reportService->getSalesByEmployee(
            $request->input('date_from'),
            $request->input('date_to')
        );

        return ApiResponse::success($result['data'], __('reports.sales_by_employee_report_generated'));
    }

    /**
     * Get inventory overview report
     */
    public function inventory(): JsonResponse
    {
        $result = $this->reportService->getInventoryReport();

        return ApiResponse::success($result['data'], __('reports.inventory_report_generated'));
    }

    /**
     * Get low stock products report
     */
    public function lowStock(Request $request): JsonResponse
    {
        $result = $this->reportService->getLowStockReport(
            $request->input('limit', 50)
        );

        return ApiResponse::success($result['data'], __('reports.low_stock_report_generated'));
    }

    /**
     * Get inventory movements report
     */
    public function inventoryMovements(Request $request): JsonResponse
    {
        $result = $this->reportService->getInventoryMovementsReport(
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('type')
        );

        return ApiResponse::success($result['data'], __('reports.inventory_movements_report_generated'));
    }

    /**
     * Get profit and loss report
     */
    public function profitLoss(Request $request): JsonResponse
    {
        $result = $this->reportService->getProfitLossReport(
            $request->input('date_from'),
            $request->input('date_to')
        );

        return ApiResponse::success($result['data'], __('reports.profit_loss_report_generated'));
    }

    /**
     * Get revenue report
     */
    public function revenue(Request $request): JsonResponse
    {
        $result = $this->reportService->getRevenueReport(
            $request->input('date_from'),
            $request->input('date_to')
        );

        return ApiResponse::success($result['data'], __('reports.revenue_report_generated'));
    }

    /**
     * Get expenses summary report
     */
    public function expenses(Request $request): JsonResponse
    {
        $result = $this->reportService->getExpensesReport(
            $request->input('date_from'),
            $request->input('date_to')
        );

        return ApiResponse::success($result['data'], __('reports.expenses_report_generated'));
    }
}
