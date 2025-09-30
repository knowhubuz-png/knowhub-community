@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600">KnowHub Community boshqaruv paneli</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    👥
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Jami Foydalanuvchilar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['users']['total'] ?? 0 }}</p>
                    <p class="text-xs text-green-600">+{{ $stats['users']['new_this_week'] ?? 0 }} bu hafta</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    📝
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Jami Postlar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['posts']['total'] ?? 0 }}</p>
                    <p class="text-xs text-green-600">+{{ $stats['posts']['today'] ?? 0 }} bugun</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    💬
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Kommentlar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['comments']['total'] ?? 0 }}</p>
                    <p class="text-xs text-green-600">+{{ $stats['comments']['today'] ?? 0 }} bugun</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    📚
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Wiki Maqolalar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['wiki']['articles'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🛠️ Tezkor Harakatlar</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <form method="POST" action="{{ route('admin.cache.clear') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="w-full flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <span class="text-2xl mb-2">🗄️</span>
                    <span class="text-sm font-medium text-blue-700">Cache Tozalash</span>
                </button>
            </form>
            
            <form method="POST" action="{{ route('admin.optimize') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="w-full flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <span class="text-2xl mb-2">⚡</span>
                    <span class="text-sm font-medium text-green-700">Optimizatsiya</span>
                </button>
            </form>
            
            <a href="{{ route('admin.users') }}" 
               class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <span class="text-2xl mb-2">👥</span>
                <span class="text-sm font-medium text-purple-700">Foydalanuvchilar</span>
            </a>
            
            <a href="{{ route('admin.posts') }}" 
               class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <span class="text-2xl mb-2">📝</span>
                <span class="text-sm font-medium text-orange-700">Postlar</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 So'nggi Faoliyat</h3>
            <div class="space-y-4">
                @foreach($recentPosts->take(5) as $post)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-sm">{{ Str::limit($post->title, 40) }}</p>
                            <p class="text-xs text-gray-500">{{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('posts.show', $post->slug) }}" 
                           class="text-indigo-600 hover:text-indigo-700 text-sm">
                            Ko'rish
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🏆 Top Foydalanuvchilar</h3>
            <div class="space-y-4">
                @foreach($topUsers as $index => $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-sm font-bold text-gray-500 mr-3">#{{ $index + 1 }}</span>
                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                                 alt="{{ $user->name }}" class="w-8 h-8 rounded-full mr-3">
                            <div>
                                <p class="font-medium text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->xp }} XP</p>
                            </div>
                        </div>
                        <a href="{{ route('profile.show', $user->username) }}" 
                           class="text-indigo-600 hover:text-indigo-700 text-sm">
                            Profil
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection