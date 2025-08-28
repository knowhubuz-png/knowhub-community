<?php

// file: app/Models/WikiArticle.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WikiArticle extends Model
{
    protected $fillable = ['title','slug','content_markdown','status','created_by','updated_by','version'];

    protected static function booted(): void
    {
        static::creating(function (WikiArticle $a) {
            $a->slug = $a->slug ?: Str::slug($a->title);
        });
    }

    public function proposals(): HasMany { return $this->hasMany(WikiProposal::class, 'article_id'); }
}

