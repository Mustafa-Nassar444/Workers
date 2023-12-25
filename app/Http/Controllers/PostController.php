<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\PostService\StorePostService;
use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    use FilterTrait;
    //
    public function store(PostRequest $request){
       return (new StorePostService())->store($request);
    }

    public function approvedPosts(){
        $posts = $this->filter()
            ->with('worker:id,name')
            ->where('status','approved')
            ->get();
        if($posts)
            return response()->json(['posts'=>$posts]);
        else
            return response()->json(['posts'=>'Empty Posts']);
    }

    public function show($id){
        $post=Post::with('photos')->find($id);
        dd(DB::getQueryLog());
        /* $post->load('photos');
         Log::debug('Loaded Photos: ' . json_encode($post->photos));*/
        return response()->json(['Post' => array_merge($post->toArray(), ['photos' => $post->photos->toArray()])]);
    }
}
