<?php
namespace App\Http\Controllers\Worker;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\WorkerRegisterRequest;
use App\Models\Worker;
use App\Services\WorkerService\WorkerAuth\WorkerLoginService;
use App\Services\WorkerService\WorkerAuth\WorkerRegisterService;

class WorkerController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:worker', ['except' => ['login', 'register','verify']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function login(LoginRequest $request){

        return (new WorkerLoginService())->login($request);

      /*  $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->guard('worker')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);*/
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(WorkerRegisterRequest $request) {
        return (new WorkerRegisterService())->register($request);
    /*    $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:clients',
            'password' => 'required|string|min:6',
            'phone'=>'required|string|max:20',
            'photo'=>'required|image',
            'location'=>'required|string'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $worker = Worker::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'photo'=>$request->file('photo')->store('Worker'),
            ]
        ));

        return response()->json([
            'message' => 'Worker successfully registered',
            'user' => $worker
        ], 201);*/
    }

    public function verify($token){
        $worker=Worker::where('Verification',$token)->first();
        if(!$worker){
            return response()->json([
                'message'=>'Invalid verification token!!'
            ]);
        }
        $worker->verification=null;
        $worker->email_verified_at=now();
        $worker->save();
        return response()->json([
            'message'=>'Your account has been verified successfully.'
        ]);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard('worker')->logout();
        return response()->json(['message' => 'Worker successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->guard('worker')->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }
}
