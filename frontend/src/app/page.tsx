'use client';
import { useQuery } from '@tanstack/react-query';
import { api } from '@/lib/api';
import PostCard from '@/components/PostCard';
import LoadingSpinner from '@/components/LoadingSpinner';
import { Post } from '@/types';

async function getTrendingPosts() {
  const res = await api.get('/posts?sort=trending');
  return res.data;
}

export default function HomePage() {
  const { data: posts, isLoading, error } = useQuery({
    queryKey: ['posts', 'trending'],
    queryFn: getTrendingPosts,
  });

  if (isLoading) return <LoadingSpinner />;
  if (error) return <div className="text-center py-12 text-red-600">Xatolik yuz berdi</div>;

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      {/* Hero Section */}
      <div className="text-center mb-12">
        <h1 className="text-4xl md:text-6xl font-bold text-gray-900 mb-4">
          KnowHub <span className="text-indigo-600">Community</span>
        </h1>
        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
          O'zbekiston va butun dunyo bo'ylab dasturchilar hamjamiyatini birlashtiruvchi ochiq platforma
        </p>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
        <div className="text-center">
          <div className="text-3xl font-bold text-indigo-600">1000+</div>
          <div className="text-gray-600">Postlar</div>
        </div>
        <div className="text-center">
          <div className="text-3xl font-bold text-indigo-600">500+</div>
          <div className="text-gray-600">Foydalanuvchilar</div>
        </div>
        <div className="text-center">
          <div className="text-3xl font-bold text-indigo-600">50+</div>
          <div className="text-gray-600">Wiki maqolalar</div>
        </div>
        <div className="text-center">
          <div className="text-3xl font-bold text-indigo-600">24/7</div>
          <div className="text-gray-600">Faol jamiyat</div>
        </div>
      </div>

      {/* Trending Posts */}
      <div>
        <div className="flex items-center justify-between mb-8">
          <h2 className="text-2xl font-bold text-gray-900">🔥 Trend Postlar</h2>
          <a 
            href="/posts" 
            className="text-indigo-600 hover:text-indigo-700 font-medium"
          >
            Barchasini ko'rish →
          </a>
        </div>
        
        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          {posts?.data?.map((post: Post) => (
            <PostCard key={post.id} post={post} />
          ))}
        </div>
      </div>
    </div>
  );
}
