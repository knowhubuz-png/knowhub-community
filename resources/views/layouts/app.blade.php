<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">
    @include('components.navbar')
    <main class="max-w-6xl mx-auto px-4 py-6">
        @yield('content')
    </main>
</body>
