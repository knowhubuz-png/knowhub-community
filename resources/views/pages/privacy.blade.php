@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Maxfiylik Siyosati</h1>
    
    <div class="prose max-w-none bg-white p-8 rounded-lg shadow-sm border border-gray-200">
        <p class="text-lg text-gray-600 mb-8">
            KnowHub Community sizning maxfiyligingizni himoya qilishga sodiqdir. 
            Ushbu maxfiylik siyosati biz qanday ma'lumotlarni to'playmiz va ulardan qanday foydalanamiz haqida ma'lumot beradi.
        </p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">To'planadigan Ma'lumotlar</h2>
        <ul class="list-disc pl-6 space-y-2">
            <li>Shaxsiy ma'lumotlar (ism, email, foydalanuvchi nomi)</li>
            <li>Profil ma'lumotlari (bio, avatar)</li>
            <li>Kontent (postlar, kommentlar)</li>
            <li>Foydalanish statistikasi</li>
        </ul>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Ma'lumotlardan Foydalanish</h2>
        <ul class="list-disc pl-6 space-y-2">
            <li>Xizmatlarni taqdim etish</li>
            <li>Foydalanuvchi tajribasini yaxshilash</li>
            <li>Texnik yordam ko'rsatish</li>
            <li>Xavfsizlikni ta'minlash</li>
        </ul>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Aloqa</h2>
        <p>
            Maxfiylik siyosati bo'yicha savollaringiz bo'lsa:
        </p>
        <ul class="list-none mt-4">
            <li>Email: privacy@knowhub.uz</li>
            <li>Telegram: @knowhub_support</li>
        </ul>
    </div>
</div>
@endsection