<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\PostService\StorePostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function store(PostRequest $request){
       return (new StorePostService())->store($request);
    }

    public function approvedPosts(){
        $posts=Post::where('status','approved')->get();
        if($posts)
            return response()->json(['posts'=>$posts]);
        else
            return response()->json(['posts'=>'Empty Posts']);
    }

    public function show(Post $post){
        return response()->json(['Post'=>$post]);
    }
}
