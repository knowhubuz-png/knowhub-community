@if ($paginator->hasPages())
    <nav role="navigation" class="flex justify-between mt-4">
        {{-- Oldingi sahifa --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded">Oldingi</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600">Oldingi</a>
        @endif

        {{-- Sahifa raqamlari --}}
        <div class="flex space-x-1">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 py-1 text-gray-500">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 bg-indigo-500 text-white rounded">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Keyingi sahifa --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600">Keyingi</a>
        @else
            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded">Keyingi</span>
        @endif
    </nav>
@endif

