<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.31-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Status-Active-success?style=for-the-badge" alt="Status">
  <img src="https://img.shields.io/badge/Progress-80%25-brightgreen?style=for-the-badge" alt="Progress">
</p>

<h1 align="center">🛍️ Perfume Shop Management System</h1>

<p align="center">
  <strong>نظام إدارة محل عطور متكامل</strong> | نظام شامل لإدارة محلات العطور مبني على Laravel 11
</p>

---

## 📖 نظرة عامة

نظام إدارة متكامل لمحلات العطور يدعم:
- إدارة المخزون بوحدات قياس متعددة (جرام، مل، تولة، قطعة)
- نظام بيع (POS) متقدم
- إدارة التركيبات العطرية مع خصم تلقائي للمكونات
- نظام عملاء (CRM) مع نقاط الولاء
- إدارة المشتريات والموردين

---

## ✨ الميزات الرئيسية

### ✅ المكتملة:
- 🔐 المصادقة والأمان (Sanctum)
- 👥 إدارة المستخدمين والصلاحيات
- 📦 إدارة المنتجات والفئات
- 🏪 إدارة الموردين
- 📊 إدارة المخزون والجرد
- 🧪 إدارة التركيبات العطرية
- 💰 نظام البيع (POS)
- 👤 إدارة العملاء ونقاط الولاء
- 🛒 إدارة المشتريات

### 🚧 قيد التطوير:
- 📈 التقارير والتحليلات
- 🔄 نظام المرتجعات
- 💸 إدارة المصاريف
- 🔔 نظام الإشعارات

---

## 🛠️ التقنيات المستخدمة

- **Backend:** Laravel 11.31, PHP 8.2
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Sanctum
- **Permissions:** Spatie Laravel Permission
- **Frontend:** Livewire, Tailwind CSS

---

## 📋 المتطلبات

- PHP ^8.2 مع Extensions المطلوبة
- Composer ^2.0
- MySQL 8.0+ أو PostgreSQL 13+
- Node.js (اختياري للواجهة الأمامية)

---

## 🚀 البدء السريع

```bash
# 1. تثبيت المكتبات
composer install

# 2. إعداد البيئة
cp .env.example .env
php artisan key:generate

# 3. تعديل ملف .env وإضافة بيانات قاعدة البيانات
# DB_HOST=127.0.0.1
# DB_DATABASE=perfume_system
# DB_USERNAME=root
# DB_PASSWORD=

# 4. تشغيل Migrations
php artisan migrate

# 5. تشغيل السيرفر
php artisan serve
```

**الوصول:** `http://localhost:8000`

---

## 📡 API Endpoints

**Base URL:** `http://localhost:8000/api`

### 🔐 Authentication
```
POST /api/auth/login          # تسجيل الدخول
POST /api/auth/logout         # تسجيل الخروج
POST /api/auth/forgot-password # استعادة كلمة المرور
```

### 📦 Inventory
```
GET    /api/products          # قائمة المنتجات
POST   /api/products          # إضافة منتج
GET    /api/products/{id}     # تفاصيل منتج
GET    /api/products/barcode/{barcode} # البحث بالباركود
GET    /api/products/low-stock # المنتجات قليلة المخزون
```

### 💰 Sales & POS
```
GET    /api/sales             # قائمة المبيعات
POST   /api/sales             # إنشاء عملية بيع
POST   /api/sales/quick       # بيع سريع
POST   /api/sales/composition-sale # بيع تركيبة
```

### 👤 Customers
```
GET    /api/customers          # قائمة العملاء
POST   /api/customers          # إضافة عميل
GET    /api/customers/search   # البحث برقم الهاتف
GET    /api/customers/{id}/loyalty-points # نقاط الولاء
```

### 🛒 Purchases
```
GET    /api/purchases          # قائمة المشتريات
POST   /api/purchases          # إنشاء طلب شراء
POST   /api/purchases/{id}/receive # استلام المشتريات
```

### 🧪 Compositions
```
GET    /api/compositions       # قائمة التركيبات
POST   /api/compositions       # إنشاء تركيبة
GET    /api/compositions/magic-recipes # الوصفات السحرية
```

> 📖 للمزيد من التفاصيل: [API_10_DAYS_PLAN.md](./API_10_DAYS_PLAN.md)

---

## 🔐 Authentication

جميع الـ APIs المحمية تحتاج Token في Header:

```bash
Authorization: Bearer {your_token_here}
```

**مثال:**
```bash
# تسجيل الدخول
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "password"}'

# استخدام الـ Token
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🗄️ قاعدة البيانات

النظام يحتوي على **27 جدول** منظمة في الوحدات التالية:

- **المستخدمون:** users, admins, roles
- **المنتجات:** categories, products, product_types, unit_types
- **التركيبات:** compositions, composition_ingredients
- **المبيعات:** customers, sales, sale_items
- **المخزون:** inventory_transactions, stocktakings
- **المشتريات:** suppliers, purchases, purchase_items

> 📖 للتفاصيل الكاملة: [DATABASE_DESIGN.md](./DATABASE_DESIGN.md)

---

## 📊 حالة المشروع

**التقدم: 80% مكتمل**

### ✅ المكتمل:
- قاعدة البيانات (27 جدول)
- Models مع العلاقات
- APIs الأساسية (Authentication, Products, Categories)
- APIs إدارة المخزون
- APIs التركيبات
- APIs المبيعات والـ POS
- APIs العملاء والـ CRM
- APIs المشتريات

### 🚧 قيد التطوير:
- APIs التقارير
- APIs المرتجعات
- APIs المصاريف
- APIs الإشعارات
- لوحة التحكم
- الاختبارات

---

## 🛠️ أوامر مفيدة

```bash
# قاعدة البيانات
php artisan migrate              # تشغيل migrations
php artisan migrate:fresh        # إعادة بناء قاعدة البيانات
php artisan db:seed              # إضافة بيانات تجريبية

# التطوير
php artisan serve                # تشغيل السيرفر
php artisan route:list | cat     # عرض جميع الـ Routes
php artisan tinker               # الوصول التفاعلي

# الـ Cache
php artisan cache:clear          # مسح الـ Cache
php artisan config:clear         # مسح إعدادات الـ Cache
php artisan optimize             # تحسين للتشغيل
```

---

## 📝 ملاحظات مهمة

### الميزات الخاصة:
- ✅ دعم وحدات قياس متعددة (جرام، مل، تولة، قطعة)
- ✅ خصم تلقائي للمخزون عند إنشاء التركيبات
- ✅ البحث بالباركود
- ✅ تنبيهات المخزون المنخفض
- ✅ نظام نقاط الولاء للعملاء
- ✅ الوصفات السحرية (تركيبات مشهورة)

### البنية المعمارية:
- **Pattern:** Repository-Service-Controller
- **Authentication:** Laravel Sanctum
- **Authorization:** Spatie Permissions (RBAC)
- **Validation:** Form Requests
- **Response:** JSON موحد عبر ApiResponse Helper

---

## 🔒 الأمان

- ✅ Token-based Authentication (Sanctum)
- ✅ Role-based Access Control (RBAC)
- ✅ Request Validation
- ✅ Password Reset آمن
- ✅ حماية من SQL Injection
- ✅ حماية من XSS

---

## 📄 الترخيص

MIT License

---

<p align="center">
  <strong>Built with ❤️ using Laravel 11</strong><br>
  <strong>Last Updated:</strong> 2025-01-27 | <strong>Version:</strong> 1.0.0
</p>
