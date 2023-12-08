<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\Auth\LoginController;
use App\Http\Controllers\Users\Auth\LogoutController;
use App\Http\Controllers\Users\Auth\ResetPasswordController;
use App\Http\Controllers\Users\Auth\Google\LoginController as GoogleLoginController;
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
use App\Http\Controllers\Users\FavoritePosts\AddToFavoritePostController;
use App\Http\Controllers\Users\FavoritePosts\RemoveFromFavoritePostController;
use App\Http\Controllers\Users\FavoritePosts\GetFavoritePostsController;
use App\Http\Controllers\Public\GarbagePosts\GetPostListController;
use App\Http\Controllers\Public\GarbagePosts\GetPostDetailController;
use App\Http\Controllers\Public\GarbagePosts\GetPostListByLocationController;
use App\Http\Controllers\Public\Users\GetUserProfileController;
use App\Http\Controllers\Public\Users\GetLeaderboardController;
use App\Http\Controllers\Public\Events\GetUpcomingEventsController;
use App\Http\Controllers\Public\Statistics\GetStatisticsController;
use App\Http\Controllers\Public\News\GetLastedNewsController;
use App\Http\Controllers\Public\EducationResources\GetListEducationResourceController;
use App\Http\Controllers\Admins\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admins\Users\GetUsersController;
use App\Http\Controllers\Admins\Users\GetUserDetailController;
use App\Http\Controllers\Admins\Users\UpdateUserController as AdminUpdateUserController;
use App\Http\Controllers\Admins\Users\DeleteUserController;
use App\Http\Controllers\Admins\Users\UserRewards\AddRewardController;
use App\Http\Controllers\Admins\Comments\GetAllCommentsController;
use App\Http\Controllers\Admins\Reports\GetReportsController;
use App\Http\Controllers\Admins\Reports\GetReportDetailController;
use App\Http\Controllers\Admins\GarbagePosts\VerifyPostController;
use App\Http\Controllers\Admins\GarbagePosts\VerifyBulkPostController;
use App\Http\Controllers\Admins\Feedback\GetFeedbackController;
use App\Http\Controllers\Admins\Feedback\GetFeedbackDetailController;
use App\Http\Controllers\Admins\Feedback\UpdateFeedbackController;
use App\Http\Controllers\Admins\Feedback\DeleteFeedbackController;
use App\Http\Controllers\Admins\ModerationQueue\GetModerationQueueController;
use App\Http\Controllers\Admins\ModerationQueue\AddToModerationQueueController;
use App\Http\Controllers\Admins\ModerationQueue\UpdateModerationQueueController;
use App\Http\Controllers\Admins\ModerationQueue\RemoveFromModerationQueueController;
use App\Http\Controllers\Admins\AiPostQueue\GetAiPostQueueController;
use App\Http\Controllers\Admins\AiPostQueue\AddToAiPostQueueController;
use App\Http\Controllers\Admins\AiLogs\GetAiLogsController;
use App\Http\Controllers\Admins\GeolocationHeatmap\GenerateHeatmapController;

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
        Route::post('/logout', [LogoutController::class,'logout'])->middleware('auth:api');
        Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword']);

        Route::namespace('Google')->group(function () {
            Route::middleware('web')->group(function () {
                Route::get('/auth/google', [GoogleLoginController::class,'redirectToGoogle']);
                Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);
            });
        });
    });
    Route::prefix('users')->middleware('auth:api')->group(function () {
        Route::prefix('profile')->namespace('Profile')->group(function() {
            Route::get('/', [GetUserInfoController::class, 'view']);
            Route::post('/', [UpdateUserController::class, 'update']);
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
        Route::prefix('favorite-posts')->namespace('FavoritePosts')->group(function() {
            Route::get('/', [GetFavoritePostsController::class, 'index']);
            Route::post('/', [AddToFavoritePostController::class, 'store']);
            Route::delete('/', [RemoveFromFavoritePostController::class, 'destroy']);
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
    Route::prefix('news')->namespace('News')->group(function() {
        Route::get('/lasted', [GetLastedNewsController::class, 'index']);
    });
    Route::prefix('education-resources')->namespace('EducationResources')->group(function() {
        Route::get('/', [GetListEducationResourceController::class, 'index']);
    });
});

Route::prefix('admins')->namespace('Admins')->group(function() {
    Route::namespace('Auth')->group(function () {
        Route::post('/login', [AdminLoginController::class, 'login']);
    });
    Route::middleware('auth:admin')->group(function () {
        Route::prefix('users')->namespace('Users')->group(function() {
            Route::get('/', [GetUsersController::class, 'index']);
            Route::get('/{userId}', [GetUserDetailController::class, 'show']);
            Route::post('/{userId}', [AdminUpdateUserController::class, 'update']);
            Route::delete('/{userId}', [DeleteUserController::class, 'destroy']);

            Route::prefix('{userId}')->group(function () {
                Route::prefix('rewards')->namespace('UserRewards')->group(function() {
                    Route::post('/', [AddRewardController::class, 'store']);
                });
            });
        });
        Route::prefix('comments')->namespace('Comments')->group(function() {
            Route::get('/get-all', [GetAllCommentsController::class, 'index']);
        });
        Route::prefix('reports')->namespace('Reports')->group(function() {
            Route::get('/', [GetReportsController::class, 'index']);
            Route::get('/{reportId}', [GetReportDetailController::class, 'show']);
        });
        Route::prefix('garbage-posts')->namespace('GarbagePosts')->group(function() {
            Route::post('/{garbagePostId}/verify', [VerifyPostController::class, 'update']);
            Route::post('/verify-bulk-post', [VerifyBulkPostController::class, 'update']);
        });
        Route::prefix('feedback')->namespace('Feedback')->group(function() {
            Route::get('/', [GetFeedbackController::class, 'index']);
            Route::get('/{feedbackId}', [GetFeedbackDetailController::class, 'show']);
            Route::post('/{feedbackId}', [UpdateFeedbackController::class, 'update']);
            Route::delete('/{feedbackId}', [DeleteFeedbackController::class, 'destroy']);
        });
        Route::prefix('moderation-queue')->namespace('ModerationQueue')->group(function() {
            Route::get('/', [GetModerationQueueController::class, 'index']);
            Route::post('/', [AddToModerationQueueController::class, 'store']);
            Route::post('/{moderationId}', [UpdateModerationQueueController::class, 'update']);
            Route::delete('/{moderationId}', [RemoveFromModerationQueueController::class, 'destroy']);
        });
        Route::prefix('ai-post-queue')->namespace('AiPostQueue')->group(function() {
            Route::get('/', [GetAiPostQueueController::class, 'index']);
            Route::post('/', [AddToAiPostQueueController::class, 'store']);
        });
        Route::prefix('ai-logs')->namespace('AiLogs')->group(function() {
            Route::get('/', [GetAiLogsController::class, 'index']);
        });
        Route::prefix('geolocation-heatmap')->namespace('GeolocationHeatmap')->group(function() {
            Route::get('/', [GenerateHeatmapController::class, 'index']);
        });
    });
});