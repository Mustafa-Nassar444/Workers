<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStatusRequest;
use App\Models\Post;
use App\Notifications\PostNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PostStatusController extends Controller
{
    //
    public function changePostStatus(PostStatusRequest $request){

        $post=Post::findOrFail($request->id);

        $post->update([
            'status'=>$request->status,
            'rejected_reason'=>$request->rejected_reason
            ]);

        Notification::send($post->worker, new PostNotifications($post,$post->worker));

        return response()->json(['message'=>'Post has been '.$request->status]);
    }
}
