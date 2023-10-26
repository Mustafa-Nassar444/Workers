<?php

namespace App\Services\PostService;

use App\Models\Admin;
use App\Models\Post;
use App\Models\PostPhotos;
use App\Models\Worker;
use App\Notifications\PostNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StorePostService
{
    protected $model;
    public function __construct()
    {
        $this->model=new Post();
    }

    public function adminPercent($price){
        $discount=$price*0.05;
        $clientPrice=$price-$discount;
        return $clientPrice;
    }
    public function storePost($request){
        $data=$request->except('photos');
        $data['price']=$this->adminPercent($data['price']);
        $data['worker_id']=auth()->guard('worker')->id();
        $post=Post::create($data);
        return $post;
    }

    public function storePhotos($request,$post)
    {
        foreach ($request->file('photos') as $photo){
            $PostPhotos= new PostPhotos();
            $PostPhotos->post_id=$post;
            $PostPhotos->photo=$photo->store('Post');
            $PostPhotos->save();
        }
    }

    function sendNotification($post){
        $admins=Admin::get();
        Notification::send($admins, new PostNotifications($post,auth()->guard('worker')->user()));
    }

    function store($request){
        try{
            DB::beginTransaction();
            $post=$this->storePost($request);
            if($request->hasFile('photos')){
                $photos=$this->storePhotos($request,$post->id);
            }
            $this->sendNotification($post);
            DB::commit();
            return response()->json([
                'message'=>'Post added successfully',
                'Profit'=>'Client profit is '.$post->price,
            ]);
        }
        catch (\Exception $e){
            DB::rollBack();
            return response()->json(['message'=>$e->getMessage()]);
        }


    }
}
