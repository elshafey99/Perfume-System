# 📚 توثيق API نظام العطور الكامل

## Perfume Shop Management System - API Documentation

---

## 📌 معلومات عامة

### Base URL

```
https://your-domain.com/api
```

### Authentication

جميع الـ endpoints (ماعدا Auth) تتطلب **Bearer Token** في الـ Header:

```
Authorization: Bearer {token}
```

### Response Format

جميع الـ responses بتكون بالشكل ده:

```json
{
    "success": true,
    "data": { ... },
    "message": "رسالة النجاح"
}
```

### Error Response

```json
{
    "success": false,
    "status": 422,
    "message": "خطأ في البيانات",
    "errors": {
        "field_name": ["رسالة الخطأ"]
    }
}
```

---

## 🔐 1. Authentication (المصادقة)

### 1.1 تسجيل الدخول

```
POST /auth/login
```

| Parameter | Type   | Required | Description          |
| --------- | ------ | -------- | -------------------- |
| email     | string | ✅       | البريد الإلكتروني    |
| password  | string | ✅       | كلمة المرور (min: 6) |

**Response:**

```json
{
    "success": true,
    "data": {
        "user": { ... },
        "token": "your-bearer-token"
    }
}
```

---

### 1.2 نسيت كلمة المرور

```
POST /auth/forgot-password
```

| Parameter | Type   | Required | Description       |
| --------- | ------ | -------- | ----------------- |
| email     | string | ✅       | البريد الإلكتروني |

---

### 1.3 التحقق من الكود

```
POST /auth/verify-code
```

| Parameter | Type   | Required | Description       |
| --------- | ------ | -------- | ----------------- |
| email     | string | ✅       | البريد الإلكتروني |
| code      | string | ✅       | كود التحقق        |

---

### 1.4 إعادة إرسال الكود

```
POST /auth/resend-code
```

| Parameter | Type   | Required | Description       |
| --------- | ------ | -------- | ----------------- |
| email     | string | ✅       | البريد الإلكتروني |

---

### 1.5 إعادة تعيين كلمة المرور

```
POST /auth/reset-password
```

| Parameter             | Type   | Required | Description         |
| --------------------- | ------ | -------- | ------------------- |
| email                 | string | ✅       | البريد الإلكتروني   |
| code                  | string | ✅       | كود التحقق          |
| password              | string | ✅       | كلمة المرور الجديدة |
| password_confirmation | string | ✅       | تأكيد كلمة المرور   |

---

### 1.6 تسجيل الخروج

```
POST /auth/logout
```

> 🔒 يتطلب Bearer Token

---

## 👤 2. Profile (الملف الشخصي)

### 2.1 عرض الملف الشخصي

```
GET /profile
```

---

### 2.2 تحديث الملف الشخصي

```
POST /profile
```

| Parameter | Type   | Required | Description    |
| --------- | ------ | -------- | -------------- |
| name      | string | ❌       | الاسم          |
| phone     | string | ❌       | رقم الهاتف     |
| image     | file   | ❌       | الصورة الشخصية |

---

### 2.3 تغيير كلمة المرور

```
POST /profile/change-password
```

| Parameter             | Type   | Required | Description         |
| --------------------- | ------ | -------- | ------------------- |
| current_password      | string | ✅       | كلمة المرور الحالية |
| password              | string | ✅       | كلمة المرور الجديدة |
| password_confirmation | string | ✅       | تأكيد كلمة المرور   |

---

## 👥 3. Users (المستخدمين)

### 3.1 جلب كل المستخدمين

```
GET /users
```

| Query Param | Type    | Description                    |
| ----------- | ------- | ------------------------------ |
| per_page    | int     | عدد النتائج لكل صفحة           |
| type        | string  | نوع المستخدم (admin, employee) |
| status      | boolean | الحالة (نشط/غير نشط)           |
| search      | string  | بحث بالاسم أو الإيميل          |

---

### 3.2 إضافة مستخدم جديد

```
POST /users
```

| Parameter             | Type    | Required | Description                  |
| --------------------- | ------- | -------- | ---------------------------- |
| name                  | string  | ✅       | الاسم                        |
| email                 | string  | ✅       | البريد الإلكتروني (unique)   |
| password              | string  | ✅       | كلمة المرور (min: 8)         |
| password_confirmation | string  | ✅       | تأكيد كلمة المرور            |
| phone                 | string  | ❌       | رقم الهاتف (unique)          |
| image                 | file    | ❌       | الصورة (max: 2MB)            |
| type                  | string  | ✅       | النوع: `admin` أو `employee` |
| role_id               | int     | ❌       | معرف الدور                   |
| position              | string  | ❌       | المنصب الوظيفي               |
| status                | boolean | ❌       | الحالة (default: true)       |

---

### 3.3 عرض مستخدم

```
GET /users/{id}
```

---

### 3.4 تحديث مستخدم

```
POST /users/{id}
```

