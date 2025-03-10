<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class NotificationsController extends Controller
{
    public function count()
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => $user->unreadNotifications()->whereNull('deleted_at')->count()
        ], 200);
    }
    public function list(Request $request)
    {
        $user = auth()->user();

        if(!empty($user)){

            if($request->type=='read'){
                $notifications = $user->readNotifications()->whereNull('deleted_at')->simplePaginate(10);
            }else if($request->type=='unread'){
                $notifications = $user->unreadNotifications()->whereNull('deleted_at')->simplePaginate(10);
            }else{
                $notifications = $user->notifications()->whereNull('deleted_at')->simplePaginate(10);
            }

            $notifications->each(function ($notification) {
                $notification->created_at_formatted = Carbon::parse($notification->created_at)->diffForHumans();
            });

            return response()->json([
                'success' => true,
                'data' => $notifications
            ], 200);
        } else{
            return response()->json([
                    'success' => false,
                'message' => 'There was an error fetching notifications',
            ], 200);
        }
    }

    public function read(Request $request)
    {
        if($request->has('notification_id')){
            $notification = auth()->user()->notifications()->where('id', $request->notification_id)->first();

            if(empty($notification->read_at)){
                $notification->markAsRead();
            }else{
                $notification->read_at = null;
                $notification->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification read status toggled successfully!'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'There was an error toggling notification read status!',
            ], 200);
        }
    }

    public function readAll(Request $request)
    {
        $notifications = auth()->user()->unreadNotifications;

        if(!empty($notifications)){

            $notifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'All Notifications marked as read!'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'There was an error marking notifications as read!',
            ], 200);
        }
    }

    public function delete(Request $request)
    {
        if($request->has('notification_id')){
            $notification = \App\Models\Notification::find($request->notification_id);

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully!'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'There was an error deleting notification!',
            ], 200);
        }
    }

    public function deleteAll(Request $request)
    {
        $notifications = \App\Models\Notification::where('notifiable_id', auth()->id())->where('deleted_at', null)->get();

        if(!empty($notifications)){
            foreach ($notifications as $notification) {
                $notification->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'All Notifications deleted successfully'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'There was an error deleting notifications',
        ], 200);
    }
}
