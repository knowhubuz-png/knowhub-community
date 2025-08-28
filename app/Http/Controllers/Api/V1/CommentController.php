<?php

// file: app/Http/Controllers/Api/V1/CommentController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $req, string $slug)
    {
        $post = Post::where('slug',$slug)->firstOrFail();
        $data = $req->validated();

        $comment = DB::transaction(function () use ($post, $req, $data) {
            $depth = 0;
            if (!empty($data['parent_id'])) {
                $parent = Comment::where('post_id',$post->id)->findOrFail($data['parent_id']);
                $depth = min(8, $parent->depth + 1);
            }
            $comment = Comment::create([
                'post_id' => $post->id,
                'user_id' => $req->user()->id,
                'parent_id' => $data['parent_id'] ?? null,
                'content_markdown' => $data['content_markdown'],
                'depth' => $depth,
            ]);
            $post->increment('answers_count');
            return $comment;
        });

        $comment->load('user','children');
        return new CommentResource($comment);
    }

    public function destroy(Request $req, int $id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('delete', $comment);
        $comment->delete();
        return response()->json(['ok'=>true]);
    }
}

