@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Biz Bilan Bog'laning</h1>
        <p class="text-xl text-gray-600">
            Savollaringiz bormi? Biz sizga yordam berishga tayyormiz!
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-12">
        <!-- Contact Info -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Aloqa Ma'lumotlari</h2>
            
            <div class="space-y-6">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                        📧
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                        <p class="text-gray-600">info@knowhub.uz</p>
                        <p class="text-gray-600">support@knowhub.uz</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        📞
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Telefon</h3>
                        <p class="text-gray-600">+998 90 123 45 67</p>
                        <p class="text-gray-600">+998 91 234 56 78</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        📍
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Manzil</h3>
                        <p class="text-gray-600">Toshkent shahar</p>
                        <p class="text-gray-600">Chilonzor tumani</p>
                        <p class="text-gray-600">O'zbekiston</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        💬
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Telegram</h3>
                        <p class="text-gray-600">@knowhub_community</p>
                        <p class="text-gray-600">@knowhub_support</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Xabar Yuborish</h2>
            
            <form class="space-y-6" method="POST" action="{{ route('contact.send') }}">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Ism Familiya
                    </label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Ismingizni kiriting">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="email@example.com">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Mavzu
                    </label>
                    <select id="subject" name="subject" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Mavzuni tanlang</option>
                        <option value="general">Umumiy savol</option>
                        <option value="technical">Texnik yordam</option>
                        <option value="bug">Xato haqida xabar</option>
                        <option value="feature">Yangi funksiya taklifi</option>
                        <option value="partnership">Hamkorlik</option>
                        <option value="other">Boshqa</option>
                    </select>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Xabar
                    </label>
                    <textarea id="message" name="message" rows="6" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                              placeholder="Xabaringizni yozing..."></textarea>
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <span class="mr-2">📤</span>
                    Xabar Yuborish
                </button>
            </form>
        </div>
    </div>
</div>
@endsection