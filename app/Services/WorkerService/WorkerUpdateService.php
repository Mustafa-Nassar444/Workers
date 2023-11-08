<?php

namespace App\Services\WorkerService;

use App\Http\Requests\WorkerUpdateRequest;
use App\Models\Worker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class WorkerUpdateService
{
    protected $model;
    public function __construct()
    {
        $this->model= Worker::find(auth()->guard()->id());
    }

    public function password($data){
        if(request()->has('password')){
            $data['password']=bcrypt(request()->password);
            return $data;
        }
        $data['password']=$this->model->password;
        return $data;
    }

    public function photo($data){
        if(request()->has('photo')){
            $data['photo']=(request()->file('photo') instanceof UploadedFile) ?
                request()->file('photo')->store('Worker') : $this->model->photo;
            return $data;
        }
        $data['photo']=null;
        return $data;
    }

    public function update($request){
        $data=$request->all();
        $data=$this->photo($data);
        $data=$this->password($data);
        $this->model->update($data);
        return response()->json([
            'message'=>'Profile updated successfully',
        ]);
    }


}
