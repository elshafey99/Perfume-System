<?php

return [
    // Success messages
    'customers_retrieved_successfully' => 'تم جلب العملاء بنجاح',
    'customer_retrieved_successfully' => 'تم جلب بيانات العميل بنجاح',
    'customer_created_successfully' => 'تم إنشاء العميل بنجاح',
    'customer_updated_successfully' => 'تم تحديث بيانات العميل بنجاح',
    'customer_deleted_successfully' => 'تم حذف العميل بنجاح',
    
    // Error messages
    'customer_not_found' => 'العميل غير موجود',
    'phone_already_exists' => 'رقم الجوال مسجل بالفعل',
    'email_already_exists' => 'البريد الإلكتروني مسجل بالفعل',
    'customer_creation_failed' => 'فشل إنشاء العميل',
    'customer_update_failed' => 'فشل تحديث بيانات العميل',
    'customer_deletion_failed' => 'فشل حذف العميل',
    'phone_too_short' => 'رقم الجوال يجب أن يكون 3 أحرف على الأقل',

    // Preferences
    'preferences_retrieved_successfully' => 'تم جلب تفضيلات العميل بنجاح',
    'preferences_updated_successfully' => 'تم تحديث تفضيلات العميل بنجاح',
    'preferences_update_failed' => 'فشل تحديث التفضيلات',

    // Sales history
    'sales_history_retrieved_successfully' => 'تم جلب سجل مشتريات العميل بنجاح',

    // Loyalty Points
    'loyalty_balance_retrieved_successfully' => 'تم جلب رصيد النقاط بنجاح',
    'loyalty_history_retrieved_successfully' => 'تم جلب سجل النقاط بنجاح',
    'points_earned_successfully' => 'تم إضافة النقاط بنجاح',
    'points_redeemed_successfully' => 'تم استبدال النقاط بنجاح',
    'points_earning_failed' => 'فشل إضافة النقاط',
    'points_redemption_failed' => 'فشل استبدال النقاط',
    'insufficient_points' => 'رصيد النقاط غير كافي',
    'invalid_points_amount' => 'قيمة النقاط غير صالحة',

    // Loyalty Levels
    'loyalty_levels' => [
        'bronze' => 'برونزي',
        'silver' => 'فضي',
        'gold' => 'ذهبي',
        'platinum' => 'بلاتيني',
    ],

    // Genders
    'genders' => [
        'male' => 'ذكر',
        'female' => 'أنثى',
        'other' => 'آخر',
    ],

    // Point types
    'point_types' => [
        'earned' => 'مكتسبة',
        'redeemed' => 'مستبدلة',
        'expired' => 'منتهية',
        'adjusted' => 'معدلة',
    ],
];
