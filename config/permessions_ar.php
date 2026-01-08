<?php

return [
    // ==========================================
    // Dashboard & Reports
    // ==========================================
    'dashboard'                        => 'لوحة التحكم',
    'dashboard.view'                   => 'عرض لوحة التحكم',
    'dashboard.statistics'             => 'عرض الإحصائيات',

    'reports'                          => 'التقارير',
    'reports.view'                     => 'عرض التقارير',
    'reports.sales'                    => 'تقارير المبيعات',
    'reports.purchases'                => 'تقارير المشتريات',
    'reports.inventory'                => 'تقارير المخزون',
    'reports.financial'                => 'التقارير المالية',
    'reports.export'                   => 'تصدير التقارير',

    // ==========================================
    // User Management
    // ==========================================
    'users'                            => 'إدارة المستخدمين',
    'users.view'                       => 'عرض المستخدمين',
    'users.create'                     => 'إضافة مستخدم',
    'users.update'                     => 'تعديل مستخدم',
    'users.delete'                     => 'حذف مستخدم',
    'users.change-status'              => 'تغيير حالة المستخدم',

    'roles'                            => 'إدارة الأدوار والصلاحيات',
    'roles.view'                       => 'عرض الأدوار',
    'roles.create'                     => 'إضافة دور',
    'roles.update'                     => 'تعديل دور',
    'roles.delete'                     => 'حذف دور',
    'roles.permissions'                => 'عرض الصلاحيات',

    'profile'                          => 'الملف الشخصي',
    'profile.view'                     => 'عرض الملف الشخصي',
    'profile.update'                   => 'تعديل الملف الشخصي',
    'profile.change-password'          => 'تغيير كلمة المرور',

    // ==========================================
    // Product Management
    // ==========================================
    'products'                         => 'إدارة المنتجات',
    'products.view'                    => 'عرض المنتجات',
    'products.create'                  => 'إضافة منتج',
    'products.update'                  => 'تعديل منتج',
    'products.delete'                  => 'حذف منتج',
    'products.low-stock'               => 'عرض المنتجات منخفضة المخزون',

    'categories'                       => 'إدارة الأصناف',
    'categories.view'                  => 'عرض الأصناف',
    'categories.create'                => 'إضافة صنف',
    'categories.update'                => 'تعديل صنف',
    'categories.delete'                => 'حذف صنف',

    'product-types'                    => 'إدارة أنواع المنتجات',
    'product-types.view'               => 'عرض أنواع المنتجات',
    'product-types.create'             => 'إضافة نوع منتج',
    'product-types.update'             => 'تعديل نوع منتج',
    'product-types.delete'             => 'حذف نوع منتج',

    'compositions'                     => 'إدارة التركيبات والخلطات',
    'compositions.view'                => 'عرض التركيبات',
    'compositions.create'              => 'إضافة تركيبة',
    'compositions.update'              => 'تعديل تركيبة',
    'compositions.delete'              => 'حذف تركيبة',
    'compositions.magic-recipes'       => 'عرض الوصفات السحرية',
    'compositions.ingredients'         => 'إدارة مكونات التركيبة',

    // ==========================================
    // Inventory Management
    // ==========================================
    'inventory-transactions'           => 'إدارة حركات المخزون',
    'inventory-transactions.view'      => 'عرض حركات المخزون',
    'inventory-transactions.create'    => 'إضافة حركة مخزون',
    'inventory-transactions.delete'    => 'حذف حركة مخزون',

    'stocktakings'                     => 'إدارة الجرد',
    'stocktakings.view'                => 'عرض الجرد',
    'stocktakings.create'              => 'إنشاء جرد جديد',
    'stocktakings.update'              => 'تعديل الجرد',
    'stocktakings.delete'              => 'حذف الجرد',
    'stocktakings.complete'            => 'إتمام الجرد',

    // ==========================================
    // Sales & Purchases
    // ==========================================
    'sales'                            => 'إدارة المبيعات',
    'sales.view'                       => 'عرض المبيعات',
    'sales.create'                     => 'إنشاء فاتورة بيع',
    'sales.update'                     => 'تعديل فاتورة بيع',
    'sales.delete'                     => 'حذف فاتورة بيع',
    'sales.cancel'                     => 'إلغاء فاتورة بيع',
    'sales.today-summary'              => 'عرض ملخص اليوم',
    'sales.add-items'                  => 'إضافة عناصر للفاتورة',
    'sales.remove-items'               => 'حذف عناصر من الفاتورة',
    'sales.record-payment'             => 'تسجيل دفعة',

    'purchases'                        => 'إدارة المشتريات',
    'purchases.view'                   => 'عرض المشتريات',
    'purchases.create'                 => 'إنشاء فاتورة شراء',
    'purchases.update'                 => 'تعديل فاتورة شراء',
    'purchases.delete'                 => 'حذف فاتورة شراء',
    'purchases.receive'                => 'استلام المشتريات',

    'returns'                          => 'إدارة المرتجعات',
    'returns.view'                     => 'عرض المرتجعات',
    'returns.create'                   => 'إنشاء مرتجع',
    'returns.update'                   => 'تعديل مرتجع',
    'returns.delete'                   => 'حذف مرتجع',
    'returns.approve'                  => 'الموافقة على المرتجع',
    'returns.reject'                   => 'رفض المرتجع',
    'returns.statistics'               => 'عرض إحصائيات المرتجعات',

    // ==========================================
    // Customers & Suppliers
    // ==========================================
    'customers'                        => 'إدارة العملاء',
    'customers.view'                   => 'عرض العملاء',
    'customers.create'                 => 'إضافة عميل',
    'customers.update'                 => 'تعديل عميل',
    'customers.delete'                 => 'حذف عميل',
    'customers.loyalty-points'         => 'إدارة نقاط الولاء',
    'customers.redeem-points'          => 'استبدال نقاط الولاء',
    'customers.purchase-history'       => 'عرض سجل مشتريات العميل',

    'suppliers'                        => 'إدارة الموردين',
    'suppliers.view'                   => 'عرض الموردين',
    'suppliers.create'                 => 'إضافة مورد',
    'suppliers.update'                 => 'تعديل مورد',
    'suppliers.delete'                 => 'حذف مورد',

    // ==========================================
    // Finance
    // ==========================================
    'expenses'                         => 'إدارة المصروفات',
    'expenses.view'                    => 'عرض المصروفات',
    'expenses.create'                  => 'إضافة مصروف',
    'expenses.update'                  => 'تعديل مصروف',
    'expenses.delete'                  => 'حذف مصروف',
    'expenses.by-category'             => 'عرض المصروفات حسب الفئة',

    // ==========================================
    // Notifications
    // ==========================================
    'notifications'                    => 'إدارة الإشعارات',
    'notifications.view'               => 'عرض الإشعارات',
    'notifications.mark-read'          => 'تحديد كمقروء',
    'notifications.delete'             => 'حذف الإشعارات',

    // ==========================================
    // Settings
    // ==========================================
    'settings'                         => 'الإعدادات',
    'settings.view'                    => 'عرض الإعدادات',
    'settings.update'                  => 'تعديل الإعدادات',
];
