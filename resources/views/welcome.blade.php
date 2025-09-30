@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                KnowHub <span class="text-yellow-300">Community</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto mb-8">
                O'zbekiston va butun dunyo bo'ylab dasturchilar hamjamiyatini birlashtiruvchi ochiq platforma
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('posts.index') }}" 
                   class="inline-flex items-center px-8 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    📚 Postlarni Ko'rish
                </a>
                @guest
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center px-8 py-3 bg-yellow-500 text-gray-900 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                    👥 Ro'yxatdan O'tish
                </a>
                @endguest
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-white py-12 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mx-auto mb-4">
                    📚
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['posts']['total'] ?? 0 }}</div>
                <div class="text-gray-600">Postlar</div>
                <div class="text-sm text-green-600 mt-1">+{{ $stats['posts']['today'] ?? 0 }} bugun</div>
            </div>
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mx-auto mb-4">
                    👥
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['users']['total'] ?? 0 }}</div>
                <div class="text-gray-600">Foydalanuvchilar</div>
                <div class="text-sm text-green-600 mt-1">+{{ $stats['users']['new_this_week'] ?? 0 }} bu hafta</div>
            </div>
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mx-auto mb-4">
                    💬
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['comments']['total'] ?? 0 }}</div>
                <div class="text-gray-600">Kommentlar</div>
                <div class="text-sm text-green-600 mt-1">+{{ $stats['comments']['today'] ?? 0 }} bugun</div>
            </div>
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mx-auto mb-4">
                    🏆
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['wiki']['articles'] ?? 0 }}</div>
                <div class="text-gray-600">Wiki Maqolalar</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Left Sidebar - Categories -->
        <div class="lg:col-span-1 order-2 lg:order-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">📁 Kategoriyalar</h2>
                    <a href="{{ route('posts.index') }}?category=all" class="text-indigo-600 hover:text-indigo-700 text-sm">
                        Barchasi
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach($categories as $category)
                        <a href="{{ route('posts.index') }}?category={{ $category->slug }}" 
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div class="flex items-center">
                                <span class="text-lg mr-3">{{ $category->icon ?? '📁' }}</span>
                                <div>
                                    <div class="font-medium text-gray-900 group-hover:text-indigo-600">
                                        {{ $category->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $category->posts_count ?? 0 }} post
                                    </div>
                                </div>
                            </div>
                            <span class="text-gray-400 group-hover:text-indigo-600">→</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Top Users -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">👑 Top Foydalanuvchilar</h2>
                    <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                        Reyting
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach($topUsers as $index => $user)
                        <a href="{{ route('profile.show', $user->username) }}" 
                           class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                            <div class="flex items-center space-x-1 mr-3">
                                @if($index === 0)
                                    <span class="text-yellow-500">👑</span>
                                @endif
                                <span class="text-sm font-bold text-gray-500">#{{ $index + 1 }}</span>
                            </div>
                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                                 alt="{{ $user->name }}" class="w-8 h-8 rounded-full mr-3">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 group-hover:text-indigo-600 text-sm">
                                    {{ $user->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $user->xp }} XP • {{ $user->posts_count ?? 0 }} post
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content - Posts -->
        <div class="lg:col-span-2 order-1 lg:order-2">
            <!-- Featured Post -->
            @if($featuredPost)
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 mb-8 text-white">
                    <div class="flex items-center mb-3">
                        <span class="text-yellow-300 mr-2">⭐</span>
                        <span class="text-sm font-medium text-indigo-100">Tanlangan Post</span>
                    </div>
                    <a href="{{ route('posts.show', $featuredPost->slug) }}">
                        <h3 class="text-xl font-bold mb-2 hover:text-yellow-300 transition-colors">
                            {{ $featuredPost->title }}
                        </h3>
                    </a>
                    <p class="text-indigo-100 mb-4">
                        {{ Str::limit(strip_tags($featuredPost->content_markdown), 120) }}
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 text-sm">
                            <span>👍 {{ $featuredPost->score }}</span>
                            <span>💬 {{ $featuredPost->answers_count }}</span>
                        </div>
                        <a href="{{ route('posts.show', $featuredPost->slug) }}" 
                           class="text-yellow-300 hover:text-yellow-200 font-medium">
                            O'qish →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Trending Posts -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <span class="text-red-500 mr-3">🔥</span>
                        <h2 class="text-2xl font-bold text-gray-900">Trend Postlar</h2>
                    </div>
                    <a href="{{ route('posts.index') }}?sort=trending" 
                       class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center">
                        Barchasini ko'rish →
                    </a>
                </div>
                
                @if($trendingPosts->count() > 0)
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach($trendingPosts->take(4) as $post)
                            @include('components.post-card', ['post' => $post])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-white rounded-lg border border-gray-200">
                        <span class="text-4xl mb-4 block">📈</span>
                        <p class="text-gray-600">Hozircha trend postlar yo'q</p>
                    </div>
                @endif
            </div>

            <!-- Latest Posts -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <span class="text-blue-500 mr-3">📅</span>
                        <h2 class="text-2xl font-bold text-gray-900">So'nggi Postlar</h2>
                    </div>
                    <a href="{{ route('posts.index') }}" 
                       class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center">
                        Barchasini ko'rish →
                    </a>
                </div>
                
                @if($latestPosts->count() > 0)
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach($latestPosts->take(4) as $post)
                            @include('components.post-card', ['post' => $post])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-white rounded-lg border border-gray-200">
                        <span class="text-4xl mb-4 block">📝</span>
                        <p class="text-gray-600">Hozircha postlar yo'q</p>
                        @auth
                            <a href="{{ route('posts.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors mt-4">
                                Birinchi post yozing
                            </a>
                        @else
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors mt-4">
                                Ro'yxatdan o'ting
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="lg:col-span-1 order-3">
            <!-- Trending Tags -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">🏷️ Trend Teglar</h2>
                    <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                        Barchasi
                    </a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($trendingTags->take(12) as $tag)
                        <a href="{{ route('posts.index') }}?tag={{ $tag->slug }}" 
                           class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-indigo-100 hover:text-indigo-700 transition-colors">
                            #{{ $tag->name }}
                            @if(isset($tag->usage_count))
                                <span class="ml-1 text-xs text-gray-500">({{ $tag->usage_count }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">⚡ Tezkor Harakatlar</h2>
                <div class="space-y-3">
                    @auth
                        <a href="{{ route('posts.create') }}" 
                           class="flex items-center p-3 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors">
                            <span class="mr-3">📝</span>
                            <span class="font-medium">Yangi Post Yozish</span>
                        </a>
                        <a href="{{ route('wiki.create') }}" 
                           class="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                            <span class="mr-3">📖</span>
                            <span class="font-medium">Wiki Maqola</span>
                        </a>
                        <a href="{{ route('profile.show', auth()->user()->username) }}" 
                           class="flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors">
                            <span class="mr-3">👤</span>
                            <span class="font-medium">Mening Profilim</span>
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="flex items-center p-3 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors">
                            <span class="mr-3">🚀</span>
                            <span class="font-medium">Ro'yxatdan O'tish</span>
                        </a>
                        <a href="{{ route('login') }}" 
                           class="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                            <span class="mr-3">🔑</span>
                            <span class="font-medium">Tizimga Kirish</span>
                        </a>
                    @endauth
                    <a href="{{ route('posts.index') }}" 
                       class="flex items-center p-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition-colors">
                        <span class="mr-3">🏆</span>
                        <span class="font-medium">Barcha Postlar</span>
                    </a>
                </div>
            </div>

            <!-- Community Stats -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-lg p-6 text-white">
                <h2 class="text-lg font-semibold mb-4">📊 Jamiyat Statistikasi</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Faol bugun:</span>
                        <span class="font-bold">{{ $stats['users']['active_today'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Yangi postlar:</span>
                        <span class="font-bold text-green-400">{{ $stats['posts']['this_week'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300">Bu hafta:</span>
                        <span class="font-bold text-blue-400">{{ $stats['users']['new_this_week'] ?? 0 }} yangi a'zo</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-700">
                    <a href="{{ route('posts.index') }}" 
                       class="flex items-center justify-center w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <span class="mr-2">👁️</span>
                        Batafsil ko'rish
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Asosiy Imkoniyatlar</h2>
            <p class="text-xl text-gray-600">KnowHub Community bilan nima qila olasiz</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    📚
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Postlar va Maqolalar</h3>
                <p class="text-gray-600">
                    Dasturlash bo'yicha savollar bering, tajribangizni baham ko'ring va boshqalardan o'rganing.
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    💻
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Kod Ishga Tushirish</h3>
                <p class="text-gray-600">
                    JavaScript, Python, PHP kodlarini to'g'ridan-to'g'ri brauzerda ishga tushiring va natijani ko'ring.
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    🏆
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Gamifikatsiya</h3>
                <p class="text-gray-600">
                    XP to'plang, darajangizni oshiring, badglar qo'lga kiriting va reyting jadvalida yuqoriga chiqing.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-indigo-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
            Hamjamiyatga Qo'shiling!
        </h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
            O'zbekistan dasturchilar hamjamiyatining bir qismi bo'ling. Bilim almashing, tajriba to'plang va karyerangizni rivojlantiring.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center px-8 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <span class="mr-2">⚡</span>
                    Bepul Ro'yxatdan O'tish
                </a>
            @endguest
            <a href="{{ route('posts.index') }}" 
               class="inline-flex items-center px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors">
                <span class="mr-2">📚</span>
                Postlarni Ko'rish
            </a>
        </div>
    </div>
</div>
@endsection