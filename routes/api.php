<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdminController, ClientController, VerifyEmailController, WorkerController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::middleware('DbBackup')->prefix('auth')->group(function (){
    Route::controller(AdminController::class)->prefix('admin')->group(
        function () {
            Route::post('/login','login');
            Route::post('/register',  'register');
            Route::post('/logout',  'logout');
            Route::post('/refresh', 'refresh');
            Route::get('/admin-profile', 'adminProfile');
        });
    Route::controller(ClientController::class)->prefix('client')->group(
        function () {
            Route::post('/login','login');
            Route::post('/register',  'register');
            Route::post('/logout',  'logout');
            Route::post('/refresh', 'refresh');
            Route::get('/client-profile', 'clientProfile');

        });
    Route::controller(WorkerController::class)->prefix('worker')->group(
        function () {
            Route::post('/login','login');
            Route::post('/register',  'register');
            Route::post('/logout',  'logout');
            Route::post('/refresh', 'refresh');
            Route::get('/worker-profile', 'workerProfile');
            Route::get('/verify/{token}','verify');

        });


});

