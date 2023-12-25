<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable=['worker_id','content','price','status','rejection_reason'];
    protected $hidden=['created_at','updated_at','status','rejection_reason'];

    public function worker(){
        return $this->belongsTo(Worker::class);
    }

    public function reviews(){
        return $this->hasMany(WorkerReview::class);
    }

    public function adminPercent($price){
        $discount=$price*0.05;
        $clientPrice=$price-$discount;
        return $clientPrice;
    }

    public function photos(){
        return $this->hasMany(PostPhotos::class,'post_id')->select('id','photo');
    }
}
