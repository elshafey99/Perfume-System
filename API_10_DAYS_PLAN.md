# خطة تطوير API في 10 أيام - نظام إدارة محل العطور
## 10 Days API Development Plan - Perfume Shop Management System

---

## 📅 نظرة عامة

**المدة:** 10 أيام عمل  
**الهدف:** إتمام جميع APIs الأساسية للنظام  
**النهج:** Backend فقط (API للفرونت)

---

## 🎯 اليوم الأول: الإعداد والبنية التحتية

### المهام:
- [x] ✅ Migrations (تم إنشاؤها)
- [ ] إنشاء Models الأساسية
- [ ] إعداد API Routes Structure
- [ ] إعداد API Response Helper
- [ ] إعداد Authentication (Sanctum)
- [ ] إعداد Validation Requests

### Models المطلوبة:
1. Category
2. Supplier
3. Product
4. Customer
5. Composition
6. CompositionIngredient

### المخرجات:
- Models جاهزة مع العلاقات
- API Response Helper
- Authentication جاهز

---

## 🎯 اليوم الثاني: إدارة المخزون (الجزء الأول)

### المهام:
- [ ] Category API (CRUD)
  - GET /api/categories
  - POST /api/categories
  - GET /api/categories/{id}
  - PUT /api/categories/{id}
  - DELETE /api/categories/{id}
- [ ] Supplier API (CRUD)
  - GET /api/suppliers
  - POST /api/suppliers
  - GET /api/suppliers/{id}
  - PUT /api/suppliers/{id}
  - DELETE /api/suppliers/{id}
- [ ] Product API (CRUD الأساسي)
  - GET /api/products
  - POST /api/products
  - GET /api/products/{id}
  - PUT /api/products/{id}
  - DELETE /api/products/{id}

### المخرجات:
- APIs للفئات والموردين والمنتجات

---

## 🎯 اليوم الثالث: إدارة المخزون (الجزء الثاني)

### المهام:
- [ ] Product API (ميزات متقدمة)
  - GET /api/products/search?q= (بحث ذكي)
  - GET /api/products/barcode/{barcode} (بحث بالباركود)
  - GET /api/products/low-stock (تنبيهات النواقص)
  - PUT /api/products/{id}/stock (تحديث المخزون)
- [ ] Inventory Transactions API
  - GET /api/inventory-transactions
  - POST /api/inventory-transactions
  - GET /api/inventory-transactions/product/{productId}
- [ ] Stocktaking API (الجرد الدوري)
  - GET /api/stocktakings
  - POST /api/stocktakings
  - GET /api/stocktakings/{id}
  - POST /api/stocktakings/{id}/complete
  - GET /api/stocktakings/{id}/items

### المخرجات:
- APIs كاملة لإدارة المخزون

---

## 🎯 اليوم الرابع: التركيبات والمبيعات (الجزء الأول)

### المهام:
- [ ] Composition API (CRUD)
  - GET /api/compositions
  - POST /api/compositions
  - GET /api/compositions/{id}
  - PUT /api/compositions/{id}
  - DELETE /api/compositions/{id}
- [ ] Composition Ingredients API
  - GET /api/compositions/{id}/ingredients
  - POST /api/compositions/{id}/ingredients
  - PUT /api/compositions/{id}/ingredients/{ingredientId}
  - DELETE /api/compositions/{id}/ingredients/{ingredientId}
- [ ] Magic Recipes API
  - GET /api/compositions/magic-recipes
  - POST /api/compositions/magic-recipes
  - GET /api/compositions/magic-recipes/{id}
- [ ] Calculate Composition Cost
  - POST /api/compositions/{id}/calculate-cost

### المخرجات:
- APIs للتركيبات والوصفات السحرية

---

## 🎯 اليوم الخامس: المبيعات (POS)

