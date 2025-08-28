<?php

// file: app/Http/Controllers/Api/V1/PostController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\GeneratePostAiDraft;

class PostController extends Controller
{
    public function index(Request $req)
    {
        $q = Post::query()->with(['user.level','tags','category'])
            ->where('status','published')
            ->when($req->get('tag'), fn($qq,$tag)=>$qq->whereHas('tags', fn($t)=>$t->where('slug',$tag)))
            ->when($req->get('category'), fn($qq,$cat)=>$qq->whereHas('category', fn($c)=>$c->where('slug',$cat)))
            ->orderByDesc('score')->orderByDesc('id');

        return PostResource::collection($q->paginate(20));
    }

    public function show(string $slug)
    {
        $post = Post::with(['user.level','tags','category'])->where('slug',$slug)->firstOrFail();
        return new PostResource($post);
    }

    public function store(PostStoreRequest $req)
    {
        $data = $req->validated();
        $post = DB::transaction(function () use ($data, $req) {
            $post = Post::create([
                'user_id' => $req->user()->id,
                'category_id' => $data['category_id'] ?? null,
                'title' => $data['title'],
                'content_markdown' => $data['content_markdown'],
                'status' => 'published',
            ]);
            if (!empty($data['tags'])) {
                $tagIds = collect($data['tags'])->map(function ($name) {
                    $name = trim($name);
                    $tag = Tag::firstOrCreate(['name'=>$name], ['slug'=>\Str::slug($name)]);
                    return $tag->id;
                });
                $post->tags()->sync($tagIds);
            }
            return $post->fresh(['user.level','tags','category']);
        });

        GeneratePostAiDraft::dispatch($post->id);

        return new PostResource($post);
    }

    public function update(PostUpdateRequest $req, string $slug)
    {
        $post = Post::where('slug',$slug)->firstOrFail();
        $this->authorize('update', $post);
        $data = $req->validated();

        DB::transaction(function () use ($post, $data) {
            $post->fill($data)->save();
            if (isset($data['tags'])) {
                $tagIds = collect($data['tags'])->map(function ($name) {
                    $tag = Tag::firstOrCreate(['name'=>$name], ['slug'=>\Str::slug($name)]);
                    return $tag->id;
                });
                $post->tags()->sync($tagIds);
            }
        });

        return new PostResource($post->fresh(['user.level','tags','category']));
    }

    public function destroy(Request $req, string $slug)
    {
        $post = Post::where('slug',$slug)->firstOrFail();
        $this->authorize('delete', $post);
        $post->delete();
        return response()->json(['ok'=>true]);
    }
}

