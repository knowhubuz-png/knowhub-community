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
use App\Models\Notification;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$request->user() || !$this->isAdmin($request->user())) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        });
    }

    private function isAdmin($user): bool
    {
        return $user->is_admin || $user->email === 'admin@knowhub.uz' || $user->id === 1;
    }
    public function dashboard()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'active_today' => User::whereDate('updated_at', today())->count(),
                'new_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'banned' => User::where('is_banned', true)->count(),
                'admins' => User::where('is_admin', true)->count(),
            ],
            'posts' => [
                'total' => Post::count(),
                'published' => Post::where('status', 'published')->count(),
                'draft' => Post::where('status', 'draft')->count(),
                'today' => Post::whereDate('created_at', today())->count(),
                'this_week' => Post::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'high_score' => Post::where('score', '>', 10)->count(),
                'with_ai' => Post::where('is_ai_suggested', true)->count(),
            ],
            'comments' => [
                'total' => Comment::count(),
                'today' => Comment::whereDate('created_at', today())->count(),
                'this_week' => Comment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'high_score' => Comment::where('score', '>', 5)->count(),
            ],
            'wiki' => [
                'articles' => WikiArticle::count(),
                'published' => WikiArticle::where('status', 'published')->count(),
                'draft' => WikiArticle::where('status', 'draft')->count(),
                'proposals' => DB::table('wiki_proposals')->where('status', 'pending')->count(),
            ],
            'code_runs' => [
                'total' => CodeRun::count(),
                'successful' => CodeRun::where('status', 'success')->count(),
                'failed' => CodeRun::where('status', 'failed')->count(),
                'today' => CodeRun::whereDate('created_at', today())->count(),
                'by_language' => CodeRun::selectRaw('language, COUNT(*) as count')
                    ->groupBy('language')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get(),
            ],
            'categories' => Category::count(),
            'tags' => Tag::count(),
            'notifications' => [
                'total' => Notification::count(),
                'unread' => Notification::whereNull('read_at')->count(),
                'today' => Notification::whereDate('created_at', today())->count(),
            ],
            'votes' => [
                'total' => Vote::count(),
                'positive' => Vote::where('value', 1)->count(),
                'negative' => Vote::where('value', -1)->count(),
                'today' => Vote::whereDate('created_at', today())->count(),
            ],
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'database_size' => $this->getDatabaseSize(),
                'storage_used' => $this->getStorageUsed(),
                'cache_status' => Cache::has('test') ? 'working' : 'not_working',
            ],
        ];

        return response()->json($stats);
    }

    private function getDatabaseSize(): string
    {
        try {
            $size = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS 'size' FROM information_schema.tables WHERE table_schema = ?", [config('database.connections.mysql.database')]);
            return ($size[0]->size ?? 0) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getStorageUsed(): string
    {
        try {
            $bytes = 0;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(storage_path(), \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                $bytes += $file->getSize();
            }
            return round($bytes / 1024 / 1024, 1) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return response()->json(['message' => 'Cache successfully cleared']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error clearing cache: ' . $e->getMessage()], 500);
        }
    }

    public function optimizeSystem()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return response()->json(['message' => 'System optimized successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error optimizing system: ' . $e->getMessage()], 500);
        }
    }

    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
            $path = storage_path('app/backups/' . $filename);
            
            // Create backups directory if it doesn't exist
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $path
            );
            
            exec($command, $output, $return_var);
            
            if ($return_var === 0) {
                return response()->json([
                    'message' => 'Database backup created successfully',
                    'filename' => $filename,
                    'size' => filesize($path) . ' bytes'
                ]);
            } else {
                return response()->json(['message' => 'Database backup failed'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating backup: ' . $e->getMessage()], 500);
        }
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