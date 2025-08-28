<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostWebController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user','tags'])
            ->where('status','published')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::with(['user','tags','comments.user','comments.children.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }

    public function storeComment(Request $request, string $slug)
    {
        $request->validate([
            'content_markdown' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $post = Post::where('slug',$slug)->firstOrFail();

        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content_markdown' => $request->content_markdown,
            'depth' => $request->parent_id ? Comment::find($request->parent_id)->depth + 1 : 0,
        ]);

        return back();
    }
}

