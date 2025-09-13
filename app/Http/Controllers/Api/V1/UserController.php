<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['level', 'badges'])
            ->withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->withCount(['followers', 'following']);

        // Search functionality
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }

        // Filter by level
        if ($level = $request->get('level')) {
            $query->whereHas('level', fn($q) => $q->where('slug', $level));
        }

        // Sort options
        $sort = $request->get('sort', 'xp');
        switch ($sort) {
            case 'xp':
                $query->orderByDesc('xp');
                break;
            case 'posts':
                $query->orderByDesc('posts_count');
                break;
            case 'followers':
                $query->orderByDesc('followers_count');
                break;
            case 'recent':
                $query->latest();
                break;
        }

        $users = $query->paginate(20);
        
        return UserResource::collection($users);
    }

    public function show(string $username)
    {
        $cacheKey = "user:profile:{$username}";
        
        $user = Cache::remember($cacheKey, 600, function () use ($username) {
            return User::with(['level', 'badges'])
                ->withCount([
                    'posts' => fn($q) => $q->where('status', 'published'),
                    'followers',
                    'following'
                ])
                ->where('username', $username)
                ->firstOrFail();
        });

        return new UserResource($user);
    }

    public function leaderboard(Request $request)
    {
        $period = $request->get('period', 'all'); // all, month, week
        $type = $request->get('type', 'xp'); // xp, posts, followers

        $cacheKey = "leaderboard:{$type}:{$period}";
        
        $users = Cache::remember($cacheKey, 300, function () use ($type, $period) {
            $query = User::with('level');

            if ($period !== 'all') {
                $date = $period === 'week' ? now()->subWeek() : now()->subMonth();
                
                if ($type === 'posts') {
                    $query->withCount(['posts' => fn($q) => $q->where('status', 'published')->where('created_at', '>=', $date)]);
                } elseif ($type === 'xp') {
                    $query->whereHas('xpTransactions', fn($q) => $q->where('created_at', '>=', $date));
                }
            } else {
                if ($type === 'posts') {
                    $query->withCount(['posts' => fn($q) => $q->where('status', 'published')]);
                } elseif ($type === 'followers') {
                    $query->withCount('followers');
                }
            }

            switch ($type) {
                case 'xp':
                    $query->orderByDesc('xp');
                    break;
                case 'posts':
                    $query->orderByDesc('posts_count');
                    break;
                case 'followers':
                    $query->orderByDesc('followers_count');
                    break;
            }

            return $query->limit(50)->get();
        });

        return UserResource::collection($users);
    }

    public function stats(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $cacheKey = "user:stats:{$user->id}";
        
        $stats = Cache::remember($cacheKey, 600, function () use ($user) {
            return [
                'posts_count' => $user->posts()->where('status', 'published')->count(),
                'comments_count' => $user->comments()->count(),
                'votes_received' => $user->posts()->sum('score') + $user->comments()->sum('score'),
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
                'badges_count' => $user->badges()->count(),
                'xp_total' => $user->xp,
                'level' => $user->level,
                'recent_activity' => [
                    'posts' => $user->posts()->where('status', 'published')->latest()->limit(5)->get(['id', 'title', 'slug', 'created_at']),
                    'comments' => $user->comments()->with('post:id,title,slug')->latest()->limit(5)->get(['id', 'post_id', 'content_markdown', 'created_at']),
                ],
                'monthly_stats' => $this->getMonthlyStats($user),
            ];
        });

        return response()->json($stats);
    }

    private function getMonthlyStats(User $user)
    {
        $months = collect();
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $months->push([
                'month' => $date->format('Y-m'),
                'posts' => $user->posts()
                    ->where('status', 'published')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count(),
                'comments' => $user->comments()
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count(),
                'xp_gained' => $user->xpTransactions()
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->sum('amount'),
            ]);
        }
        
        return $months;
    }
}