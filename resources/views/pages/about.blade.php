@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">Biz Haqimizda</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            KnowHub Community - O'zbekiston va butun dunyo bo'ylab dasturchilar hamjamiyatini 
            birlashtiruvchi ochiq platforma. Bizning maqsadimiz bilim almashish, hamkorlikda 
            loyihalar yaratish va yangi texnologiyalarni o'zlashtirishni osonlashtirish.
        </p>
    </div>

    <!-- Mission & Vision -->
    <div class="grid md:grid-cols-2 gap-8 mb-16">
        <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-3">🎯</span>
                <h2 class="text-2xl font-semibold text-gray-900">Bizning Maqsadimiz</h2>
            </div>
            <p class="text-gray-600">
                O'zbekiston dasturchilar hamjamiyatini rivojlantirish, bilim almashish uchun 
                qulay muhit yaratish va har bir dasturchining professional o'sishiga yordam berish.
            </p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-3">❤️</span>
                <h2 class="text-2xl font-semibold text-gray-900">Bizning Qadriyatlarimiz</h2>
            </div>
            <p class="text-gray-600">
                Ochiqlik, hamkorlik, o'zaro yordam va doimiy o'rganish. Biz har bir 
                hamjamiyat a'zosining fikri va hissasini qadrlaymiz.
            </p>
        </div>
    </div>

    <!-- Features -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Nima Taklif Qilamiz</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    💻
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Kod Ishga Tushirish</h3>
                <p class="text-gray-600">
                    JavaScript, Python, PHP kodlarini to'g'ridan-to'g'ri brauzerda ishga tushiring 
                    va natijani real vaqtda ko'ring.
                </p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    👥
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Hamjamiyat</h3>
                <p class="text-gray-600">
                    Minglab dasturchilar bilan bog'laning, tajriba almashing va 
                    professional tarmoqingizni kengaytiring.
                </p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    🏆
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Gamifikatsiya</h3>
                <p class="text-gray-600">
                    XP to'plang, darajangizni oshiring, badglar qo'lga kiriting va 
                    reyting jadvalida yuqoriga chiqing.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-indigo-600 p-8 rounded-lg text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Bizga Qo'shiling!</h2>
        <p class="text-xl text-indigo-100 mb-6">
            O'zbekiston dasturchilar hamjamiyatining bir qismi bo'ling va karyerangizni rivojlantiring.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center px-8 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <span class="mr-2">👥</span>
                    Ro'yxatdan O'tish
                </a>
            @endguest
            <a href="{{ route('posts.index') }}" 
               class="inline-flex items-center px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors">
                <span class="mr-2">🌐</span>
                Postlarni Ko'rish
            </a>
        </div>
    </div>
</div>
@endsection