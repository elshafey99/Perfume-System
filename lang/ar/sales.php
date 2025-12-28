<?php

return [
    // Success messages
    'sales_retrieved_successfully' => 'تم جلب المبيعات بنجاح',
    'sale_retrieved_successfully' => 'تم جلب الفاتورة بنجاح',
    'sale_created_successfully' => 'تم إنشاء الفاتورة بنجاح',
    'sale_updated_successfully' => 'تم تحديث الفاتورة بنجاح',
    'sale_cancelled_successfully' => 'تم إلغاء الفاتورة بنجاح',
    
    // Error messages
    'sale_not_found' => 'الفاتورة غير موجودة',
    'sale_already_cancelled' => 'الفاتورة ملغية بالفعل',
    'sale_already_paid' => 'الفاتورة مدفوعة بالفعل',
    'sale_creation_failed' => 'فشل إنشاء الفاتورة',
    'sale_update_failed' => 'فشل تحديث الفاتورة',
    'sale_cancellation_failed' => 'فشل إلغاء الفاتورة',
    
    // Items
    'items_retrieved_successfully' => 'تم جلب عناصر الفاتورة بنجاح',
    'item_added_successfully' => 'تم إضافة المنتج بنجاح',
    'item_updated_successfully' => 'تم تحديث المنتج بنجاح',
    'item_removed_successfully' => 'تم حذف المنتج بنجاح',
    'item_not_found' => 'المنتج غير موجود في الفاتورة',
    'item_addition_failed' => 'فشل إضافة المنتج',
    'item_update_failed' => 'فشل تحديث المنتج',
    'item_removal_failed' => 'فشل حذف المنتج',
    
    // Stock
    'insufficient_stock' => 'الكمية المطلوبة غير متوفرة في المخزون',
    
    // Payment
    'payment_recorded_successfully' => 'تم تسجيل الدفع بنجاح',
    'payment_recording_failed' => 'فشل تسجيل الدفع',
    
    // Payment methods
    'payment_methods' => [
        'cash' => 'نقدي',
        'card' => 'بطاقة',
        'bank_transfer' => 'تحويل بنكي',
        'apple_pay' => 'Apple Pay',
        'split' => 'دفع متعدد',
    ],
    
    // Payment statuses
    'payment_statuses' => [
        'pending' => 'قيد الانتظار',
        'paid' => 'مدفوع',
        'partial' => 'مدفوع جزئياً',
        'refunded' => 'مسترجع',
    ],
    
    // Sale statuses
    'statuses' => [
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'refunded' => 'مسترجع',
    ],
    
    // Units
    'units' => [
        'piece' => 'قطعة',
        'gram' => 'جرام',
        'ml' => 'مل',
        'tola' => 'تولة',
        'quarter_tola' => 'ربع تولة',
    ],

    // Quick Sale
    'quick_sale_created_successfully' => 'تم إنشاء البيع السريع بنجاح',

    // Today Summary
    'today_summary_retrieved_successfully' => 'تم جلب ملخص اليوم بنجاح',
    'summary_retrieval_failed' => 'فشل جلب الملخص',

    // Refund
    'sale_refunded_successfully' => 'تم استرجاع الفاتورة بنجاح',
    'partial_refund_successful' => 'تم الاسترجاع الجزئي بنجاح',
    'sale_already_refunded' => 'الفاتورة مسترجعة بالفعل',
    'refund_failed' => 'فشل استرجاع الفاتورة',

    // Discount
    'discount_applied_successfully' => 'تم تطبيق الخصم بنجاح',
    'discount_application_failed' => 'فشل تطبيق الخصم',
    'cannot_modify_sale' => 'لا يمكن تعديل هذه الفاتورة',

    // Composition Sale
    'composition_sale_created_successfully' => 'تم بيع التركيبة بنجاح',
    'composition_sale_failed' => 'فشل بيع التركيبة',

    // Custom Blend
    'custom_blend_created_successfully' => 'تم بيع الخلطة المخصصة بنجاح',
    'custom_blend_failed' => 'فشل بيع الخلطة المخصصة',
];


