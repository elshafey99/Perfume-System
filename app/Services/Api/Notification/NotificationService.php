<?php

namespace App\Services\Api\Notification;

use App\Repositories\Api\Notification\NotificationRepository;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    protected NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Get all notifications
     */
    public function getAll(?int $perPage = 15, ?string $type = null, ?bool $isRead = null): array
    {
        $notifications = $this->notificationRepository->getAll($perPage, $type, $isRead);

        return [
            'success' => true,
            'data' => $notifications,
        ];
    }

    /**
     * Get unread notifications
     */
    public function getUnread(): array
    {
        $notifications = $this->notificationRepository->getUnread();
        $count = $this->notificationRepository->getUnreadCount();

        return [
            'success' => true,
            'data' => [
                'notifications' => $notifications,
                'count' => $count,
            ],
        ];
    }

    /**
     * Get notification by ID
     */
    public function getById(int $id): array
    {
        $notification = $this->notificationRepository->findById($id);

        if (!$notification) {
            return [
                'success' => false,
                'message' => __('notifications.notification_not_found'),
            ];
        }

        return [
            'success' => true,
            'data' => $notification,
        ];
    }

    /**
     * Create new notification
     */
    public function create(array $data): array
    {
        try {
            $notification = $this->notificationRepository->create($data);

            return [
                'success' => true,
                'data' => $notification,
                'message' => __('notifications.notification_created_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('notifications.notification_creation_failed'),
            ];
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $id): array
    {
        $notification = $this->notificationRepository->findById($id);

        if (!$notification) {
            return [
                'success' => false,
                'message' => __('notifications.notification_not_found'),
            ];
        }

        try {
            $this->notificationRepository->markAsRead($notification);

            return [
                'success' => true,
                'data' => $notification->fresh(),
                'message' => __('notifications.notification_marked_as_read'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('notifications.mark_as_read_failed'),
            ];
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): array
    {
        try {
            $count = $this->notificationRepository->markAllAsRead();

            return [
                'success' => true,
                'data' => ['marked_count' => $count],
                'message' => __('notifications.all_marked_as_read'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('notifications.mark_all_as_read_failed'),
            ];
        }
    }

    /**
     * Delete notification
     */
    public function delete(int $id): array
    {
        $notification = $this->notificationRepository->findById($id);

        if (!$notification) {
            return [
                'success' => false,
                'message' => __('notifications.notification_not_found'),
            ];
        }

        try {
            $this->notificationRepository->delete($notification);

            return [
                'success' => true,
                'message' => __('notifications.notification_deleted_successfully'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('notifications.notification_deletion_failed'),
            ];
        }
    }

    /**
     * Get low stock alerts
     */
    public function getLowStockAlerts(): array
    {
        // Get products with low stock (current_stock <= min_stock_level)
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock_level')
            ->where('min_stock_level', '>', 0)
            ->with(['category', 'supplier'])
            ->get();

        return [
            'success' => true,
            'data' => [
                'products' => $lowStockProducts,
                'count' => $lowStockProducts->count(),
            ],
        ];
    }

    /**
     * Check and create low stock notifications
     */
    public function checkLowStock(): array
    {
        // Get products where current_stock <= min_stock_level
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock_level')
            ->where('min_stock_level', '>', 0)
            ->get();

        $notificationsCreated = 0;

        foreach ($lowStockProducts as $product) {
            // Check if notification already exists for this product
            $exists = Notification::where('type', 'low_stock')
                ->where('data->product_id', $product->id)
                ->where('is_read', false)
                ->exists();

            if (!$exists) {
                Notification::create([
                    'type' => 'low_stock',
                    'title' => __('notifications.low_stock_title'),
                    'message' => __('notifications.low_stock_message', [
                        'product' => $product->name,
                        'quantity' => $product->current_stock,
                        'min' => $product->min_stock_level,
                    ]),
                    'recipient_type' => 'admin',
                    'recipient_id' => null, // Broadcast to all admins
                    'data' => [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'current_stock' => $product->current_stock,
                        'min_stock_level' => $product->min_stock_level,
                    ],
                ]);
                $notificationsCreated++;
            }
        }

        return [
            'success' => true,
            'data' => [
                'low_stock_products' => $lowStockProducts->count(),
                'notifications_created' => $notificationsCreated,
            ],
            'message' => __('notifications.low_stock_check_completed'),
        ];
    }
}