> نفس parameters الإضافة (ماعدا password اختياري)

---

### 3.5 تغيير حالة المستخدم

```
POST /users/{id}/status
```

| Parameter | Type    | Required | Description    |
| --------- | ------- | -------- | -------------- |
| status    | boolean | ✅       | الحالة الجديدة |

---

### 3.6 حذف مستخدم

```
DELETE /users/{id}
```

---

## 🔑 4. Roles (الأدوار والصلاحيات)

### 4.1 جلب كل الأدوار

```
GET /roles
```

---

### 4.2 جلب الصلاحيات المتاحة

```
GET /roles/permissions
```

**Response:**

```json
{
    "success": true,
    "data": {
        "dashboard": "لوحة التحكم",
        "dashboard.view": "عرض لوحة التحكم",
        "sales": "إدارة المبيعات",
        ...
    }
}
```

---

### 4.3 إضافة دور جديد

```
POST /roles
```

| Parameter   | Type   | Required | Description      |
| ----------- | ------ | -------- | ---------------- |
| role        | string | ✅       | اسم الدور        |
| permissions | array  | ✅       | مصفوفة الصلاحيات |

**Example:**

```json
{
    "role": "محاسب",
    "permissions": ["sales.view", "sales.create", "reports.view"]
}
```

---

### 4.4 عرض دور

```
GET /roles/{id}
```

---

### 4.5 تحديث دور

```
PUT /roles/{id}
```

---

### 4.6 حذف دور

```
DELETE /roles/{id}
```

---

## 📁 5. Categories (الأصناف)

### 5.1 جلب كل الأصناف

```
GET /categories
```

---

### 5.2 جلب الأصناف الرئيسية فقط

```
GET /categories/parents
```

---

### 5.3 جلب الأصناف الفرعية لصنف معين

```
GET /categories/parent/{parentId}
```

---

### 5.4 إضافة صنف جديد

```
POST /categories
```

| Parameter   | Type    | Required | Description         |
| ----------- | ------- | -------- | ------------------- |
| name        | string  | ✅       | اسم الصنف           |
| parent_id   | int     | ❌       | معرف الصنف الأب     |
| icon        | file    | ❌       | الأيقونة (max: 2MB) |
| description | string  | ❌       | الوصف               |
| sort_order  | int     | ❌       | ترتيب العرض         |
| is_active   | boolean | ❌       | نشط (default: true) |

---

### 5.5 عرض صنف

```
GET /categories/{id}
```

---

### 5.6 تحديث صنف

```
POST /categories/{id}
```

---

### 5.7 حذف صنف

```
DELETE /categories/{id}
```

---

## 📦 6. Products (المنتجات)

### 6.1 جلب كل المنتجات

```
GET /products
```

| Query Param | Type    | Description            |
| ----------- | ------- | ---------------------- |
| per_page    | int     | عدد النتائج لكل صفحة   |
| active_only | boolean | المنتجات النشطة فقط    |
| category_id | int     | فلتر حسب الصنف         |
| search      | string  | بحث بالاسم أو الباركود |

---

### 6.2 جلب المنتجات منخفضة المخزون

```
GET /products/low-stock
```

---

### 6.3 البحث بالباركود

```
GET /products/barcode/{barcode}
```

> ⭐ **هام للـ POS**: لما تعمل scan للباركود، استخدم هذا الـ endpoint لجلب بيانات المنتج فوراً.

---

### 6.4 إضافة منتج جديد

```
POST /products
```

| Parameter       | Type    | Required | Description            |
| --------------- | ------- | -------- | ---------------------- |
| name            | string  | ✅       | اسم المنتج             |
| sku             | string  | ❌       | رمز المنتج (unique)    |
| barcode         | string  | ❌       | الباركود (unique)      |
| category_id     | int     | ✅       | معرف الصنف             |
| product_type_id | int     | ✅       | معرف نوع المنتج        |
| unit_type_id    | int     | ✅       | معرف وحدة القياس       |
| conversion_rate | decimal | ❌       | معدل التحويل           |
| current_stock   | decimal | ❌       | المخزون الحالي         |
| min_stock_level | decimal | ❌       | الحد الأدنى للمخزون    |
| max_stock_level | decimal | ❌       | الحد الأقصى للمخزون    |
| cost_price      | decimal | ❌       | سعر التكلفة            |
| selling_price   | decimal | ❌       | سعر البيع              |
| price_per_gram  | decimal | ❌       | السعر لكل جرام         |
| price_per_ml    | decimal | ❌       | السعر لكل مل           |
| image           | file    | ❌       | صورة المنتج (max: 5MB) |
| description     | string  | ❌       | الوصف                  |
| brand           | string  | ❌       | الماركة                |
| is_raw_material | boolean | ❌       | مادة خام               |
| is_composition  | boolean | ❌       | تركيبة                 |
| is_active       | boolean | ❌       | نشط                    |
| can_return      | boolean | ❌       | قابل للإرجاع           |
| supplier_id     | int     | ❌       | معرف المورد            |

