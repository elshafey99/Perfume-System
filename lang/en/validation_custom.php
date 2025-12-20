<?php

return [
    // Custom attribute names
    'attributes' => [
        'name'                  => 'Name',
        'phone'                 => 'Phone number',
        'email'                 => 'Email address',
        'password'              => 'Password',
        'password_confirmation' => 'Password confirmation',
        'code'                  => 'Verification code',
    ],

    // Custom messages
    'custom' => [
        'name' => [
            'required' => 'Name is required',
        ],
        'phone' => [
            'required' => 'Phone number is required',
            'unique'   => 'This phone number is already registered',
            'exists'   => 'This phone number is not registered',
        ],
        'email' => [
            'email'  => 'Invalid email address',
            'unique' => 'This email is already registered',
        ],
        'password' => [
            'required'  => 'Password is required',
            'min'       => 'Password must be at least 8 characters',
            'confirmed' => 'Password confirmation does not match',
        ],
        'code' => [
            'required' => 'Verification code is required',
            'min'      => 'Invalid verification code',
            'max'      => 'Invalid verification code',
        ],
    ],

    // Building Validation Messages
    'building_data_required' => 'Building data is required',
    'building_data_array' => 'Building data must be an array',
    'building_name_required' => 'Building name is required',
    'building_name_string' => 'Building name must be a string',
    'building_name_max' => 'Building name must not exceed 255 characters',
    'building_type_required' => 'Building type is required',
    'building_type_invalid' => 'Invalid building type',

    // Floor Validation Messages
    'floors_required' => 'Floors are required',
    'floors_array' => 'Floors must be an array',
    'floors_min' => 'At least one floor must be added',
    'floor_name_required' => 'Floor name is required',
    'floor_name_string' => 'Floor name must be a string',
    'floor_name_max' => 'Floor name must not exceed 255 characters',
    'floor_number_required' => 'Floor number is required',
    'floor_number_integer' => 'Floor number must be an integer',
    'floor_number_duplicate' => 'Floor number is duplicate',
    'floor_type_required' => 'Floor type is required',
    'floor_type_invalid' => 'Invalid floor type',

    // Unit Validation Messages
    'units_required' => 'Units are required',
    'units_array' => 'Units must be an array',
    'units_min' => 'At least one unit must be added',
    'unit_type_required' => 'Unit type is required',
    'unit_type_invalid' => 'Invalid unit type',
    'unit_number_required' => 'Unit number is required',
    'unit_number_string' => 'Unit number must be a string',
    'unit_number_max' => 'Unit number must not exceed 50 characters',
    'unit_number_duplicate' => 'Unit number is duplicate',
    'unit_name_string' => 'Unit name must be a string',
    'unit_name_max' => 'Unit name must not exceed 255 characters',
    'area_numeric' => 'Area must be a number',
    'area_min' => 'Area must be greater than or equal to zero',
    'unit_status_required' => 'Unit status is required',
    'unit_status_invalid' => 'Invalid unit status',
    'unit_numbers_must_be_unique_within_floor' => 'Unit numbers must be unique within the same floor',

    // Floor Count Validation
    'floor_count_integer' => 'Floor count must be an integer',
    'floor_count_min' => 'Floor count must be greater than or equal to zero',

    // Property Validation Messages
    'property_name_required' => 'Property name is required',
    'property_name_string' => 'Property name must be a string',
    'property_name_max' => 'Property name must not exceed 255 characters',
    'country_required' => 'Country is required',
    'country_exists' => 'Selected country does not exist',
    'governorate_required' => 'Governorate is required',
    'governorate_exists' => 'Selected governorate does not exist',
    'city_required' => 'City is required',
    'city_exists' => 'Selected city does not exist',
    'address_street_required' => 'Street address is required',
    'district_code_required' => 'District code is required',
    'area_land_required' => 'Land area is required',
    'area_land_numeric' => 'Land area must be a number',
    'area_land_min' => 'Land area must be greater than zero',
    'area_built_required' => 'Built area is required',
    'area_built_numeric' => 'Built area must be a number',
    'area_built_min' => 'Built area must be greater than zero',
    'specs_details_required' => 'Specifications details are required',
    'specs_details_array' => 'Specifications details must be an array',
];