### المهام:
- [ ] Sale API (إنشاء فاتورة)
  - POST /api/sales (إنشاء فاتورة جديدة)
  - GET /api/sales/{id}
  - GET /api/sales (قائمة المبيعات)
- [ ] Sale Items API
  - POST /api/sales/{saleId}/items (إضافة عنصر)
  - PUT /api/sales/{saleId}/items/{itemId}
  - DELETE /api/sales/{saleId}/items/{itemId}
- [ ] POS Features
  - POST /api/sales/quick-sale (بيع سريع)
  - POST /api/sales/composition-sale (بيع تركيبة)
  - POST /api/sales/custom-blend (خلطة مخصصة)
- [ ] Payment Processing
  - POST /api/sales/{id}/payment (معالجة الدفع)
  - POST /api/sales/{id}/complete (إتمام البيع)

### المخرجات:
- APIs كاملة لنقطة البيع

---

## 🎯 اليوم السادس: العملاء ونظام الولاء

### المهام:
- [ ] Customer API (CRUD)
  - GET /api/customers
  - POST /api/customers
  - GET /api/customers/{id}
  - PUT /api/customers/{id}
  - DELETE /api/customers/{id}
  - GET /api/customers/search?phone= (بحث برقم الجوال)
- [ ] Customer Preferences API
  - GET /api/customers/{id}/preferences
  - PUT /api/customers/{id}/preferences
- [ ] Loyalty Points API
  - GET /api/customers/{id}/loyalty-points
  - POST /api/customers/{id}/loyalty-points/earn
  - POST /api/customers/{id}/loyalty-points/redeem
  - GET /api/customers/{id}/loyalty-history
- [ ] Customer Sales History
  - GET /api/customers/{id}/sales

### المخرجات:
- APIs كاملة للعملاء والولاء

---

## 🎯 اليوم السابع: المشتريات والمصاريف

### المهام:
- [ ] Purchase API (CRUD)
  - GET /api/purchases
  - POST /api/purchases
  - GET /api/purchases/{id}
  - PUT /api/purchases/{id}
  - POST /api/purchases/{id}/receive (استلام المشتريات)
- [ ] Purchase Items API
  - GET /api/purchases/{id}/items
  - POST /api/purchases/{id}/items
  - PUT /api/purchases/{id}/items/{itemId}
  - DELETE /api/purchases/{id}/items/{itemId}
- [ ] Expense API
  - GET /api/expenses
  - POST /api/expenses
  - GET /api/expenses/{id}
  - PUT /api/expenses/{id}
  - DELETE /api/expenses/{id}
  - GET /api/expenses/by-category (حسب الفئة)

### المخرجات:
- APIs للمشتريات والمصاريف

---

## 🎯 اليوم الثامن: المرتجعات والإشعارات

### المهام:
- [ ] Return API
  - GET /api/returns
  - POST /api/returns
  - GET /api/returns/{id}
  - PUT /api/returns/{id}/approve
  - PUT /api/returns/{id}/reject
  - POST /api/returns/{id}/process
- [ ] Notification API
  - GET /api/notifications
  - GET /api/notifications/unread
  - PUT /api/notifications/{id}/read
  - PUT /api/notifications/read-all
  - POST /api/notifications (إنشاء إشعار)
- [ ] Low Stock Notifications
  - GET /api/notifications/low-stock
  - POST /api/notifications/low-stock/check (فحص النواقص)

### المخرجات:
- APIs للمرتجعات والإشعارات

---

## 🎯 اليوم التاسع: التقارير والتحليلات

### المهام:
- [ ] Sales Reports API
  - GET /api/reports/sales (تقارير المبيعات)
  - GET /api/reports/sales/daily
  - GET /api/reports/sales/monthly
  - GET /api/reports/sales/by-product
  - GET /api/reports/sales/by-employee
- [ ] Inventory Reports API
  - GET /api/reports/inventory
  - GET /api/reports/inventory/low-stock
  - GET /api/reports/inventory/movements
