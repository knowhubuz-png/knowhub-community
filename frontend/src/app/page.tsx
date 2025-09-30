'use client';
import { useQuery } from '@tanstack/react-query';
import { api } from '@/lib/api';
import PostCard from '@/components/PostCard';
import LoadingSpinner from '@/components/LoadingSpinner';
import { Post, Category, Tag, User } from '@/types';
import Link from 'next/link';
import { 
  TrendingUp, 
  Users, 
  BookOpen, 
  Code, 
  Award, 
  MessageCircle, 
  Star,
  ArrowRight,
  Hash,
  Crown,
  Zap,
  Calendar,
  Eye
} from 'lucide-react';

interface HomeData {
  trending_posts: Post[];
  latest_posts: Post[];
  popular_categories: Category[];
  trending_tags: Tag[];
  top_users: User[];
  stats: {
    users: { total: number; active_today: number; new_this_week: number };
    posts: { total: number; today: number; this_week: number };
    comments: { total: number; today: number };
    wiki: { articles: number };
  };
  featured_post?: Post;
}

async function getHomeData(): Promise<HomeData> {
  try {
    const res = await api.get('/home');
    return res.data;
  } catch (error) {
    console.error('Error fetching home data:', error);
    return {
      trending_posts: [],
      latest_posts: [],
      popular_categories: [],
      trending_tags: [],
      top_users: [],
      stats: {
        users: { total: 0, active_today: 0, new_this_week: 0 },
        posts: { total: 0, today: 0, this_week: 0 },
        comments: { total: 0, today: 0 },
        wiki: { articles: 0 }
      }
    };
  }
}

