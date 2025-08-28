@extends('layouts.app')
@section('content')
<article class="bg-white p-6 rounded shadow-sm">
    <h1 class="text-3xl font-bold text-indigo-700">{{ $article->title }}</h1>
    <div class="text-sm text-gray-500 mt-1">
        Versiya: {{ $article->version }} • {{ $article->created_at->diffForHumans() }}
    </div>
    <div class="prose max-w-none mt-4">
        {!! Str::markdown($article->content_markdown) !!}
    </div>
</article>
@endsection