---

### 6.5 عرض منتج

```
GET /products/{id}
```

**Response يحتوي:**

-   بيانات المنتج
-   الصنف (category)
-   نوع المنتج (productType)
-   وحدة القياس (unitType)
-   المورد (supplier)

---

### 6.6 تحديث منتج

```
PUT /products/{id}
```

---

### 6.7 تحديث المخزون

```
PUT /products/{id}/stock
```

| Parameter | Type    | Required | Description                           |
| --------- | ------- | -------- | ------------------------------------- |
| quantity  | decimal | ✅       | الكمية                                |
| type      | string  | ❌       | نوع التحديث: `set`, `add`, `subtract` |

---

### 6.8 حذف منتج

```
DELETE /products/{id}
```

> ⚠️ لا يمكن حذف منتج له مبيعات أو مشتريات

---

## 🏢 7. Suppliers (الموردين)

### 7.1 جلب كل الموردين

```
GET /suppliers
```

---

### 7.2 إضافة مورد جديد

```
POST /suppliers
```

| Parameter      | Type   | Required | Description       |
| -------------- | ------ | -------- | ----------------- |
| name           | string | ✅       | اسم المورد        |
| phone          | string | ❌       | رقم الهاتف        |
| email          | string | ❌       | البريد الإلكتروني |
| address        | string | ❌       | العنوان           |
| contact_person | string | ❌       | اسم جهة الاتصال   |
| notes          | string | ❌       | ملاحظات           |

---

### 7.3-7.5 عرض/تحديث/حذف

```
GET /suppliers/{id}
PUT /suppliers/{id}
DELETE /suppliers/{id}
```

---

## 📏 8. Unit Types (أنواع الوحدات)

### 8.1-8.6 CRUD العمليات

```
GET    /unit-types
POST   /unit-types
GET    /unit-types/{id}
PUT    /unit-types/{id}
DELETE /unit-types/{id}
```

| Parameter | Type   | Required | Description         |
| --------- | ------ | -------- | ------------------- |
| name      | string | ✅       | اسم الوحدة          |
| symbol    | string | ❌       | الرمز (مثل: جم، مل) |

---

## 🏷️ 9. Product Types (أنواع المنتجات)

### 9.1-9.6 CRUD العمليات

```
GET    /product-types
POST   /product-types
GET    /product-types/{id}
PUT    /product-types/{id}
DELETE /product-types/{id}
```

| Parameter   | Type   | Required | Description |
| ----------- | ------ | -------- | ----------- |
| name        | string | ✅       | اسم النوع   |
| description | string | ❌       | الوصف       |

---

## 📊 10. Inventory Transactions (حركات المخزون)

### 10.1 جلب كل الحركات

```
GET /inventory-transactions
```

---

### 10.2 جلب حركات منتج معين

```
GET /inventory-transactions/product/{productId}
```

---

### 10.3 إضافة حركة مخزون

```
POST /inventory-transactions
```

| Parameter      | Type    | Required | Description                      |
| -------------- | ------- | -------- | -------------------------------- |
| product_id     | int     | ✅       | معرف المنتج                      |
| type           | string  | ✅       | النوع: `in`, `out`, `adjustment` |
| quantity       | decimal | ✅       | الكمية                           |
| reference_type | string  | ❌       | نوع المرجع                       |
| reference_id   | int     | ❌       | معرف المرجع                      |
| notes          | string  | ❌       | ملاحظات                          |

---

## 📋 11. Stocktaking (الجرد)

### 11.1 جلب كل عمليات الجرد

```
GET /stocktakings
```

---

### 11.2 إنشاء جرد جديد

```
POST /stocktakings
```

| Parameter | Type   | Required | Description |
| --------- | ------ | -------- | ----------- |
| title     | string | ✅       | عنوان الجرد |
| notes     | string | ❌       | ملاحظات     |

---

### 11.3 عرض جرد

```
GET /stocktakings/{id}
```

---

### 11.4 جلب عناصر الجرد

```
GET /stocktakings/{id}/items
```

---

### 11.5 إضافة عنصر للجرد

```
POST /stocktakings/{id}/items
```

| Parameter        | Type    | Required | Description     |
| ---------------- | ------- | -------- | --------------- |
| product_id       | int     | ✅       | معرف المنتج     |
| counted_quantity | decimal | ✅       | الكمية المحسوبة |
| notes            | string  | ❌       | ملاحظات         |

---

### 11.6 إتمام الجرد

```
POST /stocktakings/{id}/complete
```

> ⚠️ هذا سيحدث المخزون بالقيم المحسوبة

---

### 11.7 حذف جرد

```
DELETE /stocktakings/{id}
```

---

## 🧪 12. Compositions (التركيبات والخلطات)

### 12.1 جلب كل التركيبات

```
GET /compositions
```

