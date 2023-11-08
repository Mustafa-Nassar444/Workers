<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerUpdateRequest;
use App\Models\Worker;
use App\Models\WorkerReview;
use App\Services\WorkerService\WorkerUpdateService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function workerProfile() {
        $id=auth()->guard('worker')->id();
        $worker=Worker::with('posts.reviews')->findOrFail($id);
        $reviews=WorkerReview::whereIn('post_id',$worker->posts()->pluck('id'))->get();
        $rate=round($reviews->sum('rate') / $reviews->count(),1);
        return response()->json(
            [
                'data'=>array_merge($worker->toArray(),['rate'=>$rate])
            ]
        );
    }

    public function edit(){
        $worker=Worker::findOrFail(auth()->guard('worker')->id())->makeHidden('verification','email_verified_at')->get();
        return response()->json([
            'worker'=>$worker,
        ]);
    }

    public function update(WorkerUpdateRequest $request){

        return (new WorkerUpdateService())->update($request);

    }
}
