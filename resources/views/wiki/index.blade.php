@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-4">Wiki Maqolalar</h1>
<div class="space-y-4">
    @foreach($articles as $a)
        <div class="bg-white p-4 rounded shadow-sm">
            <a href="{{ route('wiki.show', $a->slug) }}" class="text-lg font-bold text-indigo-600">
                {{ $a->title }}
            </a>
            <div class="text-sm text-gray-500">
                Versiya: {{ $a->version }} • {{ $a->created_at->diffForHumans() }}
            </div>
        </div>
    @endforeach
</div>
<div class="mt-6">
    {{ $articles->links('components.pagination') }}
</div>
@endsection

