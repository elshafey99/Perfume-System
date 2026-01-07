<?php

return [
    // Success messages
    'notifications_retrieved_successfully' => 'تم جلب الإشعارات بنجاح',
    'notification_retrieved_successfully' => 'تم جلب الإشعار بنجاح',
    'unread_retrieved_successfully' => 'تم جلب الإشعارات غير المقروءة بنجاح',
    'notification_created_successfully' => 'تم إنشاء الإشعار بنجاح',
    'notification_marked_as_read' => 'تم تحديد الإشعار كمقروء',
    'all_marked_as_read' => 'تم تحديد جميع الإشعارات كمقروءة',
    'notification_deleted_successfully' => 'تم حذف الإشعار بنجاح',
    'low_stock_retrieved_successfully' => 'تم جلب تنبيهات نقص المخزون بنجاح',
    'low_stock_check_completed' => 'تم فحص نقص المخزون بنجاح',

    // Error messages
    'notification_not_found' => 'الإشعار غير موجود',
    'notification_creation_failed' => 'فشل في إنشاء الإشعار',
    'mark_as_read_failed' => 'فشل في تحديد الإشعار كمقروء',
    'mark_all_as_read_failed' => 'فشل في تحديد جميع الإشعارات كمقروءة',
    'notification_deletion_failed' => 'فشل في حذف الإشعار',

    // Notification types
    'types' => [
        'low_stock' => 'نقص مخزون',
        'birthday' => 'عيد ميلاد',
        'loyalty_reminder' => 'تذكير ولاء',
        'system' => 'نظام',
        'stocktaking' => 'جرد',
    ],

    // Low stock notification
    'low_stock_title' => 'تنبيه نقص مخزون',
    'low_stock_message' => 'المنتج :product وصل للحد الأدنى. الكمية الحالية: :quantity، الحد الأدنى: :min',
];
