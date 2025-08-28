@extends('layouts.app')
@section('content')
<article class="bg-white p-6 rounded shadow-sm">
    <h1 class="text-3xl font-bold text-indigo-700">{{ $post->title }}</h1>
    <div class="mt-2 text-sm text-gray-500">
        {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }} • {{ $post->score }} ↑
    </div>
    <div class="prose max-w-none mt-4">
        {!! Str::markdown($post->content_markdown) !!}
    </div>

    @if($post->ai_suggestion)
        <div class="mt-6 p-4 border-l-4 border-indigo-500 bg-indigo-50">
            <h2 class="text-lg font-semibold">💡 AI tavsiya javobi</h2>
            <div class="prose max-w-none mt-2">
                {!! Str::markdown($post->ai_suggestion['content_markdown']) !!}
            </div>
        </div>
    @endif
</article>

<section class="mt-8">
    <h2 class="text-xl font-semibold mb-4">Kommentlar</h2>
    @include('components.comment-tree', ['comments' => $post->comments])
</section>
@endsection

