<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\WikiArticle;
use App\Models\CodeRun;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function public()
    {
        $cacheKey = 'public:stats';
        
        $stats = Cache::remember($cacheKey, 1800, function () {
            return [
                'users' => [
                    'total' => User::count(),
                    'active_this_month' => User::where('updated_at', '>=', now()->subMonth())->count(),
                ],
                'posts' => [
                    'total' => Post::where('status', 'published')->count(),
                    'this_week' => Post::where('status', 'published')
                        ->where('created_at', '>=', now()->subWeek())
                        ->count(),
                ],
                'comments' => [
                    'total' => Comment::count(),
                    'this_week' => Comment::where('created_at', '>=', now()->subWeek())->count(),
                ],
                'wiki' => [
                    'articles' => WikiArticle::where('status', 'published')->count(),
                ],
                'code_runs' => [
                    'total' => CodeRun::count(),
                    'successful' => CodeRun::where('status', 'success')->count(),
                ],
            ];
        });

        return response()->json($stats);
    }
}