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
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{slug}', [PostController::class, 'show']);
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/wiki', [WikiArticleController::class, 'index']);
    Route::get('/wiki/{slug}', [WikiArticleController::class, 'show']);

    // ==================
    //  Authenticated
    // ==================
    Route::middleware('auth:sanctum')->group(function () {
        
        // Posts CRUD
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{slug}', [PostController::class, 'update']);
        Route::delete('/posts/{slug}', [PostController::class, 'destroy']);

        // Comments
        Route::post('/posts/{slug}/comments', [CommentController::class, 'store']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

        // Votes
        Route::post('/vote', [VoteController::class, 'vote']);

        // Code Execution
        Route::post('/code-run', [CodeRunController::class, 'run']);

        // Profile
        Route::get('/profile/me', [ProfileController::class, 'me']);
        Route::put('/profile', [ProfileController::class, 'update']);

        // Wiki (PR-like oqim)
        Route::post('/wiki', [WikiArticleController::class, 'store']);
        Route::post('/wiki/{slug}/propose', [WikiArticleController::class, 'proposeEdit']);
        Route::post('/wiki/{slug}/merge/{proposalId}', [WikiArticleController::class, 'merge']);
    });
});