| Query Param        | Type    | Description          |
| ------------------ | ------- | -------------------- |
| per_page           | int     | عدد النتائج لكل صفحة |
| active_only        | boolean | التركيبات النشطة فقط |
| magic_recipes_only | boolean | الوصفات السحرية فقط  |

---

### 12.2 جلب الوصفات السحرية

```
GET /compositions/magic-recipes
```

---

### 12.3 إضافة تركيبة جديدة

```
POST /compositions
```

| Parameter                            | Type    | Required          | Description                                    |
| ------------------------------------ | ------- | ----------------- | ---------------------------------------------- |
| name                                 | string  | ✅                | اسم التركيبة                                   |
| code                                 | string  | ❌                | رمز التركيبة (unique)                          |
| product_id                           | int     | ❌                | معرف المنتج المرتبط                            |
| bottle_size                          | decimal | ✅                | حجم الزجاجة                                    |
| concentration_type                   | string  | ❌                | نوع التركيز: `EDP`, `EDT`, `Parfum`, `Cologne` |
| base_cost                            | decimal | ❌                | التكلفة الأساسية                               |
| service_fee                          | decimal | ❌                | رسوم الخدمة                                    |
| selling_price                        | decimal | ❌                | سعر البيع                                      |
| instructions                         | string  | ❌                | التعليمات                                      |
| notes                                | string  | ❌                | ملاحظات                                        |
| image                                | file    | ❌                | صورة التركيبة                                  |
| is_magic_recipe                      | boolean | ❌                | وصفة سحرية                                     |
| original_perfume_name                | string  | ❌                | اسم العطر الأصلي                               |
| is_active                            | boolean | ❌                | نشط                                            |
| **ingredients**                      | array   | ❌                | **مصفوفة المكونات**                            |
| ingredients.\*.ingredient_product_id | int     | ✅ مع ingredients | معرف المنتج المكون                             |
| ingredients.\*.quantity              | decimal | ✅ مع ingredients | الكمية                                         |
| ingredients.\*.unit                  | string  | ✅ مع ingredients | الوحدة: `piece`, `gram`, `ml`                  |
| ingredients.\*.sort_order            | int     | ❌                | ترتيب المكون                                   |

---

### 12.4 عرض تركيبة

```
GET /compositions/{id}
```

---

### 12.5 تحديث تركيبة

```
PUT /compositions/{id}
```

---

### 12.6 حساب تكلفة التركيبة

```
POST /compositions/{id}/calculate-cost
```

**Response:**

```json
{
    "success": true,
    "data": {
        "composition_id": 1,
        "composition_name": "عطر مسك",
        "ingredients_cost": [...],
        "total_ingredients_cost": 50.00,
        "service_fee": 10.00,
        "base_cost": 60.00,
        "selling_price": 100.00,
        "profit": 40.00,
        "profit_margin": 40
    }
}
```

---

### 12.7 حذف تركيبة

```
DELETE /compositions/{id}
```

---

### 12.8 مكونات التركيبة

```
GET    /compositions/{id}/ingredients
POST   /compositions/{id}/ingredients
PUT    /compositions/{id}/ingredients/{ingredientId}
DELETE /compositions/{id}/ingredients/{ingredientId}
```

---

## 🛒 13. Sales (المبيعات - POS)

> ⭐ **هذا القسم الأهم للـ POS**

### 13.1 جلب كل المبيعات

```
GET /sales
```

| Query Param    | Type   | Description                                          |
| -------------- | ------ | ---------------------------------------------------- |
| per_page       | int    | عدد النتائج لكل صفحة                                 |
| status         | string | الحالة: `completed`, `cancelled`, `refunded`         |
| payment_status | string | حالة الدفع: `pending`, `paid`, `partial`, `refunded` |
| customer_id    | int    | معرف العميل                                          |
| employee_id    | int    | معرف الموظف                                          |
| date_from      | date   | من تاريخ (Y-m-d)                                     |
| date_to        | date   | إلى تاريخ (Y-m-d)                                    |
| search         | string | بحث برقم الفاتورة أو اسم العميل                      |

---

### 13.2 ملخص مبيعات اليوم

```
GET /sales/today
```

**Response:**

```json
{
    "success": true,
    "data": {
        "date": "2026-01-10",
        "total_sales": 15,
        "total_revenue": 5000.00,
        "total_paid": 4500.00,
        "total_pending": 500.00,
        "total_tax": 750.00,
        "total_discount": 200.00,
        "average_sale": 333.33,
        "payment_methods": {...},
        "status_breakdown": {...},
        "top_products": [...]
    }
}
```

---

### 13.3 إنشاء فاتورة بيع ⭐

```
POST /sales
```

