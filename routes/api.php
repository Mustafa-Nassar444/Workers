<?php

use App\Http\Controllers\{AdminController,
    AdminDashboard\AdminNotificationController,
    AdminDashboard\PostStatusController,
    ClientController,
    ClientOrderController,
    PostController,
    Worker\ProfileController,
    Worker\WorkerController,
    Worker\ReviewController};
use Illuminate\Support\Facades\Route;

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


Route::prefix('auth')->group(function (){
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
            Route::get('/verify/{token}','verify');

        });


});
Route::controller(PostController::class)->prefix('worker/post')->group(function (){
   Route::post('/add','store')->middleware('auth:worker');
   Route::get('/approved','approvedPosts')->middleware('auth:admin');
   Route::post('/{id}','show')->middleware('auth:worker');
});

Route::prefix('worker/')->group(function (){

    Route::controller(ClientOrderController::class)->group(function (){
        Route::get('pending/orders','workerOrder');
        Route::put('update/{clientOrder}','update');
    });

    Route::get('review/post/{id}', [ReviewController::class, 'showReview']);

    Route::controller(ProfileController::class)->group(function (){
        Route::get('profile/', 'workerProfile');
        Route::post('profile/', 'update');
        Route::delete('posts/delete','delete');
    });


})->middleware('auth:worker');

Route::prefix('admin/')->group(function (){
    Route::post('post/change_status',[PostStatusController::class,'changePostStatus'])->middleware('auth:admin');

});

Route::controller(AdminNotificationController::class)->prefix('notifications')->group(function (){
    Route::get('/all','index');
    Route::get('/unreadNotifications','unreadNotification');
    Route::post('/markAllAsRead','markAllAsRead');
    Route::post('/markAsRead/{id}','markAsRead');
    Route::delete('/delete','delete');

})->middleware('auth:admin');

Route::prefix('client')->group(function (){

    Route::controller(ClientOrderController::class)->prefix('/order')->group(function (){
        Route::post('/store','store');

    });

    Route::post('review/store',[ReviewController::class,'store']);
    Route::get('/pay/{id}',[\App\Http\Controllers\PaymentController::class,'pay']);
})->middleware('auth:client');
