<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-lg font-bold text-indigo-600">KnowHub</a>
        <div class="space-x-4">
            <a href="{{ route('posts.index') }}" class="hover:text-indigo-500">Postlar</a>
            <a href="{{ route('wiki.index') }}" class="hover:text-indigo-500">Wiki</a>
            @auth
                <a href="{{ route('profile.show', auth()->user()->username) }}" class="hover:text-indigo-500">Profil</a>
            @else
                <a href="{{ route('login') }}" class="hover:text-indigo-500">Kirish</a>
            @endauth
        </div>
    </div>
</nav>

