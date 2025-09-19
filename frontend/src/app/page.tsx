'use client';
import { useQuery } from '@tanstack/react-query';
import { api } from '@/lib/api';
import PostCard from '@/components/PostCard';
import LoadingSpinner from '@/components/LoadingSpinner';
import { Post } from '@/types';
import Link from 'next/link';
import { TrendingUp, Users, BookOpen, Code, Award, MessageCircle } from 'lucide-react';

async function getTrendingPosts() {
  try {
    const res = await api.get('/posts?sort=trending&per_page=6');
    return res.data;
  } catch (error) {
    console.error('Error fetching trending posts:', error);
    return { data: [] };
  }
}

async function getStats() {
  try {
    const res = await api.get('/stats/public');
    return res.data;
  } catch (error) {
    console.error('Error fetching stats:', error);
    return {
      users: { total: 0 },
      posts: { total: 0 },
      comments: { total: 0 },
      wiki: { articles: 0 }
    };
  }
}

export default function HomePage() {
  const { data: posts, isLoading, error } = useQuery({
    queryKey: ['posts', 'trending'],
    queryFn: getTrendingPosts,
    retry: 1,
    staleTime: 5 * 60 * 1000,
  });

  const { data: stats } = useQuery({
    queryKey: ['stats'],
    queryFn: getStats,
    retry: 1,
    staleTime: 10 * 60 * 1000,
  });

  if (isLoading) return <LoadingSpinner />;

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero Section */}
      <div className="bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-700 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
          <div className="text-center">
            <h1 className="text-4xl md:text-6xl font-bold mb-6">
              KnowHub <span className="text-yellow-300">Community</span>
            </h1>
            <p className="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto mb-8">
              O'zbekiston va butun dunyo bo'ylab dasturchilar hamjamiyatini birlashtiruvchi ochiq platforma
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link
                href="/posts"
                className="inline-flex items-center px-8 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
              >
                <BookOpen className="w-5 h-5 mr-2" />
                Postlarni Ko'rish
              </Link>
              <Link
                href="/auth/register"
                className="inline-flex items-center px-8 py-3 bg-yellow-500 text-gray-900 rounded-lg font-semibold hover:bg-yellow-400 transition-colors"
              >
                <Users className="w-5 h-5 mr-2" />
                Ro'yxatdan O'tish
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* Solver AI Teaser */}
      <div className="bg-gradient-to-br from-gray-900 via-purple-900 to-violet-900 text-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <div className="mb-8">
              <div className="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full mx-auto mb-6 relative">
                <div className="absolute inset-0 bg-purple-500 rounded-full animate-ping opacity-20"></div>
                <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              </div>
              <h2 className="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                SolVer AI
              </h2>
              <p className="text-xl text-purple-200 max-w-2xl mx-auto mb-8">
                Vijdonli sun'iy intellekt. KnowHub.uz jamoasi tomonidan maxfiy ishlab chiqilmoqda.
              </p>
            </div>
            
            <div className="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
              <div className="text-center">
                <div className="w-12 h-12 bg-purple-800 rounded-lg flex items-center justify-center mx-auto mb-4">
                  <svg className="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <h3 className="text-lg font-semibold text-white mb-2">Tezkor Yechimlar</h3>
                <p className="text-purple-300 text-sm">Murakkab muammolarni soniyalarda yechadi</p>
              </div>
              
              <div className="text-center">
                <div className="w-12 h-12 bg-purple-800 rounded-lg flex items-center justify-center mx-auto mb-4">
                  <svg className="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-4.006z" />
                  </svg>
                </div>
                <h3 className="text-lg font-semibold text-white mb-2">Vijdonli AI</h3>
                <p className="text-purple-300 text-sm">Insoniy qadriyatlarini hurmat qiladi</p>
              </div>
              
              <div className="text-center">
                <div className="w-12 h-12 bg-purple-800 rounded-lg flex items-center justify-center mx-auto mb-4">
                  <svg className="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <h3 className="text-lg font-semibold text-white mb-2">Xavfsiz</h3>
                <p className="text-purple-300 text-sm">Ma'lumotlaringiz maxfiy saqlanadi</p>
              </div>
            </div>
            
            <div className="mt-12">
              <div className="inline-flex items-center px-6 py-3 bg-purple-800 rounded-full text-purple-200">
                <div className="flex items-center mr-3">
                  <div className="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                  <span className="text-sm font-medium">Ishlab chiqilmoqda</span>
                </div>
                <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
              </div>
              <p className="text-purple-400 mt-4 text-sm">
                Tez orada hamjamiyatimizga taqdim etiladi. Kuting...
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Stats Section */}
      <div className="bg-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div className="text-center">
              <div className="flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mx-auto mb-4">
                <BookOpen className="w-8 h-8 text-indigo-600" />
              </div>
              <div className="text-3xl font-bold text-gray-900">{stats?.posts?.total || 0}+</div>
              <div className="text-gray-600">Postlar</div>
            </div>
            <div className="text-center">
              <div className="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mx-auto mb-4">
                <Users className="w-8 h-8 text-green-600" />
              </div>
              <div className="text-3xl font-bold text-gray-900">{stats?.users?.total || 0}+</div>
              <div className="text-gray-600">Foydalanuvchilar</div>
            </div>
            <div className="text-center">
              <div className="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mx-auto mb-4">
                <MessageCircle className="w-8 h-8 text-purple-600" />
              </div>
              <div className="text-3xl font-bold text-gray-900">{stats?.comments?.total || 0}+</div>
              <div className="text-gray-600">Kommentlar</div>
            </div>
            <div className="text-center">
              <div className="flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mx-auto mb-4">
                <Award className="w-8 h-8 text-yellow-600" />
              </div>
              <div className="text-3xl font-bold text-gray-900">{stats?.wiki?.articles || 0}+</div>
              <div className="text-gray-600">Wiki Maqolalar</div>
            </div>
          </div>
        </div>
      </div>

      {/* Features Section */}
      <div className="bg-gray-50 py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">Asosiy Imkoniyatlar</h2>
            <p className="text-xl text-gray-600">KnowHub Community bilan nima qila olasiz</p>
          </div>
          
          <div className="grid md:grid-cols-3 gap-8">
            <div className="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
              <div className="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                <BookOpen className="w-6 h-6 text-indigo-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">Postlar va Maqolalar</h3>
              <p className="text-gray-600">
                Dasturlash bo'yicha savollar bering, tajribangizni baham ko'ring va boshqalardan o'rganing.
              </p>
            </div>
            
            <div className="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                <Code className="w-6 h-6 text-green-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">Kod Ishga Tushirish</h3>
              <p className="text-gray-600">
                JavaScript, Python, PHP kodlarini to'g'ridan-to'g'ri brauzerda ishga tushiring va natijani ko'ring.
              </p>
            </div>
            
            <div className="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
              <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <Award className="w-6 h-6 text-purple-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">Gamifikatsiya</h3>
              <p className="text-gray-600">
                XP to'plang, darajangizni oshiring, badglar qo'lga kiriting va reyting jadvalida yuqoriga chiqing.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Trending Posts */}
      <div className="bg-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between mb-8">
            <div className="flex items-center">
              <TrendingUp className="w-8 h-8 text-indigo-600 mr-3" />
              <h2 className="text-3xl font-bold text-gray-900">🔥 Trend Postlar</h2>
            </div>
            <Link 
              href="/posts" 
              className="text-indigo-600 hover:text-indigo-700 font-medium flex items-center"
            >
              Barchasini ko'rish →
            </Link>
          </div>
          
          {error ? (
            <div className="text-center py-12">
              <div className="text-gray-400 text-6xl mb-4">📝</div>
              <h3 className="text-lg font-medium text-gray-900 mb-2">Postlarni yuklab bo'lmadi</h3>
              <p className="text-gray-600">Iltimos, keyinroq qayta urinib ko'ring</p>
            </div>
          ) : posts?.data && posts.data.length > 0 ? (
            <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
              {posts.data.map((post: Post) => (
                <PostCard key={post.id} post={post} />
              ))}
            </div>
          ) : (
            <div className="text-center py-12">
              <div className="text-gray-400 text-6xl mb-4">📝</div>
              <h3 className="text-lg font-medium text-gray-900 mb-2">Hozircha postlar yo'q</h3>
              <p className="text-gray-600">Birinchi bo'lib post yozing!</p>
              <Link
                href="/auth/register"
                className="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors mt-4"
              >
                Ro'yxatdan o'tish
              </Link>
            </div>
          )}
        </div>
      </div>

      {/* CTA Section */}
      <div className="bg-indigo-600 py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold text-white mb-4">
            Hamjamiyatga Qo'shiling!
          </h2>
          <p className="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
            O'zbekiston dasturchilar hamjamiyatining bir qismi bo'ling. Bilim almashing, tajriba to'plang va karyerangizni rivojlantiring.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              href="/auth/register"
              className="inline-flex items-center px-8 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
            >
              Bepul Ro'yxatdan O'tish
            </Link>
            <Link
              href="/posts"
              className="inline-flex items-center px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors"
            >
              Postlarni Ko'rish
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
