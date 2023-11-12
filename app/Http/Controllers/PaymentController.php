<?php

namespace App\Http\Controllers;

use App\Models\AdminCash;
use App\Models\Client;
use App\Models\Post;
use App\Models\WorkerCash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    //
    public function pay($id){
        try {
            DB::beginTransaction();
            $post=Post::find($id);
            $client=Client::find(auth()->guard('client')->id());
            $percent=$post->price-$post->adminPercent($post->price);
            $payLink=$client->charge($post->price,$post->content);
            $workerCash=WorkerCash::create([
                'client_id'=>$client->id,
                'post_id'=>$post->id,
                'total'=>$post->adminPercent($post->price)
            ]);
            $adminCash=AdminCash::create([
                'worker_id'=>$post->worker->id,
                'post_id'=>$post->id,
                'percent'=>$percent,
            ]);
            DB::commit();
            return response()->json(
                [
                    'payLink'=>$payLink,
                ]
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'message'=>$e->getMessage()
            ]);
        }

    }
}
