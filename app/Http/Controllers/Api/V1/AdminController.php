<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\WikiArticle;
use App\Models\CodeRun;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$request->user() || !$request->user()->is_admin) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'active_today' => User::whereDate('updated_at', today())->count(),
                'new_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ],
            'posts' => [
                'total' => Post::count(),
                'published' => Post::where('status', 'published')->count(),
                'draft' => Post::where('status', 'draft')->count(),
                'today' => Post::whereDate('created_at', today())->count(),
                'this_week' => Post::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'comments' => [
                'total' => Comment::count(),
                'today' => Comment::whereDate('created_at', today())->count(),
                'this_week' => Comment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'wiki' => [
                'articles' => WikiArticle::count(),
                'published' => WikiArticle::where('status', 'published')->count(),
                'draft' => WikiArticle::where('status', 'draft')->count(),
            ],
            'code_runs' => [
                'total' => CodeRun::count(),
                'successful' => CodeRun::where('status', 'success')->count(),
                'failed' => CodeRun::where('status', 'failed')->count(),
                'today' => CodeRun::whereDate('created_at', today())->count(),
            ],
            'categories' => Category::count(),
            'tags' => Tag::count(),
        ];

        return response()->json($stats);
    }

    public function users(Request $request)
    {
        $query = User::with('level')
            ->withCount(['posts', 'comments', 'followers', 'following']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            switch ($status) {
                case 'active':
                    $query->where('updated_at', '>=', now()->subDays(7));
                    break;
                case 'inactive':
                    $query->where('updated_at', '<', now()->subDays(30));
                    break;
                case 'new':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
            }
        }

        $users = $query->latest()->paginate(20);
        
        return response()->json($users);
    }

    public function posts(Request $request)
    {
        $query = Post::with(['user', 'category', 'tags'])
            ->withCount(['comments', 'votes']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content_markdown', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->get('category')) {
            $query->where('category_id', $category);
        }

        $posts = $query->latest()->paginate(20);
        
        return response()->json($posts);
    }

    public function comments(Request $request)
    {
        $query = Comment::with(['user', 'post'])
            ->withCount('votes');

        if ($search = $request->get('search')) {
            $query->where('content_markdown', 'LIKE', "%{$search}%");
        }

        if ($postId = $request->get('post_id')) {
            $query->where('post_id', $postId);
        }

        $comments = $query->latest()->paginate(20);
        
        return response()->json($comments);
    }

    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays((int)$period);

        $analytics = [
            'user_growth' => $this->getUserGrowth($startDate),
            'post_activity' => $this->getPostActivity($startDate),
            'engagement_metrics' => $this->getEngagementMetrics($startDate),
            'popular_categories' => $this->getPopularCategories($startDate),
            'popular_tags' => $this->getPopularTags($startDate),
            'code_execution_stats' => $this->getCodeExecutionStats($startDate),
            'top_users' => $this->getTopUsers($startDate),
        ];

        return response()->json($analytics);
    }

    public function updateUserStatus(Request $request, $userId)
    {
        $data = $request->validate([
            'is_admin' => 'sometimes|boolean',
            'is_banned' => 'sometimes|boolean',
            'ban_reason' => 'sometimes|string|max:500',
        ]);

        $user = User::findOrFail($userId);
        $user->update($data);

        return response()->json(['message' => 'User status updated successfully']);
    }

    public function deletePost($postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function systemSettings()
    {
        $settings = [
            'maintenance_mode' => config('app.maintenance', false),
            'registration_enabled' => config('app.registration_enabled', true),
            'max_posts_per_day' => config('app.max_posts_per_day', 10),
            'max_comments_per_day' => config('app.max_comments_per_day', 50),
            'code_execution_enabled' => config('app.code_execution_enabled', true),
        ];

        return response()->json($settings);
    }

    public function updateSystemSettings(Request $request)
    {
        $data = $request->validate([
            'maintenance_mode' => 'sometimes|boolean',
            'registration_enabled' => 'sometimes|boolean',
            'max_posts_per_day' => 'sometimes|integer|min:1|max:100',
            'max_comments_per_day' => 'sometimes|integer|min:1|max:500',
            'code_execution_enabled' => 'sometimes|boolean',
        ]);

        // Update settings in cache or config
        foreach ($data as $key => $value) {
            Cache::forever("settings.{$key}", $value);
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }

    private function getUserGrowth($startDate)
    {
        return User::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getPostActivity($startDate)
    {
        return Post::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getEngagementMetrics($startDate)
    {
        return [
            'avg_comments_per_post' => Post::where('created_at', '>=', $startDate)->avg('answers_count'),
            'avg_score_per_post' => Post::where('created_at', '>=', $startDate)->avg('score'),
            'total_votes' => DB::table('votes')->where('created_at', '>=', $startDate)->count(),
            'active_users' => User::whereHas('posts', function ($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            })->orWhereHas('comments', function ($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            })->count(),
        ];
    }

    private function getPopularCategories($startDate)
    {
        return DB::table('posts')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->where('posts.created_at', '>=', $startDate)
            ->select('categories.name', 'categories.slug', DB::raw('COUNT(*) as posts_count'))
            ->groupBy('categories.id', 'categories.name', 'categories.slug')
            ->orderByDesc('posts_count')
            ->limit(10)
            ->get();
    }

    private function getPopularTags($startDate)
    {
        return DB::table('post_tag')
            ->join('tags', 'post_tag.tag_id', '=', 'tags.id')
            ->join('posts', 'post_tag.post_id', '=', 'posts.id')
            ->where('posts.created_at', '>=', $startDate)
            ->select('tags.name', 'tags.slug', DB::raw('COUNT(*) as usage_count'))
            ->groupBy('tags.id', 'tags.name', 'tags.slug')
            ->orderByDesc('usage_count')
            ->limit(10)
            ->get();
    }

    private function getCodeExecutionStats($startDate)
    {
        return [
            'total_runs' => CodeRun::where('created_at', '>=', $startDate)->count(),
            'success_rate' => CodeRun::where('created_at', '>=', $startDate)
                ->where('status', 'success')
                ->count() / max(1, CodeRun::where('created_at', '>=', $startDate)->count()) * 100,
            'popular_languages' => CodeRun::where('created_at', '>=', $startDate)
                ->selectRaw('language, COUNT(*) as count')
                ->groupBy('language')
                ->orderByDesc('count')
                ->get(),
            'avg_runtime' => CodeRun::where('created_at', '>=', $startDate)
                ->where('status', 'success')
                ->avg('runtime_ms'),
        ];
    }

    private function getTopUsers($startDate)
    {
        return User::with('level')
            ->whereHas('posts', function ($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            })
            ->withCount(['posts' => function ($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            }])
            ->orderByDesc('posts_count')
            ->limit(10)
            ->get();
    }
}