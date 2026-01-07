<?php

namespace App\Services\Api\Dashboard;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\ProductReturn;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get general dashboard statistics
     */
    public function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Today's stats
        $todaySales = Sale::where('status', '!=', 'cancelled')
            ->whereDate('sale_date', $today)
            ->sum('total');

        $todaySalesCount = Sale::where('status', '!=', 'cancelled')
            ->whereDate('sale_date', $today)
            ->count();

        // This month's stats
        $monthSales = Sale::where('status', '!=', 'cancelled')
            ->whereDate('sale_date', '>=', $thisMonth)
            ->sum('total');

        $monthExpenses = Expense::whereDate('expense_date', '>=', $thisMonth)
            ->sum('amount');

        // General counts
        $totalProducts = Product::where('is_active', true)->count();
        $totalCustomers = Customer::count();
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock_level')
            ->where('min_stock_level', '>', 0)
            ->count();

        // Pending orders
        $pendingReturns = ProductReturn::where('status', 'pending')->count();
        $pendingPurchases = Purchase::where('status', 'pending')->count();

        return [
            'success' => true,
            'data' => [
                'today' => [
                    'sales_total' => round($todaySales, 2),
                    'sales_count' => $todaySalesCount,
                ],
                'this_month' => [
                    'sales_total' => round($monthSales, 2),
                    'expenses_total' => round($monthExpenses, 2),
                    'net_profit' => round($monthSales - $monthExpenses, 2),
                ],
                'counts' => [
                    'total_products' => $totalProducts,
                    'total_customers' => $totalCustomers,
                    'low_stock_products' => $lowStockProducts,
                    'pending_returns' => $pendingReturns,
                    'pending_purchases' => $pendingPurchases,
                ],
            ],
        ];
    }

    /**
     * Get today's sales summary
     */
    public function getSalesToday(): array
    {
        $today = Carbon::today();

        $sales = Sale::with(['customer:id,name', 'employee:id,name'])
            ->where('status', '!=', 'cancelled')
            ->whereDate('sale_date', $today)
            ->orderByDesc('created_at')
            ->get();

        $totalSales = $sales->sum('total');
        $totalPaid = $sales->sum('paid_amount');

        // By payment method
        $byPaymentMethod = $sales->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => round($group->sum('total'), 2),
            ];
        });

        // Hourly breakdown
        $hourlyBreakdown = $sales->groupBy(function ($sale) {
            return Carbon::parse($sale->sale_date)->format('H');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => round($group->sum('total'), 2),
            ];
        });

        return [
            'success' => true,
            'data' => [
                'total_sales' => round($totalSales, 2),
                'total_paid' => round($totalPaid, 2),
                'sales_count' => $sales->count(),
                'average_sale' => $sales->count() > 0 ? round($totalSales / $sales->count(), 2) : 0,
                'by_payment_method' => $byPaymentMethod,
                'hourly_breakdown' => $hourlyBreakdown,
                'recent_sales' => $sales->take(10),
            ],
        ];
    }

    /**
     * Get top selling products
     */
    public function getTopProducts(?int $limit = 10, ?string $period = 'month'): array
    {
        $dateFrom = match ($period) {
            'today' => Carbon::today(),
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $topProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', '!=', 'cancelled')
            ->whereDate('sales.sale_date', '>=', $dateFrom)
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'products.image',
                'products.selling_price',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total) as total_revenue'),
                DB::raw('COUNT(DISTINCT sales.id) as times_sold')
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.image', 'products.selling_price')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();

        return [
            'success' => true,
            'data' => [
                'products' => $topProducts,
                'period' => $period,
                'date_from' => $dateFrom->toDateString(),
            ],
        ];
    }

    /**
     * Get top customers
     */
    public function getTopCustomers(?int $limit = 10, ?string $period = 'month'): array
    {
        $dateFrom = match ($period) {
            'today' => Carbon::today(),
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $topCustomers = Sale::join('customers', 'sales.customer_id', '=', 'customers.id')
            ->where('sales.status', '!=', 'cancelled')
            ->whereDate('sales.sale_date', '>=', $dateFrom)
            ->select(
                'customers.id',
                'customers.name',
                'customers.phone',
                'customers.loyalty_points',
                DB::raw('COUNT(*) as purchases_count'),
                DB::raw('SUM(sales.total) as total_spent')
            )
            ->groupBy('customers.id', 'customers.name', 'customers.phone', 'customers.loyalty_points')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get();

        return [
            'success' => true,
            'data' => [
                'customers' => $topCustomers,
                'period' => $period,
                'date_from' => $dateFrom->toDateString(),
            ],
        ];
    }
}
