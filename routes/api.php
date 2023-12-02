<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\Auth\LoginController;
use App\Http\Controllers\Users\Auth\LogoutController;
use App\Http\Controllers\Users\Auth\ResetPasswordController;
use App\Http\Controllers\Users\Profile\GetUserInfoController;
use App\Http\Controllers\Users\Profile\UpdateUserController;
use App\Http\Controllers\Users\Profile\UpdatePasswordController;
use App\Http\Controllers\Users\GarbagePosts\CreateGarbagePostController;
use App\Http\Controllers\Users\GarbagePosts\UpdateGarbagePostController;
use App\Http\Controllers\Users\GarbagePosts\DeleteGarbagePostController;
use App\Http\Controllers\Users\GarbagePosts\DetailGarbagePostController;
use App\Http\Controllers\Users\GarbagePosts\GetListGarbagePostController;
use App\Http\Controllers\Users\GarbagePosts\Comments\CreateCommentController;
use App\Http\Controllers\Users\GarbagePosts\Comments\DeleteCommentController;
use App\Http\Controllers\Users\GarbagePosts\Reactions\AddReactionController;
use App\Http\Controllers\Users\GarbagePosts\Reactions\RemoveReactionController;
use App\Http\Controllers\Users\Points\GetPointController;
use App\Http\Controllers\Users\ActivityLogs\GetActivityLogsController;
use App\Http\Controllers\Users\NotificationSettings\GetNotificationSettingsController;
use App\Http\Controllers\Users\NotificationSettings\UpdateNotificationSettingController;
use App\Http\Controllers\Users\Reports\CreateReportController;
use App\Http\Controllers\Public\GarbagePosts\GetPostListController;
use App\Http\Controllers\Public\GarbagePosts\GetPostDetailController;
use App\Http\Controllers\Public\GarbagePosts\GetPostListByLocationController;
use App\Http\Controllers\Public\Users\GetUserProfileController;
use App\Http\Controllers\Public\Users\GetLeaderboardController;
use App\Http\Controllers\Public\Events\GetUpcomingEventsController;
use App\Http\Controllers\Public\Statistics\GetStatisticsController;

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
    Route::prefix('user')->middleware('auth:api')->group(function () {
        Route::namespace('Profile')->group(function() {
            Route::get('/', [GetUserInfoController::class, 'view']);
            Route::put('/', [UpdateUserController::class, 'update']);
            Route::post('/update-password', [UpdatePasswordController::class, 'update']);
        });
        Route::prefix('garbage-posts')->namespace('GarbagePosts')->group(function() {
            Route::post('/', [CreateGarbagePostController::class, 'store']);
            Route::post('/{garbagePostId}', [UpdateGarbagePostController::class, 'update']);
            Route::delete('/{garbagePostId}', [DeleteGarbagePostController::class, 'delete']);
            Route::get('/{garbagePostId}', [DetailGarbagePostController::class, 'show']);
            Route::get('/', [GetListGarbagePostController::class, 'index']);

            Route::prefix('{garbagePostId}')->group(function () {
                Route::prefix('comments')->namespace('Comments')->group(function () {
                    Route::post('/', [CreateCommentController::class, 'store']);
                    Route::delete('/{commentId}', [DeleteCommentController::class, 'destroy']);
                });

                Route::prefix('reactions')->namespace('Reactions')->group(function () {
                    Route::post('/', [AddReactionController::class, 'store']);
                    Route::delete('/{reactionId}', [RemoveReactionController::class, 'destroy']);
                });
            });
        });
        Route::namespace('Points')->group(function() {
            Route::get('/point', [GetPointController::class, 'view']);
        });
        Route::namespace('ActivityLogs')->group(function() {
            Route::get('/activity-logs', [GetActivityLogsController::class, 'index']);
        });
        Route::prefix('notification-settings')->namespace('NotificationSettings')->group(function() {
            Route::get('/', [GetNotificationSettingsController::class, 'index']);
            Route::post('/{settingId}', [UpdateNotificationSettingController::class, 'update']);
        });
        Route::prefix('reports')->namespace('Reports')->group(function() {
            Route::post('/', [CreateReportController::class, 'store']);
        });
    });
});

Route::namespace('Public')->group(function() {
    Route::prefix('garbage-posts')->namespace('GarbagePosts')->group(function() {
        Route::get('/', [GetPostListController::class, 'index']);
        Route::get('/{garbagePostId}', [GetPostDetailController::class, 'show']);
        Route::get('/location/{locationType}/{locationId}', [GetPostListByLocationController::class, 'index']);
    });
    Route::prefix('users')->namespace('Users')->group(function() {
        Route::get('/profiles/{userId}', [GetUserProfileController::class, 'show']);
        Route::get('/leaderboard', [GetLeaderboardController::class, 'getLeaderboard']);
    });
    Route::prefix('events')->namespace('Users')->group(function() {
        Route::get('/upcoming', [GetUpcomingEventsController::class, 'index']);
    });
    Route::prefix('statistics')->namespace('Statistic')->group(function() {
        Route::get('/', [GetStatisticsController::class, 'index']);
    });
});