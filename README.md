<p align="center"><strong>Perfume Shop Management System</strong> — Laravel 11 (PHP 8.2)</p>

## Overview

نظام إدارة محل عطور متكامل (Perfume Shop Management System) مبني على Laravel 11. النظام يدعم إدارة المخزون المعقدة (وحدات قياس متعددة)، عمليات تركيب وخلط العطور، نقطة البيع (POS) المتقدمة، إدارة علاقات العملاء (CRM)، والتقارير المالية.

**A comprehensive Perfume Shop Management System built on Laravel 11. The system supports complex inventory management (multiple measurement units), perfume composition and mixing operations, advanced POS, CRM, and financial reports.**

## 📚 Documentation

تم إعداد تحليل شامل ومتكامل للمشروع. يرجى مراجعة الملفات التالية:

**Comprehensive project analysis has been prepared. Please review the following files:**

- **[PROJECT_SUMMARY.md](./PROJECT_SUMMARY.md)** - ملخص المشروع / Project Summary
- **[REQUIREMENTS_ANALYSIS.md](./REQUIREMENTS_ANALYSIS.md)** - تحليل المتطلبات / Requirements Analysis
- **[DATABASE_DESIGN.md](./DATABASE_DESIGN.md)** - تصميم قاعدة البيانات / Database Design
- **[FEATURES_GAP_ANALYSIS.md](./FEATURES_GAP_ANALYSIS.md)** - تحليل الفجوات والميزات / Features Gap Analysis
- **[DEVELOPMENT_PLAN.md](./DEVELOPMENT_PLAN.md)** - خطة التطوير / Development Plan
- **[API_10_DAYS_PLAN.md](./API_10_DAYS_PLAN.md)** - خطة API في 10 أيام / 10 Days API Plan ⭐

## 🎯 Key Features

### Core Features:
- ✅ **إدارة المخزون** - Inventory Management (multiple units: gram, ml, tola, piece)
- ✅ **نظام البيع (POS)** - Point of Sale System (touch-friendly interface)
- ✅ **إدارة التركيبات** - Perfume Composition Management (auto-deduct ingredients)
- ✅ **نظام CRM** - Customer Relationship Management (preferences, loyalty points)
- ✅ **المحاسبة والتقارير** - Accounting & Financial Reports (VAT, profitability)
- ✅ **ميزة الوصفات السحرية** - Magic Recipes Feature (famous perfume formulas)
- ✅ **لوحة التحكم** - Dashboard (real-time analytics)
- ✅ **جرد المخزون بالذكاء الاصطناعي** - AI-powered Inventory Management

### Missing Features (High Priority):
- 🔴 إدارة الموردين - Suppliers Management
- 🔴 نظام الجرد الدوري - Stocktaking System
- 🔴 نظام النسخ الاحتياطي - Backup System
- 🔴 نظام Audit Log - Audit Logging System

## Tech Stack

-   PHP 8.2
-   Laravel 11.31
-   Laravel Sanctum (API Authentication)
-   Spatie Laravel Permission (Role & Permissions)
-   Livewire (UI Components)
-   MySQL (Database)
-   Tailwind CSS (Styling)
-   Chart.js (Charts & Analytics)

## Requirements

-   PHP ^8.2
-   Composer
-   A database (MySQL/PostgreSQL) with appropriate PHP extensions
-   Optional: Node.js (only if you plan to run frontend assets)

## Getting Started

1. Install dependencies

```bash
composer install
```

2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure database credentials in `.env` (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

3. Run database migrations

```bash
php artisan migrate
```

4. Start the development server

```bash
php artisan serve
```

Optional (full dev workflow: server + queue + logs + Vite)

```bash
composer run dev
```

## Authentication

Most routes are protected by `auth:sanctum`.

-   Login: `POST /api/login` → returns a token. Use it as `Authorization: Bearer <token>`.
-   Logout: `GET /api/logout`.

## 🗄️ Database Structure

The system includes 19 main tables:

- **Products & Categories** - إدارة المنتجات والفئات
- **Compositions & Ingredients** - التركيبات ومكوناتها
- **Sales & Customers** - المبيعات والعملاء
- **Inventory Transactions** - حركات المخزون
- **Suppliers & Purchases** - الموردين والمشتريات
- **Employees & Branches** - الموظفين والفروع
- **Loyalty Points & Notifications** - نقاط الولاء والإشعارات
- **Audit Logs** - سجل العمليات

For detailed database design, see [DATABASE_DESIGN.md](./DATABASE_DESIGN.md)

## 📅 Development Timeline

**Estimated Duration:** 12-16 weeks

- Week 1: Setup & Preparation
- Week 2-4: Infrastructure & Dashboard
- Week 5-6: Inventory Management
- Week 7-10: POS System
- Week 11-12: CRM & Loyalty
- Week 13-14: Accounting & Reports
- Week 15-16: Advanced Features
- Week 17-18: Missing Features (High Priority)
- Week 19-20: Testing & Optimization

For detailed development plan, see [DEVELOPMENT_PLAN.md](./DEVELOPMENT_PLAN.md)

## Useful Commands

```bash
php artisan migrate:fresh        # rebuild the database
php artisan route:list | cat     # list routes without pager
php artisan tinker               # interact with the app
```

## 🚀 Next Steps

1. ✅ Database Migrations created (18 tables)
2. Create Models with relationships
3. Implement APIs according to [API_10_DAYS_PLAN.md](./API_10_DAYS_PLAN.md)
4. Test all APIs
5. Frontend integration

## 📅 10 Days API Plan

See [API_10_DAYS_PLAN.md](./API_10_DAYS_PLAN.md) for detailed daily plan to complete all APIs in 10 days.

## 📝 Notes

- The system supports Arabic and English languages
- Multiple measurement units: piece, gram, ml, tola, quarter_tola
- Automatic inventory deduction for perfume compositions
- AI-powered inventory predictions
- Modern Luxury UI/UX design (Matte Black + Royal Gold)

## 🔒 Security

- Data encryption for sensitive information
- Role-based access control (RBAC)
- Audit logging for all operations
- Automatic daily backups

## License

MIT
