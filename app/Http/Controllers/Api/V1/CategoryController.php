<?php
// file: app/Http/Controllers/Api/V1/CategoryController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() { return Category::select('id','name','slug')->orderBy('name')->get(); }
}

