<?php

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Notification\NotificationResource;
use App\Services\Api\Notification\NotificationService;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get all notifications
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $type = $request->input('type');
        $isRead = $request->has('is_read') ? filter_var($request->input('is_read'), FILTER_VALIDATE_BOOLEAN) : null;

        $result = $this->notificationService->getAll($perPage, $type, $isRead);

        $data = $result['data'];

        // If paginated
        if (method_exists($data, 'items')) {
            return ApiResponse::paginated(
                NotificationResource::collection($data->items()),
                $data,
                __('notifications.notifications_retrieved_successfully')
            );
        }

        // If collection
        return ApiResponse::success(
            NotificationResource::collection($data),
            __('notifications.notifications_retrieved_successfully')
        );
    }

    /**
     * Get unread notifications
     */
    public function unread(): JsonResponse
    {
        $result = $this->notificationService->getUnread();

        return ApiResponse::success([
            'notifications' => NotificationResource::collection($result['data']['notifications']),
            'count' => $result['data']['count'],
        ], __('notifications.unread_retrieved_successfully'));
    }

    /**
     * Get notification by ID
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->notificationService->getById($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 404);
        }

        return ApiResponse::success(
            new NotificationResource($result['data']),
            __('notifications.notification_retrieved_successfully')
        );
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $id): JsonResponse
    {
        $result = $this->notificationService->markAsRead($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            new NotificationResource($result['data']),
            $result['message']
        );
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $result = $this->notificationService->markAllAsRead();

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(
            $result['data'],
            $result['message']
        );
    }

    /**
     * Delete notification
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->notificationService->delete($id);

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 400);
        }

        return ApiResponse::success(null, $result['message']);
    }

    /**
     * Get low stock alerts
     */
    public function lowStock(): JsonResponse
    {
        $result = $this->notificationService->getLowStockAlerts();

        return ApiResponse::success(
            $result['data'],
            __('notifications.low_stock_retrieved_successfully')
        );
    }

    /**
     * Check and create low stock notifications
     */
    public function checkLowStock(): JsonResponse
    {
        $result = $this->notificationService->checkLowStock();

        return ApiResponse::success(
            $result['data'],
            $result['message']
        );
    }
}
