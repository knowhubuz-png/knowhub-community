<?php

// file: app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'user_id','category_id','title','slug','content_markdown','is_ai_suggested','ai_suggestion','status','score','answers_count'
    ];
    protected $casts = [
        'ai_suggestion' => 'array',
        'is_ai_suggested' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            $post->slug = $post->slug ?: Str::slug(Str::limit($post->title, 60, ''));
        });
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function tags(): BelongsToMany { return $this->belongsToMany(Tag::class); }
    public function comments(): HasMany { return $this->hasMany(Comment::class)->whereNull('parent_id'); }
    public function votes(): MorphMany { return $this->morphMany(Vote::class, 'votable'); }
}



?>
