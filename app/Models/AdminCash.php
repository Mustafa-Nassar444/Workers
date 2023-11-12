<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCash extends Model
{
    use HasFactory;

    protected $fillable=['worker_id','post_id','percent'];
    public function worker(){
        return $this->belongsTo(Worker::class);
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
