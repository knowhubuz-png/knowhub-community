@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded shadow-sm max-w-3xl mx-auto">
    <div class="flex items-center space-x-4">
        <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
             class="w-16 h-16 rounded-full">
        <div>
            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
            <p class="text-sm text-gray-500">@{{ $user->username }}</p>
            <p class="mt-1 text-sm">{{ $user->bio }}</p>
        </div>
    </div>

    <div class="mt-4">
        <div class="bg-gray-100 rounded-full h-4 overflow-hidden">
            <div class="bg-indigo-500 h-full" style="width: {{ min(100, ($user->xp % 1000) / 10) }}%"></div>
        </div>
        <p class="mt-1 text-sm text-gray-600">
            XP: {{ $user->xp }} — Level: {{ $user->level?->name ?? 'N/A' }}
        </p>
    </div>

    <div class="mt-6">
        <h2 class="text-lg font-semibold mb-2">Badglar</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($user->badges ?? [] as $badge)
                <div class="flex items-center space-x-1 bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                    <span>{{ $badge->icon ?? '🏅' }}</span>
                    <span class="text-sm">{{ $badge->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

