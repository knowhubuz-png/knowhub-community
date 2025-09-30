@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Yangi Post Yaratish</h1>
            <a href="{{ route('posts.index') }}" 
               class="text-gray-600 hover:text-gray-900 flex items-center">
                ← Ortga
            </a>
        </div>

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-6">
            @csrf
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Post sarlavhasi *
                </label>
                <input type="text" id="title" name="title" 
                       value="{{ old('title') }}"
                       placeholder="Muhokama uchun sarlavha..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-500 @enderror"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Kategoriya
                </label>
                <select id="category_id" name="category_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Kategoriyani tanlang</option>
                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content_markdown" class="block text-sm font-medium text-gray-700 mb-2">
                    Post kontenti *
                </label>
                <textarea id="content_markdown" name="content_markdown" rows="15"
                          placeholder="Postni shu yerga yozing... Markdown formatini ishlatishingiz mumkin."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono @error('content_markdown') border-red-500 @enderror"
                          required>{{ old('content_markdown') }}</textarea>
                @error('content_markdown')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="mt-2 text-sm text-gray-500">
                    <p><strong>Markdown formatini ishlating:</strong></p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2 text-xs">
                        <span class="bg-gray-100 px-2 py-1 rounded">**qalin**</span>
                        <span class="bg-gray-100 px-2 py-1 rounded">*kursiv*</span>
                        <span class="bg-gray-100 px-2 py-1 rounded"># Sarlavha</span>
                        <span class="bg-gray-100 px-2 py-1 rounded">`kod`</span>
                        <span class="bg-gray-100 px-2 py-1 rounded">- ro'yxat</span>
                        <span class="bg-gray-100 px-2 py-1 rounded">[link](url)</span>
                        <span class="bg-gray-100 px-2 py-1 rounded">```kod blok```</span>
                        <span class="bg-gray-100 px-2 py-1 rounded">> iqtibos</span>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                    Teglar (vergul bilan ajrating)
                </label>
                <input type="text" id="tags" name="tags" 
                       value="{{ old('tags') }}"
                       placeholder="Laravel, PHP, JavaScript"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="mt-1 text-sm text-gray-500">
                    Masalan: Laravel, PHP, JavaScript (vergul bilan ajrating)
                </p>
                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('posts.index') }}" 
                   class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Bekor qilish
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <span class="mr-2">💾</span>
                    Nashr qilish
                </button>
            </div>
        </form>
    </div>
</div>
@endsection