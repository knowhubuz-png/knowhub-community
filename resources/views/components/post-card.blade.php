<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all">
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center space-x-3">
            <img src="{{ $post->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name) }}" 
                 alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full">
            <div>
                <p class="font-medium text-gray-900">{{ $post->user->name }}</p>
                <div class="flex items-center text-sm text-gray-500">
                    <span class="mr-1">🕒</span>
                    {{ $post->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        
        <!-- User Level -->
        @if($post->user->level)
            <div class="flex items-center space-x-1 bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full text-xs">
                <span>{{ $post->user->level->name }}</span>
                <span>({{ $post->user->xp }} XP)</span>
            </div>
        @endif
    </div>

    <!-- Title -->
    <a href="{{ route('posts.show', $post->slug) }}">
        <h3 class="text-lg font-semibold text-gray-900 hover:text-indigo-600 transition-colors mb-3 line-clamp-2">
            {{ $post->title }}
        </h3>
    </a>

    <!-- Content Preview -->
    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
        {{ Str::limit(strip_tags($post->content_markdown), 150) }}
    </p>

    <!-- Tags -->
    @if($post->tags->count() > 0)
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach($post->tags->take(3) as $tag)
                <a href="{{ route('posts.index') }}?tag={{ $tag->slug }}" 
                   class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full hover:bg-gray-200 transition-colors">
                    #{{ $tag->name }}
                </a>
            @endforeach
            @if($post->tags->count() > 3)
                <span class="text-xs text-gray-500">+{{ $post->tags->count() - 3 }} ko'proq</span>
            @endif
        </div>
    @endif

    <!-- Category -->
    @if($post->category)
        <div class="mb-4">
            <a href="{{ route('posts.index') }}?category={{ $post->category->slug }}" 
               class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 text-sm rounded-full hover:bg-indigo-100 transition-colors">
                📁 {{ $post->category->name }}
            </a>
        </div>
    @endif

    <!-- Footer Stats -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-1 text-gray-500">
                <span>👍</span>
                <span class="text-sm">{{ $post->score }}</span>
            </div>
            <div class="flex items-center space-x-1 text-gray-500">
                <span>💬</span>
                <span class="text-sm">{{ $post->answers_count }}</span>
            </div>
            @if($post->created_at->isToday())
                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                    🆕 Yangi
                </span>
            @endif
        </div>

        <div class="flex items-center space-x-2">
            <!-- AI Suggestion Indicator -->
            @if($post->is_ai_suggested)
                <div class="flex items-center space-x-1 text-purple-600 text-xs">
                    <span>🤖</span>
                    <span>AI tavsiya</span>
                </div>
            @endif
            @if($post->score > 10)
                <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                    🔥 Hot
                </span>
            @endif
        </div>
    </div>
</div>