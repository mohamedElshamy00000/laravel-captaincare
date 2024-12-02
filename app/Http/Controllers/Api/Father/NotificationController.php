<?php

namespace App\Http\Controllers\Api\Father;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // get notifications
    public function getNotifications() {
        $father = auth('father')->user();
        $notifications = $father->notifications()->get(); // Assuming notifications are related to the father model
        // dd($notifications);
        $data = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'],
                'message' => $notification->data['message'],
                'read' => $notification->read_at ? $notification->read_at->format('Y M d - h A') : false
            ];
        });
        return response()->json([
            'data' => $data
        ], 200);
    }
    // mark notification as read
    public function markAsRead($id) {
        $notification = auth('father')->user()->notifications()->find($id);
        $notification->read_at = now();
        $notification->save();
        return response()->json([
            'status' => true,
            'message' => 'marked as read'
        ], 200);
    }
}
