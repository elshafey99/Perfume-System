<?php

return [
    // Success messages
    'returns_retrieved_successfully' => 'Returns retrieved successfully',
    'return_retrieved_successfully' => 'Return retrieved successfully',
    'return_created_successfully' => 'Return request created successfully',
    'return_approved_successfully' => 'Return approved successfully',
    'return_rejected_successfully' => 'Return rejected successfully',
    'return_processed_successfully' => 'Return processed successfully',
    'return_deleted_successfully' => 'Return deleted successfully',
    'statistics_retrieved_successfully' => 'Statistics retrieved successfully',

    // Error messages
    'return_not_found' => 'Return not found',
    'sale_not_found' => 'Sale not found',
    'sale_not_completed' => 'Cannot create return for incomplete sale',
    'sale_item_not_found' => 'Sale item not found',
    'return_amount_exceeds_item_total' => 'Return amount exceeds item total',
    'return_amount_exceeds_sale_total' => 'Return amount exceeds sale total',
    'duplicate_return_exists' => 'A pending or approved return already exists for this sale',
    'return_amount_exceeds_remaining_total' => 'Return amount exceeds remaining sale total',
    'return_amount_exceeds_remaining_item_total' => 'Return amount exceeds remaining item total',
    'return_not_pending' => 'Return is not in pending status',
    'return_not_approved' => 'Return is not approved',
    'only_pending_can_be_deleted' => 'Only pending returns can be deleted',
    'return_creation_failed' => 'Failed to create return',
    'return_approval_failed' => 'Failed to approve return',
    'return_rejection_failed' => 'Failed to reject return',
    'return_processing_failed' => 'Failed to process return',
    'return_deletion_failed' => 'Failed to delete return',

    // Reasons
    'reasons' => [
        'defective' => 'Defective Product',
        'wrong_item' => 'Wrong Item',
        'customer_request' => 'Customer Request',
        'other' => 'Other',
    ],

    // Types
    'types' => [
        'refund' => 'Refund',
        'exchange' => 'Exchange',
        'store_credit' => 'Store Credit',
    ],

    // Statuses
    'statuses' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
    ],

    // Validation
    'validation' => [
        'sale_id_required' => 'Sale ID is required',
        'sale_id_exists' => 'Sale does not exist',
        'sale_item_id_exists' => 'Sale item does not exist',
        'return_reason_required' => 'Return reason is required',
        'return_reason_in' => 'Invalid return reason',
        'return_type_required' => 'Return type is required',
        'return_type_in' => 'Invalid return type',
        'return_amount_required' => 'Return amount is required',
        'return_amount_numeric' => 'Return amount must be a number',
        'return_amount_min' => 'Return amount must be greater than zero',
        'notes_max' => 'Notes must not exceed 1000 characters',
    ],
];
