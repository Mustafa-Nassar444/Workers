<?php

namespace App\Services\WorkerService\WorkerAuth;

use App\Mail\SendEmailVerification;
use App\Models\Worker;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
class WorkerRegisterService
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

    public function store($request,$data){
        $worker = $this->model->create(array_merge(
            $data->validated(),
            [
                'password' => bcrypt($request->password),
                'photo'=>$request->file('photo')->store('Worker'),
            ]
        ));
        return $worker;
    }

    public function createToken($email){
        $token=substr(md5(rand(0,9).$email.time()),0,32);
        $worker=$this->model->whereEmail($email)->first();
        $worker->verification=$token;
        $worker->save();
        return $worker;
    }

    public function sendEmail($worker){


        Mail::to($worker->email)->send(new SendEmailVerification($worker));
    }

    public function register($request){
        try{
            DB::beginTransaction();
            $data= $this->validation($request);
            $user=$this->store($request,$data);
            $worker=$this->createToken($user->email);
            //dd($worker);
            $this->sendEmail($worker);
            DB::commit();
            return response()->json(['message'=>'Please activate your account to start sending emails. We sent an activation email to '.$worker->email.'.']);
        }
        catch (Exception $e){
            DB::rollBack();
            return response()->json(['message'=>$e->getMessage()]);
        }


    }
}
