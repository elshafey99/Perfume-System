<?php

return [
    // Success messages
    'returns_retrieved_successfully' => 'تم جلب المرتجعات بنجاح',
    'return_retrieved_successfully' => 'تم جلب المرتجع بنجاح',
    'return_created_successfully' => 'تم إنشاء طلب المرتجع بنجاح',
    'return_approved_successfully' => 'تم الموافقة على المرتجع بنجاح',
    'return_rejected_successfully' => 'تم رفض المرتجع بنجاح',
    'return_processed_successfully' => 'تم معالجة المرتجع بنجاح',
    'return_deleted_successfully' => 'تم حذف المرتجع بنجاح',
    'statistics_retrieved_successfully' => 'تم جلب الإحصائيات بنجاح',

    // Error messages
    'return_not_found' => 'المرتجع غير موجود',
    'sale_not_found' => 'الفاتورة غير موجودة',
    'sale_not_completed' => 'لا يمكن إنشاء مرتجع لفاتورة غير مكتملة',
    'sale_item_not_found' => 'عنصر الفاتورة غير موجود',
    'return_amount_exceeds_item_total' => 'مبلغ الاسترجاع يتجاوز إجمالي العنصر',
    'return_amount_exceeds_sale_total' => 'مبلغ الاسترجاع يتجاوز إجمالي الفاتورة',
    'duplicate_return_exists' => 'يوجد طلب استرجاع معلق أو معتمد لهذه الفاتورة بالفعل',
    'return_amount_exceeds_remaining_total' => 'مبلغ الاسترجاع يتجاوز المبلغ المتبقي للفاتورة',
    'return_amount_exceeds_remaining_item_total' => 'مبلغ الاسترجاع يتجاوز المبلغ المتبقي للعنصر',
    'return_not_pending' => 'المرتجع ليس في حالة انتظار',
    'return_not_approved' => 'المرتجع غير معتمد',
    'only_pending_can_be_deleted' => 'يمكن حذف المرتجعات المعلقة فقط',
    'return_creation_failed' => 'فشل في إنشاء المرتجع',
    'return_approval_failed' => 'فشل في الموافقة على المرتجع',
    'return_rejection_failed' => 'فشل في رفض المرتجع',
    'return_processing_failed' => 'فشل في معالجة المرتجع',
    'return_deletion_failed' => 'فشل في حذف المرتجع',

    // Reasons
    'reasons' => [
        'defective' => 'منتج معيب',
        'wrong_item' => 'منتج خاطئ',
        'customer_request' => 'طلب العميل',
        'other' => 'أخرى',
    ],

    // Types
    'types' => [
        'refund' => 'استرداد نقدي',
        'exchange' => 'استبدال',
        'store_credit' => 'رصيد متجر',
    ],

    // Statuses
    'statuses' => [
        'pending' => 'معلق',
        'approved' => 'معتمد',
        'rejected' => 'مرفوض',
        'completed' => 'مكتمل',
    ],

    // Validation
    'validation' => [
        'sale_id_required' => 'رقم الفاتورة مطلوب',
        'sale_id_exists' => 'الفاتورة غير موجودة',
        'sale_item_id_exists' => 'عنصر الفاتورة غير موجود',
        'return_reason_required' => 'سبب الإرجاع مطلوب',
        'return_reason_in' => 'سبب الإرجاع غير صالح',
        'return_type_required' => 'نوع الإرجاع مطلوب',
        'return_type_in' => 'نوع الإرجاع غير صالح',
        'return_amount_required' => 'مبلغ الاسترجاع مطلوب',
        'return_amount_numeric' => 'مبلغ الاسترجاع يجب أن يكون رقماً',
        'return_amount_min' => 'مبلغ الاسترجاع يجب أن يكون أكبر من صفر',
        'notes_max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف',
    ],
];
