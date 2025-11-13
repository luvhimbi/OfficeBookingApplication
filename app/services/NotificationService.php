<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Create a notification for a user.
     *
     * @param  int|null  $userId
     * @param  string   $title
     * @param  string   $message
     * @param  string|null $type
     * @return Notification
     */
    public function createNotification($userId, string $title, string $message, string $type = 'info')
    {
        $notification = Notification::create([
            'user_id' => $userId ?? Auth::id(),
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
        ]);

        // Optional: push real-time notification using event/pusher here

        return $notification;
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->update(['is_read' => true]);
        return $notification;
    }

    /**
     * Mark all notifications for a user as read
     */
    public function markAllAsRead($userId = null)
    {
        $userId = $userId ?? Auth::id();
        return Notification::where('user_id', $userId)->update(['is_read' => true]);
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications($userId = null)
    {
        $userId = $userId ?? Auth::id();
        return Notification::where('user_id', $userId)->where('is_read', false)->latest()->get();
    }
}
