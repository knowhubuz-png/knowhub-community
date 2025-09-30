@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Foydalanish Shartlari</h1>
    
    <div class="prose max-w-none bg-white p-8 rounded-lg shadow-sm border border-gray-200">
        <p class="text-lg text-gray-600 mb-8">
            KnowHub Community platformasidan foydalanish orqali siz quyidagi shartlarga rozilik bildirasiz.
        </p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Umumiy Qoidalar</h2>
        <ul class="list-disc pl-6 space-y-2">
            <li>Platformadan faqat qonuniy maqsadlarda foydalaning</li>
            <li>Boshqa foydalanuvchilarga hurmat bilan munosabatda bo'ling</li>
            <li>Spam va keraksiz kontent joylashtirmang</li>
            <li>Mualliflik huquqlarini hurmat qiling</li>
        </ul>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. Kontent Qoidalari</h2>
        <ul class="list-disc pl-6 space-y-2">
            <li>Faqat o'zingizga tegishli yoki ruxsat etilgan kontentni joylashtiring</li>
            <li>Haqoratli, kamsituvchi yoki zararli kontent taqiqlanadi</li>
            <li>Dasturlash va texnologiya mavzulariga oid kontent joylashtiring</li>
            <li>Aniq va tushunarli sarlavhalar ishlating</li>
        </ul>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Aloqa</h2>
        <p>
            Foydalanish shartlari bo'yicha savollaringiz bo'lsa:
        </p>
        <ul class="list-none mt-4">
            <li>Email: legal@knowhub.uz</li>
            <li>Telegram: @knowhub_support</li>
        </ul>
    </div>
</div>
@endsection