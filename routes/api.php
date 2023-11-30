<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\Auth\LoginController;
use App\Http\Controllers\Users\Auth\LogoutController;
use App\Http\Controllers\Users\Auth\ResetPasswordController;
use App\Http\Controllers\Users\Profile\GetUserInfoController;
use App\Http\Controllers\Users\Profile\UpdateUserController;
use App\Http\Controllers\Users\GarbagePosts\CreateGarbagePostController;
use App\Http\Controllers\Users\GarbagePosts\UpdateGarbagePostController;
use App\Http\Controllers\Users\GarbagePosts\DeleteGarbagePostController;
use App\Http\Controllers\Users\GarbagePosts\DetailGarbagePostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Users')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/logout', 'AuthController@logout')->middleware('auth:api');
        Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword']);
    });
    Route::middleware('auth:api')->group(function () {
        Route::namespace('Profile')->group(function() {
            Route::get('/user', [GetUserInfoController::class, 'view']);
            Route::put('/user', [UpdateUserController::class, 'update']);
        });
        Route::namespace('GarbagePosts')->group(function() {
            Route::post('/garbage-posts', [CreateGarbagePostController::class, 'store']);
            Route::put('/garbage-posts/{garbagePostId}', [UpdateGarbagePostController::class, 'update']);
            Route::delete('/garbage-posts/{garbagePostId}', [DeleteGarbagePostController::class, 'delete']);
            Route::get('/garbage-posts/{garbagePostId}', [DetailGarbagePostController::class, 'show']);
            Route::get('/users/{userId}/garbage-posts', [GetListGarbagePostController::class, 'getList']);
        });
    });
});