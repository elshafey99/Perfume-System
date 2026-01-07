<?php

namespace App\Repositories\Api\Notification;

use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class NotificationRepository
{
    /**
     * Get all notifications for current user with pagination
     */
    public function getAll(int $perPage = 15, ?string $type = null, ?bool $isRead = null): LengthAwarePaginator
    {
        $query = $this->getBaseQuery();

        if ($type) {
            $query->where('type', $type);
        }

        if ($isRead !== null) {
            $query->where('is_read', $isRead);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get unread notifications for current user
     */
    public function getUnread(): Collection
    {
        return $this->getBaseQuery()
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get unread count for current user
     */
    public function getUnreadCount(): int
    {
        return $this->getBaseQuery()
            ->where('is_read', false)
            ->count();
    }

    /**
     * Find notification by ID
     */
    public function findById(int $id): ?Notification
    {
        return $this->getBaseQuery()->find($id);
    }

    /**
     * Create new notification
     */
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): Notification
    {
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return $notification;
    }

    /**
     * Mark all notifications as read for current user
     */
    public function markAllAsRead(): int
    {
        $user = Auth::user();
        
        return Notification::where('recipient_type', $this->getRecipientType($user))
            ->where('recipient_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Delete notification
     */
    public function delete(Notification $notification): bool
    {
        return $notification->delete();
    }

    /**
     * Delete all read notifications for current user
     */
    public function deleteAllRead(): int
    {
        $user = Auth::user();
        
        return Notification::where('recipient_type', $this->getRecipientType($user))
            ->where('recipient_id', $user->id)
            ->where('is_read', true)
            ->delete();
    }

    /**
     * Get low stock notifications
     */
    public function getLowStockNotifications(): Collection
    {
        return Notification::where('type', 'low_stock')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Base query for current user's notifications
     */
    protected function getBaseQuery()
    {
        $user = Auth::user();
        $recipientType = $this->getRecipientType($user);
        
        // Get notifications for this user OR broadcast notifications (recipient_id is null)
        return Notification::where(function($query) use ($user, $recipientType) {
            $query->where(function($q) use ($user, $recipientType) {
                $q->where('recipient_type', $recipientType)
                  ->where('recipient_id', $user->id);
            })->orWhere(function($q) use ($recipientType) {
                $q->where('recipient_type', $recipientType)
                  ->whereNull('recipient_id');
            });
        });
    }

    /**
     * Get recipient type based on user model
     */
    protected function getRecipientType($user): string
    {
        // Determine recipient type based on user's type field
        // users.type enum: 'admin', 'employee'
        if ($user->type === 'admin') {
            return 'admin';
        }
        
        return 'employee';
    }
}
