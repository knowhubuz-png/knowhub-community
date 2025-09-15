<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\EmailAuthController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\VoteController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\WikiArticleController;
use App\Http\Controllers\Api\V1\CodeRunController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\BookmarkController;
use App\Http\Controllers\Api\V1\FollowController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Middleware\RateLimitMiddleware;
use App\Http\Middleware\CacheMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
| Har bir endpoint "api/v1" prefiks ostida joylashgan.
*/

Route::prefix('v1')->group(function () {
    
    // ==================
    //  Public Auth
    // ==================
    Route::post('/auth/email/register', [EmailAuthController::class, 'register']);
    Route::post('/auth/email/login', [EmailAuthController::class, 'login']);
    Route::post('/auth/email/logout', [EmailAuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/auth/google/redirect', [OAuthController::class, 'redirectGoogle']);
    Route::get('/auth/google/callback', [OAuthController::class, 'handleGoogleCallback']);
    Route::get('/auth/github/redirect', [OAuthController::class, 'redirectGithub']);
    Route::get('/auth/github/callback', [OAuthController::class, 'handleGithubCallback']);

    // ==================
    //  Public Data
    // ==================
    Route::get('/stats/public', [App\Http\Controllers\Api\V1\StatsController::class, 'public']);
    
    Route::middleware([CacheMiddleware::class . ':300'])->group(function () {
        Route::get('/posts', [PostController::class, 'index']);
        Route::get('/posts/{slug}', [PostController::class, 'show']);
        Route::get('/tags', [TagController::class, 'index']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/wiki', [WikiArticleController::class, 'index']);
        Route::get('/wiki/{slug}', [WikiArticleController::class, 'show']);
    });

    // Search endpoints
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/search/suggestions', [SearchController::class, 'suggestions']);
    
    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{username}', [UserController::class, 'show']);
    Route::get('/users/leaderboard', [UserController::class, 'leaderboard']);
    Route::get('/users/{username}/stats', [UserController::class, 'stats']);
    
    // Posts - additional endpoints
    Route::get('/posts/{slug}/related', [PostController::class, 'related']);
    Route::get('/posts/trending', [PostController::class, 'trending']);
    Route::get('/posts/featured', [PostController::class, 'featured']);
    
    // Tags - additional endpoints
    Route::get('/tags/{slug}', [TagController::class, 'show']);
    Route::get('/tags/trending', [TagController::class, 'trending']);
    
    // Categories - additional endpoints
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    // ==================
    //  Authenticated
    // ==================
    Route::middleware(['auth:sanctum', RateLimitMiddleware::class . ':api,100'])->group(function () {
        
        // Posts CRUD
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{slug}', [PostController::class, 'update']);
        Route::delete('/posts/{slug}', [PostController::class, 'destroy']);

        // Comments
        Route::post('/posts/{slug}/comments', [CommentController::class, 'store']);
        Route::get('/comments/{id}', [CommentController::class, 'show']);
        Route::put('/comments/{id}', [CommentController::class, 'update']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

        // Votes
        Route::post('/vote', [VoteController::class, 'vote']);
        Route::get('/vote/{type}/{id}', [VoteController::class, 'getVote']);

        // Bookmarks
        Route::get('/bookmarks', [BookmarkController::class, 'index']);
        Route::post('/bookmarks/toggle', [BookmarkController::class, 'toggle']);
        Route::get('/bookmarks/check/{postId}', [BookmarkController::class, 'check']);

        // Follow system
        Route::post('/follow/toggle', [FollowController::class, 'toggle']);
        Route::get('/users/{userId}/followers', [FollowController::class, 'followers']);
        Route::get('/users/{userId}/following', [FollowController::class, 'following']);
        Route::get('/follow/check/{userId}', [FollowController::class, 'check']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);

        // Code Execution
        Route::middleware(RateLimitMiddleware::class . ':code-run,10')->group(function () {
            Route::post('/code-run', [CodeRunController::class, 'run']);
        });

        // Profile
        Route::get('/profile/me', [ProfileController::class, 'me']);
        Route::put('/profile', [ProfileController::class, 'update']);
        
        // Dashboard
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::get('/dashboard/activity', [DashboardController::class, 'activity']);
        Route::get('/dashboard/trending', [DashboardController::class, 'trending']);
        Route::get('/dashboard/analytics', [DashboardController::class, 'analytics']);

        // Admin routes
        Route::prefix('admin')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Api\V1\AdminController::class, 'dashboard']);
            Route::get('/users', [App\Http\Controllers\Api\V1\AdminController::class, 'users']);
            Route::get('/posts', [App\Http\Controllers\Api\V1\AdminController::class, 'posts']);
            Route::get('/comments', [App\Http\Controllers\Api\V1\AdminController::class, 'comments']);
            Route::get('/analytics', [App\Http\Controllers\Api\V1\AdminController::class, 'analytics']);
            Route::put('/users/{userId}/status', [App\Http\Controllers\Api\V1\AdminController::class, 'updateUserStatus']);
            Route::delete('/posts/{postId}', [App\Http\Controllers\Api\V1\AdminController::class, 'deletePost']);
            Route::delete('/comments/{commentId}', [App\Http\Controllers\Api\V1\AdminController::class, 'deleteComment']);
            Route::get('/settings', [App\Http\Controllers\Api\V1\AdminController::class, 'systemSettings']);
            Route::put('/settings', [App\Http\Controllers\Api\V1\AdminController::class, 'updateSystemSettings']);
            
            // System maintenance
            Route::post('/cache/clear', [App\Http\Controllers\Api\V1\AdminController::class, 'clearCache']);
            Route::post('/system/optimize', [App\Http\Controllers\Api\V1\AdminController::class, 'optimizeSystem']);
            Route::post('/database/backup', [App\Http\Controllers\Api\V1\AdminController::class, 'backupDatabase']);
        });

        // Wiki (PR-like oqim)
        Route::post('/wiki', [WikiArticleController::class, 'store']);
        Route::post('/wiki/{slug}/propose', [WikiArticleController::class, 'proposeEdit']);
        Route::post('/wiki/{slug}/merge/{proposalId}', [WikiArticleController::class, 'merge']);
    });
});





