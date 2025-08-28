<?php



// file: app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name','slug','description'];

    protected static function booted(): void
    {
        static::creating(function (Category $c) {
            $c->slug = $c->slug ?: Str::slug($c->name);
        });
    }
}


?>
