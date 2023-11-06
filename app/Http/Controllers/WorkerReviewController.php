<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerReviewRequest;
use App\Http\Resources\WorkerReviewResource;
use App\Models\WorkerReview;
use Illuminate\Http\Request;

class WorkerReviewController extends Controller
{
    public function store(WorkerReviewRequest $request){
        $data=$request->all();
        $data['client_id']=auth()->guard('client')->id();
        $review=WorkerReview::create($data);
        return response()->json([
            'message'=>'Review added successfully'
        ]);
    }

    public function showReview($id){
        $reviews=WorkerReview::wherePostId($id)->get();
        $average=$reviews->sum('rate') / $reviews->count();
        return response()->json([
        "total_rate" => round($average, 1),
            "data" => WorkerReviewResource::collection($reviews)
        ]);
    }
}