- [ ] Financial Reports API
  - GET /api/reports/financial/profit-loss
  - GET /api/reports/financial/revenue
  - GET /api/reports/financial/expenses
- [ ] Dashboard API
  - GET /api/dashboard/stats (إحصائيات عامة)
  - GET /api/dashboard/sales-today
  - GET /api/dashboard/top-products
  - GET /api/dashboard/top-customers

### المخرجات:
- APIs كاملة للتقارير والتحليلات

---

## 🎯 اليوم العاشر: المراجعة والتحسين

### المهام:
- [ ] Audit Log API
  - GET /api/audit-logs
  - GET /api/audit-logs/by-user/{userId}
  - GET /api/audit-logs/by-model/{modelType}
- [ ] Testing & Bug Fixes
  - اختبار جميع APIs
  - إصلاح الأخطاء
  - تحسين الأداء
- [ ] API Documentation
  - توثيق جميع Endpoints
  - أمثلة للطلبات والاستجابات
- [ ] Final Review
  - مراجعة شاملة
  - تحسين الأمان
  - تحسين Validation

### المخرجات:
- APIs مختبرة ومكتملة
- توثيق كامل

---

## 📋 قائمة APIs الكاملة

### Authentication & Authorization
- POST /api/login
- POST /api/logout
- GET /api/profile
- PUT /api/profile

### Categories
- GET /api/categories
- POST /api/categories
- GET /api/categories/{id}
- PUT /api/categories/{id}
- DELETE /api/categories/{id}

### Suppliers
- GET /api/suppliers
- POST /api/suppliers
- GET /api/suppliers/{id}
- PUT /api/suppliers/{id}
- DELETE /api/suppliers/{id}

### Products
- GET /api/products
- POST /api/products
- GET /api/products/{id}
- PUT /api/products/{id}
- DELETE /api/products/{id}
- GET /api/products/search?q=
- GET /api/products/barcode/{barcode}
- GET /api/products/low-stock
- PUT /api/products/{id}/stock

### Compositions
- GET /api/compositions
- POST /api/compositions
- GET /api/compositions/{id}
- PUT /api/compositions/{id}
- DELETE /api/compositions/{id}
- GET /api/compositions/magic-recipes
- POST /api/compositions/{id}/calculate-cost
- GET /api/compositions/{id}/ingredients
- POST /api/compositions/{id}/ingredients
- PUT /api/compositions/{id}/ingredients/{ingredientId}
- DELETE /api/compositions/{id}/ingredients/{ingredientId}

### Sales (POS)
- GET /api/sales
- POST /api/sales
- GET /api/sales/{id}
- POST /api/sales/quick-sale
- POST /api/sales/composition-sale
- POST /api/sales/custom-blend
- POST /api/sales/{id}/payment
- POST /api/sales/{id}/complete
- GET /api/sales/{id}/items
- POST /api/sales/{saleId}/items
- PUT /api/sales/{saleId}/items/{itemId}
- DELETE /api/sales/{saleId}/items/{itemId}

### Customers
- GET /api/customers
- POST /api/customers
- GET /api/customers/{id}
- PUT /api/customers/{id}
- DELETE /api/customers/{id}
- GET /api/customers/search?phone=
- GET /api/customers/{id}/preferences
- PUT /api/customers/{id}/preferences
- GET /api/customers/{id}/sales
- GET /api/customers/{id}/loyalty-points
- POST /api/customers/{id}/loyalty-points/earn
- POST /api/customers/{id}/loyalty-points/redeem
- GET /api/customers/{id}/loyalty-history

### Purchases
- GET /api/purchases
- POST /api/purchases
- GET /api/purchases/{id}
- PUT /api/purchases/{id}
- POST /api/purchases/{id}/receive
- GET /api/purchases/{id}/items
- POST /api/purchases/{id}/items
- PUT /api/purchases/{id}/items/{itemId}
- DELETE /api/purchases/{id}/items/{itemId}

