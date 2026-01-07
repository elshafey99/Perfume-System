<?php

namespace App\Services\Api\Report;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get general sales report
     */
    public function getSalesReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $query = Sale::where('status', '!=', 'cancelled');

        if ($dateFrom) {
            $query->whereDate('sale_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('sale_date', '<=', $dateTo);
        }

        $sales = $query->get();

        $totalSales = $sales->sum('total');
        $totalDiscount = $sales->sum('discount');
        $totalTax = $sales->sum('tax_amount');
        $salesCount = $sales->count();
        $paidAmount = $sales->sum('paid_amount');

        return [
            'success' => true,
            'data' => [
                'total_sales' => round($totalSales, 2),
                'total_discount' => round($totalDiscount, 2),
                'total_tax' => round($totalTax, 2),
                'sales_count' => $salesCount,
                'paid_amount' => round($paidAmount, 2),
                'average_sale' => $salesCount > 0 ? round($totalSales / $salesCount, 2) : 0,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];
    }

    /**
     * Get daily sales report
     */
    public function getDailySalesReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?: Carbon::now()->subDays(30)->toDateString();
        $dateTo = $dateTo ?: Carbon::now()->toDateString();

        $dailySales = Sale::where('status', '!=', 'cancelled')
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo)
            ->select(
                DB::raw('DATE(sale_date) as date'),
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('SUM(paid_amount) as paid_amount'),
                DB::raw('SUM(discount) as total_discount')
            )
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->orderBy('date', 'desc')
            ->get();

        return [
            'success' => true,
            'data' => [
                'daily_sales' => $dailySales,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];
    }

    /**
     * Get monthly sales report
     */
    public function getMonthlySalesReport(?int $year = null): array
    {
        $year = $year ?: Carbon::now()->year;

        $monthlySales = Sale::where('status', '!=', 'cancelled')
            ->whereYear('sale_date', $year)
            ->select(
                DB::raw('MONTH(sale_date) as month'),
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('SUM(paid_amount) as paid_amount'),
                DB::raw('SUM(discount) as total_discount')
            )
            ->groupBy(DB::raw('MONTH(sale_date)'))
            ->orderBy('month')
            ->get();

        return [
            'success' => true,
            'data' => [
                'monthly_sales' => $monthlySales,
                'year' => $year,
            ],
        ];
    }

    /**
     * Get sales by product report
     */
    public function getSalesByProduct(?string $dateFrom = null, ?string $dateTo = null, ?int $limit = 20): array
    {
        $query = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', '!=', 'cancelled');

        if ($dateFrom) {
            $query->whereDate('sales.sale_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('sales.sale_date', '<=', $dateTo);
        }

        $productSales = $query->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total) as total_revenue'),
                DB::raw('COUNT(DISTINCT sales.id) as sales_count')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();

        return [
            'success' => true,
            'data' => [
                'products' => $productSales,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];
    }

    /**
     * Get sales by employee report
     */
    public function getSalesByEmployee(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $query = Sale::join('users', 'sales.employee_id', '=', 'users.id')
            ->where('sales.status', '!=', 'cancelled');

        if ($dateFrom) {
            $query->whereDate('sales.sale_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('sales.sale_date', '<=', $dateTo);
        }

        $employeeSales = $query->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(sales.total) as total_sales'),
                DB::raw('SUM(sales.paid_amount) as paid_amount')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_sales')
            ->get();

        return [
            'success' => true,
            'data' => [
                'employees' => $employeeSales,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];
    }

    /**
     * Get inventory overview report
     */
    public function getInventoryReport(): array
    {
        $products = Product::where('is_active', true)->get();

        $totalProducts = $products->count();
        $totalStock = $products->sum('current_stock');
        $totalValue = $products->sum(function ($product) {
            return $product->current_stock * $product->cost_price;
        });
        $lowStockCount = $products->filter(function ($product) {
            return $product->current_stock <= $product->min_stock_level && $product->min_stock_level > 0;
        })->count();
        $outOfStockCount = $products->filter(function ($product) {
            return $product->current_stock <= 0;
        })->count();

        return [
            'success' => true,
            'data' => [
                'total_products' => $totalProducts,
                'total_stock_quantity' => round($totalStock, 2),
                'total_stock_value' => round($totalValue, 2),
                'low_stock_count' => $lowStockCount,
                'out_of_stock_count' => $outOfStockCount,
            ],
        ];
    }

    /**
     * Get low stock products report
     */
    public function getLowStockReport(?int $limit = 50): array
    {
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('current_stock', '<=', 'min_stock_level')
            ->where('min_stock_level', '>', 0)
            ->select('id', 'name', 'sku', 'current_stock', 'min_stock_level', 'cost_price', 'selling_price')
            ->orderBy('current_stock')
            ->limit($limit)
            ->get();

        return [
            'success' => true,
            'data' => [
                'products' => $lowStockProducts,
                'count' => $lowStockProducts->count(),
            ],
        ];
    }

    /**
     * Get inventory movements report
     */
    public function getInventoryMovementsReport(?string $dateFrom = null, ?string $dateTo = null, ?string $type = null): array
    {
        $query = InventoryTransaction::with(['product:id,name,sku', 'creator:id,name']);

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        if ($type) {
            $query->where('type', $type);
        }

        $movements = $query->orderByDesc('created_at')->limit(100)->get();

        // Summary by type
        $summaryQuery = InventoryTransaction::query();
        if ($dateFrom) {
            $summaryQuery->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $summaryQuery->whereDate('created_at', '<=', $dateTo);
        }

        $summary = $summaryQuery->select(
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy('type')
            ->get();

        return [
            'success' => true,
            'data' => [
                'movements' => $movements,
                'summary' => $summary,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];
    }

    /**
     * Get profit and loss report
     */
    public function getProfitLossReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?: Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $dateTo ?: Carbon::now()->toDateString();

        // Revenue from sales
        $salesRevenue = Sale::where('status', '!=', 'cancelled')
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo)
            ->sum('total');

        // Cost of goods sold
        $costOfGoods = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', '!=', 'cancelled')
            ->whereDate('sales.sale_date', '>=', $dateFrom)
            ->whereDate('sales.sale_date', '<=', $dateTo)
            ->select(DB::raw('SUM(sale_items.quantity * products.cost_price) as total_cost'))
            ->first()
            ->total_cost ?? 0;

        // Expenses
        $expenses = Expense::whereDate('expense_date', '>=', $dateFrom)
            ->whereDate('expense_date', '<=', $dateTo)
            ->sum('amount');

        $grossProfit = $salesRevenue - $costOfGoods;
        $netProfit = $grossProfit - $expenses;

        return [
            'success' => true,
            'data' => [
                'revenue' => round($salesRevenue, 2),
                'cost_of_goods' => round($costOfGoods, 2),
                'gross_profit' => round($grossProfit, 2),
                'expenses' => round($expenses, 2),
                'net_profit' => round($netProfit, 2),
                'profit_margin' => $salesRevenue > 0 ? round(($netProfit / $salesRevenue) * 100, 2) : 0,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];
    }

    /**
     * Get revenue report
     */
    public function getRevenueReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?: Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $dateTo ?: Carbon::now()->toDateString();

        // Revenue by payment method
        $revenueByPayment = Sale::where('status', '!=', 'cancelled')
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo)
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('payment_method')
            ->get();

        // Revenue by status
        $revenueByStatus = Sale::whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo)
            ->select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('status')
            ->get();

        $totalRevenue = Sale::where('status', '!=', 'cancelled')
            ->whereDate('sale_date', '>=', $dateFrom)
            ->whereDate('sale_date', '<=', $dateTo)
            ->sum('total');

        return [
            'success' => true,
            'data' => [
                'total_revenue' => round($totalRevenue, 2),
                'by_payment_method' => $revenueByPayment,
                'by_status' => $revenueByStatus,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];
    }

    /**
     * Get expenses summary report
     */
    public function getExpensesReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?: Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $dateTo ?: Carbon::now()->toDateString();

        // Expenses by category
        $expensesByCategory = Expense::whereDate('expense_date', '>=', $dateFrom)
            ->whereDate('expense_date', '<=', $dateTo)
            ->select(
                'category',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $totalExpenses = Expense::whereDate('expense_date', '>=', $dateFrom)
            ->whereDate('expense_date', '<=', $dateTo)
            ->sum('amount');

        return [
            'success' => true,
            'data' => [
                'total_expenses' => round($totalExpenses, 2),
                'by_category' => $expensesByCategory,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];
    }
}
