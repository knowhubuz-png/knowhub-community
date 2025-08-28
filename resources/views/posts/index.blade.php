@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-4">So‘nggi Postlar</h1>
<div class="space-y-4">
    @foreach($posts as $post)
        @include('components.post-card', ['post' => $post])
    @endforeach
</div>
<div class="mt-6">
    {{ $posts->links('components.pagination') }}
</div>
@endsection

