<?php

return [
    // Success messages
    'purchases_retrieved_successfully' => 'تم جلب المشتريات بنجاح',
    'purchase_retrieved_successfully' => 'تم جلب أمر الشراء بنجاح',
    'purchase_created_successfully' => 'تم إنشاء أمر الشراء بنجاح',
    'purchase_updated_successfully' => 'تم تحديث أمر الشراء بنجاح',
    'purchase_cancelled_successfully' => 'تم إلغاء أمر الشراء بنجاح',
    'purchase_received_successfully' => 'تم استلام البضاعة وإضافتها للمخزون بنجاح',

    // Error messages
    'purchase_not_found' => 'أمر الشراء غير موجود',
    'purchase_creation_failed' => 'فشل إنشاء أمر الشراء',
    'purchase_update_failed' => 'فشل تحديث أمر الشراء',
    'purchase_cancellation_failed' => 'فشل إلغاء أمر الشراء',
    'purchase_receive_failed' => 'فشل استلام البضاعة',
    'purchase_already_received' => 'أمر الشراء مستلم بالفعل',
    'purchase_already_cancelled' => 'أمر الشراء ملغي بالفعل',
    'cannot_modify_received' => 'لا يمكن تعديل أمر شراء مستلم',
    'cannot_cancel_received' => 'لا يمكن إلغاء أمر شراء مستلم',
    'cannot_modify_purchase' => 'لا يمكن تعديل هذا الأمر',

    // Items
    'items_retrieved_successfully' => 'تم جلب عناصر أمر الشراء بنجاح',
    'item_added_successfully' => 'تم إضافة المنتج بنجاح',
    'item_updated_successfully' => 'تم تحديث المنتج بنجاح',
    'item_removed_successfully' => 'تم حذف المنتج بنجاح',
    'item_not_found' => 'المنتج غير موجود في أمر الشراء',
    'item_addition_failed' => 'فشل إضافة المنتج',
    'item_update_failed' => 'فشل تحديث المنتج',
    'item_removal_failed' => 'فشل حذف المنتج',

    // Statuses
    'statuses' => [
        'pending' => 'قيد الانتظار',
        'received' => 'تم الاستلام',
        'cancelled' => 'ملغي',
    ],
];
