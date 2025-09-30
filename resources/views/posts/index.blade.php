@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4 sm:mb-0">Postlar</h1>
        @auth
            <a href="{{ route('posts.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <span class="mr-2">➕</span>
                Yangi Post
            </a>
        @endauth
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('posts.index') }}" class="space-y-4">
            <!-- Search Bar -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
                <input type="text" name="search" 
                       placeholder="Postlarni qidirish..." 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Category Filter -->
                <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Barcha kategoriyalar</option>
                    <option value="all" {{ request('category') === 'all' ? 'selected' : '' }}>Barchasi</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->posts_count }})
                        </option>
                    @endforeach
                </select>

                <!-- Tag Filter -->
                <select name="tag" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Barcha teglar</option>
                    <option value="all" {{ request('tag') === 'all' ? 'selected' : '' }}>Barchasi</option>
                    @foreach($popularTags as $tag)
                        <option value="{{ $tag->slug }}" {{ request('tag') === $tag->slug ? 'selected' : '' }}>
                            {{ $tag->name }} ({{ $tag->usage_count }})
                        </option>
                    @endforeach
                </select>

                <!-- Sort Filter -->
                <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="" {{ !request('sort') ? 'selected' : '' }}>So'nggi</option>
                    <option value="trending" {{ request('sort') === 'trending' ? 'selected' : '' }}>Trend</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Mashhur</option>
                    <option value="unanswered" {{ request('sort') === 'unanswered' ? 'selected' : '' }}>Javobsiz</option>
                </select>

                <!-- Submit Button -->
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Qidirish
                </button>
            </div>
        </form>
    </div>

    <!-- Posts Grid -->
    @if($posts->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
                @include('components.post-card', ['post' => $post])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $posts->appends(request()->query())->links('components.pagination') }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
            <span class="text-6xl mb-4 block">📝</span>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Postlar topilmadi</h3>
            <p class="text-gray-600 mb-6">Qidiruv shartlaringizni o'zgartirib ko'ring</p>
            @auth
                <a href="{{ route('posts.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Birinchi post yozing
                </a>
            @else
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Ro'yxatdan o'ting
                </a>
            @endauth
        </div>
    @endif
</div>
@endsection