<?php
// file: app/Http/Controllers/Api/V1/TagController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagController extends Controller
{
    public function index() { return Tag::select('id','name','slug')->orderBy('name')->get(); }
}