export default function HomePage() {
  const { data: homeData, isLoading, error } = useQuery({
    queryKey: ['home'],
    queryFn: getHomeData,
    retry: 1,
    staleTime: 5 * 60 * 1000,
  });

  if (isLoading) return <LoadingSpinner />;

  const {
    trending_posts = [],
    latest_posts = [],
    popular_categories = [],
    trending_tags = [],
    top_users = [],
    stats,
    featured_post
  } = homeData || {};

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
                href="/register"
                className="inline-flex items-center px-8 py-3 bg-yellow-500 text-gray-900 rounded-lg font-semibold hover:bg-yellow-400 transition-colors"
              >
                <Users className="w-5 h-5 mr-2" />
                Ro'yxatdan O'tish
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* Stats Section */}
      <div className="bg-white py-12 border-b border-gray-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div className="text-center">
              <div className="flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mx-auto mb-4">
                <BookOpen className="w-8 h-8 text-indigo-600" />
              </div>
              <div className="text-3xl font-bold text-gray-900">{stats?.posts?.total || 0}</div>
              <div className="text-gray-600">Postlar</div>
              <div className="text-sm text-green-600 mt-1">+{stats?.posts?.today || 0} bugun</div>
            </div>
            <div className="text-center">
              <div className="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mx-auto mb-4">
                <Users className="w-8 h-8 text-green-600" />
              </div>
              <div className="text-3xl font-bold text-gray-900">{stats?.users?.total || 0}</div>
              <div className="text-gray-600">Foydalanuvchilar</div>
              <div className="text-sm text-green-600 mt-1">+{stats?.users?.new_this_week || 0} bu hafta</div>
            </div>
            <div className="text-center">
              <div className="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mx-auto mb-4">
                <MessageCircle className="w-8 h-8 text-purple-600" />
              </div>
              <div className="text-3xl font-bold text-gray-900">{stats?.comments?.total || 0}</div>
              <div className="text-gray-600">Kommentlar</div>
              <div className="text-sm text-green-600 mt-1">+{stats?.comments?.today || 0} bugun</div>
            </div>
            <div className="text-center">
              <div className="flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mx-auto mb-4">
                <Award className="w-8 h-8 text-yellow-600" />
              </div>
              <div className="text-3xl font-bold text-gray-900">{stats?.wiki?.articles || 0}</div>
              <div className="text-gray-600">Wiki Maqolalar</div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
          {/* Left Sidebar - Categories */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-lg font-semibold text-gray-900">📁 Kategoriyalar</h2>
                <Link href="/categories" className="text-indigo-600 hover:text-indigo-700 text-sm">
                  Barchasi
                </Link>
              </div>
              <div className="space-y-3">
                {popular_categories.slice(0, 6).map((category: any) => (
                  <Link
                    key={category.id}
                    href={`/posts?category=${category.slug}`}
                    className="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors group"
                  >
                    <div className="flex items-center">
                      <span className="text-lg mr-3">{category.icon}</span>
                      <div>
                        <div className="font-medium text-gray-900 group-hover:text-indigo-600">
                          {category.name}
                        </div>
                        <div className="text-xs text-gray-500">
                          {category.posts_count} post
                        </div>
                      </div>
                    </div>
                    <ArrowRight className="w-4 h-4 text-gray-400 group-hover:text-indigo-600" />
                  </Link>
                ))}
              </div>
            </div>

            {/* Top Users */}
            <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-lg font-semibold text-gray-900">👑 Top Foydalanuvchilar</h2>
                <Link href="/leaderboard" className="text-indigo-600 hover:text-indigo-700 text-sm">
                  Reyting
                </Link>
              </div>
              <div className="space-y-3">
                {top_users.slice(0, 5).map((user: any, index: number) => (
                  <Link
                    key={user.id}
                    href={`/profile/${user.username}`}
                    className="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group"
                  >
                    <div className="flex items-center space-x-1 mr-3">
                      {index === 0 && <Crown className="w-4 h-4 text-yellow-500" />}
                      <span className="text-sm font-bold text-gray-500">#{index + 1}</span>
                    </div>
                    <img
                      src={user.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}`}
                      alt={user.name}
                      className="w-8 h-8 rounded-full mr-3"
                    />
                    <div className="flex-1">
                      <div className="font-medium text-gray-900 group-hover:text-indigo-600 text-sm">
                        {user.name}
                      </div>
                      <div className="text-xs text-gray-500">
                        {user.xp} XP • {user.posts_count} post
                      </div>
                    </div>
                  </Link>
                ))}
              </div>
            </div>
          </div>

          {/* Main Content - Posts */}
          <div className="lg:col-span-2">
            {/* Featured Post */}
            {featured_post && (
              <div className="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 mb-8 text-white">
                <div className="flex items-center mb-3">
                  <Star className="w-5 h-5 text-yellow-300 mr-2" />
                  <span className="text-sm font-medium text-indigo-100">Tanlangan Post</span>
                </div>
                <Link href={`/posts/${featured_post.slug}`}>
                  <h3 className="text-xl font-bold mb-2 hover:text-yellow-300 transition-colors">
                    {featured_post.title}
                  </h3>
                </Link>
                <p className="text-indigo-100 mb-4 line-clamp-2">
                  {featured_post.content_markdown.replace(/[#*`]/g, '').substring(0, 120)}...
                </p>
                <div className="flex items-center justify-between">
                  <div className="flex items-center space-x-4 text-sm">
                    <span>👍 {featured_post.score}</span>
                    <span>💬 {featured_post.answers_count}</span>
                  </div>
                  <Link
                    href={`/posts/${featured_post.slug}`}
                    className="text-yellow-300 hover:text-yellow-200 font-medium"
                  >
                    O'qish →
                  </Link>
                </div>
              </div>
            )}

            {/* Trending Posts */}
            <div className="mb-8">
              <div className="flex items-center justify-between mb-6">
                <div className="flex items-center">
                  <TrendingUp className="w-6 h-6 text-red-500 mr-3" />
                  <h2 className="text-2xl font-bold text-gray-900">🔥 Trend Postlar</h2>
                </div>
                <Link 
                  href="/posts?sort=trending" 
                  className="text-indigo-600 hover:text-indigo-700 font-medium flex items-center"
                >
                  Barchasini ko'rish <ArrowRight className="w-4 h-4 ml-1" />
                </Link>
              </div>
              
              {trending_posts.length > 0 ? (
                <div className="grid gap-6 md:grid-cols-2">
                  {trending_posts.slice(0, 4).map((post: Post) => (
                    <PostCard key={post.id} post={post} />
                  ))}
                </div>
              ) : (
                <div className="text-center py-8 bg-white rounded-lg border border-gray-200">
                  <TrendingUp className="w-12 h-12 text-gray-300 mx-auto mb-4" />
                  <p className="text-gray-600">Hozircha trend postlar yo'q</p>
                </div>
              )}
            </div>

            {/* Latest Posts */}
            <div>
              <div className="flex items-center justify-between mb-6">
                <div className="flex items-center">
                  <Calendar className="w-6 h-6 text-blue-500 mr-3" />
                  <h2 className="text-2xl font-bold text-gray-900">📝 So'nggi Postlar</h2>
                </div>
                <Link 
                  href="/posts" 
                  className="text-indigo-600 hover:text-indigo-700 font-medium flex items-center"
                >
                  Barchasini ko'rish <ArrowRight className="w-4 h-4 ml-1" />
                </Link>
              </div>
              
              {latest_posts.length > 0 ? (
                <div className="grid gap-6 md:grid-cols-2">
                  {latest_posts.slice(0, 4).map((post: Post) => (
                    <PostCard key={post.id} post={post} />
                  ))}
                </div>
              ) : (
                <div className="text-center py-8 bg-white rounded-lg border border-gray-200">
                  <BookOpen className="w-12 h-12 text-gray-300 mx-auto mb-4" />
                  <p className="text-gray-600">Hozircha postlar yo'q</p>
                  <Link
                    href="/register"
                    className="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors mt-4"
                  >
                    Birinchi post yozing
                  </Link>
                </div>
              )}
            </div>
          </div>

          {/* Right Sidebar */}
          <div className="lg:col-span-1">
            {/* Trending Tags */}
            <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-lg font-semibold text-gray-900">🏷️ Trend Teglar</h2>
                <Link href="/tags" className="text-indigo-600 hover:text-indigo-700 text-sm">
                  Barchasi
                </Link>
              </div>
              <div className="flex flex-wrap gap-2">
                {trending_tags.slice(0, 12).map((tag: any) => (
                  <Link
                    key={tag.slug}
                    href={`/posts?tag=${tag.slug}`}
                    className="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-indigo-100 hover:text-indigo-700 transition-colors"
                  >
                    <Hash className="w-3 h-3 mr-1" />
                    {tag.name}
                    <span className="ml-1 text-xs text-gray-500">({tag.usage_count})</span>
                  </Link>
                ))}
              </div>
            </div>

            {/* Quick Actions */}
            <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
              <h2 className="text-lg font-semibold text-gray-900 mb-4">⚡ Tezkor Harakatlar</h2>
              <div className="space-y-3">
                <Link
                  href="/posts/create"
                  className="flex items-center p-3 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors"
                >
                  <BookOpen className="w-5 h-5 mr-3" />
                  <span className="font-medium">Yangi Post Yozish</span>
                </Link>
                <Link
                  href="/wiki/create"
                  className="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors"
                >
                  <Code className="w-5 h-5 mr-3" />
                  <span className="font-medium">Wiki Maqola</span>
                </Link>
                <Link
                  href="/users"
                  className="flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors"
                >
                  <Users className="w-5 h-5 mr-3" />
                  <span className="font-medium">Foydalanuvchilar</span>
                </Link>
                <Link
                  href="/leaderboard"
                  className="flex items-center p-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition-colors"
                >
                  <Award className="w-5 h-5 mr-3" />
                  <span className="font-medium">Reyting Jadvali</span>
                </Link>
              </div>
            </div>

            {/* Community Stats */}
            <div className="bg-gradient-to-br from-gray-900 to-gray-800 rounded-lg p-6 text-white">
              <h2 className="text-lg font-semibold mb-4">📊 Jamiyat Statistikasi</h2>
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <span className="text-gray-300">Faol bugun:</span>
                  <span className="font-bold">{stats?.users?.active_today || 0}</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-gray-300">Yangi postlar:</span>
                  <span className="font-bold text-green-400">{stats?.posts?.this_week || 0}</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-gray-300">Bu hafta:</span>
                  <span className="font-bold text-blue-400">{stats?.users?.new_this_week || 0} yangi a'zo</span>
                </div>
              </div>
              <div className="mt-6 pt-4 border-t border-gray-700">
                <Link
                  href="/dashboard"
                  className="flex items-center justify-center w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                  <Eye className="w-4 h-4 mr-2" />
                  Batafsil ko'rish
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Features Section */}
      <div className="bg-gray-100 py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">Asosiy Imkoniyatlar</h2>
            <p className="text-xl text-gray-600">KnowHub Community bilan nima qila olasiz</p>
          </div>
          
          <div className="grid md:grid-cols-3 gap-8">
            <div className="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
              <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <BookOpen className="w-8 h-8 text-indigo-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">Postlar va Maqolalar</h3>
              <p className="text-gray-600">
                Dasturlash bo'yicha savollar bering, tajribangizni baham ko'ring va boshqalardan o'rganing.
              </p>
            </div>
            
            <div className="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
              <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Code className="w-8 h-8 text-green-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">Kod Ishga Tushirish</h3>
              <p className="text-gray-600">
                JavaScript, Python, PHP kodlarini to'g'ridan-to'g'ri brauzerda ishga tushiring va natijani ko'ring.
              </p>
            </div>
            
            <div className="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
              <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Award className="w-8 h-8 text-purple-600" />
              </div>
              <h3 className="text-xl font-semibold text-gray-900 mb-3">Gamifikatsiya</h3>
              <p className="text-gray-600">
                XP to'plang, darajangizni oshiring, badglar qo'lga kiriting va reyting jadvalida yuqoriga chiqing.
              </p>
            </div>
          </div>
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
              href="/register"
              className="inline-flex items-center px-8 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
            >
              <Zap className="w-5 h-5 mr-2" />
              Bepul Ro'yxatdan O'tish
            </Link>
            <Link
              href="/posts"
              className="inline-flex items-center px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors"
            >
              <BookOpen className="w-5 h-5 mr-2" />
              Postlarni Ko'rish
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}