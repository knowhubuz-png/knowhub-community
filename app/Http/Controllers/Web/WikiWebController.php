<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WikiArticle;

class WikiWebController extends Controller
{
    public function index()
    {
        $articles = WikiArticle::latest()->paginate(10);
        return view('wiki.index', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = WikiArticle::where('slug',$slug)->firstOrFail();
        return view('wiki.show', compact('article'));
    }
}

