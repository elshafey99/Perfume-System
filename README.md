<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.31-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Status-Active%20Development-success?style=for-the-badge" alt="Status">
  <img src="https://img.shields.io/badge/Progress-80%25-brightgreen?style=for-the-badge" alt="Progress">
</p>

<h1 align="center">🛍️ Perfume Shop Management System</h1>

<p align="center">
  <strong>نظام إدارة محل عطور متكامل</strong> | <strong>Comprehensive Perfume Shop Management System</strong>
</p>

<p align="center">
  نظام إدارة شامل لمحلات العطور مبني على Laravel 11 مع دعم كامل لإدارة المخزون المعقدة، نظام POS متقدم، CRM، وإدارة التركيبات العطرية
</p>

---

## 📑 Table of Contents

- [Overview](#overview)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Requirements](#-requirements)
- [Quick Start](#-quick-start)
- [Installation](#-getting-started)
- [API Documentation](#-api-endpoints)
- [Database Structure](#️-database-structure)
- [Project Status](#-project-status)
- [Security](#-security-features)
- [Documentation](#-documentation)

---

## Overview

نظام إدارة محل عطور متكامل (Perfume Shop Management System) مبني على Laravel 11. النظام يدعم إدارة المخزون المعقدة (وحدات قياس متعددة)، عمليات تركيب وخلط العطور، نقطة البيع (POS) المتقدمة، إدارة علاقات العملاء (CRM)، والتقارير المالية.

**A comprehensive Perfume Shop Management System built on Laravel 11. The system supports complex inventory management (multiple measurement units), perfume composition and mixing operations, advanced POS, CRM, and financial reports.**

## 📚 Documentation

تم إعداد تحليل شامل ومتكامل للمشروع. يرجى مراجعة الملفات التالية:

**Comprehensive project analysis has been prepared. Please review the following files:**

| Document | Description | Status |
|----------|-------------|--------|
| **[PROJECT_SUMMARY.md](./PROJECT_SUMMARY.md)** | ملخص المشروع / Project Summary | ✅ Complete |
| **[REQUIREMENTS_ANALYSIS.md](./REQUIREMENTS_ANALYSIS.md)** | تحليل المتطلبات / Requirements Analysis | ✅ Complete |
| **[DATABASE_DESIGN.md](./DATABASE_DESIGN.md)** | تصميم قاعدة البيانات / Database Design | ✅ Complete |
| **[FEATURES_GAP_ANALYSIS.md](./FEATURES_GAP_ANALYSIS.md)** | تحليل الفجوات والميزات / Features Gap Analysis | ✅ Complete |
| **[DEVELOPMENT_PLAN.md](./DEVELOPMENT_PLAN.md)** | خطة التطوير / Development Plan | ✅ Complete |
| **[API_10_DAYS_PLAN.md](./API_10_DAYS_PLAN.md)** | خطة API في 10 أيام / 10 Days API Plan ⭐ | ✅ Complete |

## 🎯 Key Features

### ✅ Implemented Features:
- ✅ **المصادقة والأمان** - Authentication & Security (Sanctum, Password Reset)
- ✅ **إدارة المستخدمين** - Users & Roles Management (RBAC with Spatie)
- ✅ **إدارة الفئات** - Categories Management (Hierarchical categories)
- ✅ **إدارة الموردين** - Suppliers Management (CRUD operations)
- ✅ **إدارة المخزون** - Inventory Management (multiple units: gram, ml, tola, piece)
- ✅ **إدارة المنتجات** - Products Management (with barcode, SKU, stock tracking)
- ✅ **نظام الجرد الدوري** - Stocktaking System (periodic inventory checks)
- ✅ **إدارة التركيبات** - Perfume Composition Management (auto-deduct ingredients)
- ✅ **ميزة الوصفات السحرية** - Magic Recipes Feature (famous perfume formulas)
- ✅ **نظام البيع (POS)** - Point of Sale System (quick sale, composition sale, custom blend)
- ✅ **نظام العملاء (CRM)** - Customer Relationship Management (preferences, loyalty points)
- ✅ **نظام المشتريات** - Purchases Management (with receiving workflow)
- ✅ **حركات المخزون** - Inventory Transactions (track all stock movements)

### 🚧 Planned Features (Not Yet Implemented):
- ⏳ **التقارير المالية** - Financial Reports (sales analytics, profitability)
- ⏳ **لوحة التحكم** - Dashboard (real-time analytics)
- ⏳ **نظام المرتجعات** - Returns Management
- ⏳ **نظام المصاريف** - Expenses Management
- ⏳ **نظام الإشعارات** - Notifications System
- ⏳ **نظام النسخ الاحتياطي** - Automated Backup System
- ⏳ **نظام Audit Log** - Comprehensive Audit Logging

## Tech Stack

-   PHP 8.2
-   Laravel 11.31
-   Laravel Sanctum (API Authentication)
-   Spatie Laravel Permission (Role & Permissions)
-   Livewire (UI Components)
-   MySQL (Database)
-   Tailwind CSS (Styling)
-   Chart.js (Charts & Analytics)

## 📋 Requirements

### Minimum Requirements:
- **PHP:** ^8.2 (with required extensions)
- **Composer:** ^2.0
- **Database:** MySQL 8.0+ or PostgreSQL 13+
- **Web Server:** Apache 2.4+ or Nginx 1.18+
- **Memory:** 512MB minimum (1GB recommended)

### PHP Extensions Required:
- `php-mbstring`
- `php-xml`
- `php-curl`
- `php-zip`
- `php-gd`
- `php-mysql` or `php-pgsql`
- `php-bcmath`
- `php-json`

### Optional (for frontend development):
- **Node.js:** ^18.0 or ^20.0
- **NPM:** ^9.0 or **Yarn:** ^1.22

### Server Configuration:
- **Upload Max Filesize:** 64M
- **Post Max Size:** 68M
- **Memory Limit:** 128M
- **Max Execution Time:** 300 seconds

## ⚡ Quick Start

```bash
# Clone the repository
git clone <repository-url>
cd Perfume-System

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
# DB_HOST=127.0.0.1
# DB_DATABASE=perfume_system
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Start development server
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🚀 Getting Started

### Step 1: Install Dependencies

```bash
composer install
npm install  # Optional: only if working on frontend
```

### Step 2: Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

**Configure `.env` file with your settings:**

```env
APP_NAME="Perfume Shop Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perfume_system
DB_USERNAME=root
DB_PASSWORD=

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1

# Mail Configuration (for password reset)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

### Step 3: Database Setup

```bash
# Run migrations
php artisan migrate

# Seed database with sample data (optional)
php artisan db:seed
```

### Step 4: Storage Setup

```bash
# Create symbolic link for storage
php artisan storage:link
```

### Step 5: Start Development Server

```bash
# Simple server
php artisan serve

# OR Full development workflow (server + queue + logs + Vite)
composer run dev
```

### Step 6: Access the Application

- **API Base URL:** `http://localhost:8000/api`
- **Dashboard:** `http://localhost:8000/dashboard` (if implemented)

### Default Admin Credentials

After seeding, you can use:
- **Email:** `admin@example.com`
- **Password:** Check `database/seeders/AdminSeeder.php`

> ⚠️ **Important:** Change default credentials in production!

## 🔐 Authentication

Most routes are protected by `auth:sanctum`.

### Public Endpoints:
- `POST /api/auth/login` - User login (returns token)
- `POST /api/auth/forgot-password` - Request password reset code
- `POST /api/auth/verify-code` - Verify reset code
- `POST /api/auth/resend-code` - Resend verification code
- `POST /api/auth/reset-password` - Reset password with code

### Protected Endpoints:
- `POST /api/auth/logout` - Logout (requires token)

**Usage:**
```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "password"}'

# Use token in subsequent requests
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 🗄️ Database Structure

The system includes **27 database tables** organized into the following modules:

### Core Tables:
- **Users & Authentication** - `users`, `admins`, `roles`, `personal_access_tokens`
- **Products & Categories** - `categories`, `products`, `product_types`, `unit_types`
- **Compositions** - `compositions`, `composition_ingredients`
- **Sales & Customers** - `customers`, `sales`, `sale_items`
- **Inventory** - `inventory_transactions`, `stocktakings`, `stocktaking_items`
- **Purchases** - `suppliers`, `purchases`, `purchase_items`
- **Additional** - `expenses`, `returns`, `loyalty_points`, `notifications`, `audit_logs`, `settings`

For detailed database design, see [DATABASE_DESIGN.md](./DATABASE_DESIGN.md)

## 📊 Project Progress

### ✅ Completed Modules (80%):
- ✅ Authentication & Authorization
- ✅ Users & Roles Management
- ✅ Categories Management
- ✅ Suppliers Management
- ✅ Products Management
- ✅ Inventory Transactions
- ✅ Stocktakings
- ✅ Compositions & Ingredients
- ✅ Sales & POS System
- ✅ Customers & CRM
- ✅ Purchases Management

### 🚧 Remaining Work (20%):
- ⏳ Reports & Analytics
- ⏳ Returns Management
- ⏳ Expenses Management
- ⏳ Notifications System
- ⏳ Dashboard APIs
- ⏳ Testing Suite
- ⏳ Performance Optimization

**Overall Progress: ~80% Complete**

For detailed development plan, see [DEVELOPMENT_PLAN.md](./DEVELOPMENT_PLAN.md)

## 🛠️ Useful Commands

### Database:
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh       # Rebuild database (⚠️ drops all data)
php artisan migrate:refresh     # Rollback and re-run migrations
php artisan db:seed             # Seed database with sample data
```

### Development:
```bash
php artisan serve               # Start development server
php artisan route:list | cat   # List all routes (without pager)
php artisan tinker              # Interactive REPL
php artisan make:model          # Create new model
php artisan make:controller     # Create new controller
```

### Cache & Optimization:
```bash
php artisan config:clear        # Clear configuration cache
php artisan cache:clear         # Clear application cache
php artisan route:clear         # Clear route cache
php artisan view:clear          # Clear compiled views
php artisan optimize            # Optimize for production
```

### Full Development Workflow:
```bash
composer run dev                # Start server + queue + logs + Vite
```

## 📡 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication
All protected endpoints require Bearer token in Authorization header:
```
Authorization: Bearer {your_token_here}
```

### Implemented API Modules:

#### 🔐 Authentication & Profile
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `POST /api/auth/forgot-password` - Request password reset
- `POST /api/auth/reset-password` - Reset password
- `GET /api/profile` - Get user profile
- `POST /api/profile` - Update profile
- `POST /api/profile/change-password` - Change password

#### 👥 Users & Roles
- `GET /api/users` - List all users
- `POST /api/users` - Create user
- `GET /api/users/{id}` - Get user details
- `POST /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user
- `GET /api/roles` - List all roles
- `POST /api/roles` - Create role
- `GET /api/roles/permissions` - Get all permissions

#### 📦 Inventory Management
- **Categories:** CRUD operations with hierarchical support
- **Products:** CRUD, barcode search, low stock alerts, stock management
- **Suppliers:** Full CRUD operations
- **Unit Types & Product Types:** Management endpoints
- **Inventory Transactions:** Track all stock movements
- **Stocktakings:** Periodic inventory checks and reconciliation

#### 🧪 Compositions
- `GET /api/compositions` - List compositions
- `POST /api/compositions` - Create composition
- `GET /api/compositions/magic-recipes` - Get magic recipes
- `POST /api/compositions/{id}/calculate-cost` - Calculate composition cost
- **Ingredients Management:** Full CRUD for composition ingredients

#### 💰 Sales & POS
- `GET /api/sales` - List all sales
- `POST /api/sales` - Create sale
- `POST /api/sales/quick` - Quick sale
- `POST /api/sales/composition-sale` - Sell composition
- `POST /api/sales/custom-blend` - Custom blend sale
- `POST /api/sales/{id}/payment` - Record payment
- `POST /api/sales/{id}/apply-discount` - Apply discount
- **Invoice Management:** Generate and manage invoices

#### 👤 Customers & CRM
- `GET /api/customers` - List customers
- `GET /api/customers/search` - Search by phone
- `GET /api/customers/{id}/sales` - Customer sales history
- `GET /api/customers/{id}/loyalty-points` - Get loyalty balance
- `POST /api/customers/{id}/loyalty-points/earn` - Earn points
- `POST /api/customers/{id}/loyalty-points/redeem` - Redeem points
- **Preferences Management:** Store and manage customer preferences

#### 🛒 Purchases
- `GET /api/purchases` - List purchases
- `POST /api/purchases` - Create purchase order
- `POST /api/purchases/{id}/receive` - Receive purchase
- `POST /api/purchases/{id}/cancel` - Cancel purchase
- **Purchase Items:** Full management of purchase items

### API Response Format

**Success Response:**
```json
{
  "success": true,
  "status": 200,
  "message": "Operation successful",
  "data": { ... }
}
```

**Error Response:**
```json
{
  "success": false,
  "status": 400,
  "message": "Error message",
  "errors": { ... }
}
```

**Paginated Response:**
```json
{
  "success": true,
  "status": 200,
  "message": "Data retrieved successfully",
  "data": [ ... ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

For complete API documentation, see [API_10_DAYS_PLAN.md](./API_10_DAYS_PLAN.md)

## 🚀 Project Status

### ✅ Completed:
1. ✅ Database Migrations (27 tables)
2. ✅ Models with relationships
3. ✅ Core APIs (Authentication, Products, Categories, Suppliers)
4. ✅ Inventory Management APIs
5. ✅ Compositions APIs
6. ✅ Sales & POS APIs
7. ✅ Customers & CRM APIs
8. ✅ Purchases APIs

### 🚧 In Progress / Planned:
1. ⏳ Reports & Analytics APIs
2. ⏳ Returns Management APIs
3. ⏳ Expenses Management APIs
4. ⏳ Notifications APIs
5. ⏳ Dashboard APIs
6. ⏳ Testing Suite
7. ⏳ Frontend Integration

## 📅 10 Days API Plan

See [API_10_DAYS_PLAN.md](./API_10_DAYS_PLAN.md) for detailed daily plan to complete all APIs in 10 days.

## 📝 Important Notes

### Language Support:
- ✅ Full Arabic language support
- ✅ English language support (partial)
- ✅ Bilingual error messages and validation

### Key Features:
- **Multiple Measurement Units:** piece, gram, ml, tola, quarter_tola
- **Automatic Inventory Deduction:** When creating compositions, ingredients are automatically deducted
- **Barcode Support:** Products can be searched by barcode
- **Low Stock Alerts:** Automatic detection of products below minimum stock level
- **Loyalty Points System:** Earn and redeem points for customers
- **Magic Recipes:** Pre-defined famous perfume formulas
- **Purchase Workflow:** Support for purchase orders and receiving

### Architecture:
- **Pattern:** Repository-Service-Controller
- **Authentication:** Laravel Sanctum (Token-based)
- **Authorization:** Spatie Laravel Permission (RBAC)
- **Validation:** Form Requests with custom error messages
- **API Response:** Standardized JSON responses via ApiResponse helper

## 🔒 Security Features

- ✅ **Token-based Authentication** - Laravel Sanctum
- ✅ **Role-based Access Control** - Spatie Permissions
- ✅ **Request Validation** - Form Requests with custom rules
- ✅ **Password Reset** - Secure OTP-based password reset
- ✅ **SQL Injection Protection** - Eloquent ORM
- ✅ **XSS Protection** - Laravel built-in protection
- ⏳ **Audit Logging** - Planned (table exists, API pending)
- ⏳ **Rate Limiting** - Planned
- ⏳ **Data Encryption** - Planned for sensitive fields

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- [ ] Generate application key: `php artisan key:generate`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Optimize application: `php artisan optimize`
- [ ] Clear all caches: `php artisan config:clear && php artisan cache:clear`
- [ ] Set up queue worker for background jobs
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up SSL certificate
- [ ] Configure backup system
- [ ] Set up monitoring and logging

### Production Commands

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Run migrations in production
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

> ⚠️ **Note:** Testing suite is currently in development.

---

## 📊 Performance

### Optimization Tips:
- Enable OPcache in production
- Use Redis for caching and sessions
- Implement database query optimization
- Use CDN for static assets
- Enable HTTP/2 and Gzip compression

---

## 📄 License

MIT License - see LICENSE file for details

---

## 👥 Contributing

This is a private project. For any suggestions or issues, please contact the development team.

---

## 📞 Support & Contact

For technical support or questions:
- 📖 Review the documentation files in the project root
- 📡 Check [API_10_DAYS_PLAN.md](./API_10_DAYS_PLAN.md) for API details
- 🗄️ Review [DATABASE_DESIGN.md](./DATABASE_DESIGN.md) for database structure
- 📋 See [DEVELOPMENT_PLAN.md](./DEVELOPMENT_PLAN.md) for development roadmap

---

## 📈 Project Statistics

- **Total API Endpoints:** 80+
- **Database Tables:** 27
- **Models:** 24
- **Controllers:** 19
- **Services:** 13
- **Repositories:** 13
- **Progress:** 80% Complete

---

<p align="center">
  <strong>Built with ❤️ using Laravel 11</strong>
</p>

<p align="center">
  <strong>Last Updated:</strong> 2025-01-27 | 
  <strong>Version:</strong> 1.0.0 | 
  <strong>Status:</strong> Active Development 🚀
</p>
