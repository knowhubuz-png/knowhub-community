@foreach($comments as $comment)
<div class="mb-4 pl-{{ $comment->depth * 4 }}">
    <div class="bg-gray-50 p-3 rounded">
        <div class="text-sm font-semibold">{{ $comment->user->name }}</div>
        <div class="mt-1 prose">{!! Str::markdown($comment->content_markdown) !!}</div>
        <div class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
    </div>
    @if($comment->children->count())
        @include('components.comment-tree', ['comments' => $comment->children])
    @endif
</div>
@endforeach

