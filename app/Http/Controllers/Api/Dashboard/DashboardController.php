<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Api\Dashboard\DashboardService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get general dashboard statistics
     */
    public function stats(): JsonResponse
    {
        $result = $this->dashboardService->getStats();

        return ApiResponse::success($result['data'], __('reports.dashboard_stats_generated'));
    }

    /**
     * Get today's sales summary
     */
    public function salesToday(): JsonResponse
    {
        $result = $this->dashboardService->getSalesToday();

        return ApiResponse::success($result['data'], __('reports.sales_today_generated'));
    }

    /**
     * Get top selling products
     */
    public function topProducts(Request $request): JsonResponse
    {
        $result = $this->dashboardService->getTopProducts(
            $request->input('limit', 10),
            $request->input('period', 'month')
        );

        return ApiResponse::success($result['data'], __('reports.top_products_generated'));
    }

    /**
     * Get top customers
     */
    public function topCustomers(Request $request): JsonResponse
    {
        $result = $this->dashboardService->getTopCustomers(
            $request->input('limit', 10),
            $request->input('period', 'month')
        );

        return ApiResponse::success($result['data'], __('reports.top_customers_generated'));
    }
}
