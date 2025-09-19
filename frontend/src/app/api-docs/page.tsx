'use client';
import { BookOpen, Code, FileText, Database, Shield, Zap } from 'lucide-react';

export default function ApiDocsPage() {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-700 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="text-center">
            <div className="flex items-center justify-center mb-4">
              <Code className="w-12 h-12" />
            </div>
            <h1 className="text-4xl md:text-5xl font-bold mb-4">API Hujjatlari</h1>
            <p className="text-xl text-indigo-100 max-w-2xl mx-auto">
              KnowHub Community API dan foydalanish uchun to'liq qo'llanma
            </p>
          </div>
        </div>
      </div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* API Info */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-4">Asosiy Ma'lumotlar</h2>
          <div className="grid md:grid-cols-2 gap-6">
            <div>
              <h3 className="font-semibold text-gray-900 mb-2">Base URL</h3>
              <code className="block bg-gray-100 p-3 rounded-lg text-sm">
                https://api.knowhub.uz/api/v1
              </code>
            </div>
            <div>
              <h3 className="font-semibold text-gray-900 mb-2">Authentication</h3>
              <code className="block bg-gray-100 p-3 rounded-lg text-sm">
                Bearer Token (Laravel Sanctum)
              </code>
            </div>
          </div>
        </div>

        {/* Endpoints */}
        <div className="space-y-6">
          {/* Authentication */}
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center mb-4">
              <Shield className="w-6 h-6 text-green-600 mr-3" />
              <h3 className="text-xl font-bold text-gray-900">Autentifikatsiya</h3>
            </div>
            <div className="space-y-4">
              <div className="border-l-4 border-green-500 pl-4">
                <h4 className="font-semibold text-gray-900">Email bilan ro'yxatdan o'tish</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">POST /auth/email/register</code>
                <p className="text-gray-600 mt-2">Foydalanuvchi nomi, email va parol orqali ro'yxatdan o'tish</p>
              </div>
              <div className="border-l-4 border-green-500 pl-4">
                <h4 className="font-semibold text-gray-900">Kirish</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">POST /auth/email/login</code>
                <p className="text-gray-600 mt-2">Email va parol orqali tizimga kirish</p>
              </div>
              <div className="border-l-4 border-green-500 pl-4">
                <h4 className="font-semibold text-gray-900">OAuth</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">GET /auth/google/redirect</code>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded ml-2">GET /auth/github/redirect</code>
                <p className="text-gray-600 mt-2">Google va GitHub orqali kirish</p>
              </div>
            </div>
          </div>

          {/* Posts */}
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center mb-4">
              <FileText className="w-6 h-6 text-blue-600 mr-3" />
              <h3 className="text-xl font-bold text-gray-900">Postlar</h3>
            </div>
            <div className="space-y-4">
              <div className="border-l-4 border-blue-500 pl-4">
                <h4 className="font-semibold text-gray-900">Postlar ro'yxati</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">GET /posts?sort=trending&tag=laravel</code>
                <p className="text-gray-600 mt-2">Postlarni turli parametrlar bilan saralash va filterlash</p>
              </div>
              <div className="border-l-4 border-blue-500 pl-4">
                <h4 className="font-semibold text-gray-900">Post yaratish</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">POST /posts</code>
                <p className="text-gray-600 mt-2">Yangi post yaratish (authentication required)</p>
              </div>
              <div className="border-l-4 border-blue-500 pl-4">
                <h4 className="font-semibold text-gray-900">Post ko'rish</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">GET /posts/{'{slug}'}</code>
                <p className="text-gray-600 mt-2">Bitta postni detallari bilan ko'rish</p>
              </div>
            </div>
          </div>

          {/* Users */}
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center mb-4">
              <Database className="w-6 h-6 text-purple-600 mr-3" />
              <h3 className="text-xl font-bold text-gray-900">Foydalanuvchilar</h3>
            </div>
            <div className="space-y-4">
              <div className="border-l-4 border-purple-500 pl-4">
                <h4 className="font-semibold text-gray-900">Foydalanuvchilar ro'yxati</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">GET /users?sort=xp&search=john</code>
                <p className="text-gray-600 mt-2">Foydalanuvchilarni qidirish va saralash</p>
              </div>
              <div className="border-l-4 border-purple-500 pl-4">
                <h4 className="font-semibold text-gray-900">Profil ko'rish</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">GET /users/{'{username}'}</code>
                <p className="text-gray-600 mt-2">Foydalanuvchi profilini ko'rish</p>
              </div>
              <div className="border-l-4 border-purple-500 pl-4">
                <h4 className="font-semibold text-gray-900">Reyting jadvali</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">GET /users/leaderboard?period=month</code>
                <p className="text-gray-600 mt-2">Eng faol foydalanuvchilar ro'yxati</p>
              </div>
            </div>
          </div>

          {/* Wiki */}
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center mb-4">
              <BookOpen className="w-6 h-6 text-green-600 mr-3" />
              <h3 className="text-xl font-bold text-gray-900">Wiki</h3>
            </div>
            <div className="space-y-4">
              <div className="border-l-4 border-green-500 pl-4">
                <h4 className="font-semibold text-gray-900">Wiki maqolalari</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">GET /wiki</code>
                <p className="text-gray-600 mt-2">Barcha wiki maqolalari ro'yxati</p>
              </div>
              <div className="border-l-4 border-green-500 pl-4">
                <h4 className="font-semibold text-gray-900">Maqola ko'rish</h4>
                <code className="text-sm bg-gray-100 px-2 py-1 rounded">GET /wiki/{'{slug}'}</code>
                <p className="text-gray-600 mt-2">Wiki maqolasini to'liq ko'rish</p>
              </div>
            </div>
          </div>

          {/* Rate Limits */}
          <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div className="flex items-center mb-4">
              <Zap className="w-6 h-6 text-yellow-600 mr-3" />
              <h3 className="text-xl font-bold text-gray-900">Rate Limiting</h3>
            </div>
            <div className="space-y-2 text-gray-600">
              <p>• Umumiy API: 100 soat/da</p>
              <p>• Kod ishga tushirish: 10 soat/da</p>
              <p>• Authentication endpointlari: 20 soat/da</p>
              <p>• Barcha response lar da <code className="bg-gray-100 px-1 rounded">X-RateLimit-Limit</code> va <code className="bg-gray-100 px-1 rounded">X-RateLimit-Remaining</code> headerlari mavjud</p>
            </div>
          </div>
        </div>

        {/* Example */}
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-8">
          <h3 className="text-xl font-bold text-gray-900 mb-4">Misol</h3>
          <div className="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto">
            <pre className="text-sm">
              <code>{`// Postlarni olish
fetch('https://api.knowhub.uz/api/v1/posts?sort=trending&per_page=10')
  .then(response => response.json())
  .then(data => console.log(data));

// Authentication bilan post yaratish
fetch('https://api.knowhub.uz/api/v1/posts', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer YOUR_TOKEN_HERE'
  },
  body: JSON.stringify({
    title: 'Laravel Tips',
    content_markdown: '# Laravel Tips\\n\\nSome useful tips...',
    category_id: 1,
    tags: ['Laravel', 'PHP']
  })
});`}</code>
            </pre>
          </div>
        </div>
      </div>
    </div>
  );
}
