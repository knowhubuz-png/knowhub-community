<?php


// file: app/Http/Resources/PostResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'content_markdown' => $this->content_markdown,
            'status' => $this->status,
            'score' => $this->score,
            'answers_count' => $this->answers_count,
            'tags' => $this->tags->map(fn($t) => ['name' => $t->name, 'slug' => $t->slug]),
            'category' => $this->category?->only(['id','name','slug']),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'avatar_url' => $this->user->avatar_url,
                'level' => $this->user->level?->only(['id','name','min_xp']),
                'xp' => $this->user->xp,
            ],
            'ai_suggestion' => $this->ai_suggestion,
            'created_at' => $this->created_at,
        ];
    }
}

