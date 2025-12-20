<?php

return [
    // Custom attribute names
    'attributes' => [
        'name'                  => 'الاسم',
        'phone'                 => 'رقم الهاتف',
        'email'                 => 'البريد الإلكتروني',
        'password'              => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'code'                  => 'رمز التحقق',
    ],

    // Custom messages
    'custom' => [
        'name' => [
            'required' => 'الاسم مطلوب',
        ],
        'phone' => [
            'required' => 'رقم الهاتف مطلوب',
            'unique'   => 'رقم الهاتف مستخدم بالفعل',
            'exists'   => 'رقم الهاتف غير مسجل',
        ],
        'email' => [
            'email'  => 'البريد الإلكتروني غير صحيح',
            'unique' => 'البريد الإلكتروني مستخدم بالفعل',
        ],
        'password' => [
            'required'  => 'كلمة المرور مطلوبة',
            'min'       => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'confirmed' => 'كلمة المرور غير متطابقة',
        ],
        'code' => [
            'required' => 'رمز التحقق مطلوب',
            'min'      => 'رمز التحقق غير صحيح',
            'max'      => 'رمز التحقق غير صحيح',
        ],
    ],

    // Building Validation Messages
    'building_data_required' => 'بيانات المبنى مطلوبة',
    'building_data_array' => 'بيانات المبنى يجب أن تكون مصفوفة',
    'building_name_required' => 'اسم المبنى مطلوب',
    'building_name_string' => 'اسم المبنى يجب أن يكون نص',
    'building_name_max' => 'اسم المبنى يجب ألا يتجاوز 255 حرف',
    'building_type_required' => 'نوع المبنى مطلوب',
    'building_type_invalid' => 'نوع المبنى غير صحيح',

    // Floor Validation Messages
    'floors_required' => 'الطوابق مطلوبة',
    'floors_array' => 'الطوابق يجب أن تكون مصفوفة',
    'floors_min' => 'يجب إضافة طابق واحد على الأقل',
    'floor_name_required' => 'اسم الطابق مطلوب',
    'floor_name_string' => 'اسم الطابق يجب أن يكون نص',
    'floor_name_max' => 'اسم الطابق يجب ألا يتجاوز 255 حرف',
    'floor_number_required' => 'رقم الطابق مطلوب',
    'floor_number_integer' => 'رقم الطابق يجب أن يكون رقم صحيح',
    'floor_number_duplicate' => 'رقم الطابق مكرر',
    'floor_type_required' => 'نوع الطابق مطلوب',
    'floor_type_invalid' => 'نوع الطابق غير صحيح',

    // Unit Validation Messages
    'units_required' => 'الوحدات مطلوبة',
    'units_array' => 'الوحدات يجب أن تكون مصفوفة',
    'units_min' => 'يجب إضافة وحدة واحدة على الأقل',
    'unit_type_required' => 'نوع الوحدة مطلوب',
    'unit_type_invalid' => 'نوع الوحدة غير صحيح',
    'unit_number_required' => 'رقم الوحدة مطلوب',
    'unit_number_string' => 'رقم الوحدة يجب أن يكون نص',
    'unit_number_max' => 'رقم الوحدة يجب ألا يتجاوز 50 حرف',
    'unit_number_duplicate' => 'رقم الوحدة مكرر',
    'unit_name_string' => 'اسم الوحدة يجب أن يكون نص',
    'unit_name_max' => 'اسم الوحدة يجب ألا يتجاوز 255 حرف',
    'area_numeric' => 'المساحة يجب أن تكون رقم',
    'area_min' => 'المساحة يجب أن تكون أكبر من أو تساوي صفر',
    'unit_status_required' => 'حالة الوحدة مطلوبة',
    'unit_status_invalid' => 'حالة الوحدة غير صحيحة',
    'unit_numbers_must_be_unique_within_floor' => 'أرقام الوحدات يجب أن تكون فريدة داخل نفس الطابق',

    // Floor Count Validation
    'floor_count_integer' => 'عدد الطوابق يجب أن يكون رقم صحيح',
    'floor_count_min' => 'عدد الطوابق يجب أن يكون أكبر من أو يساوي صفر',

    // Property Validation Messages
    'property_name_required' => 'اسم العقار مطلوب',
    'property_name_string' => 'اسم العقار يجب أن يكون نص',
    'property_name_max' => 'اسم العقار يجب ألا يتجاوز 255 حرف',
    'country_required' => 'الدولة مطلوبة',
    'country_exists' => 'الدولة المحددة غير موجودة',
    'governorate_required' => 'المحافظة مطلوبة',
    'governorate_exists' => 'المحافظة المحددة غير موجودة',
    'city_required' => 'المدينة مطلوبة',
    'city_exists' => 'المدينة المحددة غير موجودة',
    'address_street_required' => 'عنوان الشارع مطلوب',
    'district_code_required' => 'الرقم الكودي للحي مطلوب',
    'area_land_required' => 'مساحة الأرض مطلوبة',
    'area_land_numeric' => 'مساحة الأرض يجب أن تكون رقم',
    'area_land_min' => 'مساحة الأرض يجب أن تكون أكبر من صفر',
    'area_built_required' => 'المساحة المبنية مطلوبة',
    'area_built_numeric' => 'المساحة المبنية يجب أن تكون رقم',
    'area_built_min' => 'المساحة المبنية يجب أن تكون أكبر من صفر',
    'specs_details_required' => 'تفاصيل المواصفات مطلوبة',
    'specs_details_array' => 'تفاصيل المواصفات يجب أن تكون مصفوفة',
];
