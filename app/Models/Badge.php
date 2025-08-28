<?php


// file: app/Models/Badge.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['name','slug','icon','description','xp_reward'];
}



?>
