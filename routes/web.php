<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PostWebController;
use App\Http\Controllers\Web\WikiWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\ProfileWebController;
use App\Http\Controllers\Web\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Bu yerda faqat Blade view’lar uchun marshrutlar.
| API alohida `routes/api.php` da ishlayapti.
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * Auth sahifalari (Laravel Breeze yoki custom)
 */
Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);
Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthWebController::class, 'register']);
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

/**
 * Postlar
 */
Route::prefix('posts')->group(function () {
    Route::get('/', [PostWebController::class, 'index'])->name('posts.index');
    Route::get('/{slug}', [PostWebController::class, 'show'])->name('posts.show');
    Route::middleware('auth')->group(function () {
        Route::get('create', [PostWebController::class, 'create'])->name('posts.create');
        Route::post('/', [PostWebController::class, 'store'])->name('posts.store');
        Route::get('/{slug}/edit', [PostWebController::class, 'edit'])->name('posts.edit');
        Route::put('/{slug}', [PostWebController::class, 'update'])->name('posts.update');
        Route::delete('/{slug}', [PostWebController::class, 'destroy'])->name('posts.destroy');

        // Komment qo‘shish
        Route::post('/{slug}/comments', [PostWebController::class, 'storeComment'])->name('posts.comments.store');
    });
});

/**
 * Wiki maqolalari
 */
Route::prefix('wiki')->group(function () {
    Route::get('/', [WikiWebController::class, 'index'])->name('wiki.index');
    Route::get('/{slug}', [WikiWebController::class, 'show'])->name('wiki.show');
    Route::middleware('auth')->group(function () {
        Route::get('create', [WikiWebController::class, 'create'])->name('wiki.create');
        Route::post('/', [WikiWebController::class, 'store'])->name('wiki.store');
    });
});

/**
 * Profil
 */
Route::get('/profile/{username}', [ProfileWebController::class, 'show'])->name('profile.show')
    ->where('username', '[A-Za-z0-9\-_]+');

/**
 * Admin routes
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        $stats = app(\App\Http\Controllers\Api\V1\StatsController::class)->public()->getData();
        $recentPosts = \App\Models\Post::with('user')->latest()->limit(10)->get();
        $topUsers = \App\Models\User::orderByDesc('xp')->limit(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recentPosts', 'topUsers'));
    })->name('dashboard');
    
    Route::get('/users', function () {
        $users = \App\Models\User::with('level')->withCount(['posts', 'comments'])->latest()->paginate(20);
        return view('admin.users', compact('users'));
    })->name('users');
    
    Route::get('/posts', function () {
        $posts = \App\Models\Post::with(['user', 'category'])->withCount(['comments', 'votes'])->latest()->paginate(20);
        return view('admin.posts', compact('posts'));
    })->name('posts');
    
    Route::post('/cache/clear', function () {
        \Artisan::call('cache:clear');
        return back()->with('success', 'Cache tozalandi');
    })->name('cache.clear');
    
    Route::post('/optimize', function () {
        \Artisan::call('config:cache');
        \Artisan::call('route:cache');
        \Artisan::call('view:cache');
        return back()->with('success', 'Tizim optimallashtirildi');
    })->name('optimize');
});

/**
 * Static pages
 */
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/help', 'pages.help')->name('help');
Route::post('/contact/send', [\App\Http\Controllers\Web\ContactController::class, 'send'])->name('contact.send');