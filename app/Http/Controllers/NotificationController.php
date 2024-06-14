<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        // Validate the request
        $request->validate([
            'notificationId' => 'required|exists:notifications,id'
        ]);

        // Find the notification by ID for the authenticated user
        $notification = auth()->user()->notifications()->find($request->notificationId);

        // If notification found, mark it as read
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        // Handle case where notification is not found
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
}
