<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $notifications = $this->notificationService->getUnreadNotifications();
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $this->notificationService->markAsRead($id);
        return redirect()->back()->with('success', 'Notification marked as read.');
    }
}
