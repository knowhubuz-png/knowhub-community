@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Yordam Markazi</h1>
        <p class="text-xl text-gray-600">KnowHub Community dan qanday foydalanishni o'rganing</p>
    </div>

    <div class="grid md:grid-cols-2 gap-8 mb-12">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-3">📚</span>
                <h2 class="text-xl font-semibold text-gray-900">Post Yozish</h2>
            </div>
            <p class="text-gray-600 mb-4">
                Dasturlash bo'yicha savollar bering, tajribangizni baham ko'ring va boshqalardan o'rganing.
            </p>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>• Aniq va tushunarli sarlavha yozing</li>
                <li>• Markdown formatidan foydalaning</li>
                <li>• Teglar qo'shing</li>
                <li>• Kod namunalarini kiriting</li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-3">💬</span>
                <h2 class="text-xl font-semibold text-gray-900">Komment Qoldirish</h2>
            </div>
            <p class="text-gray-600 mb-4">
                Postlarga javob bering, o'z fikringizni bildiring va muhokamaga qo'shiling.
            </p>
            <ul class="text-sm text-gray-600 space-y-2">
                <li>• Foydali va konstruktiv javoblar bering</li>
                <li>• Kod namunalari bilan tushuntiring</li>
                <li>• Boshqa kommentlarga javob bering</li>
                <li>• Hurmatli munosabatda bo'ling</li>
            </ul>
        </div>
    </div>

    <!-- FAQ -->
    <div class="bg-indigo-50 p-8 rounded-lg">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Tez-tez So'raladigan Savollar</h2>
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">KnowHub Community bepulmi?</h3>
                <p class="text-gray-600">
                    Ha, KnowHub Community to'liq bepul platforma. Barcha asosiy funksiyalar doimo bepul bo'lib qoladi.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">Qanday qilib post yozishni boshlasam?</h3>
                <p class="text-gray-600">
                    Avval ro'yxatdan o'ting, keyin "Post yozish" tugmasini bosing. Sarlavha, kontent va teglar qo'shing.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">XP tizimi qanday ishlaydi?</h3>
                <p class="text-gray-600">
                    Post yozish, komment qoldirish va ovoz olish orqali XP to'playsiz. XP sizning darajangizni oshiradi.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection