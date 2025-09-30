<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">KH</span>
                </div>
                <span class="font-bold text-xl text-gray-900 hidden sm:block">KnowHub</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('posts.index') }}" 
                   class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                    Postlar
                </a>
                <a href="{{ route('wiki.index') }}" 
                   class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                    Wiki
                </a>
                <a href="{{ route('posts.index') }}?sort=trending" 
                   class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                    Trend
                </a>
                <a href="{{ route('posts.index') }}?category=all" 
                   class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">
                    Kategoriyalar
                </a>
            </div>

            <!-- Search Bar -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <form action="{{ route('posts.index') }}" method="GET" class="w-full">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
                        <input type="text" name="search" 
                               placeholder="Qidirish..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </form>
            </div>

            <!-- Desktop Auth -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <a href="{{ route('posts.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <span class="mr-2">➕</span>
                        Post yozish
                    </a>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 focus:outline-none">
                            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" 
                                 alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                            <span class="font-medium">{{ auth()->user()->name }}</span>
                            <span class="text-gray-400">▼</span>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <a href="{{ route('profile.show', auth()->user()->username) }}" 
                               class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                <span class="mr-2">👤</span>
                                Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <span class="mr-2">🚪</span>
                                    Chiqish
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-indigo-600 font-medium">
                            Kirish
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Ro'yxatdan o'tish
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <button onclick="toggleMobileMenu()" 
                    class="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <span id="menu-icon">☰</span>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden py-4 border-t border-gray-200 hidden">
            <div class="space-y-4">
                <!-- Mobile Search -->
                <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
                        <input type="text" name="search" 
                               placeholder="Qidirish..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </form>

                <!-- Mobile Links -->
                <div class="space-y-2">
                    <a href="{{ route('posts.index') }}" 
                       class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                        Postlar
                    </a>
                    <a href="{{ route('wiki.index') }}" 
                       class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                        Wiki
                    </a>
                    <a href="{{ route('posts.index') }}?sort=trending" 
                       class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                        Trend
                    </a>
                </div>

                <!-- Mobile Auth -->
                @auth
                    <div class="space-y-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('posts.create') }}" 
                           class="flex items-center px-3 py-2 bg-indigo-600 text-white rounded-lg">
                            <span class="mr-2">➕</span>
                            Post yozish
                        </a>
                        <a href="{{ route('profile.show', auth()->user()->username) }}" 
                           class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" 
                                 alt="{{ auth()->user()->name }}" class="w-6 h-6 rounded-full mr-2">
                            {{ auth()->user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                                <span class="mr-2">🚪</span>
                                Chiqish
                            </button>
                        </form>
                    </div>
                @else
                    <div class="space-y-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('login') }}" 
                           class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                            Kirish
                        </a>
                        <a href="{{ route('register') }}" 
                           class="block px-3 py-2 bg-indigo-600 text-white rounded-lg text-center">
                            Ro'yxatdan o'tish
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('menu-icon');
    
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        icon.textContent = '✕';
    } else {
        menu.classList.add('hidden');
        icon.textContent = '☰';
    }
}
</script>