| Parameter                | Type     | Required | Description                                                        |
| ------------------------ | -------- | -------- | ------------------------------------------------------------------ |
| customer_id              | int      | ❌       | معرف العميل                                                        |
| payment_method           | string   | ✅       | طريقة الدفع: `cash`, `card`, `bank_transfer`, `apple_pay`, `split` |
| discount                 | decimal  | ❌       | قيمة الخصم (default: 0)                                            |
| discount_type            | string   | ❌       | نوع الخصم: `amount`, `percentage`                                  |
| tax_rate                 | decimal  | ❌       | نسبة الضريبة (default: 15)                                         |
| paid_amount              | decimal  | ❌       | المبلغ المدفوع                                                     |
| sale_date                | datetime | ❌       | تاريخ البيع (default: now)                                         |
| notes                    | string   | ❌       | ملاحظات                                                            |
| **items**                | array    | ✅       | **مصفوفة المنتجات**                                                |
| items.\*.product_id      | int      | ✅\*     | معرف المنتج                                                        |
| items.\*.composition_id  | int      | ✅\*     | معرف التركيبة                                                      |
| items.\*.quantity        | decimal  | ✅       | الكمية                                                             |
| items.\*.unit            | string   | ✅       | الوحدة: `piece`, `gram`, `ml`, `tola`, `quarter_tola`              |
| items.\*.unit_price      | decimal  | ❌       | سعر الوحدة **(يُجلب تلقائياً من المنتج)**                          |
| items.\*.is_custom_blend | boolean  | ❌       | خلطة مخصصة                                                         |
| items.\*.notes           | string   | ❌       | ملاحظات                                                            |

> ✅\* يجب إرسال `product_id` **أو** `composition_id` لكل عنصر

> 💡 **ملاحظة مهمة:** إذا لم تُرسل `unit_price`، النظام سيجلب سعر البيع من المنتج تلقائياً

**Example Request:**

```json
{
    "customer_id": 1,
    "payment_method": "cash",
    "discount": 10,
    "discount_type": "percentage",
    "items": [
        {
            "product_id": 5,
            "quantity": 2,
            "unit": "piece"
        },
        {
            "composition_id": 3,
            "quantity": 1,
            "unit": "tola"
        }
    ]
}
```

---

### 13.4 البيع السريع ⭐

```
POST /sales/quick
```

> للبيع السريع بمنتج واحد فقط

| Parameter      | Type    | Required | Description   |
| -------------- | ------- | -------- | ------------- |
| product_id     | int     | ✅\*     | معرف المنتج   |
| composition_id | int     | ✅\*     | معرف التركيبة |
| customer_id    | int     | ❌       | معرف العميل   |
| quantity       | decimal | ✅       | الكمية        |
| unit           | string  | ❌       | الوحدة        |
| unit_price     | decimal | ❌       | سعر الوحدة    |
| payment_method | string  | ✅       | طريقة الدفع   |
| discount       | decimal | ❌       | الخصم         |
| discount_type  | string  | ❌       | نوع الخصم     |
| tax_rate       | decimal | ❌       | نسبة الضريبة  |

---

### 13.5 بيع تركيبة جاهزة ⭐

```
POST /sales/composition-sale
```

| Parameter      | Type    | Required | Description            |
| -------------- | ------- | -------- | ---------------------- |
| composition_id | int     | ✅       | معرف التركيبة          |
| customer_id    | int     | ❌       | معرف العميل            |
| quantity       | decimal | ✅       | الكمية                 |
| unit           | string  | ❌       | الوحدة (default: tola) |
| unit_price     | decimal | ❌       | سعر الوحدة             |
| payment_method | string  | ✅       | طريقة الدفع            |
| discount       | decimal | ❌       | الخصم                  |
| discount_type  | string  | ❌       | نوع الخصم              |
| tax_rate       | decimal | ❌       | نسبة الضريبة           |
| notes          | string  | ❌       | ملاحظات                |

---

### 13.6 بيع خلطة مخصصة ⭐

```
POST /sales/custom-blend
```

> للعميل اللي عايز يخلط مكونات حسب اختياره

| Parameter                 | Type    | Required | Description                  |
| ------------------------- | ------- | -------- | ---------------------------- |
| customer_id               | int     | ❌       | معرف العميل                  |
| blend_name                | string  | ❌       | اسم الخلطة                   |
| payment_method            | string  | ✅       | طريقة الدفع                  |
| discount                  | decimal | ❌       | الخصم                        |
| discount_type             | string  | ❌       | نوع الخصم                    |
| tax_rate                  | decimal | ❌       | نسبة الضريبة                 |
| notes                     | string  | ❌       | ملاحظات                      |
| **ingredients**           | array   | ✅       | **مصفوفة المكونات (min: 2)** |
| ingredients.\*.product_id | int     | ✅       | معرف المنتج                  |
| ingredients.\*.quantity   | decimal | ✅       | الكمية                       |
| ingredients.\*.unit       | string  | ✅       | الوحدة                       |

**Example:**

```json
{
    "customer_id": 1,
    "blend_name": "خلطة خاصة للعميل",
    "payment_method": "cash",
    "ingredients": [
        { "product_id": 10, "quantity": 5, "unit": "gram" },
        { "product_id": 15, "quantity": 3, "unit": "ml" },
        { "product_id": 20, "quantity": 2, "unit": "gram" }
    ]
}
```

---

### 13.7 البحث برقم الفاتورة

```
GET /sales/invoice/{invoiceNumber}
```

---

### 13.8 عرض فاتورة

```
GET /sales/{id}
```

---

### 13.9 تحديث فاتورة

```
PUT /sales/{id}
```

---

### 13.10 إلغاء فاتورة

```
POST /sales/{id}/cancel
```

> ⚠️ سيتم استعادة المخزون تلقائياً

---

### 13.11 استرجاع فاتورة (Refund)

```
POST /sales/{id}/refund
```

| Parameter         | Type    | Required    | Description                                          |
| ----------------- | ------- | ----------- | ---------------------------------------------------- |
| items             | array   | ❌          | عناصر للاسترجاع الجزئي (إذا لم تُرسل = استرجاع كامل) |
| items.\*.item_id  | int     | ✅ مع items | معرف العنصر                                          |
| items.\*.quantity | decimal | ❌          | الكمية للاسترجاع                                     |
| refund_amount     | decimal | ❌          | مبلغ الاسترجاع                                       |

---

### 13.12 تطبيق خصم

```
POST /sales/{id}/apply-discount
```

| Parameter     | Type    | Required | Description                       |
| ------------- | ------- | -------- | --------------------------------- |
| discount      | decimal | ✅       | قيمة الخصم                        |
| discount_type | string  | ❌       | نوع الخصم: `amount`, `percentage` |

---

### 13.13 إدارة عناصر الفاتورة

```
GET    /sales/{id}/items       # جلب العناصر
POST   /sales/{id}/items       # إضافة عنصر
PUT    /sales/{id}/items/{itemId}    # تحديث عنصر
DELETE /sales/{id}/items/{itemId}    # حذف عنصر
```

---

### 13.14 تسجيل دفعة

```
POST /sales/{id}/payment
```

| Parameter      | Type    | Required | Description    |
| -------------- | ------- | -------- | -------------- |
| amount         | decimal | ✅       | المبلغ المدفوع |
| payment_method | string  | ❌       | طريقة الدفع    |

---

## 👥 14. Customers (العملاء)

### 14.1 جلب كل العملاء

```
GET /customers
```

| Query Param   | Type    | Description          |
| ------------- | ------- | -------------------- |
| per_page      | int     | عدد النتائج          |
| loyalty_level | string  | مستوى الولاء         |
| is_active     | boolean | نشط فقط              |
| search        | string  | بحث بالاسم أو الهاتف |

---

### 14.2 إضافة عميل جديد

```
POST /customers
```

| Parameter         | Type    | Required | Description                |
| ----------------- | ------- | -------- | -------------------------- |
| name              | string  | ✅       | اسم العميل                 |
| phone             | string  | ✅       | رقم الهاتف (unique)        |
| email             | string  | ❌       | البريد الإلكتروني (unique) |
| birth_date        | date    | ❌       | تاريخ الميلاد              |
| gender            | string  | ❌       | الجنس: `male`, `female`    |
| address           | string  | ❌       | العنوان                    |
| preferred_scents  | array   | ❌       | الروائح المفضلة            |
| favorite_products | array   | ❌       | المنتجات المفضلة           |
| notes             | string  | ❌       | ملاحظات                    |
| is_active         | boolean | ❌       | نشط                        |

---

### 14.3 البحث بالهاتف

```
GET /customers/search?phone={phone}
```

---

### 14.4-14.6 عرض/تحديث/حذف

```
GET    /customers/{id}
PUT    /customers/{id}
DELETE /customers/{id}
```

---

### 14.7 سجل مشتريات العميل

```
GET /customers/{id}/sales
```

---

### 14.8 تفضيلات العميل

```
GET /customers/{id}/preferences
PUT /customers/{id}/preferences
```

---

### 14.9 نقاط الولاء

```
GET  /customers/{id}/loyalty-points        # رصيد النقاط
POST /customers/{id}/loyalty-points/earn   # إضافة نقاط
POST /customers/{id}/loyalty-points/redeem # استبدال نقاط
GET  /customers/{id}/loyalty-history       # سجل النقاط
```

---

## 🚚 15. Purchases (المشتريات)

### 15.1 جلب كل المشتريات

```
GET /purchases
```

| Query Param | Type   | Description                                |
| ----------- | ------ | ------------------------------------------ |
| per_page    | int    | عدد النتائج                                |
| supplier_id | int    | معرف المورد                                |
| status      | string | الحالة: `pending`, `received`, `cancelled` |
| date_from   | date   | من تاريخ                                   |
| date_to     | date   | إلى تاريخ                                  |
| search      | string | بحث                                        |

---