### Inventory
- GET /api/inventory-transactions
- POST /api/inventory-transactions
- GET /api/inventory-transactions/product/{productId}

### Stocktaking
- GET /api/stocktakings
- POST /api/stocktakings
- GET /api/stocktakings/{id}
- POST /api/stocktakings/{id}/complete
- GET /api/stocktakings/{id}/items

### Expenses
- GET /api/expenses
- POST /api/expenses
- GET /api/expenses/{id}
- PUT /api/expenses/{id}
- DELETE /api/expenses/{id}
- GET /api/expenses/by-category

### Returns
- GET /api/returns
- POST /api/returns
- GET /api/returns/{id}
- PUT /api/returns/{id}/approve
- PUT /api/returns/{id}/reject
- POST /api/returns/{id}/process

### Notifications
- GET /api/notifications
- GET /api/notifications/unread
- PUT /api/notifications/{id}/read
- PUT /api/notifications/read-all
- POST /api/notifications
- GET /api/notifications/low-stock

### Reports
- GET /api/reports/sales
- GET /api/reports/sales/daily
- GET /api/reports/sales/monthly
- GET /api/reports/sales/by-product
- GET /api/reports/sales/by-employee
- GET /api/reports/inventory
- GET /api/reports/inventory/low-stock
- GET /api/reports/inventory/movements
- GET /api/reports/financial/profit-loss
- GET /api/reports/financial/revenue
- GET /api/reports/financial/expenses

### Dashboard
- GET /api/dashboard/stats
- GET /api/dashboard/sales-today
- GET /api/dashboard/top-products
- GET /api/dashboard/top-customers

### Audit Logs
- GET /api/audit-logs
- GET /api/audit-logs/by-user/{userId}
- GET /api/audit-logs/by-model/{modelType}

---

## 🔧 التقنيات والأدوات

### Laravel Packages:
- Laravel Sanctum (API Authentication)
- Spatie Permissions (الصلاحيات - موجود)
- Laravel Excel (لتصدير التقارير - اختياري)

### API Response Format:
```json
{
    "success": true,
    "message": "تمت العملية بنجاح",
    "data": {},
    "errors": null
}
```

### Error Response Format:
```json
{
    "success": false,
    "message": "حدث خطأ",
    "data": null,
    "errors": {
        "field": ["رسالة الخطأ"]
    }
}
```

---

## ✅ قائمة التحقق اليومية

### كل يوم:
- [ ] اختبار APIs التي تم إنشاؤها
- [ ] التأكد من Validation
- [ ] التأكد من Authentication & Authorization
- [ ] كتابة Comments في الكود
- [ ] Commit التغييرات

---

## 📝 ملاحظات مهمة

1. **Authentication:** جميع APIs محمية بـ `auth:sanctum` ما عدا Login
2. **Validation:** استخدام Form Requests لكل API
3. **Resources:** استخدام API Resources لتنسيق الاستجابات
4. **Pagination:** جميع قوائم البيانات paginated
5. **Search & Filter:** دعم البحث والتصفية في القوائم
6. **Error Handling:** معالجة شاملة للأخطاء
7. **Audit Log:** تسجيل جميع العمليات المهمة

---

## 🚀 البدء

1. اليوم الأول: إنشاء Models و API Structure
2. اليوم الثاني: Categories, Suppliers, Products
3. اليوم الثالث: Inventory & Stocktaking
4. اليوم الرابع: Compositions
5. اليوم الخامس: Sales (POS)
6. اليوم السادس: Customers & Loyalty
7. اليوم السابع: Purchases & Expenses
8. اليوم الثامن: Returns & Notifications
9. اليوم التاسع: Reports & Dashboard
10. اليوم العاشر: Testing & Documentation

---

**تاريخ الإنشاء:** 2025-01-27  
**آخر تحديث:** 2025-01-27  
**الإصدار:** 1.0

