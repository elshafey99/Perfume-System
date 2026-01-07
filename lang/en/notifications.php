<?php

return [
    // Success messages
    'notifications_retrieved_successfully' => 'Notifications retrieved successfully',
    'notification_retrieved_successfully' => 'Notification retrieved successfully',
    'unread_retrieved_successfully' => 'Unread notifications retrieved successfully',
    'notification_created_successfully' => 'Notification created successfully',
    'notification_marked_as_read' => 'Notification marked as read',
    'all_marked_as_read' => 'All notifications marked as read',
    'notification_deleted_successfully' => 'Notification deleted successfully',
    'low_stock_retrieved_successfully' => 'Low stock alerts retrieved successfully',
    'low_stock_check_completed' => 'Low stock check completed successfully',

    // Error messages
    'notification_not_found' => 'Notification not found',
    'notification_creation_failed' => 'Failed to create notification',
    'mark_as_read_failed' => 'Failed to mark notification as read',
    'mark_all_as_read_failed' => 'Failed to mark all notifications as read',
    'notification_deletion_failed' => 'Failed to delete notification',

    // Notification types
    'types' => [
        'low_stock' => 'Low Stock',
        'birthday' => 'Birthday',
        'loyalty_reminder' => 'Loyalty Reminder',
        'system' => 'System',
        'stocktaking' => 'Stocktaking',
    ],

    // Low stock notification
    'low_stock_title' => 'Low Stock Alert',
    'low_stock_message' => 'Product :product has reached minimum level. Current quantity: :quantity, Minimum: :min',
];