### 15.2 إنشاء فاتورة شراء

```
POST /purchases
```

| Parameter              | Type    | Required | Description           |
| ---------------------- | ------- | -------- | --------------------- |
| supplier_id            | int     | ✅       | معرف المورد           |
| purchase_date          | date    | ❌       | تاريخ الشراء          |
| expected_delivery_date | date    | ❌       | تاريخ التسليم المتوقع |
| notes                  | string  | ❌       | ملاحظات               |
| **items**              | array   | ✅       | مصفوفة المنتجات       |
| items.\*.product_id    | int     | ✅       | معرف المنتج           |
| items.\*.quantity      | decimal | ✅       | الكمية                |
| items.\*.unit          | string  | ❌       | الوحدة                |
| items.\*.cost_price    | decimal | ❌       | سعر التكلفة           |

---

### 15.3 عرض/تحديث/إلغاء

```
GET    /purchases/{id}
PUT    /purchases/{id}
POST   /purchases/{id}/cancel
```

---

### 15.4 استلام المشتريات

```
POST /purchases/{id}/receive
```

> ✅ سيتم إضافة المخزون تلقائياً

---

### 15.5 إدارة عناصر الشراء

```
GET    /purchases/{id}/items
POST   /purchases/{id}/items
PUT    /purchases/{id}/items/{itemId}
DELETE /purchases/{id}/items/{itemId}
```

---

## 💸 16. Expenses (المصروفات)

### 16.1 جلب كل المصروفات

```
GET /expenses
```

| Query Param | Type   | Description |
| ----------- | ------ | ----------- |
| per_page    | int    | عدد النتائج |
| category    | string | الفئة       |
| date_from   | date   | من تاريخ    |
| date_to     | date   | إلى تاريخ   |

---

### 16.2 المصروفات حسب الفئة

```
GET /expenses/by-category
```

---

### 16.3 إضافة مصروف

```
POST /expenses
```

| Parameter     | Type    | Required | Description  |
| ------------- | ------- | -------- | ------------ |
| category      | string  | ✅       | الفئة        |
| amount        | decimal | ✅       | المبلغ       |
| expense_date  | date    | ❌       | التاريخ      |
| description   | string  | ❌       | الوصف        |
| receipt_image | file    | ❌       | صورة الإيصال |

---

### 16.4-16.6 عرض/تحديث/حذف

```
GET    /expenses/{id}
PUT    /expenses/{id}
DELETE /expenses/{id}
```

---

## 🔄 17. Returns (المرتجعات)

### 17.1 جلب كل المرتجعات

```
GET /returns
```

| Query Param | Type   | Description                                            |
| ----------- | ------ | ------------------------------------------------------ |
| per_page    | int    | عدد النتائج                                            |
| status      | string | الحالة: `pending`, `approved`, `rejected`, `completed` |
| date_from   | date   | من تاريخ                                               |
| date_to     | date   | إلى تاريخ                                              |

---

### 17.2 إحصائيات المرتجعات

```
GET /returns/statistics
```

---

### 17.3 إنشاء مرتجع

```
POST /returns
```

| Parameter     | Type    | Required | Description                                                   |
| ------------- | ------- | -------- | ------------------------------------------------------------- |
| sale_id       | int     | ✅       | معرف الفاتورة                                                 |
| sale_item_id  | int     | ❌       | معرف عنصر الفاتورة (للاسترجاع الجزئي)                         |
| return_reason | string  | ✅       | السبب: `defective`, `wrong_item`, `customer_request`, `other` |
| return_type   | string  | ✅       | النوع: `refund`, `exchange`, `store_credit`                   |
| return_amount | decimal | ❌       | المبلغ (يُحسب تلقائياً إذا لم يُرسل)                          |
| notes         | string  | ❌       | ملاحظات                                                       |

---

### 17.4 عرض مرتجع

```
GET /returns/{id}
```

---

### 17.5 الموافقة على مرتجع

```
PUT /returns/{id}/approve
```

---

### 17.6 رفض مرتجع

```
PUT /returns/{id}/reject
```

| Parameter | Type   | Required | Description |
| --------- | ------ | -------- | ----------- |
| notes     | string | ❌       | سبب الرفض   |

---

### 17.7 معالجة مرتجع (إتمام)

```
POST /returns/{id}/process
```

---

### 17.8 حذف مرتجع

```
DELETE /returns/{id}
```

> ⚠️ فقط المرتجعات بحالة `pending` يمكن حذفها

---

## 🔔 18. Notifications (الإشعارات)

### 18.1 جلب كل الإشعارات

```
GET /notifications
```

---

### 18.2 الإشعارات غير المقروءة

```
GET /notifications/unread
```

---

### 18.3 إشعارات المخزون المنخفض

```
GET /notifications/low-stock
```

---

### 18.4 فحص المخزون المنخفض

```
POST /notifications/check-low-stock
```

---

### 18.5 تحديد الكل كمقروء

