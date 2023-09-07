<?php

namespace App\Services\WorkerService\WorkerAuth;

use App\Models\Worker;
use Illuminate\Support\Facades\Validator;

class WorkerLoginService
{
    protected $model;
    public function __construct()
    {
        $this->model= new Worker();
    }

    public function validation($request){
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }

    public function isValidData($data){
        if (! $token = auth()->guard('worker')->attempt($data->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $token;
    }

 /*   public function getStatus($email){
        $worker=$this->model->where('email',$email)->first();
        $status=$worker->status;
        return $status;
    }*/
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }

    public function login($request){
        $data= $this->validation($request);
        $token=$this->isValidData($data);
     /*   $status= $this->getStatus($request->email);
        if($status==0){
            return response()->json(['message'=>'Activation is needed!!']);
        }*/
        return $this->createNewToken($token);
    }
}
