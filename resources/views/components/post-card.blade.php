<div class="bg-white p-4 rounded shadow-sm hover:shadow-md transition">
    <a href="{{ route('posts.show', $post->slug) }}" class="text-lg font-bold text-indigo-600">
        {{ $post->title }}
    </a>
    <div class="text-sm text-gray-500 mt-1">
        {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }} • {{ $post->score }} ↑
    </div>
    <div class="mt-2 text-gray-700 line-clamp-3">
        {!! \Illuminate\Support\Str::limit(strip_tags($post->content_markdown), 150) !!}
    </div>
    <div class="mt-2 space-x-2">
        @foreach($post->tags as $tag)
            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded">{{ $tag->name }}</span>
        @endforeach
    </div>
</div>

