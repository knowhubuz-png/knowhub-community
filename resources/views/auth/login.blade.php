@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow-sm">
    <h1 class="text-xl font-semibold mb-4">Kirish</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 text-sm">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1 text-sm">Parol</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>
        <button class="w-full bg-indigo-600 text-white py-2 rounded">Kirish</button>
    </form>
    <div class="mt-4 text-center text-sm">
        Yangi foydalanuvchimisiz? 
        <a href="{{ route('register') }}" class="text-indigo-600">Ro‘yxatdan o‘tish</a>
    </div>
</div>
@endsection