```
PUT /notifications/read-all
```

---

### 18.6-18.8 عرض/تحديد كمقروء/حذف

```
GET    /notifications/{id}
PUT    /notifications/{id}/read
DELETE /notifications/{id}
```

---

## 📈 19. Reports (التقارير)

### تقارير المبيعات

```
GET /reports/sales                    # تقرير المبيعات العام
GET /reports/sales/daily              # المبيعات اليومية
GET /reports/sales/monthly            # المبيعات الشهرية
GET /reports/sales/by-product         # المبيعات حسب المنتج
GET /reports/sales/by-employee        # المبيعات حسب الموظف
```

| Query Param | Type | Description            |
| ----------- | ---- | ---------------------- |
| date_from   | date | من تاريخ               |
| date_to     | date | إلى تاريخ              |
| year        | int  | السنة (للتقرير الشهري) |
| limit       | int  | عدد النتائج            |

---

### تقارير المخزون

```
GET /reports/inventory                # نظرة عامة على المخزون
GET /reports/inventory/low-stock      # المنتجات منخفضة المخزون
GET /reports/inventory/movements      # حركات المخزون
```

---

### التقارير المالية

```
GET /reports/financial/profit-loss    # تقرير الأرباح والخسائر
GET /reports/financial/revenue        # تقرير الإيرادات
GET /reports/financial/expenses       # تقرير المصروفات
```

---

## 📊 20. Dashboard (لوحة التحكم)

### 20.1 الإحصائيات العامة

```
GET /dashboard/stats
```

**Response:**

```json
{
    "success": true,
    "data": {
        "today": {
            "sales_total": 5000.0,
            "sales_count": 15
        },
        "this_month": {
            "sales_total": 150000.0,
            "expenses_total": 30000.0,
            "net_profit": 120000.0
        },
        "counts": {
            "total_products": 250,
            "total_customers": 100,
            "low_stock_products": 5,
            "pending_returns": 2,
            "pending_purchases": 3
        }
    }
}
```

---

### 20.2 مبيعات اليوم

```
GET /dashboard/sales-today
```

---

### 20.3 أكثر المنتجات مبيعاً

```
GET /dashboard/top-products
```

| Query Param | Type   | Description                              |
| ----------- | ------ | ---------------------------------------- |
| limit       | int    | عدد المنتجات (default: 10)               |
| period      | string | الفترة: `today`, `week`, `month`, `year` |

---

### 20.4 أفضل العملاء

```
GET /dashboard/top-customers
```

| Query Param | Type   | Description                              |
| ----------- | ------ | ---------------------------------------- |
| limit       | int    | عدد العملاء (default: 10)                |
| period      | string | الفترة: `today`, `week`, `month`, `year` |

---

## 🔢 الثوابت (Constants)

### طرق الدفع

```javascript
const PAYMENT_METHODS = ["cash", "card", "bank_transfer", "apple_pay", "split"];
```

### حالات الدفع

```javascript
const PAYMENT_STATUS = ["pending", "paid", "partial", "refunded"];
```

### حالات الفاتورة

```javascript
const SALE_STATUS = ["completed", "cancelled", "refunded"];
```

### الوحدات

```javascript
const UNITS = ["piece", "gram", "ml", "tola", "quarter_tola"];
```

### أسباب الإرجاع

```javascript
const RETURN_REASONS = ["defective", "wrong_item", "customer_request", "other"];
```

### أنواع الإرجاع

```javascript
const RETURN_TYPES = ["refund", "exchange", "store_credit"];
```

### أنواع التركيز

```javascript
const CONCENTRATION_TYPES = ["EDP", "EDT", "Parfum", "Cologne"];
```

---

## 💡 نصائح للـ Frontend

### 1. عند إضافة فاتورة بيع

-   ✅ جلب المنتجات أولاً: `GET /products?active_only=true`
-   ✅ عند اختيار منتج، استخدم `selling_price` منه لملء حقل السعر تلقائياً
-   ✅ `unit_price` اختياري - النظام يجلبه تلقائياً

### 2. مسح الباركود

-   استخدم `GET /products/barcode/{barcode}` لجلب المنتج

### 3. حساب الإجماليات

```javascript
const subtotal = items.reduce(
    (sum, item) => sum + item.quantity * item.unit_price,
    0
);
const discountAmount =
    discountType === "percentage" ? subtotal * (discount / 100) : discount;
const taxableAmount = subtotal - discountAmount;
const taxAmount = taxableAmount * (taxRate / 100);
const total = taxableAmount + taxAmount;
```

### 4. التعامل مع الصور

-   استخدم `multipart/form-data` لرفع الصور
-   الحد الأقصى: 5MB للمنتجات، 2MB لباقي الصور

---

**تم إعداد هذا التوثيق بتاريخ:** 2026-01-10

**للاستفسارات:** راجع الكود المصدري أو تواصل مع فريق الـ Backend
