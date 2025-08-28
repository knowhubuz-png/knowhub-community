<?php

// file: app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name','username','email','password','avatar_url','provider','provider_id','xp','level_id','bio'
    ];
    protected $hidden = ['password','remember_token'];

    public function posts(): HasMany { return $this->hasMany(Post::class); }
    public function comments(): HasMany { return $this->hasMany(Comment::class); }
    public function level(): BelongsTo { return $this->belongsTo(Level::class); }
    public function xpTransactions(): HasMany { return $this->hasMany(XpTransaction::class); }
}

