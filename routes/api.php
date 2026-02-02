<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Supplier\SupplierController;
use App\Http\Controllers\Api\UnitType\UnitTypeController;
use App\Http\Controllers\Api\ProductType\ProductTypeController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\ProductPrintController;
use App\Http\Controllers\Api\InventoryTransaction\InventoryTransactionController;
use App\Http\Controllers\Api\Stocktaking\StocktakingController;
use App\Http\Controllers\Api\Composition\CompositionController;
use App\Http\Controllers\Api\Composition\CompositionIngredientController;
use App\Http\Controllers\Api\Sale\SaleController;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\Purchase\PurchaseController;
use App\Http\Controllers\Api\Expense\ExpenseController;
use App\Http\Controllers\Api\Return\ReturnController;
use App\Http\Controllers\Api\Notification\NotificationController;
use App\Http\Controllers\Api\Report\ReportController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Setting\SettingController;
use App\Http\Controllers\Api\DailyClosing\DailyClosingController;
use Illuminate\Support\Facades\Route;

// Auth Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/resend-code', [AuthController::class, 'resendCode']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});


     Route::get('users', [UserController::class, 'index']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'profile']);
        Route::post('/', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
    });

    // Categories Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/parents', [CategoryController::class, 'parents']);
        Route::get('/parent/{parentId}', [CategoryController::class, 'byParent']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // Roles Routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/permissions', [RoleController::class, 'permissions']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::patch('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    // Users Routes (Employees & Admins)
    Route::prefix('users')->group(function () {
       // Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/{id}', [UserController::class, 'update']);
        Route::post('/{id}/status', [UserController::class, 'changeStatus']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Suppliers Routes
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{id}', [SupplierController::class, 'show']);
        Route::put('/{id}', [SupplierController::class, 'update']);
        Route::patch('/{id}', [SupplierController::class, 'update']);
        Route::delete('/{id}', [SupplierController::class, 'destroy']);
        
        // Supplier Payments Routes
        Route::get('/{id}/payments', [SupplierController::class, 'getPayments']);
        Route::post('/{id}/payments', [SupplierController::class, 'addPayment']);
        Route::get('/{id}/statement', [SupplierController::class, 'getStatement']);
        Route::get('/{id}/balance', [SupplierController::class, 'getBalance']);
    });

    // Unit Types Routes
    Route::prefix('unit-types')->group(function () {
        Route::get('/', [UnitTypeController::class, 'index']);
        Route::post('/', [UnitTypeController::class, 'store']);
        Route::get('/{id}', [UnitTypeController::class, 'show']);
        Route::put('/{id}', [UnitTypeController::class, 'update']);
        Route::patch('/{id}', [UnitTypeController::class, 'update']);
        Route::delete('/{id}', [UnitTypeController::class, 'destroy']);
    });

    // Product Types Routes
    Route::prefix('product-types')->group(function () {
        Route::get('/', [ProductTypeController::class, 'index']);
        Route::post('/', [ProductTypeController::class, 'store']);
        Route::get('/{id}', [ProductTypeController::class, 'show']);
        Route::put('/{id}', [ProductTypeController::class, 'update']);
        Route::patch('/{id}', [ProductTypeController::class, 'update']);
        Route::delete('/{id}', [ProductTypeController::class, 'destroy']);
    });

    // Products Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/low-stock', [ProductController::class, 'getLowStock']);
        Route::get('/barcode/{barcode}', [ProductController::class, 'getByBarcode']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::patch('/{id}', [ProductController::class, 'update']);
        Route::put('/{id}/stock', [ProductController::class, 'updateStock']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        
        // QR Code & Print Routes
        Route::get('/{id}/qr-code', [ProductPrintController::class, 'generateQRCode']);
        Route::get('/{id}/print-label', [ProductPrintController::class, 'printLabel']);
    });

    // Inventory Transactions Routes
    Route::prefix('inventory-transactions')->group(function () {
        Route::get('/', [InventoryTransactionController::class, 'index']);
        Route::get('/product/{productId}', [InventoryTransactionController::class, 'getByProductId']);
        Route::post('/', [InventoryTransactionController::class, 'store']);
        Route::get('/{id}', [InventoryTransactionController::class, 'show']);
        Route::delete('/{id}', [InventoryTransactionController::class, 'destroy']);
    });

    // Stocktakings Routes
    Route::prefix('stocktakings')->group(function () {
        Route::get('/', [StocktakingController::class, 'index']);
        Route::post('/', [StocktakingController::class, 'store']);
        Route::get('/{id}', [StocktakingController::class, 'show']);
        Route::get('/{id}/items', [StocktakingController::class, 'getItems']);
        Route::post('/{id}/items', [StocktakingController::class, 'addItem']);
        Route::post('/{id}/complete', [StocktakingController::class, 'complete']);
        Route::delete('/{id}', [StocktakingController::class, 'destroy']);
    });

    // Compositions Routes
    Route::prefix('compositions')->group(function () {
        Route::get('/', [CompositionController::class, 'index']);
        Route::get('/magic-recipes', [CompositionController::class, 'getMagicRecipes']);
        Route::post('/', [CompositionController::class, 'store']);
        Route::get('/{id}', [CompositionController::class, 'show']);
        Route::put('/{id}', [CompositionController::class, 'update']);
        Route::patch('/{id}', [CompositionController::class, 'update']);
        Route::post('/{id}/calculate-cost', [CompositionController::class, 'calculateCost']);
        Route::delete('/{id}', [CompositionController::class, 'destroy']);

        // Composition Ingredients Routes
        Route::get('/{id}/ingredients', [CompositionController::class, 'getIngredients']);
        Route::post('/{id}/ingredients', [CompositionIngredientController::class, 'store']);
        Route::put('/{id}/ingredients/{ingredientId}', [CompositionIngredientController::class, 'update']);
        Route::patch('/{id}/ingredients/{ingredientId}', [CompositionIngredientController::class, 'update']);
        Route::delete('/{id}/ingredients/{ingredientId}', [CompositionIngredientController::class, 'destroy']);
    });

    // Sales Routes
    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index']);
        Route::post('/', [SaleController::class, 'store']);
        Route::get('/today', [SaleController::class, 'todaySummary']);
        Route::post('/quick', [SaleController::class, 'quickSale']);
        Route::post('/composition-sale', [SaleController::class, 'compositionSale']);
        Route::post('/custom-blend', [SaleController::class, 'customBlend']);
        Route::get('/invoice/{invoiceNumber}', [SaleController::class, 'getByInvoiceNumber']);
        Route::get('/{id}', [SaleController::class, 'show']);
        Route::put('/{id}', [SaleController::class, 'update']);
        Route::post('/{id}/cancel', [SaleController::class, 'cancel']);
        Route::post('/{id}/refund', [SaleController::class, 'refund']);
        Route::post('/{id}/apply-discount', [SaleController::class, 'applyDiscount']);
        Route::get('/{id}/items', [SaleController::class, 'getItems']);
        Route::post('/{id}/items', [SaleController::class, 'addItem']);
        Route::put('/{id}/items/{itemId}', [SaleController::class, 'updateItem']);
        Route::delete('/{id}/items/{itemId}', [SaleController::class, 'removeItem']);
        Route::post('/{id}/payment', [SaleController::class, 'recordPayment']);
    });

    // Customers Routes
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/search', [CustomerController::class, 'searchByPhone']);
        Route::get('/{id}', [CustomerController::class, 'show']);
        Route::put('/{id}', [CustomerController::class, 'update']);
        Route::delete('/{id}', [CustomerController::class, 'destroy']);
        Route::get('/{id}/sales', [CustomerController::class, 'getSalesHistory']);
        Route::get('/{id}/preferences', [CustomerController::class, 'getPreferences']);
        Route::put('/{id}/preferences', [CustomerController::class, 'updatePreferences']);
        Route::get('/{id}/loyalty-points', [CustomerController::class, 'getLoyaltyBalance']);
        Route::post('/{id}/loyalty-points/earn', [CustomerController::class, 'earnPoints']);
        Route::post('/{id}/loyalty-points/redeem', [CustomerController::class, 'redeemPoints']);
        Route::get('/{id}/loyalty-history', [CustomerController::class, 'getLoyaltyHistory']);
    });

    // Purchases Routes
    Route::prefix('purchases')->group(function () {
        Route::get('/', [PurchaseController::class, 'index']);
        Route::post('/', [PurchaseController::class, 'store']);
        Route::get('/{id}', [PurchaseController::class, 'show']);
        Route::put('/{id}', [PurchaseController::class, 'update']);
        Route::post('/{id}/cancel', [PurchaseController::class, 'cancel']);
        Route::post('/{id}/receive', [PurchaseController::class, 'receive']);
        Route::get('/{id}/items', [PurchaseController::class, 'getItems']);
        Route::post('/{id}/items', [PurchaseController::class, 'addItem']);
        Route::put('/{id}/items/{itemId}', [PurchaseController::class, 'updateItem']);
        Route::delete('/{id}/items/{itemId}', [PurchaseController::class, 'removeItem']);
    });

    // Expenses Routes
    Route::prefix('expenses')->group(function () {
        Route::get('/', [ExpenseController::class, 'index']);
        Route::get('/by-category', [ExpenseController::class, 'byCategory']);
        Route::post('/', [ExpenseController::class, 'store']);
        Route::get('/{id}', [ExpenseController::class, 'show']);
        Route::put('/{id}', [ExpenseController::class, 'update']);
        Route::post('/{id}', [ExpenseController::class, 'update']);
        Route::delete('/{id}', [ExpenseController::class, 'destroy']);
    });

    // Returns Routes
    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/statistics', [ReturnController::class, 'statistics']);
        Route::post('/', [ReturnController::class, 'store']);
        Route::get('/{id}', [ReturnController::class, 'show']);
        Route::put('/{id}/approve', [ReturnController::class, 'approve']);
        Route::put('/{id}/reject', [ReturnController::class, 'reject']);
        Route::post('/{id}/process', [ReturnController::class, 'process']);
        Route::delete('/{id}', [ReturnController::class, 'destroy']);
    });

    // Notifications Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::get('/low-stock', [NotificationController::class, 'lowStock']);
        Route::post('/check-low-stock', [NotificationController::class, 'checkLowStock']);
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::get('/{id}', [NotificationController::class, 'show']);
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
    });

    // Reports Routes
    Route::prefix('reports')->group(function () {
        // Sales Reports
        Route::get('/sales', [ReportController::class, 'sales']);
        Route::get('/sales/daily', [ReportController::class, 'dailySales']);
        Route::get('/sales/monthly', [ReportController::class, 'monthlySales']);
        Route::get('/sales/by-product', [ReportController::class, 'salesByProduct']);
        Route::get('/sales/by-employee', [ReportController::class, 'salesByEmployee']);

        // Inventory Reports
        Route::get('/inventory', [ReportController::class, 'inventory']);
        Route::get('/inventory/low-stock', [ReportController::class, 'lowStock']);
        Route::get('/inventory/movements', [ReportController::class, 'inventoryMovements']);

        // Financial Reports
        Route::get('/financial/profit-loss', [ReportController::class, 'profitLoss']);
        Route::get('/financial/revenue', [ReportController::class, 'revenue']);
        Route::get('/financial/expenses', [ReportController::class, 'expenses']);
    });

    // Dashboard Routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [DashboardController::class, 'stats']);
        Route::get('/sales-today', [DashboardController::class, 'salesToday']);
        Route::get('/top-products', [DashboardController::class, 'topProducts']);
        Route::get('/top-customers', [DashboardController::class, 'topCustomers']);
    });

    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'update']);

    // Daily Closings Routes
    Route::prefix('daily-closings')->group(function () {
        Route::get('/', [DailyClosingController::class, 'index']);
        Route::post('/', [DailyClosingController::class, 'store']);
        Route::get('/today', [DailyClosingController::class, 'today']);
        Route::get('/{id}', [DailyClosingController::class, 'show']);
        Route::get('/date/{date}', [DailyClosingController::class, 'getByDate']);
    });
});
