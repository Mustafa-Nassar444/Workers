<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    //
    public function index(){
        $admin=Admin::find(auth()->id());
        return response()->json([
           'notifications'=>$admin->notifications
        ]);
    }

    public function unreadNotification(){
        $admin=Admin::find(auth()->id());
        return response()->json([
            'notifications'=>$admin->unreadNotifications
        ]);
    }

    public function markAllAsRead(){
        $admin=Admin::find(auth()->id());
        $admin->unreadNotifications->markAsRead();
        return response()->json(['message'=>'Notifications Marked as read successfully']);
    }

    public function markAsRead($id){
        $admin=Admin::find(auth()->id());
        DB::table('notifications')->where('id',$id)->update(['read_at'=>now()]);
        return response()->json(['message'=>'Notification Marked as read successfully']);

    }
    public function delete(){
        $admin=Admin::find(auth()->id());
        $admin->notifications()->delete();
        return response()->json(['message'=>'Notifications deleted successfully']);

    }
}
